<?php

namespace App\Services\Admin;

use App\Models\Course;
use App\Models\VerifyCourse;
use Illuminate\Support\Facades\DB;

class CourseVerificationService
{
    public function getPendingCourses(int $perPage = 15)
    {
        return Course::with(['mentor.profileAccount', 'category', 'sessions.lessons.materials', 'sessions.exercises'])
            ->where('status_review', 'pending')
            ->latest()
            ->paginate($perPage);
    }

    public function approve(Course $course): Course
    {
        return DB::transaction(function () use ($course) {
            $course->update([
                'status_publish' => 'published',
                'status_review'  => 'approved',
            ]);

            VerifyCourse::create([
                'admin_id' => auth()->id(),
                'course_id' => $course->id,
                'action' => 'approved',
                'course_rejection_reason' => null,
                'verify_at' => now(),
            ]);

            return $course;
        });
    }

    public function reject(Course $course, string $reason): Course
    {
        return DB::transaction(function () use ($course, $reason) {
            $course->update([
                'status_publish' => 'draft',
                'status_review'  => 'rejected',
            ]);

            VerifyCourse::create([
                'admin_id' => auth()->id(),
                'course_id' => $course->id,
                'action' => 'rejected',
                'course_rejection_reason' => $reason,
                'verify_at' => now(),
            ]);

            return $course;
        });
    }
}
