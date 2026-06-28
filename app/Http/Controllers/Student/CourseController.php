<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use Barryvdh\DomPDF\Facade\Pdf;

class CourseController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        
        $enrollments = \App\Models\Enrollment::where('student_id', $user->id)
            ->where(function($query) {
                $query->whereHas('course', function($q) {
                    $q->where('course_price', 0);
                })->orWhereHas('payment', function($q) {
                    $q->where('connection_status', 'success');
                });
            })
            ->with(['course.category', 'course.mentor'])
            ->latest()
            ->paginate(12);

        return view('student.courses.index', compact('enrollments'));
    }

    public function show($slug): View
    {
        $course = Course::where('course_slug', $slug)
            ->with(['enrollments', 'rates']) // Removed sessions.lessons which might not exist directly
            ->firstOrFail();

        $sudahEnroll = false;
        $isPaid = false;
        
        if (auth()->check()) {
            $enrollment = Enrollment::where('student_id', auth()->id())
                ->where('course_id', $course->id)
                ->with('payment')
                ->first();
                
            if ($enrollment) {
                $sudahEnroll = true;
                // Course is free OR payment is success
                if ($course->course_price == 0 || ($enrollment->payment && $enrollment->payment->connection_status === 'success')) {
                    $isPaid = true;
                }
            }
        }

        return view('student.courses.show', compact('course', 'sudahEnroll', 'isPaid'));
    }

    public function enroll($slug): RedirectResponse
    {
        $course = Course::where('course_slug', $slug)->firstOrFail();

        $sudahEnroll = Enrollment::where('student_id', auth()->id())
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
            'student_id' => auth()->id(),
            'course_id'  => $course->id,
            'progress'   => 0,
            'start_date' => now(),
        ]);

        return redirect()->route('student.course.lesson', $course->course_slug)
            ->with('success', 'Berhasil mendaftar kursus!');
    }

    public function showLesson($slug): View|RedirectResponse
    {
        $course = Course::where('course_slug', $slug)
            ->with(['sessions.lessons.materials', 'sessions.exercise'])
            ->firstOrFail();
        
        // Pengecekan akses: Pastikan student sudah enroll dan sudah bayar lunas (atau kursus gratis)
        $enrollment = Enrollment::where('student_id', auth()->id())
            ->where('course_id', $course->id)
            ->with('payment')
            ->first();

        $hasAccess = false;
        
        if ($enrollment) {
            if ($course->course_price == 0 || ($enrollment->payment && $enrollment->payment->connection_status === 'success')) {
                $hasAccess = true;
            }
        }

        // Jika belum memiliki akses, kembalikan ke halaman detail kursus dengan pesan error
        if (!$hasAccess) {
            return redirect()->route('student.course.show', $course->course_slug)
                ->with('error', 'Anda harus menyelesaikan pembayaran terlebih dahulu untuk mengakses materi ini.');
        }

        return view('student.courses.lesson', compact('course'));
    }

    public function certificates(): View
    {
        $user = auth()->user();
        
        $certificates = \App\Models\Certificate::whereHas('enrollment', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->with(['enrollment.course.mentor'])
            ->latest()
            ->paginate(12);

        $totalCertificates = \App\Models\Certificate::whereHas('enrollment', function ($query) use ($user) {
            $query->where('student_id', $user->id);
        })->count();

        $completedCourses = Enrollment::where('student_id', $user->id)
            ->where('progress', 100)
            ->count();

        return view('student.certificate.index', compact('certificates', 'totalCertificates', 'completedCourses'));
    }

    public function showCertificates($id): View
    {
        $certificate = \App\Models\Certificate::with(['enrollment.course.mentor', 'enrollment.student'])->findOrFail($id);
        
        // Pastikan hanya student pemilik sertifikat yang bisa melihatnya
        if ($certificate->enrollment->student_id !== auth()->id()) {
            abort(403);
        }

        return view('student.certificate.show', compact('certificate'));
    }

    public function downloadCertificate($id)
    {
        $certificate = \App\Models\Certificate::with(['enrollment.course.mentor', 'enrollment.student'])->findOrFail($id);
        
        if ($certificate->enrollment->student_id !== auth()->id()) {
            abort(403);
        }

        $pdf = Pdf::loadView('student.certificate.pdf', compact('certificate'))
                  ->setPaper('a4', 'landscape');
                  
        return $pdf->download('certificate-' . $certificate->certificate_number . '.pdf');
    }

    public function progress(): View
    {
        $user = auth()->user();

        // Ambil semua enrollment milik student beserta relasinya
        $enrollments = \App\Models\Enrollment::where('student_id', $user->id)
            ->where(function($query) {
                // Pastikan hanya course yang gratis atau sudah dibayar yang masuk hitungan progress
                $query->whereHas('course', function($q) {
                    $q->where('course_price', 0);
                })->orWhereHas('payment', function($q) {
                    $q->where('connection_status', 'success');
                });
            })
            ->with(['course.category'])
            ->latest()
            ->get();

        $activeCoursesCount = $enrollments->where('progress', '<', 100)->count();
        $completedCoursesCount = $enrollments->where('progress', 100)->count();
        
        // Asumsi nilai kuis diambil dari rata-rata ExerciseResult, 
        // Jika belum ada datanya, bisa kita berikan nilai default atau hitung berdasarkan yang ada.
        $averageQuizScore = \App\Models\ExerciseResult::whereHas('attempt', function($q) use ($user) {
            $q->where('student_id', $user->id);
        })->avg('score') ?? 0;

        return view('student.courses.progress', compact('enrollments', 'activeCoursesCount', 'completedCoursesCount', 'averageQuizScore'));
    }
}
