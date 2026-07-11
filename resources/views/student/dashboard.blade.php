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
<main class="flex-1 p-6 lg:p-10 transition-colors duration-300 dark:bg-[#0F0B1A] bg-slate-50">
    <div class="max-w-6xl mx-auto">

        {{-- Header Halaman --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h1 class="text-xl font-bold dark:text-white text-slate-800">Beranda Pelajar</h1>
                <p class="text-[11px] dark:text-slate-500 text-slate-400">Lanjutkan pembelajaran dari kursus yang telah Anda ikuti</p>
            </div>
            <a href="{{ route('course') }}" class="bg-primary text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 transition hover:scale-105 shadow-lg shadow-primary/20 active:scale-95">
                <i class="fa-solid fa-magnifying-glass"></i> Cari Kursus Baru
            </a>
        </div>

        {{-- Ringkasan Statistik --}}
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
            <!-- Statistik 1 -->
            <div class="p-5 dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-[#A487F8]/10 flex items-center justify-center text-[#A487F8] mb-3 text-sm">
                    <i class="fa-solid fa-book-open"></i>
                </div>
                <h3 class="text-xl font-black dark:text-white text-slate-800">{{ $totalEnrolled }}</h3>
                <p class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-wider mt-1">Kursus Diikuti</p>
            </div>
            <!-- Statistik 2 -->
            <div class="p-5 dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
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
            <div class="p-5 dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-8 h-8 rounded-xl bg-green-500/10 flex items-center justify-center text-green-500 mb-3 text-sm">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <h3 class="text-xl font-black dark:text-white text-slate-800">{{ $completedCoursesCount }}</h3>
                <p class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-wider mt-1">Kursus Selesai</p>
            </div>
        </div>

        {{-- List Kursus (Horizontal Card Style) --}}
        <div class="space-y-4 mb-6">
            @forelse($activeEnrollments->take(3) as $enrollment)
                @php
                    $pendingResult = $enrollment->finalProjectResults ? $enrollment->finalProjectResults->whereNull('final_project_score')->first() : null;
                    $isPending = (bool) $pendingResult;
                    $failedResult = $enrollment->finalProjectResults ? $enrollment->finalProjectResults->whereNotNull('final_project_score')->where('final_project_score', '<', 70)->first() : null;
                    $isFailed = (bool) $failedResult;
                    $isCompleted = !$isPending && !$isFailed && $enrollment->progress == 100;
                    $colorClass = $isCompleted ? 'emerald' : ($isPending ? 'amber' : ($isFailed ? 'red' : 'primary'));
                    $colorHex = $isCompleted ? 'rgba(16,185,129,0.4)' : ($isPending ? 'rgba(245,158,11,0.4)' : ($isFailed ? 'rgba(239,68,68,0.4)' : 'rgba(164,135,248,0.4)'));
                    $hasCertificate = $enrollment->certificate ? true : false;
                    $finalProject = $enrollment->course->sessions->flatMap->finalProjects->first();
                    $projectId = $finalProject?->id;
                    $coverImage = $enrollment->course->course_thumbnail 
                        ? (Str::startsWith($enrollment->course->course_thumbnail, 'http') 
                            ? $enrollment->course->course_thumbnail 
                            : Storage::url($enrollment->course->course_thumbnail)) 
                        : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=400&auto=format&fit=crop';
                @endphp
                <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden flex flex-col md:flex-row hover:border-{{ $colorClass }}-500/50 transition-colors shadow-sm group">
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
                                    <p class="text-[10px] dark:text-slate-500 text-slate-400 font-medium">{{ $enrollment->course->mentor->name ?? 'Tim Pengajar Clearn' }}</p>
                                </div>
                                <span class="text-[10px] font-black text-{{ $isCompleted ? 'emerald' : ($isPending ? 'amber' : ($isFailed ? 'red' : 'primary')) }}-500 bg-{{ $isCompleted ? 'emerald' : ($isPending ? 'amber' : ($isFailed ? 'red' : 'primary')) }}-500/10 px-2 py-1 rounded-md">
                                    {{ $isCompleted ? 'LULUS' : ($isPending ? 'MENUNGGU' : ($isFailed ? 'TIDAK LULUS' : $enrollment->progress . '%')) }}
                                </span>
                            </div>

                            <div class="w-full dark:bg-[#0F0B1A] bg-slate-100 h-1.5 rounded-full mb-1">
                                <div class="bg-{{ $isCompleted ? 'emerald' : ($isPending ? 'amber' : ($isFailed ? 'red' : 'primary')) }}-500 h-1.5 rounded-full shadow-[0_0_8px_{{ $colorHex }}]" style="width: {{ $isPending ? 100 : $enrollment->progress }}%"></div>
                            </div>
                            
                            @if($isCompleted && $hasCertificate)
                                <div class="flex items-center gap-1.5 text-[9px] text-emerald-500 font-bold uppercase tracking-wider">
                                    <i class="fas fa-shield-alt"></i> Sertifikat Diklaim
                                </div>
                            @elseif($isCompleted)
                                <div class="flex items-center gap-1.5 text-[9px] text-emerald-500 font-bold uppercase tracking-wider">
                                    <i class="fas fa-shield-alt"></i> Sertifikat Tersedia
                                </div>
                            @elseif($isPending)
                                <p class="text-[9px] dark:text-slate-500 text-slate-400 font-bold uppercase tracking-wider">Menunggu Penilaian</p>
                            @elseif($isFailed)
                                <p class="text-[9px] text-red-500 font-bold uppercase tracking-wider">Tidak Lulus - Ajukan Ulang</p>
                            @else
                                <p class="text-[9px] dark:text-slate-500 text-slate-400 font-bold uppercase tracking-wider">Progres Belajar</p>
                            @endif
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 pt-3 mt-3 border-t dark:border-white/5 border-slate-100">
                            <p class="text-[10px] dark:text-slate-400 text-slate-500">
                                Status: <span class="dark:text-white text-slate-800 font-bold">{{ $isCompleted && $hasCertificate ? 'Sertifikat sudah diklaim' : ($isCompleted ? 'Semua materi telah diselesaikan' : ($isPending ? 'Menunggu penilaian pengajar' : ($isFailed ? 'Tugas akhir tidak memenuhi syarat' : 'Sedang Dipelajari'))) }}</span>
                            </p>
                            
                            <div class="flex gap-2 w-full sm:w-auto">
                                @if($isCompleted && $hasCertificate)
                                    <button onclick="window.location='{{ route('student.certificate.show', $enrollment->certificate->id) }}'"
                                        class="flex-1 sm:flex-initial bg-emerald-500 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-emerald-500/30 transition-all active:scale-95">
                                        <i class="fas fa-eye"></i> Lihat Sertifikat
                                    </button>
                                @elseif($isCompleted)
                                    <form action="{{ route('student.certificate.claim', $enrollment->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="flex-1 sm:flex-initial bg-emerald-500 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-emerald-500/30 transition-all active:scale-95">
                                            Klaim Sertifikat
                                        </button>
                                    </form>
                                @elseif($isPending)
                                    <div class="w-full sm:w-auto bg-amber-500 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest text-center opacity-70 cursor-not-allowed">
                                        Menunggu Penilaian
                                    </div>
                                @elseif($isFailed)
                                    <button onclick="window.location.href='{{ $projectId ? route('student.project.show', $projectId) : '#' }}'"
                                        class="w-full sm:w-auto bg-red-500 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-red-500/30 transition-all active:scale-95">
                                        <i class="fa-solid fa-rotate"></i> Ajukan Ulang
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
                <div class="text-center p-8 bg-white dark:bg-[#1A1625] rounded-2xl border border-slate-200 dark:border-white/5">
                    <div class="w-16 h-16 mx-auto bg-slate-100 dark:bg-[#0F0B1A] rounded-full flex items-center justify-center mb-4">
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
                <a href="{{ route('course') }}" class="inline-block bg-white text-primary px-8 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:scale-105 transition-transform active:scale-95">
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