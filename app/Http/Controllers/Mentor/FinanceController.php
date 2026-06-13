<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\WalletTransaction;
use Illuminate\Support\Collection;

class FinanceController extends Controller
{
    public function index()
    {
        $mentorId = auth()->id();

        $successStatuses = ['success', 'settlement', 'paid', 'sukses'];

        /*
        |--------------------------------------------------------------------------
        | Pemasukan Mentor
        |--------------------------------------------------------------------------
        | Diambil dari pembayaran course yang mentor_id-nya adalah user login.
        | Karena platform ambil 20%, mentor dapat 80%.
        */
        $payments = Payment::with([
                'enrollment.course',
                'enrollment.student',
            ])
            ->whereIn('connection_status', $successStatuses)
            ->whereHas('enrollment.course', function ($query) use ($mentorId) {
                $query->where('mentor_id', $mentorId);
            })
            ->latest()
            ->get();

        $totalPemasukan = $payments->sum(function ($payment) {
            return ($payment->gross_amount ?? 0) * 0.80;
        });

        /*
        |--------------------------------------------------------------------------
        | Pengeluaran Mentor
        |--------------------------------------------------------------------------
        | Diambil dari wallet transaction mentor.
        | Sesuaikan wallet_permissions dengan isi database kamu.
        */
        $walletTransactions = WalletTransaction::with('wallet')
            ->whereHas('wallet', function ($query) use ($mentorId) {
                $query->where('user_id', $mentorId);
            })
            ->latest()
            ->get();

        $totalPengeluaran = $walletTransactions
            ->filter(function ($transaction) {
                return strtolower($transaction->wallet_permissions ?? '') === 'withdraw';
            })
            ->sum('amount');

        $netProfit = $totalPemasukan - $totalPengeluaran;

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

                'nominal' => ($payment->gross_amount ?? 0) * 0.80,

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
                    : 'Transaksi wallet mentor',

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

        return view('mentor.finance.index', compact(
            'totalPemasukan',
            'totalPengeluaran',
            'netProfit',
            'cashFlows'
        ));
    }
}