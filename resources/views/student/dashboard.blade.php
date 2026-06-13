@extends('layouts.dashboard')

@section('title', 'Dashboard Pelajar | Clearn - Platform Pembelajaran Online')

@section('content')

<!-- Component Sidebar -->
<x-dashboard.sidebar role="student" :name="auth()->user()?->name ?? 'Guest'" active="my-courses" />

<!-- Content -->
<main class="flex-1 p-6 lg:p-10 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50">
    <div class="max-w-6xl mx-auto">

        {{-- Header Halaman --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-xl font-bold dark:text-white text-slate-800">Beranda Pelajar</h1>
                <p class="text-[11px] dark:text-slate-500 text-slate-400">Lanjutkan pembelajaran dari kursus yang telah Anda ikuti</p>
            </div>
            <button class="bg-primary text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 transition hover:scale-105 shadow-lg shadow-primary/20 active:scale-95">
                <i class="fa-solid fa-magnifying-glass"></i> Cari Kursus Baru
            </button>
        </div>

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <!-- Statistik 1 -->
            <div class="p-5 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-500 mb-3 text-sm">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <h3 class="text-xl font-black dark:text-white text-slate-800">3</h3>
                <p class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-wider mt-1">Kursus Diikuti</p>
            </div>
            <!-- Statistik 2 -->
            <div class="p-5 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500 mb-3 text-sm">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
                <h3 class="text-xl font-black dark:text-white text-slate-800">45%</h3>
                <p class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-wider mt-1">Total Progres</p>
            </div>
            <!-- Statistik 3 -->
            <div class="p-5 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-green-500/10 flex items-center justify-center text-green-500 mb-3 text-sm">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <h3 class="text-xl font-black dark:text-white text-slate-800">4</h3>
                <p class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-wider mt-1">Kursus Selesai</p>
            </div>
            <!-- Statistik 4 -->
            <div class="p-5 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-yellow-500/10 flex items-center justify-center text-yellow-500 mb-3 text-sm">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <h3 class="text-xl font-black dark:text-white text-slate-800">12</h3>
                <p class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-wider mt-1">Poin Sertifikat</p>
            </div>
        </div>

        {{-- List Kursus (Horizontal Card Style) --}}
        <div class="space-y-4 mb-6">

            {{-- Kursus 1: Program Pelatihan Lengkap Pengembangan Web --}}
            <div class="dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden flex flex-col md:flex-row hover:border-primary/50 transition-colors shadow-sm group">
                <div class="w-full md:w-52 aspect-[16/10] md:aspect-auto overflow-hidden bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-2 gap-2">
                            <div>
                                <h3 class="font-bold text-sm leading-tight dark:text-white text-slate-800 mb-1">Program Pelatihan Lengkap Pengembangan Web</h3>
                                <p class="text-[10px] dark:text-slate-500 text-slate-400 font-medium">Tim Mentor Clearn</p>
                            </div>
                            <span class="text-[10px] font-black text-primary bg-primary/10 px-2 py-1 rounded-md">65%</span>
                        </div>

                        <div class="w-full dark:bg-[#0b0a1a] bg-slate-100 h-1.5 rounded-full mb-1">
                            <div class="bg-primary h-1.5 rounded-full shadow-[0_0_8px_rgba(124,58,237,0.4)]" style="width: 65%"></div>
                        </div>
                        <p class="text-[9px] dark:text-slate-500 text-slate-400 font-bold uppercase tracking-wider">25 dari 38 pelajaran</p>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-3 mt-3 border-t dark:border-white/5 border-slate-100">
                        <p class="text-[10px] dark:text-slate-400 text-slate-500">
                            Terakhir: <span class="dark:text-white text-slate-800 font-bold">Membangun Website Responsive...</span>
                        </p>
                        <button onclick="window.location.href='{{ route('student.lesson') }}'"
                            class="w-full sm:w-auto bg-primary text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-primary/30 transition-all active:scale-95">
                            <i class="fa-solid fa-play"></i> Lanjutkan
                        </button>
                    </div>
                </div>
            </div>

            {{-- Kursus 2: Dasar UI/UX Design untuk Pemula (KARTU BARU) --}}
            <div class="dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden flex flex-col md:flex-row hover:border-emerald-500/50 transition-colors shadow-sm group">
                <div class="w-full md:w-52 aspect-[16/10] md:aspect-auto overflow-hidden bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?q=80&w=400&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-2 gap-2">
                            <div>
                                <h3 class="font-bold text-sm leading-tight dark:text-white text-slate-800 mb-1">Dasar UI/UX Design untuk Pemula</h3>
                                <p class="text-[10px] dark:text-slate-500 text-slate-400 font-medium">Mentor Desain Clearn</p>
                            </div>
                            <span class="text-[10px] font-black text-emerald-500 bg-emerald-500/10 px-2 py-1 rounded-md">LULUS</span>
                        </div>

                        <div class="w-full dark:bg-[#0b0a1a] bg-slate-100 h-1.5 rounded-full mb-1">
                            <div class="bg-emerald-500 h-1.5 rounded-full shadow-[0_0_8px_rgba(16,185,129,0.4)]" style="width: 100%"></div>
                        </div>
                        <div class="flex items-center gap-1.5 text-[9px] text-emerald-500 font-bold uppercase tracking-wider">
                            <i class="fas fa-shield-alt"></i> Sertifikat Tersedia
                        </div>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-3 mt-3 border-t dark:border-white/5 border-slate-100">
                        <p class="text-[10px] dark:text-slate-400 text-slate-500">
                            Status: <span class="dark:text-white text-slate-800 font-bold">Semua materi telah diselesaikan</span>
                        </p>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button class="flex-1 sm:flex-initial bg-emerald-500 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-emerald-500/30 transition-all active:scale-95">
                                Klaim Sertifikat
                            </button>
                            <button class="px-4 py-2.5 border dark:border-white/5 border-slate-200 dark:text-slate-400 text-slate-500 dark:bg-[#0b0a1a] bg-transparent rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 transition-all active:scale-95 flex items-center justify-center">
                                <i class="fas fa-sync-alt text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer CTA Section --}}
        <div class="bg-primary p-8 rounded-2xl text-center text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="font-bold text-sm mb-1">Siap lanjut belajar?</h3>
                <p class="text-[11px] text-white/80 mb-6 font-medium">Temukan lebih banyak kursus untuk meningkatkan skill digital Anda</p>
                <button class="bg-white text-primary px-8 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:scale-105 transition-transform active:scale-95">
                    Jelajahi Semua Kursus <i class="fa-solid fa-chevron-right ml-2 text-[9px]"></i>
                </button>
            </div>

            {{-- Aksen Ornamen Lingkaran Khas Clearn CTA --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
        </div>

    </div>
</main>

@endsection