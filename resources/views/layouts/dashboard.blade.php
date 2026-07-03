<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-clearn.png') }}">
    <title>@yield('title', 'CLEARN – Dashboard')</title>

    <!-- Dark mode awal supaya tidak kedip -->
    <script>
        if (
            localStorage.theme === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>

<body class="antialiased">
    <div class="flex min-h-screen">
        @yield('content')
    </div>
</body>

@stack('library')
@stack('scripts')

@if(auth()->check() && auth()->user()->role === 'mentor' && auth()->user()->status === 'pending')
{{-- Modal Status Pending --}}
<div id="pendingModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-4 bg-black/20 backdrop-blur-sm transition-all">
    <div class="bg-white dark:bg-[#161525] p-8 rounded-2xl shadow-2xl border dark:border-white/5 w-full max-w-sm text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-amber-50 dark:bg-amber-500/10 flex items-center justify-center">
            <i class="fas fa-exclamation-triangle text-2xl text-amber-500"></i>
        </div>
        <h3 class="text-lg font-black text-slate-800 dark:text-white mb-2">Akun Belum Diverifikasi</h3>
        <p class="text-xs text-slate-400 mb-8 font-medium">Status akun Anda saat ini masih pending. Silakan tunggu konfirmasi dari admin untuk dapat menambahkan kursus baru.</p>

        <div class="flex justify-center">
            <button onclick="document.getElementById('pendingModal').classList.add('hidden')"
                class="w-full py-3 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:brightness-110 transition-all shadow-lg shadow-primary/20">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
    function showPendingAlert() {
        document.getElementById('pendingModal').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (!sessionStorage.getItem('mentorPendingAlertShown')) {
            showPendingAlert();
            sessionStorage.setItem('mentorPendingAlertShown', 'true');
        }
    });
</script>
@endif

@if(auth()->check() && auth()->user()->role === 'mentor' && auth()->user()->status === 'active')
{{-- Modal Status Active --}}
<div id="activeModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-4 bg-black/20 backdrop-blur-sm transition-all">
    <div class="bg-white dark:bg-[#161525] p-8 rounded-2xl shadow-2xl border dark:border-white/5 w-full max-w-sm text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center">
            <i class="fas fa-check text-2xl text-emerald-500"></i>
        </div>
        <h3 class="text-lg font-black text-slate-800 dark:text-white mb-2">Selamat! Akun Diverifikasi</h3>
        <p class="text-xs text-slate-400 mb-8 font-medium">Akun pengajar Anda telah disetujui oleh admin. Sekarang Anda dapat mulai membuat dan mengelola kursus Anda.</p>

        <div class="flex justify-center">
            <button onclick="document.getElementById('activeModal').classList.add('hidden')"
                class="w-full py-3 bg-primary text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:brightness-110 transition-all shadow-lg shadow-primary/20">
                Mulai Mengajar
            </button>
        </div>
    </div>
</div>

<script>
    function showActiveAlert() {
        document.getElementById('activeModal').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (!localStorage.getItem('mentorActiveAlertShown_' + '{{ auth()->user()->id }}')) {
            showActiveAlert();
            localStorage.setItem('mentorActiveAlertShown_' + '{{ auth()->user()->id }}', 'true');
        }
    });
</script>
@endif

@if(auth()->check() && auth()->user()->role === 'mentor' && auth()->user()->status === 'rejected')
@php
    $rejectionRecord = \App\Models\DetailVerify::where('mentor_id', auth()->user()->id)
        ->where('action', 'rejected')
        ->latest('verify_at')
        ->first();
@endphp
{{-- Modal Status Rejected --}}
<div id="rejectedModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-4 bg-black/20 backdrop-blur-sm transition-all">
    <div class="bg-white dark:bg-[#161525] p-8 rounded-2xl shadow-2xl border dark:border-white/5 w-full max-w-sm text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-50 dark:bg-red-500/10 flex items-center justify-center">
            <i class="fas fa-times text-2xl text-red-500"></i>
        </div>
        <h3 class="text-lg font-black text-slate-800 dark:text-white mb-2">Akun Ditolak</h3>
        <p class="text-xs text-slate-400 mb-4 font-medium">Mohon maaf, pengajuan akun pengajar Anda ditolak oleh admin karena alasan berikut:</p>
        
        <div class="bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 p-3 rounded-xl text-xs text-left mb-6 font-medium">
            "{{ $rejectionRecord ? $rejectionRecord->mentor_rejection_reason : 'Alasan tidak ditemukan.' }}"
        </div>

        <p class="text-[11px] text-slate-500 dark:text-slate-400 mb-8">Silakan perbaiki data di halaman Pengaturan dan tunggu proses peninjauan ulang.</p>

        <div class="flex justify-center">
            <button onclick="document.getElementById('rejectedModal').classList.add('hidden')"
                class="w-full py-3 bg-red-500 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:brightness-110 transition-all shadow-lg shadow-red-500/20">
                Mengerti
            </button>
        </div>
    </div>
</div>

<script>
    function showRejectedAlert() {
        document.getElementById('rejectedModal').classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        if (!sessionStorage.getItem('mentorRejectedAlertShown')) {
            showRejectedAlert();
            sessionStorage.setItem('mentorRejectedAlertShown', 'true');
        }
    });
</script>
@endif

</html>