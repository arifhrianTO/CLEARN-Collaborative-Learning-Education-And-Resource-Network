<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

#[Signature('mentor:payout')]
#[Description('Otomatis transfer saldo mentor ke rekening bank setiap tanggal 10')]
class PayoutMentorCommand extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Mentor Payout process...');
        
        // Cari semua wallet mentor yang saldonya lebih dari 0
        $wallets = Wallet::where('balance', '>', 0)
            ->whereHas('user', function($query) {
                $query->where('role', 'mentor');
            })
            ->with(['user.bank']) // Pastikan relasi ke model Bank dipanggil
            ->get();

        if ($wallets->isEmpty()) {
            $this->info('Tidak ada mentor yang perlu dibayar saat ini.');
            return;
        }

        foreach ($wallets as $wallet) {
            $mentor = $wallet->user;
            $bank = $mentor->bank;
            $payoutAmount = $wallet->balance;

            // Pastikan mentor sudah memasukkan rekening yang aktif
            if (!$bank || $bank->bank_account_status !== 'active') {
                Log::warning("Mentor Payout: Gagal payout untuk Mentor {$mentor->name} karena rekening bank belum diatur atau tidak aktif.");
                $this->warn("Skipped mentor: {$mentor->name} (No active bank account)");
                continue;
            }

            DB::beginTransaction();
            try {
                // Di sini Anda memanggil API Midtrans Payout (Iris).
                // Karena Midtrans Iris butuh integrasi dan credentials tersendiri, kita akan 
                // merepresentasikan proses tersebut dengan log untuk MVP.
                
                // --- START API IRIS INTEGRATION ---
                // $iris = new \Midtrans\Payouts();
                // $response = $iris->create([
                //     "payouts" => [
                //         [
                //             "beneficiary_name" => $bank->bank_holder,
                //             "beneficiary_account" => $bank->bank_account,
                //             "beneficiary_bank" => $bank->bank_name,
                //             "beneficiary_email" => $mentor->email,
                //             "amount" => $payoutAmount,
                //             "notes" => "Payout Penghasilan Kursus"
                //         ]
                //     ]
                // ]);
                // --- END API IRIS INTEGRATION ---

                // Mengosongkan saldo wallet mentor (reset ke 0)
                $wallet->balance = 0;
                $wallet->save();

                // Mencatat transaksi payout (debit) di tabel WalletTransaction
                WalletTransaction::create([
                    'wallet_id' => $wallet->id,
                    'revenue_shares_id' => null, // Payout tidak ada revenue share ID (ini pengeluaran uang)
                    'wallet_permissions' => 'debit', // Debit berarti mengurangi saldo
                    'source_id' => null,
                    'source_type' => 'payout', 
                    'source_amount' => $payoutAmount,
                    'amount' => $payoutAmount,
                ]);

                DB::commit();
                
                $msg = "Berhasil membuat payout untuk Mentor {$mentor->name} sebesar Rp " . number_format($payoutAmount, 0, ',', '.');
                Log::info("Mentor Payout: " . $msg);
                $this->info($msg);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Mentor Payout: Gagal payout untuk Mentor {$mentor->name}. Error: " . $e->getMessage());
                $this->error("Failed to process payout for {$mentor->name}");
            }
        }

        $this->info('Mentor Payout process completed.');
    }
}

