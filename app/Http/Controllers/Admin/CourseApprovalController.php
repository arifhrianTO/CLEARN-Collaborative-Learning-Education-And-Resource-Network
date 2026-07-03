<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\VerifyCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseApprovalController extends Controller
{
    public function index()
    {
        $pendingCourses = Course::with([
            'mentor',
            'category',
            'sessions',
        ])
            ->where('status_review', 'pending')
            ->latest()
            ->paginate(10);

        return view('admin.courses.index', compact('pendingCourses'));
    }

    public function show(Course $course)
    {
        $course->load([
            'mentor',
            'category',
            'sessions.lessons.materials',
            'sessions.exercises.questions.options',
            'sessions.finalProjects.materials',
            'verifications.admin',
        ]);

        return view('admin.courses.detail', compact('course'));
    }

    public function approve(Course $course)
    {
        $course->update([
            'status_review' => 'approved',
            'status_publish' => 'published',
        ]);

        VerifyCourse::create([
            'admin_id' => Auth::id(),
            'course_id' => $course->id,
            'action' => 'approved',
            'course_rejection_reason' => null,
            'verify_at' => now(),
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kursus berhasil disetujui dan diterbitkan.');
    }

    public function reject(Request $request, Course $course)
    {
        $request->validate([
            'course_rejection_reason' => ['required', 'string', 'max:1000'],
        ], [
            'course_rejection_reason.required' => 'Alasan penolakan wajib diisi.',
        ]);

        $course->update([
            'status_review' => 'rejected',
            'status_publish' => 'draft',
        ]);

        VerifyCourse::create([
            'admin_id' => Auth::id(),
            'course_id' => $course->id,
            'action' => 'rejected',
            'course_rejection_reason' => $request->course_rejection_reason,
            'verify_at' => now(),
        ]);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Kursus berhasil ditolak.');
    }
}
