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

        $result = FinalProjectResult::where('final_project_id', $projectId)
            ->where('enrollment_id', $enrollment->id)
            ->first();

        return view('student.project.submit', compact('project', 'result', 'enrollment'));
    }

    public function submit(Request $request, int $projectId): RedirectResponse
    {
        $project = FinalProject::findOrFail($projectId);

        $request->validate([
            'submission_file' => ['required', 'file', 'max:51200'],
        ], [
            'submission_file.required' => 'File tugas wajib diunggah.',
            'submission_file.max' => 'Ukuran file tidak boleh melebihi 50MB.',
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

        if ($existingResult) {
            // Jika masih menunggu penilaian, tidak bisa kirim ulang
            if ($existingResult->final_project_score === null) {
                return back()->with('error', 'Tugas sudah dikumpulkan, tidak dapat diunggah ulang.');
            }

            // Jika sudah lulus (>= 70), tidak bisa kirim ulang
            if ($existingResult->final_project_score >= 70) {
                return back()->with('error', 'Tugas sudah dinyatakan lulus, tidak dapat diunggah ulang.');
            }

            // Jika tidak lulus (< 70), hapus file lama dan reset nilai
            if ($existingResult->submission_file && Storage::disk('public')->exists($existingResult->submission_file)) {
                Storage::disk('public')->delete($existingResult->submission_file);
            }

            $filePath = $request->file('submission_file')->store('final_projects', 'public');

            $existingResult->update([
                'submission_file'     => $filePath,
                'final_project_score' => null,
                'mentor_notes'        => null,
                'started_at'          => now(),
            ]);

            return back()->with('success', 'Tugas berhasil diunggah ulang. Silakan tunggu penilaian dari pengajar.');
        }

        // Upload File
        $filePath = $request->file('submission_file')->store('final_projects', 'public');

        // Save Final Project Result
        FinalProjectResult::create([
            'final_project_id' => $projectId,
            'enrollment_id'    => $enrollment->id,
            'submission_file'  => $filePath,
        ]);

        return back()->with('success', 'Tugas akhir berhasil dikirim. Silakan tunggu penilaian dari pengajar.');
    }
}
