<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\RevenueShare;
use Illuminate\Support\Facades\Auth;

class FinanceController extends Controller
{
    public function index()
    {
        // LOGIC DARI ADMIN (DIPINDAH KE MENTOR)
        $successStatuses = ['success', 'settlement', 'paid', 'sukses'];
        $mentorId = Auth::id(); // Filter tambahan untuk mentor

        /*
        |--------------------------------------------------------------------------
        | Total Penjualan Bruto
        |--------------------------------------------------------------------------
        */
        $totalGross = Payment::whereIn('connection_status', $successStatuses)
            ->whereHas('enrollment.course', function ($query) use ($mentorId) {
                $query->where('mentor_id', $mentorId);
            })
            ->sum('gross_amount');

        /*
        |--------------------------------------------------------------------------
        | Komisi
        |--------------------------------------------------------------------------
        */
        $totalNet = RevenueShare::where('receiver_role', 'mentor')
            ->where('user_id', $mentorId)
            ->where('status', 'success')
            ->sum('amount');

        if ($totalNet <= 0 && $totalGross > 0) {
            $totalNet = $totalGross * 0.80; // Fallback untuk mentor
        }

        /*
        |--------------------------------------------------------------------------
        | Pending Payout Mentor
        |--------------------------------------------------------------------------
        */
        $pendingPayouts = RevenueShare::where('receiver_role', 'mentor')
            ->where('user_id', $mentorId)
            ->where('status', 'pending')
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Transaksi Terbaru
        |--------------------------------------------------------------------------
        */
        $transactions = Payment::with([
                'enrollment.student',
                'enrollment.course.mentor',
                'revenueShares' => function ($query) use ($mentorId) {
                    $query->where('user_id', $mentorId);
                },
            ])
            ->whereHas('enrollment.course', function ($query) use ($mentorId) {
                $query->where('mentor_id', $mentorId);
            })
            ->latest()
            ->take(20)
            ->get()
            ->map(function ($payment) use ($mentorId) {
                $courseName = $payment->enrollment?->course?->course_title ?? 'Kursus';
                $studentName = $payment->enrollment?->student?->name ?? 'Pelajar';
                
                // Ambil fee potongan mentor dari tabel revenue_share kalau ada
                $mentorShare = $payment->revenueShares->first();
                $platformFee = $payment->gross_amount - ($mentorShare ? $mentorShare->amount : ($payment->gross_amount * 0.80));

                return [
                    'id' => $payment->midtrans_order_id ?? 'PAY-' . $payment->id,
                    'name' => $studentName,
                    'description' => 'Membeli: ' . $courseName,
                    'type' => 'Pembelian',
                    'amount' => $payment->gross_amount,
                    'platform_fee' => $platformFee,
                    'status' => $payment->connection_status,
                ];
            });

        return view('mentor.finance.index', compact(
            'totalGross',
            'totalNet',
            'pendingPayouts',
            'transactions'
        ));
    }
}