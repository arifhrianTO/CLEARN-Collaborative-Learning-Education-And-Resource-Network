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

        // Check if student is enrolled in the course
        $enrollment = Enrollment::where('student_id', $userId)
            ->whereHas('course.sessions.finalProjects', function ($query) use ($projectId) {
                $query->where('final_projects.id', $projectId);
            })
            ->first();

        if (!$enrollment) {
            return redirect()->route('student.courses')->with('error', 'Anda belum terdaftar di kursus ini.');
        }

        // Get submission if exists
        $result = FinalProjectResult::where('final_project_id', $projectId)
            ->where('enrollment_id', $enrollment->id)
            ->first();

        return view('student.project.submit', compact('project', 'result'));
    }

    public function submit(Request $request, int $projectId): RedirectResponse
    {
        $project = FinalProject::findOrFail($projectId);
        
        $request->validate([
            'submission_file' => ['required', 'file', 'max:51200'], // max 50MB
        ], [
            'submission_file.required' => 'File tugas wajib diunggah.',
            'submission_file.max' => 'Ukuran file tidak boleh melebihi 50MB.',
        ]);

        $userId = auth()->id();

        $enrollment = Enrollment::where('student_id', $userId)
            ->whereHas('course.sessions.finalProjects', function ($query) use ($projectId) {
                $query->where('final_projects.id', $projectId);
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

        FinalProjectResult::create(
            [
                'final_project_id'    => $projectId,
                'enrollment_id'       => $enrollment->id,
                'submission_file'     => $filePath,
                'started_at'          => now(),
            ]
        );

        return back()->with('success', 'Tugas akhir berhasil dikirim dan menunggu penilaian.');
    }
}