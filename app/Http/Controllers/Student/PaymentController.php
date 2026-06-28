<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Payment;
use App\Models\RevenueShare;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function history()
    {
        $user = Auth::user();

        // Ambil riwayat pembayaran milik student yang login
        $payments = Payment::whereHas('enrollment', function ($query) use ($user) {
                $query->where('student_id', $user->id);
            })
            ->with(['enrollment.course.mentor'])
            ->latest()
            ->paginate(10);

        // Hitung statistik untuk dashboard history
        $totalOrders = Payment::whereHas('enrollment', function ($query) use ($user) {
            $query->where('student_id', $user->id);
        })->count();

        $totalSpent = Payment::whereHas('enrollment', function ($query) use ($user) {
            $query->where('student_id', $user->id);
        })->where('connection_status', 'success')->sum('gross_amount');

        $activeCourses = Enrollment::where('student_id', $user->id)
            ->where(function($query) {
                $query->whereHas('course', function($q) {
                    $q->where('course_price', 0);
                })->orWhereHas('payment', function($q) {
                    $q->where('connection_status', 'success');
                });
            })->count();

        return view('student.history.index', compact('payments', 'totalOrders', 'totalSpent', 'activeCourses'));
    }

    public function checkout(Request $request, $course_id)
    {
        $course = Course::findOrFail($course_id);
        $user = Auth::user();

        // Cek apakah user sudah enroll kursus ini sebelumnya
        $existingEnrollment = Enrollment::where('student_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        // Jika sudah enroll dan gratis/sudah lunas, jangan izinkan beli lagi
        if ($existingEnrollment) {
            $existingPayment = Payment::where('enrollment_id', $existingEnrollment->id)
                ->where('connection_status', 'success')
                ->first();
            
            if ($existingPayment || $course->course_price == 0) {
                 return redirect()->route('student.course.show', $course->slug)
                     ->with('error', 'Anda sudah terdaftar di kursus ini.');
            }
        }

        // Buat Enrollment baru atau ambil yang pending
        if (!$existingEnrollment) {
            $existingEnrollment = Enrollment::create([
                'student_id' => $user->id,
                'course_id' => $course->id,
                'progress' => 0,
                'start_date' => now(),
            ]);
        }

        // Buat Payment Record
        // Generate Order ID (kombinasi enrollment_id dan timestamp untuk unik)
        $orderId = 'TRX-' . $existingEnrollment->id . '-' . time();
        $grossAmount = $course->course_price;

        $payment = Payment::create([
            'enrollment_id' => $existingEnrollment->id,
            'midtrans_order_id' => $orderId,
            'transaction_id' => null, // Akan diisi saat webhook callback
            'payment_type' => null,
            'connection_status' => 'pending',
            'gross_amount' => $grossAmount,
            'paid_at' => null,
            'raw_response' => null,
        ]);

        // Setup Parameter Midtrans
        $params = array(
            'transaction_details' => array(
                'order_id' => $orderId,
                'gross_amount' => $grossAmount,
            ),
            'customer_details' => array(
                'first_name' => $user->name,
                'email' => $user->email,
            ),
            'item_details' => array(
                array(
                    'id' => $course->id,
                    'price' => $grossAmount,
                    'quantity' => 1,
                    'name' => $course->course_title
                )
            )
        );

        try {
            // Dapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);
            
            // Tampilkan view checkout beserta token-nya
            return view('student.courses.checkout', compact('course', 'snapToken', 'payment'));
            
        } catch (\Exception $e) {
            // Tangani jika terjadi error dari Midtrans
            return redirect()->back()->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function webhook(Request $request)
    {
        try {
            $notification = new Notification();

            $transaction = $notification->transaction_status;
            $type = $notification->payment_type;
            $orderId = $notification->order_id;
            $fraud = $notification->fraud_status;
            
            $payment = Payment::where('midtrans_order_id', $orderId)->first();
            
            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }
            
            $grossAmount = $payment->gross_amount;
            
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $payment->connection_status = 'challenge';
                    } else {
                        $payment->connection_status = 'success';
                    }
                }
            } else if ($transaction == 'settlement') {
                $payment->connection_status = 'success';
            } else if ($transaction == 'pending') {
                $payment->connection_status = 'pending';
            } else if ($transaction == 'deny') {
                $payment->connection_status = 'failed';
            } else if ($transaction == 'expire') {
                $payment->connection_status = 'expired';
            } else if ($transaction == 'cancel') {
                $payment->connection_status = 'canceled';
            }
            
            $payment->transaction_id = $notification->transaction_id;
            $payment->payment_type = $type;
            
            if ($payment->connection_status == 'success') {
                $payment->paid_at = now();
                
                // Distribusi Revenue Share (Opsi A: Bagi dari Gross Amount)
                // Pastikan belum pernah dibagi hasilnya (mencegah webhook berulang membagi hasil dobel)
                $hasShared = RevenueShare::where('payment_id', $payment->id)->exists();
                
                if (!$hasShared && $payment->enrollment && $payment->enrollment->course) {
                    $course = $payment->enrollment->course;
                    $mentorId = $course->mentor_id;
                    
                    // Admin mendapatkan 20%
                    $adminPercentage = 20;
                    $adminAmount = ($grossAmount * $adminPercentage) / 100;
                    
                    // Mentor mendapatkan 80%
                    $mentorPercentage = 80;
                    $mentorAmount = ($grossAmount * $mentorPercentage) / 100;
                    
                    // Create Revenue Share for Mentor
                    $mentorShare = RevenueShare::create([
                        'payment_id' => $payment->id,
                        'user_id' => $mentorId,
                        'receiver_role' => 'mentor',
                        'percentage' => $mentorPercentage,
                        'total_amount' => $grossAmount,
                        'amount' => $mentorAmount,
                        'status' => 'success',
                    ]);
                    
                    // Add balance to Mentor's Wallet
                    $mentorWallet = Wallet::firstOrCreate(
                        ['user_id' => $mentorId],
                        ['balance' => 0]
                    );
                    $mentorWallet->increment('balance', $mentorAmount);
                    
                    WalletTransaction::create([
                        'wallet_id' => $mentorWallet->id,
                        'revenue_shares_id' => $mentorShare->id,
                        'wallet_permissions' => 'credit', // Credit means adding money
                        'source_id' => $payment->id,
                        'source_type' => Payment::class,
                        'source_amount' => $grossAmount,
                        'amount' => $mentorAmount,
                    ]);
                    
                    // Note: Untuk admin, Anda bisa juga mencatatnya ke tabel Admin Wallet jika ada.
                    // Jika tidak ada tabel dompet khusus admin, kita hanya catat RevenueShare-nya saja sebagai history laporan
                    // Anggap User ID 1 adalah Super Admin (atau ambil dari konstan)
                    RevenueShare::create([
                        'payment_id' => $payment->id,
                        'user_id' => 1, // Ganti dengan ID Admin Anda jika dinamis
                        'receiver_role' => 'admin',
                        'percentage' => $adminPercentage,
                        'total_amount' => $grossAmount,
                        'amount' => $adminAmount,
                        'status' => 'success',
                    ]);
                }
            }
            
            $payment->raw_response = json_encode($notification);
            $payment->save();

            return response()->json(['message' => 'Webhook handled successfully']);
            
        } catch (\Exception $e) {
            Log::error('Midtrans Webhook Error: ' . $e->getMessage());
            return response()->json(['message' => 'Internal server error'], 500);
        }
    }
}
