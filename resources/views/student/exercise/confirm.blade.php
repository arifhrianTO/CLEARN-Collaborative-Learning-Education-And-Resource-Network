@extends('layouts.learning')

@section('title', 'Mulai Latihan')

@section('content')
    <div class="flex-1 min-w-0 transition-all duration-300 bg-slate-50 dark:bg-dark-bg min-h-screen">
        <main class="flex-1 p-6 lg:p-8 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50 min-h-screen flex items-center justify-center">
            <div class="max-w-md w-full bg-white dark:bg-[#1c1826] border border-gray-100 dark:border-gray-800 rounded-2xl p-8 shadow-sm text-center">
                
                <div class="w-20 h-20 mx-auto bg-primary/10 text-primary rounded-full flex items-center justify-center text-4xl mb-6">
                    <i class="fas fa-file-alt"></i>
                </div>

                <h1 class="text-2xl font-black text-slate-800 dark:text-white mb-2">{{ $exercise->exercise_title }}</h1>
                <p class="text-sm text-slate-500 mb-6">{{ $exercise->exercise_description ?? 'Selesaikan latihan ini untuk mengukur pemahaman Anda.' }}</p>

                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6 mb-8 text-left space-y-3">
                    <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                        <span class="text-xs font-bold text-slate-500">Jumlah Soal</span>
                        <span class="text-xs font-black text-slate-800 dark:text-white">{{ count($exercise->questions) }} Soal</span>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-3">
                        <span class="text-xs font-bold text-slate-500">Sesi</span>
                        <span class="text-xs font-black text-slate-800 dark:text-white">{{ $exercise->session->sessions_title }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-slate-500">Sifat</span>
                        <span class="text-xs font-black text-emerald-500">Sekali Kesempatan</span>
                    </div>
                </div>

                <div class="flex flex-col gap-3">
                    <a href="{{ route('student.exercise.start', $exercise->id) }}" class="px-6 py-3 bg-primary hover:bg-primary/90 text-white text-[11px] font-black rounded-lg uppercase tracking-widest transition-all">
                        Mulai Kerjakan Latihan
                    </a>
                    <a href="{{ route('student.course.lesson', $exercise->session->course->course_slug) }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[11px] font-black rounded-lg uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                        Kembali ke Materi
                    </a>
                </div>
                
            </div>
        </main>
    </div>
@endsection