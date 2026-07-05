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
        $project = FinalProject::with(['session.course'])->findOrFail($projectId);
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

        $existingRate = \App\Models\Rate::where('enrollment_id', $enrollment->id)->first();

        return view('student.project.submit', compact('project', 'result', 'enrollment', 'existingRate'));
    }

    public function submit(Request $request, int $projectId): RedirectResponse
    {
        $project = FinalProject::findOrFail($projectId);

        $request->validate([
            'submission_file' => ['required', 'file', 'max:51200'],
            'course_rate'     => ['required', 'numeric', 'min:1', 'max:5'],
            'course_comment'  => ['nullable', 'string', 'max:1000'],
        ], [
            'submission_file.required' => 'File tugas wajib diunggah.',
            'submission_file.max' => 'Ukuran file tidak boleh melebihi 50MB.',
            'course_rate.required' => 'Rating wajib diisi.',
            'course_rate.numeric' => 'Rating harus berupa angka.',
            'course_rate.min' => 'Rating minimal 1.',
            'course_rate.max' => 'Rating maksimal 5.',
            'course_comment.max' => 'Komentar maksimal 1000 karakter.',
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

        // Check if already submitted
        $existingResult = FinalProjectResult::where('final_project_id', $projectId)
            ->where('enrollment_id', $enrollment->id)
            ->first();

        if ($existingResult) {
            return back()->with('error', 'Tugas sudah dikumpulkan, tidak dapat diunggah ulang.');
        }

        // Upload File
        $filePath = $request->file('submission_file')->store('final_projects', 'public');

        // Save Final Project Result
        FinalProjectResult::create([
            'final_project_id' => $projectId,
            'enrollment_id'    => $enrollment->id,
            'submission_file'  => $filePath,
            'started_at'       => now(),
        ]);

        // Save Rating
        \App\Models\Rate::create([
            'course_id'       => $enrollment->course_id,
            'enrollment_id'   => $enrollment->id,
            'course_rate'     => $request->course_rate,
            'course_comment'  => $request->course_comment,
        ]);

        // Mark enrollment as completed
        $enrollment->update(['progress' => 100]);

        return back()->with('success', 'Tugas akhir berhasil dikirim. Silakan tunggu penilaian dari pengajar.');
    }
}