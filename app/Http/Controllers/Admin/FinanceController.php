<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\RevenueShare;

class FinanceController extends Controller
{
    public function index()
    {
        $successStatuses = ['success', 'settlement', 'paid', 'sukses'];

        /*
        |--------------------------------------------------------------------------
        | Total Penjualan Bruto
        |--------------------------------------------------------------------------
        | Diambil dari semua pembayaran yang sudah sukses.
        */
        $totalGross = Payment::whereIn('connection_status', $successStatuses)
            ->sum('gross_amount');

        /*
        |--------------------------------------------------------------------------
        | Komisi Platform
        |--------------------------------------------------------------------------
        | Diambil dari revenue_shares milik admin.
        | Kalau revenue_shares belum tergenerate, fallback 20% dari gross.
        */
        $totalNet = RevenueShare::where('receiver_role', 'admin')
            ->where('status', 'success')
            ->sum('amount');

        if ($totalNet <= 0 && $totalGross > 0) {
            $totalNet = $totalGross * 0.20;
        }

        /*
        |--------------------------------------------------------------------------
        | Pending Payout Mentor
        |--------------------------------------------------------------------------
        | Dana mentor yang status revenue share-nya masih pending.
        */
        $pendingPayouts = RevenueShare::where('receiver_role', 'mentor')
            ->where('status', 'pending')
            ->sum('amount');

        /*
        |--------------------------------------------------------------------------
        | Transaksi Terbaru
        |--------------------------------------------------------------------------
        | Ambil pembayaran terbaru beserta student, course, mentor, dan revenue share.
        */
        $transactions = Payment::with([
                'enrollment.student',
                'enrollment.course.mentor',
                'revenueShares.user',
            ])
            ->latest()
            ->take(20)
            ->get();

        return view('admin.finance.index', compact(
            'totalGross',
            'totalNet',
            'pendingPayouts',
            'transactions'
        ));
    }
}