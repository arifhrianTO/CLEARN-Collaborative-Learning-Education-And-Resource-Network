@extends('layouts.dashboard')

@section('title', 'CLEARN │ Dashboard Pelajar')

@section('content')

<!-- Component Sidebar -->
<x-dashboard.sidebar
    role="student"
    name="{{ auth()->user()->name ?? 'Guest' }}"
    initials="{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}"
    photo="{{ auth()->user()->profile_picture }}"
    active="my-courses" />

<!-- Content -->
<main class="flex-1 p-6 lg:p-10 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50">
    <div class="max-w-6xl mx-auto">

        {{-- Header Halaman --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-xl font-bold dark:text-white text-slate-800">Beranda Pelajar</h1>
                <p class="text-[11px] dark:text-slate-500 text-slate-400">Lanjutkan pembelajaran dari kursus yang telah Anda ikuti</p>
            </div>
            <a href="{{ route('home') }}" class="bg-primary text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 transition hover:scale-105 shadow-lg shadow-primary/20 active:scale-95">
                <i class="fa-solid fa-magnifying-glass"></i> Cari Kursus Baru
            </a>
        </div>

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <!-- Statistik 1 -->
            <div class="p-5 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-[#A487F8]/10 flex items-center justify-center text-[#A487F8] mb-3 text-sm">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <h3 class="text-xl font-black dark:text-white text-slate-800">{{ $totalEnrolled }}</h3>
                <p class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-wider mt-1">Kursus Diikuti</p>
            </div>
            <!-- Statistik 2 -->
            <div class="p-5 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500 mb-3 text-sm">
                    <i class="fa-solid fa-chart-simple"></i>
                </div>
                @php
                    $averageProgress = $totalEnrolled > 0 ? $activeEnrollments->avg('progress') : 0;
                @endphp
                <h3 class="text-xl font-black dark:text-white text-slate-800">{{ round($averageProgress) }}%</h3>
                <p class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-wider mt-1">Rata-rata Progres</p>
            </div>
            <!-- Statistik 3 -->
            <div class="p-5 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-green-500/10 flex items-center justify-center text-green-500 mb-3 text-sm">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <h3 class="text-xl font-black dark:text-white text-slate-800">{{ $completedCoursesCount }}</h3>
                <p class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-wider mt-1">Kursus Selesai</p>
            </div>
            <!-- Statistik 4 -->
            <div class="p-5 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-yellow-500/10 flex items-center justify-center text-yellow-500 mb-3 text-sm">
                    <i class="fa-solid fa-trophy"></i>
                </div>
                <h3 class="text-xl font-black dark:text-white text-slate-800">{{ $totalCertificates }}</h3>
                <p class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-wider mt-1">Poin Sertifikat</p>
            </div>
        </div>

        {{-- List Kursus (Horizontal Card Style) --}}
        <div class="space-y-4 mb-6">
            @forelse($activeEnrollments->take(3) as $enrollment)
                @php
                    $isCompleted = $enrollment->progress == 100;
                    $colorClass = $isCompleted ? 'emerald' : 'primary';
                    $colorHex = $isCompleted ? 'rgba(16,185,129,0.4)' : 'rgba(164,135,248,0.4)';
                    $coverImage = $enrollment->course->course_cover 
                        ? (Str::startsWith($enrollment->course->course_cover, 'http') 
                            ? $enrollment->course->course_cover 
                            : Storage::url($enrollment->course->course_cover)) 
                        : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=400&auto=format&fit=crop';
                @endphp
                <div class="dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden flex flex-col md:flex-row hover:border-{{ $colorClass }}-500/50 transition-colors shadow-sm group">
                    <div class="w-full md:w-52 aspect-[16/10] md:aspect-auto overflow-hidden bg-slate-200 relative">
                        <img src="{{ $coverImage }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @if($enrollment->course->category)
                            <div class="absolute top-2 left-2 bg-black/70 backdrop-blur-sm text-white text-[9px] font-bold px-2 py-1 rounded">
                                {{ $enrollment->course->category->category_name }}
                            </div>
                        @endif
                    </div>

                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-2 gap-2">
                                <div>
                                    <h3 class="font-bold text-sm leading-tight dark:text-white text-slate-800 mb-1">{{ $enrollment->course->course_title }}</h3>
                                    <p class="text-[10px] dark:text-slate-500 text-slate-400 font-medium">{{ $enrollment->course->mentor->name ?? 'Tim Mentor Clearn' }}</p>
                                </div>
                                <span class="text-[10px] font-black text-{{ $isCompleted ? 'emerald' : 'primary' }}-500 bg-{{ $isCompleted ? 'emerald' : 'primary' }}-500/10 px-2 py-1 rounded-md">
                                    {{ $isCompleted ? 'LULUS' : $enrollment->progress . '%' }}
                                </span>
                            </div>

                            <div class="w-full dark:bg-[#0b0a1a] bg-slate-100 h-1.5 rounded-full mb-1">
                                <div class="bg-{{ $isCompleted ? 'emerald' : 'primary' }}-500 h-1.5 rounded-full shadow-[0_0_8px_{{ $colorHex }}]" style="width: {{ $enrollment->progress }}%"></div>
                            </div>
                            
                            @if($isCompleted)
                                <div class="flex items-center gap-1.5 text-[9px] text-emerald-500 font-bold uppercase tracking-wider">
                                    <i class="fas fa-shield-alt"></i> Sertifikat Tersedia
                                </div>
                            @else
                                <p class="text-[9px] dark:text-slate-500 text-slate-400 font-bold uppercase tracking-wider">Progres Belajar</p>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-3 mt-3 border-t dark:border-white/5 border-slate-100">
                            <p class="text-[10px] dark:text-slate-400 text-slate-500">
                                Status: <span class="dark:text-white text-slate-800 font-bold">{{ $isCompleted ? 'Semua materi telah diselesaikan' : 'Sedang Dipelajari' }}</span>
                            </p>
                            
                            <div class="flex gap-2 w-full sm:w-auto">
                                @if($isCompleted)
                                    <button class="flex-1 sm:flex-initial bg-emerald-500 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-emerald-500/30 transition-all active:scale-95">
                                        Klaim Sertifikat
                                    </button>
                                @else
                                    <button onclick="window.location.href='{{ route('student.course.lesson', $enrollment->course->course_slug) }}'"
                                        class="w-full sm:w-auto bg-primary text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-primary/30 transition-all active:scale-95">
                                        <i class="fa-solid fa-play"></i> Lanjutkan
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center p-8 bg-white dark:bg-[#161525] rounded-2xl border border-slate-200 dark:border-white/5">
                    <div class="w-16 h-16 mx-auto bg-slate-100 dark:bg-[#0b0a1a] rounded-full flex items-center justify-center mb-4">
                        <i class="fa-solid fa-book-open text-slate-400 text-2xl"></i>
                    </div>
                    <h3 class="text-slate-800 dark:text-white font-bold mb-2">Belum ada kursus</h3>
                    <p class="text-xs text-slate-500 mb-4">Anda belum mendaftar di kursus mana pun. Yuk cari kursus yang menarik!</p>
                </div>
            @endforelse
        </div>

        {{-- Footer CTA Section --}}
        <div class="bg-primary p-8 rounded-2xl text-center text-white shadow-xl shadow-primary/20 relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="font-bold text-sm mb-1">Siap lanjut belajar?</h3>
                <p class="text-[11px] text-white/80 mb-6 font-medium">Temukan lebih banyak kursus untuk meningkatkan skill Anda</p>
                <a href="{{ route('home') }}" class="inline-block bg-white text-primary px-8 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:scale-105 transition-transform active:scale-95">
                    Jelajahi Semua Kursus <i class="ml-2 text-[9px]"></i>
                </a>
            </div>

            {{-- Aksen Ornamen Lingkaran Khas Clearn CTA --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
        </div>

    </div>
</main>

@endsection