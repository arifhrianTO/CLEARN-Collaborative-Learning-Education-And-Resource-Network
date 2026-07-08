<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Certificate;
use App\Models\ExerciseResult;
use App\Models\Rate;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Barryvdh\DomPDF\Facade\Pdf;

class CourseController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();

        $enrollments = Enrollment::where('student_id', $user->id)
            ->where(function ($query) {
                $query->whereHas('course', function ($q) {
                    $q->where('course_price', 0);
                })->orWhereHas('payment', function ($q) {
                    $q->whereIn('connection_status', ['success', 'settlement', 'capture', 'paid', 'sukses']);
                });
            })
            ->with(['course.category', 'course.mentor', 'finalProjectResults', 'rate', 'certificate'])
            ->latest()
            ->paginate(12);

        return view('student.courses.index', compact('enrollments'));
    }

    public function show($slug): View
    {
        $course = Course::where('course_slug', $slug)
            ->with(['enrollments', 'rates', 'sessions.lessons', 'sessions.exercise', 'sessions.finalProjects.materials'])
            ->withCount('sessions')
            ->firstOrFail();

        $sudahEnroll = false;
        $isPaid = false;

        if (Auth::check()) {
            $enrollment = Enrollment::where('student_id', Auth::id())
                ->where('course_id', $course->id)
                ->with('payment')
                ->first();

            if ($enrollment) {
                $sudahEnroll = true;
                // Course is free OR payment is success
                if ($course->course_price == 0 || ($enrollment->payment && in_array($enrollment->payment->connection_status, ['success', 'settlement', 'capture', 'paid', 'sukses']))) {
                    $isPaid = true;
                }
            }
        }

        return view('student.courses.show', compact('course', 'sudahEnroll', 'isPaid'));
    }

    public function enroll($slug): RedirectResponse
    {
        $course = Course::where('course_slug', $slug)->firstOrFail();

        $sudahEnroll = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $course->id)
            ->exists();

        if ($sudahEnroll) {
            return redirect()->route('student.course.lesson', $course->course_slug)
                ->with('info', 'Kamu sudah terdaftar di kursus ini.');
        }

        // Kalau kursus berbayar, tolak proses ini dan arahkan ke checkout
        if ($course->course_price > 0) {
            return redirect()->route('student.checkout', ['course_id' => $course->id]);
        }

        Enrollment::create([
            'student_id' => Auth::id(),
            'course_id'  => $course->id,
            'progress'   => 0,
            'start_date' => now(),
        ]);

        return redirect()->route('student.course.lesson', $course->course_slug)
            ->with('success', 'Berhasil mendaftar kursus!');
    }

    public function showLesson(\Illuminate\Http\Request $request, $slug): View|RedirectResponse
    {
        $course = Course::where('course_slug', $slug)
            ->with(['sessions.lessons.materials', 'sessions.exercise', 'sessions.finalProjects'])
            ->firstOrFail();

        // Pengecekan akses: Pastikan student sudah enroll dan sudah bayar lunas (atau kursus gratis)
        $enrollment = Enrollment::where('student_id', Auth::id())
            ->where('course_id', $course->id)
            ->with(['payment', 'exerciseResults.exerciseAttempt'])
            ->first();

        $hasAccess = false;

        if ($enrollment) {
            if ($course->course_price == 0 || ($enrollment->payment && in_array($enrollment->payment->connection_status, ['success', 'settlement', 'capture', 'paid', 'sukses']))) {
                $hasAccess = true;
            }
        }

        // Jika belum memiliki akses, kembalikan ke halaman detail kursus dengan pesan error
        if (!$hasAccess) {
            return redirect()->route('student.course.show', $course->course_slug)
                ->with('error', 'Anda harus menyelesaikan pembayaran terlebih dahulu untuk mengakses materi ini.');
        }

        $lessonId = $request->query('lesson_id');
        $activeLesson = null;
        
        if ($lessonId) {
            // Find specific lesson from loaded sessions
            foreach ($course->sessions as $session) {
                $lesson = $session->lessons->firstWhere('id', $lessonId);
                if ($lesson) {
                    $activeLesson = $lesson;
                    break;
                }
            }
        }
        
        // Default to first lesson if not found or not specified
        if (!$activeLesson && $course->sessions->isNotEmpty() && $course->sessions->first()->lessons->isNotEmpty()) {
            $activeLesson = $course->sessions->first()->lessons->first();
        }

        return view('student.courses.lesson', compact('course', 'activeLesson', 'enrollment'));
    }

    public function certificates(): View
    {
        $user = Auth::user();

        $certificates = Certificate::whereHas('enrollment', function ($query) use ($user) {
            $query->where('enrollments.student_id', $user->id);
        })
            ->with(['enrollment.course.mentor'])
            ->latest()
            ->paginate(12);

        $totalCertificates = Certificate::whereHas('enrollment', function ($query) use ($user) {
            $query->where('enrollments.student_id', $user->id);
        })->count();

        $completedCourses = Enrollment::where('student_id', $user->id)
            ->where('progress', 100)
            ->whereDoesntHave('finalProjectResults', function ($q) {
                $q->whereNotNull('final_project_score')->where('final_project_score', '<', 70);
            })
            ->count();

        // Courses that can claim certificate (completed + graded >= 70, no certificate yet)
        $claimableEnrollments = Enrollment::where('student_id', $user->id)
            ->where('progress', 100)
            ->whereDoesntHave('certificate')
            ->whereHas('finalProjectResults', function ($q) {
                $q->where('final_project_score', '>=', 70);
            })
            ->with(['course.mentor', 'finalProjectResults'])
            ->get();

        return view('student.certificate.index', compact('certificates', 'totalCertificates', 'completedCourses', 'claimableEnrollments'));
    }

    public function showCertificates($id): View
    {
        $certificate = Certificate::with(['enrollment.course.mentor', 'enrollment.student'])->findOrFail($id);

        // Pastikan hanya student pemilik sertifikat yang bisa melihatnya
        if ($certificate->enrollment->student_id !== Auth::id()) {
            abort(403);
        }

        return view('student.certificate.show', compact('certificate'));
    }

    public function downloadCertificate($id)
    {
        $certificate = Certificate::with(['enrollment.course.mentor', 'enrollment.student'])->findOrFail($id);

        if ($certificate->enrollment->student_id !== Auth::id()) {
            abort(403);
        }

        $pdf = Pdf::loadView('student.certificate.pdf', compact('certificate'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('certificate-' . $certificate->certificate_number . '.pdf');
    }

    public function claimCertificate(Enrollment $enrollment): RedirectResponse
    {
        if ($enrollment->student_id !== Auth::id()) {
            abort(403);
        }

        if ($enrollment->progress < 100) {
            return back()->with('error', 'Kursus belum selesai.');
        }

        if ($enrollment->certificate()->exists()) {
            return back()->with('error', 'Sertifikat sudah diklaim.');
        }

        $passedResult = $enrollment->finalProjectResults()
            ->where('final_project_score', '>=', 70)
            ->first();

        if (!$passedResult) {
            return back()->with('error', 'Tugas akhir belum dinilai atau belum lulus.');
        }

        $certificate = Certificate::create([
            'enrollment_id'      => $enrollment->id,
            'certificate_number' => 'CLN-' . strtoupper(Str::random(16)),
            'issue_date'         => now(),
        ]);

        return redirect()->route('student.certificate.show', $certificate->id)
            ->with('success', 'Selamat! Sertifikat berhasil diklaim.');
    }

    public function submitRating(Request $request, Enrollment $enrollment): \Illuminate\Http\JsonResponse
    {
        if ($enrollment->student_id !== Auth::id()) {
            abort(403);
        }

        // Cek apakah tugas sudah dinilai
        $hasGradedResult = $enrollment->finalProjectResults()
            ->whereNotNull('final_project_score')
            ->exists();

        if (!$hasGradedResult) {
            return response()->json(['success' => false, 'message' => 'Belum bisa memberi rating, tugas belum dinilai pengajar.'], 403);
        }

        $data = $request->validate([
            'course_rate'    => ['required', 'numeric', 'min:1', 'max:5'],
            'course_comment' => ['nullable', 'string', 'max:1000'],
        ]);

        Rate::updateOrCreate(
            ['enrollment_id' => $enrollment->id],
            [
                'course_id'      => $enrollment->course_id,
                'course_rate'    => $data['course_rate'],
                'course_comment' => $data['course_comment'] ?? null,
            ]
        );

        return response()->json(['success' => true]);
    }

    public function progress(): View
    {
        $user = Auth::user();

        // Ambil semua enrollment milik student beserta relasinya
        $enrollments = Enrollment::where('student_id', $user->id)
            ->where(function ($query) {
                // Pastikan hanya course yang gratis atau sudah dibayar yang masuk hitungan progress
                $query->whereHas('course', function ($q) {
                    $q->where('course_price', 0);
                })->orWhereHas('payment', function ($q) {
                    $q->whereIn('connection_status', ['success', 'settlement', 'capture', 'paid', 'sukses']);
                });
            })
            ->with(['course.category', 'finalProjectResults'])
            ->latest()
            ->get();

        $activeCoursesCount = $enrollments->where('progress', '<', 100)
            ->filter(fn($e) => !$e->finalProjectResults?->whereNull('final_project_score')->count())
            ->count();
        $completedCoursesCount = $enrollments->where('progress', 100)
            ->filter(fn($e) => !$e->finalProjectResults?->whereNull('final_project_score')->count())
            ->filter(fn($e) => !$e->finalProjectResults?->where('final_project_score', '<', 70)->count())
            ->count();

        // Asumsi nilai kuis diambil dari rata-rata ExerciseResult, 
        // Jika belum ada datanya, bisa kita berikan nilai default atau hitung berdasarkan yang ada.
        $averageQuizScore = ExerciseResult::whereHas('exerciseAttempt.enrollment', function ($q) use ($user) {
            $q->where('student_id', $user->id);
        })->avg('exercise_result_score') ?? 0;

        return view('student.courses.progress', compact('enrollments', 'activeCoursesCount', 'completedCoursesCount', 'averageQuizScore'));
    }
}
