<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function studentData(): ?array
    {
        $user = User::with('profileAccount')->find(auth()->id());
        
        $activeEnrollments = \App\Models\Enrollment::where('student_id', $user->id)
            ->where(function($query) {
                $query->whereHas('course', function($q) {
                    $q->where('course_price', 0);
                })->orWhereHas('payment', function($q) {
                    $q->whereIn('connection_status', ['success', 'settlement', 'capture', 'paid', 'sukses']);
                });
            })
            ->with(['course.category', 'course.mentor'])
            ->get();
            
        $completedCoursesCount = $activeEnrollments->where('progress', 100)->count();
        $totalCertificates = \App\Models\Certificate::whereHas('enrollment', function ($q) use ($user) {
            $q->where('enrollments.student_id', $user->id);
        })->count();

        return [
            'user' => $user,
            'activeEnrollments' => $activeEnrollments,
            'completedCoursesCount' => $completedCoursesCount,
            'totalCertificates' => $totalCertificates,
            'totalEnrolled' => $activeEnrollments->count(),
        ];
    }

    public function mentorData(): ?User
    {
        return User::with('profileAccount')
            ->where('role', 'mentor')
            ->find(auth()->id());
    }

    public function adminStats(): array
    {
        $successStatuses = ['success', 'settlement', 'paid', 'sukses'];

        $salesByCategory = Payment::query()
            ->select(
                'categories.category_name as category_name',
                DB::raw('COUNT(payments.id) as total_sales')
            )
            ->join('enrollments', 'payments.enrollment_id', '=', 'enrollments.id')
            ->join('courses', 'enrollments.course_id', '=', 'courses.id')
            ->join('categories', 'courses.category_id', '=', 'categories.id')
            ->whereIn('payments.connection_status', $successStatuses)
            ->groupBy('categories.category_name')
            ->orderByDesc('total_sales')
            ->get();

        return [
            'totalUser'          => User::count(),
            'totalPelajar'       => User::where('role', 'student')->count(),
            'totalMentor'        => User::where('role', 'mentor')->count(),
            'totalMentorPending' => User::where('role', 'mentor')->where('status', 'pending')->count(),
            'totalKursus'        => Course::count(),

            'totalPemasukan'     => Payment::whereIn('connection_status', $successStatuses)
                ->sum('gross_amount'),

            'categoryLabels'     => $salesByCategory->pluck('category_name')->toArray(),
            'categorySales'      => $salesByCategory->pluck('total_sales')->map(fn($value) => (int) $value)->toArray(),
        ];
    }
}
