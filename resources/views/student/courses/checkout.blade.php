@extends('layouts.sections')

@section('title', 'CLEARN │ Checkout - ' . $course->course_title)

@section('content')
<div class="p-6 lg:p-10 flex justify-center items-center min-h-[70vh]">
    <div class="max-w-md w-full bg-white dark:bg-[#1A1625] border border-gray-200 dark:border-[#2d2644] rounded-2xl p-8 shadow-lg text-center">
        
        <div class="mb-6">
            <i class="fas fa-wallet text-5xl text-primary mb-4"></i>
            <h2 class="text-2xl font-black mb-2">Selesaikan Pembayaran</h2>
            <p class="text-muted-custom text-sm">Anda akan membeli kelas <strong>{{ $course->course_title }}</strong>.</p>
        </div>

        <div class="bg-gray-50 dark:bg-[#1A1625] rounded-xl p-4 mb-8 text-left border border-gray-100 dark:border-[#2d2644]">
            <div class="flex justify-between mb-2">
                <span class="text-muted-custom text-sm">Total Tagihan</span>
                <span class="font-bold">Rp{{ number_format($payment->gross_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-muted-custom text-sm">Order ID</span>
                <span class="font-bold text-sm">{{ $payment->midtrans_order_id }}</span>
            </div>
        </div>

        <button id="pay-button" class="w-full bg-primary hover:brightness-110 text-white font-black py-4 rounded-xl flex items-center justify-center gap-3 transition-all active:scale-95 shadow-lg shadow-primary/20 uppercase tracking-[0.2em] text-[10px]">
            <i class="fas fa-money-bill-wave text-sm"></i>
            Bayar Sekarang
        </button>
        
        <p class="text-xs text-muted-custom mt-4">Pilih metode pembayaran pada popup yang muncul.</p>
    </div>
</div>
@endsection

@push('scripts')
<!-- Masukkan script Midtrans -->
<!-- Gunakan link sandbox ini jika sedang testing. Jika live, gunakan: https://app.midtrans.com/snap/snap.js -->
<script src="{{ config('midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('midtrans.client_key') }}"></script>

<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(){
        // SnapToken dari controller diletakkan di sini
        snap.pay('{{ $snapToken }}', {
            // Callback jika pembayaran berhasil
            onSuccess: function(result){
                console.log(result);
                alert("Pembayaran berhasil!");
                // Arahkan ke route verifikasi pembayaran dengan order_id
                window.location.href = "{{ route('student.payment.success') }}?order_id=" + result.order_id;
            },
            // Callback jika pembayaran masih pending
            onPending: function(result){
                console.log(result);
                alert("Menunggu pembayaran Anda!"); 
                window.location.href = "{{ route('student.course.show', $course->course_slug) }}";
            },
            // Callback jika pembayaran gagal
            onError: function(result){
                console.log(result);
                alert("Pembayaran gagal!");
            },
            // Callback jika user menutup popup
            onClose: function(){
                alert('Anda menutup jendela popup tanpa menyelesaikan pembayaran');
            }
        });
    };
</script>
@endpush