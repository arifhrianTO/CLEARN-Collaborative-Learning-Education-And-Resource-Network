@extends('layouts.learning')

@section('title', 'Latihan Selesai')

@section('content')
<div class="flex-1 min-w-0 transition-all duration-300 bg-slate-50 dark:bg-dark-bg min-h-screen">
    <main class="flex-1 p-6 lg:p-8 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50 min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white dark:bg-[#1c1826] border border-gray-100 dark:border-gray-800 rounded-2xl p-8 shadow-sm text-center">
            
            <div class="w-20 h-20 mx-auto bg-emerald-500/10 text-emerald-500 rounded-full flex items-center justify-center text-4xl mb-6">
                <i class="fas fa-check-circle"></i>
            </div>

            <h1 class="text-2xl font-black text-slate-800 dark:text-white mb-2">Latihan Selesai!</h1>
            <p class="text-sm text-slate-500 mb-6">Anda telah menyelesaikan latihan ini.</p>

            <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-6 mb-8">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Skor Anda</p>
                <div class="text-5xl font-black {{ $score >= 70 ? 'text-emerald-500' : 'text-red-500' }}">
                    {{ $score }}
                </div>
                <p class="text-xs text-slate-500 mt-2">Menjawab benar {{ $correctCount }} dari {{ $totalQuestions }} soal.</p>
            </div>

            <div class="flex gap-4 justify-center">
                <a href="{{ route('student.course.lesson', $exercise->session->course->course_slug) }}" class="px-6 py-3 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-[10px] font-black rounded-lg uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-slate-700 transition-all">
                    Kembali ke Materi
                </a>
            </div>
            
        </div>
    </main>
</div>
@endsection
