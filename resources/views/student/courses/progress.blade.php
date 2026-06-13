@extends('layouts.dashboard')

@section('content')

<!-- Component Sidebar -->
<x-dashboard.sidebar role="student" name="User Pelajar" active="progress" />

<!-- Content -->
<main class="flex-1 p-6 lg:p-10 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50">
    <div class="max-w-5xl mx-auto">

        {{-- Header Halaman --}}
        <div class="mb-6">
            <h1 class="text-xl font-bold dark:text-white text-slate-800">Progres Belajar</h1>
            <p class="dark:text-slate-500 text-slate-400 text-[11px]">Pantau perkembangan keahlian, kelas, dan pencapaian akademis Anda di sini.</p>
        </div>

        {{-- Ringkasan Statistik Progres --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Stat 1: Kelas Aktif -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">2</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest mt-1 block">Kelas Diikuti</span>
                </div>
            </div>

            <!-- Stat 2: Sesi yang Sudah Diikuti -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">19</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest mt-1 block">Sesi yang Sudah Diikuti</span>
                </div>
            </div>

            <!-- Stat 3: Rata-rata Nilai Kuis -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">88%</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest mt-1 block">Rata-rata Nilai Kuis</span>
                </div>
            </div>
        </div>

        {{-- Daftar Kelas & Progress Bar --}}
        <div class="space-y-4">

            <!-- Kursus 1: Pengembangan Web -->
            <div class="p-5 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm flex flex-col md:flex-row gap-5 items-start md:items-center justify-between">
                <div class="flex flex-col sm:flex-row gap-4 flex-1 w-full">
                    <div class="w-full sm:w-28 h-20 bg-slate-100 dark:bg-[#0b0a1a] rounded-xl overflow-hidden flex-shrink-0 border dark:border-white/5 border-slate-200">
                        <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=400&auto=format&fit=crop" alt="Web Dev Thumbnail" class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">Web Development</span>
                            <span class="text-[10px] font-bold dark:text-slate-400 text-slate-500">7 dari 10 Modul Selesai</span>
                        </div>
                        <h3 class="text-base font-bold dark:text-white text-slate-800 leading-tight truncate">Program Pelatihan Lengkap Pengembangan Web</h3>

                        <p class="text-[11px] dark:text-slate-500 text-slate-400 mt-1 flex items-center gap-1.5">
                            <span class="font-semibold dark:text-slate-400 text-slate-600">Sesi Terakhir:</span>
                            <span class="truncate italic">"Implementasi Eloquent ORM & Relasi Database"</span>
                        </p>

                        <div class="w-full bg-slate-100 dark:bg-[#0b0a1a] h-1.5 rounded-full mt-3 overflow-hidden">
                            <div class="bg-primary h-full transition-all duration-500" style="width: 70%"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between md:justify-end gap-6 border-t md:border-t-0 pt-3 md:pt-0 border-slate-100 dark:border-white/5 w-full md:w-auto flex-shrink-0">
                    <div class="text-left md:text-right">
                        <p class="text-xl font-black dark:text-white text-slate-800">70%</p>
                        <p class="text-[9px] dark:text-slate-500 text-slate-400 font-bold uppercase tracking-wider">Selesai</p>
                    </div>
                    <button class="bg-primary text-white text-[10px] font-bold px-5 py-2.5 rounded-xl uppercase tracking-widest hover:brightness-110 active:scale-95 transition-all w-full sm:w-auto text-center">
                        Lanjutkan
                    </button>
                </div>
            </div>

            <!-- Kursus 2: UI/UX Design (SUDAH SELESAI) -->
            <div class="p-5 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm flex flex-col md:flex-row gap-5 items-start md:items-center justify-between">
                <div class="flex flex-col sm:flex-row gap-4 flex-1 w-full">
                    <div class="w-full sm:w-28 h-20 bg-slate-100 dark:bg-[#0b0a1a] rounded-xl overflow-hidden flex-shrink-0 border dark:border-white/5 border-slate-200">
                        <img src="https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?q=80&w=600" alt="UI/UX Thumbnail" class="w-full h-full object-cover">
                    </div>

                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-wider bg-green-500/10 text-green-500 border border-green-500/20">Design</span>
                            <span class="text-[10px] font-bold dark:text-slate-400 text-slate-500">12 dari 12 Modul Selesai</span>
                        </div>
                        <h3 class="text-base font-bold dark:text-white text-slate-800 leading-tight truncate">Dasar UI/UX Design untuk Pemula</h3>

                        <p class="text-[11px] dark:text-slate-500 text-slate-400 mt-1 flex items-center gap-1.5">
                            <span class="font-semibold dark:text-green-500 text-green-600">Status:</span>
                            <span class="truncate italic">"Selesai & Lulus"</span>
                        </p>

                        {{-- Progress Bar 100% --}}
                        <div class="w-full bg-slate-100 dark:bg-[#0b0a1a] h-1.5 rounded-full mt-3 overflow-hidden">
                            <div class="bg-green-500 h-full transition-all duration-500" style="width: 100%"></div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between md:justify-end gap-6 border-t md:border-t-0 pt-3 md:pt-0 border-slate-100 dark:border-white/5 w-full md:w-auto flex-shrink-0">
                    <div class="text-left md:text-right">
                        <p class="text-xl font-black dark:text-white text-slate-800">100%</p>
                        <p class="text-[9px] dark:text-slate-500 text-slate-400 font-bold uppercase tracking-wider">Selesai</p>
                    </div>
                    <button class="bg-slate-200 dark:bg-[#0b0a1a] text-slate-500 dark:text-slate-400 text-[10px] font-bold px-5 py-2.5 rounded-xl uppercase tracking-widest cursor-not-allowed w-full sm:w-auto text-center">
                        Selesai
                    </button>
                </div>
            </div>
        </div>
</main>

@endsection