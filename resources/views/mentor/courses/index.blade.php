@extends('layouts.dashboard')

@section('title', 'Daftar Kursus | Dashboard Mentor | Clearn - Platform Pembelajaran Online')

@section('content')

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}"
    active="courses" />

<main class="flex-1 p-5 lg:p-8">
    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-lg font-bold dark:text-white text-slate-800">
                    Kursus Saya
                </h1>

                <p class="text-[11px] dark:text-slate-500 text-slate-400 font-medium">
                    Pantau Performa Materi Pelatihan Anda
                </p>
            </div>

            @if(auth()->user()->status === 'pending')
            <button onclick="showPendingAlert()"
                class="flex items-center gap-2 px-4 py-2 bg-primary text-white text-[10px] font-bold rounded-lg hover:brightness-110 transition-all uppercase tracking-widest shadow-lg shadow-primary/20 cursor-pointer">
                <i class="fas fa-plus text-[9px]"></i>
                Tambah Kursus
            </button>
            @elseif(auth()->user()->status === 'rejected')
            <button onclick="showRejectedAlert()"
                class="flex items-center gap-2 px-4 py-2 bg-primary text-white text-[10px] font-bold rounded-lg hover:brightness-110 transition-all uppercase tracking-widest shadow-lg shadow-primary/20 cursor-pointer">
                <i class="fas fa-plus text-[9px]"></i>
                Tambah Kursus
            </button>
            @else
            <a href="{{ route('mentor.courses.create') }}"
                class="flex items-center gap-2 px-4 py-2 bg-primary text-white text-[10px] font-bold rounded-lg hover:brightness-110 transition-all uppercase tracking-widest shadow-lg shadow-primary/20">
                <i class="fas fa-plus text-[9px]"></i>
                Tambah Kursus
            </a>
            @endif
        </div>

        {{-- Alert Success --}}
        @if(session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-4 py-3 rounded-xl text-xs font-bold">
            {{ session('success') }}
        </div>
        @endif

        {{-- Alert Error --}}
        @if(session('error'))
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl text-xs font-bold">
            {{ session('error') }}
        </div>
        @endif

        {{-- Popup Alert untuk Bank Kosong --}}
        @if(session('showBankPopup'))
        <div id="bankPopup" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm animate-fade-in">
            <div class="bg-white dark:bg-[#1c1826] border border-gray-100 dark:border-white/10 rounded-3xl p-8 max-w-md w-full shadow-2xl relative animate-scale-up">
                
                {{-- Tombol Close --}}
                <button onclick="document.getElementById('bankPopup').remove()" class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>

                <div class="flex flex-col items-center text-center">
                    {{-- Icon Peringatan --}}
                    <div class="w-20 h-20 bg-amber-500/10 rounded-full flex items-center justify-center mb-5">
                        <i class="fas fa-building-columns text-4xl text-amber-500"></i>
                    </div>

                    <h2 class="text-2xl font-black text-slate-800 dark:text-white mb-2">
                        Data Bank Belum Lengkap!
                    </h2>
                    
                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-8 font-medium">
                        Untuk dapat menambahkan kursus baru dan menerima pencairan dana, Anda wajib melengkapi informasi rekening bank terlebih dahulu.
                    </p>

                    <div class="flex flex-col sm:flex-row w-full gap-3">
                        <a href="{{ route('settings.edit') }}" class="flex-1 py-3 px-4 bg-[#7C3AED] hover:bg-[#6D28D9] text-white rounded-xl font-bold transition-all text-xs shadow-lg shadow-[#7C3AED]/30">
                            Isi Data Bank <i class="fas fa-arrow-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
            // Animasi untuk Popup
            document.head.insertAdjacentHTML("beforeend", `<style>
                @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
                @keyframes scaleUp { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
                .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
                .animate-scale-up { animation: scaleUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
            </style>`);
        </script>
        @endif

        {{-- Statistik Ringkas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

            {{-- Total Kursus --}}
            <div class="p-4 flex items-center justify-between dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800 mb-1">
                        {{ $totalCourse ?? 0 }}
                    </h2>

                    <span class="text-[8px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">
                        Total Kursus
                    </span>
                </div>

                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center text-blue-500">
                    <i class="fas fa-book text-base"></i>
                </div>
            </div>

            {{-- Total Approved --}}
            <div class="p-4 flex items-center justify-between dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800 mb-1">
                        {{ $totalApproved ?? 0 }}
                    </h2>

                    <span class="text-[8px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">
                        Sudah Terbit
                    </span>
                </div>

                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center text-emerald-500">
                    <i class="fas fa-check-circle text-base"></i>
                </div>
            </div>

            {{-- Total Pending --}}
            <div class="p-4 flex items-center justify-between dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800 mb-1">
                        {{ $totalPending ?? 0 }}
                    </h2>

                    <span class="text-[8px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">
                        Menunggu Verifikasi
                    </span>
                </div>

                <div class="w-8 h-8 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center text-amber-500">
                    <i class="fas fa-clock text-base"></i>
                </div>
            </div>

        </div>

        {{-- Daftar Kursus --}}
        @if($courses->count() > 0)

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

            @foreach($courses as $course)
            <div class="dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden flex flex-col group shadow-sm transition-all duration-300 hover:border-primary/50 hover:-translate-y-1">

                {{-- Thumbnail --}}
                <div class="relative h-48 overflow-hidden bg-slate-200 dark:bg-[#0f0a19]">

                    @if($course->course_thumbnail)
                    <img src="{{ asset('storage/' . $course->course_thumbnail) }}"
                        alt="{{ $course->course_title }}"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                        <i class="fas fa-image text-2xl mb-2"></i>

                        <span class="text-[9px] font-bold uppercase tracking-widest">
                            Tidak Ada Gambar
                        </span>
                    </div>
                    @endif

                    {{-- Status Review --}}
                    <div class="absolute top-2.5 right-2.5">

                        @if($course->status_review === 'approved')
                        <span class="bg-emerald-500 text-white text-[8px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg shadow-md">
                            Disetujui
                        </span>

                        @elseif($course->status_review === 'rejected')
                        <span class="bg-red-500 text-white text-[8px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg shadow-md">
                            Ditolak
                        </span>

                        @elseif($course->status_review === 'pending')
                        <span class="bg-amber-500 text-white text-[8px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg shadow-md">
                            Menunggu
                        </span>

                        @else
                        <span class="bg-blue-500 text-white text-[8px] font-black uppercase tracking-wider px-2.5 py-1 rounded-lg shadow-md">
                            Draf
                        </span>
                        @endif

                    </div>
                </div>

                {{-- Isi Card --}}
                <div class="p-5 flex-1 flex flex-col justify-between">

                    <div class="mb-4">
                        <h3 class="text-xs font-bold dark:text-white text-slate-800 mb-1.5 line-clamp-2 leading-snug">
                            {{ $course->course_title }}
                        </h3>

                        <p class="text-[9px] dark:text-slate-500 text-slate-400 font-medium uppercase tracking-widest">
                            {{ $course->category->category_name ?? 'Tanpa Kategori' }}
                        </p>
                    </div>

                    <div class="space-y-3.5">

                        {{-- Informasi Kursus --}}
                        <div class="flex items-center justify-between text-[10px] font-bold dark:text-slate-500 text-slate-400">

                            {{-- Rating --}}
                            <div class="flex items-center gap-1 text-amber-400">
                                <i class="fas fa-star text-[9px]"></i>

                                <span>
                                    {{ number_format($course->rates_avg_course_rate ?? 0, 1) }}
                                </span>
                            </div>

                            {{-- Jumlah Pelajar --}}
                            <div class="flex items-center gap-1 dark:text-slate-400 text-slate-500">
                                <i class="fas fa-users text-[9px]"></i>

                                <span>
                                    {{ $course->enrollments_count ?? 0 }} Pelajar
                                </span>
                            </div>

                        </div>

                        {{-- Tombol Detail --}}
                        <a href="{{ route('mentor.courses.show', $course->id) }}"
                            class="flex items-center justify-center gap-2 w-full py-2.5 bg-primary text-white text-[10px] font-bold rounded-lg shadow-lg shadow-primary/20 hover:brightness-110 transition-all uppercase tracking-widest active:scale-95">

                            <i class="fas fa-eye text-[9px]"></i>

                            Lihat Detail
                        </a>

                    </div>
                </div>

            </div>
            @endforeach

        </div>

        {{-- Pagination --}}
        @if($courses->hasPages())
        <div class="mt-8 p-4 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">

                {{-- Informasi Pagination --}}
                <p class="text-[10px] font-semibold dark:text-slate-500 text-slate-400">
                    Menampilkan

                    <span class="font-black dark:text-white text-slate-700">
                        {{ $courses->firstItem() }}
                    </span>

                    sampai

                    <span class="font-black dark:text-white text-slate-700">
                        {{ $courses->lastItem() }}
                    </span>

                    dari

                    <span class="font-black dark:text-white text-slate-700">
                        {{ $courses->total() }}
                    </span>

                    kursus
                </p>

                {{-- Tombol Pagination --}}
                <div class="flex items-center gap-1.5">

                    {{-- Tombol Sebelumnya --}}
                    @if($courses->onFirstPage())
                    <span class="w-9 h-9 flex items-center justify-center rounded-lg border dark:border-white/5 border-slate-200 dark:bg-white/5 bg-slate-50 dark:text-slate-700 text-slate-300 cursor-not-allowed">
                        <i class="fas fa-chevron-left text-[9px]"></i>
                    </span>
                    @else
                    <a href="{{ $courses->previousPageUrl() }}"
                        class="w-9 h-9 flex items-center justify-center rounded-lg border dark:border-white/5 border-slate-200 dark:bg-white/5 bg-slate-50 dark:text-slate-400 text-slate-500 hover:bg-primary hover:text-white hover:border-primary transition-all">
                        <i class="fas fa-chevron-left text-[9px]"></i>
                    </a>
                    @endif

                    {{-- Nomor Halaman --}}
                    @foreach($courses->getUrlRange(1, $courses->lastPage()) as $page => $url)

                    @if($page === $courses->currentPage())
                    <span class="w-9 h-9 flex items-center justify-center rounded-lg bg-primary text-white text-[10px] font-black shadow-lg shadow-primary/20">
                        {{ $page }}
                    </span>
                    @else
                    <a href="{{ $url }}"
                        class="w-9 h-9 flex items-center justify-center rounded-lg border dark:border-white/5 border-slate-200 dark:bg-white/5 bg-slate-50 dark:text-slate-400 text-slate-500 hover:bg-primary hover:text-white hover:border-primary text-[10px] font-bold transition-all">
                        {{ $page }}
                    </a>
                    @endif

                    @endforeach

                    {{-- Tombol Berikutnya --}}
                    @if($courses->hasMorePages())
                    <a href="{{ $courses->nextPageUrl() }}"
                        class="w-9 h-9 flex items-center justify-center rounded-lg border dark:border-white/5 border-slate-200 dark:bg-white/5 bg-slate-50 dark:text-slate-400 text-slate-500 hover:bg-primary hover:text-white hover:border-primary transition-all">
                        <i class="fas fa-chevron-right text-[9px]"></i>
                    </a>
                    @else
                    <span class="w-9 h-9 flex items-center justify-center rounded-lg border dark:border-white/5 border-slate-200 dark:bg-white/5 bg-slate-50 dark:text-slate-700 text-slate-300 cursor-not-allowed">
                        <i class="fas fa-chevron-right text-[9px]"></i>
                    </span>
                    @endif

                </div>
            </div>
        </div>
        @endif

        @else

        {{-- Empty State --}}
        <div class="dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl p-10 text-center shadow-sm">

            <div class="w-14 h-14 mx-auto rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-4">
                <i class="fas fa-book-open text-xl"></i>
            </div>

            <h2 class="text-sm font-black dark:text-white text-slate-800">
                Belum Ada Kursus
            </h2>

            <p class="text-[11px] dark:text-slate-500 text-slate-400 mt-2 max-w-md mx-auto">
                Buat kursus pertama kamu terlebih dahulu. Setelah kursus tersimpan,
                kamu dapat menambahkan session dan materi dari halaman detail.
            </p>

            @if(auth()->user()->status === 'pending')
            <button onclick="showPendingAlert()"
                class="inline-flex items-center gap-2 mt-5 px-4 py-2 bg-primary text-white text-[10px] font-bold rounded-lg hover:brightness-110 transition-all uppercase tracking-widest shadow-lg shadow-primary/20 cursor-pointer">
                <i class="fas fa-plus text-[9px]"></i>
                Tambah Kursus
            </button>
            @elseif(auth()->user()->status === 'rejected')
            <button onclick="showRejectedAlert()"
                class="inline-flex items-center gap-2 mt-5 px-4 py-2 bg-primary text-white text-[10px] font-bold rounded-lg hover:brightness-110 transition-all uppercase tracking-widest shadow-lg shadow-primary/20 cursor-pointer">
                <i class="fas fa-plus text-[9px]"></i>
                Tambah Kursus
            </button>
            @else
            <a href="{{ route('mentor.courses.create') }}"
                class="inline-flex items-center gap-2 mt-5 px-4 py-2 bg-primary text-white text-[10px] font-bold rounded-lg hover:brightness-110 transition-all uppercase tracking-widest shadow-lg shadow-primary/20">

                <i class="fas fa-plus text-[9px]"></i>

                Tambah Kursus
            </a>
            @endif

        </div>

        @endif

    </div>
</main>

@endsection