<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\FinalProject;
use App\Models\FinalProjectResult;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class FinalProjectController extends Controller
{
    public function show(int $projectId): View|RedirectResponse
    {
        $project = FinalProject::with(['session.course', 'materials'])->findOrFail($projectId);
        $userId = auth()->id();

        $enrollment = Enrollment::where('student_id', $userId)
            ->whereHas('course', function ($q) use ($projectId) {
                $q->whereHas('sessions', function ($q) use ($projectId) {
                    $q->whereHas('finalProjects', function ($q) use ($projectId) {
                        $q->where('final_projects.id', $projectId);
                    });
                });
            })
            ->first();

        if (!$enrollment) {
            return redirect()->route('student.course.index')->with('error', 'Anda belum terdaftar di kursus ini.');
        }

        // --- Pengecekan Kunci (Locking) ---
        $course = $enrollment->course;
        $course->load(['sessions.lessons', 'sessions.exercise', 'sessions.finalProjects']);
        $linearCurriculum = [];
        foreach ($course->sessions as $session) {
            foreach ($session->lessons as $lesson) {
                $linearCurriculum[] = ['type' => 'lesson', 'id' => $lesson->id];
            }
            if ($session->exercise) {
                $linearCurriculum[] = ['type' => 'exercise', 'id' => $session->exercise->id];
            }
            foreach ($session->finalProjects as $fp) {
                $linearCurriculum[] = ['type' => 'final_project', 'id' => $fp->id];
            }
        }

        $completedLessons = is_array($enrollment->completed_lessons) ? $enrollment->completed_lessons : [];
        $attemptedExercises = $enrollment->exerciseResults()->with('exerciseAttempt')->get()
            ->pluck('exerciseAttempt.exercise_id')->filter()->unique()->toArray();
        $submittedProjects = $course->sessions->flatMap->finalProjects->map(function ($fp) use ($enrollment) {
            $result = $enrollment->finalProjectResults()->where('final_project_id', $fp->id)->first();
            if ($result && $result->submission_file) {
                if ($result->final_project_score === null || $result->final_project_score >= 70) {
                    return $fp->id;
                }
            }
            return null;
        })->filter()->toArray();

        $lastUnlockedIndex = 0;
        for ($i = 0; $i < count($linearCurriculum); $i++) {
            $item = $linearCurriculum[$i];
            $isCompleted = false;

            if ($item['type'] === 'lesson') {
                $isCompleted = in_array($item['id'], $completedLessons);
            } elseif ($item['type'] === 'exercise') {
                $isCompleted = in_array($item['id'], $attemptedExercises);
            } elseif ($item['type'] === 'final_project') {
                $isCompleted = in_array($item['id'], $submittedProjects);
            }

            if ($isCompleted) {
                $lastUnlockedIndex = $i + 1;
            } else {
                break;
            }
        }

        $requestedIndex = -1;
        foreach ($linearCurriculum as $index => $item) {
             if ($item['type'] === 'final_project' && $item['id'] === $projectId) {
                 $requestedIndex = $index;
                 break;
             }
        }

        if ($requestedIndex > $lastUnlockedIndex) {
            return redirect()->route('student.course.lesson', $course->course_slug)
                ->with('error', 'Tugas Akhir ini masih terkunci. Silakan selesaikan materi sebelumnya.');
        }
        // --- End Pengecekan Kunci ---

        $result = FinalProjectResult::where('final_project_id', $projectId)
            ->where('enrollment_id', $enrollment->id)
            ->first();

        // Jika belum pernah dibuka, catat waktu mulai dan deadline
        if (!$result) {
            $result = FinalProjectResult::create([
                'final_project_id'    => $projectId,
                'enrollment_id'       => $enrollment->id,
                'final_project_score' => null,
                'started_at'          => now(),
                'deadline'            => now()->addDays($project->duration_days),
            ]);
        }

        return view('student.project.submit', compact('project', 'result', 'enrollment'));
    }

    public function submit(Request $request, int $projectId): RedirectResponse
    {
        $project = FinalProject::findOrFail($projectId);
        
        $allowedExts = str_replace('.', '', $project->allowed_extensions); // Pastikan tidak ada titik (misal .pdf, .zip -> pdf, zip)

        $request->validate([
            'submission_file' => ['required', 'file', 'max:51200', 'mimes:' . $allowedExts],
        ], [
            'submission_file.required' => 'File tugas wajib diunggah.',
            'submission_file.max' => 'Ukuran file tidak boleh melebihi 50MB.',
            'submission_file.mimes' => 'Format file tidak diizinkan! Format yang diperbolehkan hanya: ' . $allowedExts,
        ]);

        $userId = auth()->id();

        $enrollment = Enrollment::where('student_id', $userId)
            ->whereHas('course', function ($q) use ($projectId) {
                $q->whereHas('sessions', function ($q) use ($projectId) {
                    $q->whereHas('finalProjects', function ($q) use ($projectId) {
                        $q->where('final_projects.id', $projectId);
                    });
                });
            })
            ->firstOrFail();

        // Check existing submission
        $existingResult = FinalProjectResult::where('final_project_id', $projectId)
            ->where('enrollment_id', $enrollment->id)
            ->first();

        // Hanya terapkan proteksi kirim ulang JIKA siswa benar-benar sudah pernah mengunggah file
        if ($existingResult && $existingResult->submission_file) {
            // Jika masih menunggu penilaian, tidak bisa kirim ulang
            if ($existingResult->final_project_score === null) {
                return back()->with('error', 'Tugas sudah dikumpulkan, tidak dapat diunggah ulang.');
            }

            // Jika sudah lulus (>= 70), tidak bisa kirim ulang
            if ($existingResult->final_project_score >= 70) {
                return back()->with('error', 'Tugas sudah dinyatakan lulus, tidak dapat diunggah ulang.');
            }

            // Jika tidak lulus (< 70), hapus file lama dan reset nilai
            if (Storage::disk('public')->exists($existingResult->submission_file)) {
                Storage::disk('public')->delete($existingResult->submission_file);
            }

            $filePath = $request->file('submission_file')->store('final_projects', 'public');

            $existingResult->update([
                'submission_file'     => $filePath,
                'final_project_score' => null,
                'mentor_notes'        => null,
                'updated_at'          => now(),
            ]);

            $enrollment->recalculateAndSaveProgress();

            return back()->with('success', 'Tugas berhasil diunggah ulang. Silakan tunggu penilaian dari pengajar.');
        }

        // --- Alur Upload Baru / Pertama Kali ---
        $filePath = $request->file('submission_file')->store('final_projects', 'public');

        if ($existingResult) {
            // Update record yang dibuat saat show()
            $existingResult->update([
                'submission_file' => $filePath,
                'updated_at'      => now(),
            ]);
        } else {
            // Jaga-jaga jika show() terlewat
            FinalProjectResult::create([
                'final_project_id' => $projectId,
                'enrollment_id'    => $enrollment->id,
                'submission_file'  => $filePath,
                'started_at'       => now(),
                'deadline'         => now()->addDays($project->duration_days),
            ]);
        }

        $enrollment->recalculateAndSaveProgress();

        return back()->with('success', 'Tugas akhir berhasil dikirim. Silakan tunggu penilaian dari pengajar.');
    }
}
