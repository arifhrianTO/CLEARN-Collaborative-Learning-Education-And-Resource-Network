@extends('layouts.dashboard')

@section('title', 'CLEARN │ Penilaian')

@section('content')

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}"
    active="penilaian" />

<main class="flex-1 p-5 lg:p-8">
    <div class="max-w-6xl mx-auto">

        <div class="mb-8">
            <h1 class="text-lg font-bold dark:text-white text-slate-800">Penilaian</h1>
            <p class="text-[11px] dark:text-slate-500 text-slate-400 font-medium">
                Kursus yang memiliki tugas akhir menunggu penilaian.
            </p>
        </div>

        @if($courses->isEmpty())
        <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl p-12 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center text-slate-400">
                <i class="fa-solid fa-check-circle text-2xl"></i>
            </div>
            <h3 class="text-sm font-bold dark:text-white text-slate-800 mb-1">Semua sudah dinilai</h3>
            <p class="text-[11px] text-slate-400">Tidak ada tugas akhir yang menunggu penilaian saat ini.</p>
        </div>
        @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @foreach($courses as $course)
            <a href="{{ route('mentor.penilaian.course', $course) }}"
                class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl p-6 hover:shadow-lg transition-all group">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm font-bold dark:text-white text-slate-800 group-hover:text-primary transition-colors line-clamp-2">
                            {{ $course->course_title }}
                        </h3>
                    </div>
                    <div class="ml-4 shrink-0">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                            <i class="fa-solid fa-clock"></i>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 text-[10px] font-bold">
                    <div class="flex items-center gap-1.5 text-amber-500">
                        <i class="fa-solid fa-hourglass-half"></i>
                        <span>{{ $course->pending_count }} Menunggu</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-slate-400">
                        <i class="fa-solid fa-file-arrow-up"></i>
                        <span>{{ $course->total_submissions }} Total</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        @endif

    </div>
</main>
@endsection