<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\WalletTransaction;
use Illuminate\Support\Collection;

class FinanceController extends Controller
{
    public function index()
    {
        // LOGIC DARI MENTOR (DIPINDAH KE ADMIN)
        $mentorId = auth()->id(); // Untuk admin ini mungkin gak relevan filter per user, tapi kita keep logic aslinya

        $successStatuses = ['success', 'settlement', 'paid', 'sukses'];

        /*
        |--------------------------------------------------------------------------
        | Pemasukan
        |--------------------------------------------------------------------------
        */
        $payments = Payment::with([
                'enrollment.course',
                'enrollment.student',
            ])
            ->whereIn('connection_status', $successStatuses)
            ->latest()
            ->get();

        $totalPemasukan = $payments->sum(function ($payment) {
            return ($payment->gross_amount ?? 0); // Admin melihat 100% dari Gross
        });

        /*
        |--------------------------------------------------------------------------
        | Pengeluaran
        |--------------------------------------------------------------------------
        */
        $walletTransactions = collect(); // Admin belum tentu punya wallet transaction
        if (class_exists(\App\Models\WalletTransaction::class)) {
            $walletTransactions = WalletTransaction::with('wallet')
                ->whereHas('wallet', function ($query) use ($mentorId) {
                    $query->where('user_id', $mentorId);
                })
                ->latest()
                ->get();
        }

        $totalPengeluaran = $walletTransactions
            ->filter(function ($transaction) {
                return strtolower($transaction->wallet_permissions ?? '') === 'withdraw';
            })
            ->sum('amount');

        // Net profit Admin adalah 20% dari total pemasukan (Gross) dikurangi pengeluaran jika ada
        $netProfit = ($totalPemasukan * 0.20) - $totalPengeluaran;

        /*
        |--------------------------------------------------------------------------
        | Gabungkan Payment + Wallet Transaction
        |--------------------------------------------------------------------------
        */
        $paymentCashFlows = $payments->map(function ($payment) {
            $courseName = $payment->enrollment?->course?->course_title
                ?? $payment->enrollment?->course?->course_name
                ?? 'Kursus';

            $studentName = $payment->enrollment?->student?->name ?? 'Pelajar';

            return [
                'id_transaksi' => $payment->midtrans_order_id
                    ?? $payment->transaction_id
                    ?? 'PAY-' . $payment->id,

                'deskripsi' => 'Pembelian kursus ' . $courseName . ' oleh ' . $studentName,

                'jenis' => 'PEMASUKAN',

                'nominal' => ($payment->gross_amount ?? 0), // Admin melihat nominal gross (harga asli) di list

                'tanggal' => $payment->created_at,

                'status' => $payment->connection_status ?? 'success',
            ];
        });

        $walletCashFlows = $walletTransactions->map(function ($transaction) {
            $permission = strtoupper($transaction->wallet_permissions ?? 'TRANSAKSI');

            $jenis = strtolower($transaction->wallet_permissions ?? '') === 'withdraw'
                ? 'PENGELUARAN'
                : $permission;

            return [
                'id_transaksi' => 'WLT-' . $transaction->id,

                'deskripsi' => $transaction->source_type
                    ? 'Transaksi wallet dari ' . $transaction->source_type
                    : 'Transaksi wallet',

                'jenis' => $jenis,

                'nominal' => $transaction->amount ?? 0,

                'tanggal' => $transaction->created_at,

                'status' => $transaction->wallet_permissions ?? 'success',
            ];
        });

        $cashFlows = collect()
            ->merge($paymentCashFlows)
            ->merge($walletCashFlows)
            ->sortByDesc('tanggal')
            ->values();

        return view('admin.finance.index', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'netProfit',
            'cashFlows'
        ));
    }
}