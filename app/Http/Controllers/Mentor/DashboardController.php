<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\Rate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $mentorId =  Auth::id();

        $successStatuses = ['success', 'settlement', 'paid', 'sukses'];

        /*
        |--------------------------------------------------------------------------
        | Total Pendapatan Mentor
        |--------------------------------------------------------------------------
        | Mentor mendapat 80% dari pembayaran sukses.
        */
        $payments = Payment::with('enrollment.course')
            ->whereIn('connection_status', $successStatuses)
            ->whereHas('enrollment.course', function ($query) use ($mentorId) {
                $query->where('mentor_id', $mentorId);
            })
            ->get();

        $totalPendapatan = $payments->sum(function ($payment) {
            return ($payment->gross_amount ?? 0) * 0.80;
        });

        /*
        |--------------------------------------------------------------------------
        | Total Pelajar
        |--------------------------------------------------------------------------
        | Supaya aman, cek dulu kolom student_id atau user_id.
        */
        $totalPelajar = Enrollment::whereHas('course', function ($query) use ($mentorId) {
            $query->where('mentor_id', $mentorId);
        })
            ->distinct('student_id')
            ->count('student_id');

        /*
        |--------------------------------------------------------------------------
        | Kursus Aktif
        |--------------------------------------------------------------------------
        | Menghitung kursus mentor yang published / approved.
        */
        $kursusAktif = Course::where('mentor_id', $mentorId)
            ->where(function ($query) {
                $query->where('status_publish', 'published')
                    ->orWhere('status_review', 'approved');
            })
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Rata-rata Penilaian
        |--------------------------------------------------------------------------
        | Cek nama kolom rating/rate agar tidak mudah error.
        */
        $ratingColumn = Schema::hasColumn('rates', 'rating')
            ? 'rating'
            : 'rate';

        /*
        |--------------------------------------------------------------------------
        | Data Chart 6 Bulan Terakhir
        |--------------------------------------------------------------------------
        */
        $months = collect();

        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);

            $months->push([
                'label' => $date->translatedFormat('M'),
                'month' => $date->month,
                'year' => $date->year,
            ]);
        }

        $revenueChartData = $months->map(function ($month) use ($mentorId, $successStatuses) {
            $total = Payment::whereIn('connection_status', $successStatuses)
                ->whereMonth('created_at', $month['month'])
                ->whereYear('created_at', $month['year'])
                ->whereHas('enrollment.course', function ($query) use ($mentorId) {
                    $query->where('mentor_id', $mentorId);
                })
                ->sum('gross_amount');

            return $total * 0.80;
        });

        $enrollmentChartData = $months->map(function ($month) use ($mentorId) {
            return Enrollment::whereMonth('created_at', $month['month'])
                ->whereYear('created_at', $month['year'])
                ->whereHas('course', function ($query) use ($mentorId) {
                    $query->where('mentor_id', $mentorId);
                })
                ->count();
        });

        $chartLabels = $months->pluck('label');

        return view('mentor.dashboard', compact(
            'totalPendapatan',
            'totalPelajar',
            'kursusAktif',
            'chartLabels',
            'revenueChartData',
            'enrollmentChartData'
        ));
    }
}
