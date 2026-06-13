<?php

namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\Course;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    public function studentData(): ?User
    {
        return User::with('profileAccount')->find(session('user_id', auth()->id()));
    }

    public function mentorData(): ?User
    {
        return User::with('profileAccount')
            ->where('role', 'mentor')
            ->find(session('user_id', auth()->id()));
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
