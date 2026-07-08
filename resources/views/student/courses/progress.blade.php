@extends('layouts.dashboard')

@section('content')

<!-- Component Sidebar -->
<x-dashboard.sidebar
    role="student"
    name="{{ auth()->user()->name ?? 'User Pelajar' }}"
    initials="{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}"
    photo="{{ auth()->user()->profile_picture }}"
    active="progress" />

<!-- Content -->
<main class="flex-1 p-6 lg:p-10 transition-colors duration-300 dark:bg-[#0F0B1A] bg-slate-50">
    <div class="max-w-5xl mx-auto">

        {{-- Header Halaman --}}
        <div class="mb-6">
            <h1 class="text-xl font-bold dark:text-white text-slate-800">Progres Belajar</h1>
            <p class="dark:text-slate-500 text-slate-400 text-[11px]">Pantau perkembangan keahlian, kelas, dan pencapaian akademis Anda di sini.</p>
        </div>

        {{-- Ringkasan Statistik Progres --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <!-- Stat 1: Kelas Aktif -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                    <i class="fas fa-book-open"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">{{ $activeCoursesCount }}</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest mt-1 block">Kelas Aktif</span>
                </div>
            </div>

            <!-- Stat 2: Sesi yang Sudah Diikuti -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">{{ $completedCoursesCount }}</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest mt-1 block">Kelas Selesai</span>
                </div>
            </div>

            <!-- Stat 3: Rata-rata Nilai Kuis -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                    <i class="fas fa-star"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">{{ number_format($averageQuizScore, 1) }}%</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest mt-1 block">Rata-rata Nilai Kuis</span>
                </div>
            </div>
        </div>

        {{-- Daftar Kelas & Progres Bar --}}
        <div class="space-y-4">
            @forelse($enrollments as $enrollment)
            <!-- Kursus -->
            <div class="p-5 dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm flex flex-col md:flex-row gap-5 items-start md:items-center justify-between">
                <div class="flex flex-col sm:flex-row gap-4 flex-1 w-full">
                    <div class="w-full sm:w-28 h-20 bg-slate-100 dark:bg-[#0F0B1A] rounded-xl overflow-hidden flex-shrink-0 border dark:border-white/5 border-slate-200">
                        @php
                            $progCover = $enrollment->course->course_thumbnail
                                ? (Str::startsWith($enrollment->course->course_thumbnail, 'http')
                                    ? $enrollment->course->course_thumbnail
                                    : Storage::url($enrollment->course->course_thumbnail))
                                : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=400&auto=format&fit=crop';
                        @endphp
                        <img src="{{ $progCover }}" alt="Thumbnail" class="w-full h-full object-cover">
                    </div>

                    @php
                        $pendingProgResult = $enrollment->finalProjectResults ? $enrollment->finalProjectResults->whereNull('final_project_score')->first() : null;
                        $isProgPending = (bool) $pendingProgResult;
                        $failedProgResult = $enrollment->finalProjectResults ? $enrollment->finalProjectResults->whereNotNull('final_project_score')->where('final_project_score', '<', 70)->first() : null;
                        $isProgFailed = (bool) $failedProgResult;
                        $isProgCompleted = !$isProgPending && !$isProgFailed && $enrollment->progress == 100;
                        $progFinalProject = $enrollment->course->sessions->flatMap->finalProjects->first();
                        $projectId = $progFinalProject?->id;
                    @endphp
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                            @if($isProgCompleted)
                                <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-wider bg-green-500/10 text-green-500 border border-green-500/20">{{ $enrollment->course->category->category_name ?? 'Kategori' }}</span>
                            @elseif($isProgPending)
                                <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-wider bg-amber-500/10 text-amber-500 border border-amber-500/20">{{ $enrollment->course->category->category_name ?? 'Kategori' }}</span>
                            @elseif($isProgFailed)
                                <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-wider bg-red-500/10 text-red-500 border border-red-500/20">{{ $enrollment->course->category->category_name ?? 'Kategori' }}</span>
                            @else
                                <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-wider bg-primary/10 text-primary border border-primary/20">{{ $enrollment->course->category->category_name ?? 'Kategori' }}</span>
                            @endif
                        </div>
                        <h3 class="text-base font-bold dark:text-white text-slate-800 leading-tight truncate">{{ $enrollment->course->course_title }}</h3>

                        <p class="text-[11px] dark:text-slate-500 text-slate-400 mt-1 flex items-center gap-1.5">
                            @if($isProgCompleted)
                                <span class="font-semibold dark:text-green-500 text-green-600">Status:</span>
                                <span class="truncate italic">"Selesai & Lulus"</span>
                            @elseif($isProgPending)
                                <span class="font-semibold dark:text-amber-500 text-amber-600">Status:</span>
                                <span class="truncate italic">"Menunggu Penilaian"</span>
                            @elseif($isProgFailed)
                                <span class="font-semibold dark:text-red-500 text-red-600">Status:</span>
                                <span class="truncate italic">"Tidak Lulus"</span>
                            @else
                                <span class="font-semibold dark:text-slate-400 text-slate-600">Terdaftar sejak:</span>
                                <span class="truncate italic">{{ $enrollment->created_at->format('d M Y') }}</span>
                            @endif
                        </p>

                        <div class="w-full bg-slate-100 dark:bg-[#0F0B1A] h-1.5 rounded-full mt-3 overflow-hidden">
                            <div class="{{ $isProgCompleted ? 'bg-green-500' : ($isProgPending ? 'bg-amber-500' : ($isProgFailed ? 'bg-red-500' : 'bg-primary')) }} h-full transition-all duration-500" style="width: {{ $isProgPending || $isProgFailed ? 100 : $enrollment->progress }}%"></div>
                        </div>
                    </div>
                </div>

                    <div class="flex items-center justify-between md:justify-end gap-6 border-t md:border-t-0 pt-3 md:pt-0 border-slate-100 dark:border-white/5 w-full md:w-auto flex-shrink-0">
                        <div class="text-left md:text-right">
                            <p class="text-xl font-black dark:text-white text-slate-800">{{ $isProgPending ? '-' : ($isProgFailed ? '0' : $enrollment->progress) . '%' }}</p>
                            <p class="text-[9px] dark:text-slate-500 text-slate-400 font-bold uppercase tracking-wider">{{ $isProgPending ? 'Menunggu' : ($isProgFailed ? 'Gagal' : 'Selesai') }}</p>
                        </div>
                        @if($isProgCompleted)
                            <button class="bg-slate-200 dark:bg-[#0F0B1A] text-slate-500 dark:text-slate-400 text-[10px] font-bold px-5 py-2.5 rounded-xl uppercase tracking-widest cursor-not-allowed w-full sm:w-auto text-center">
                                Selesai
                            </button>
                        @elseif($isProgPending)
                            <div class="bg-amber-500/20 text-amber-500 text-[10px] font-bold px-5 py-2.5 rounded-xl uppercase tracking-widest w-full sm:w-auto text-center cursor-not-allowed">
                                Menunggu
                            </div>
                        @elseif($isProgFailed)
                            <a href="{{ $projectId ? route('student.project.show', $projectId) : '#' }}" class="inline-block bg-red-500 text-white text-[10px] font-bold px-5 py-2.5 rounded-xl uppercase tracking-widest hover:brightness-110 active:scale-95 transition-all w-full sm:w-auto text-center">
                                Ajukan Ulang
                            </a>
                        @else
                            <a href="{{ route('student.course.lesson', $enrollment->course->course_slug) }}" class="inline-block bg-primary text-white text-[10px] font-bold px-5 py-2.5 rounded-xl uppercase tracking-widest hover:brightness-110 active:scale-95 transition-all w-full sm:w-auto text-center">
                                Lanjutkan
                            </a>
                        @endif
                    </div>
            </div>
            @empty
                <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl p-10 text-center">
                    <div class="w-16 h-16 dark:bg-[#0F0B1A] bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 dark:text-slate-500 text-slate-400">
                        <i class="fas fa-book text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold dark:text-white text-slate-800 mb-2">Belum ada kelas</h3>
                    <p class="text-xs dark:text-slate-500 text-slate-400 mb-6">Anda belum mendaftar ke kursus apapun.</p>
                    <a href="{{ route('course') }}" class="inline-block bg-primary text-white px-6 py-2 rounded-xl text-xs font-bold shadow-lg shadow-primary/20 hover:brightness-110 active:scale-95 transition-all">Cari Kursus</a>
                </div>
            @endforelse
        </div>
</main>

@endsection