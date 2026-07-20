@extends('layouts.learning')

@section('title', 'Kuis')

@section('content')
<div class="flex w-full h-[calc(100vh)] overflow-hidden bg-slate-50 dark:bg-[#0b0a1a]">

    @php
        $totalSoal = count($exercise->questions);
        $currentSoal = (int)request('q', 1);
        $currentSoal = max(1, min($totalSoal, $currentSoal));
        $isLast = ($currentSoal == $totalSoal);
        $question = $totalSoal > 0 ? $exercise->questions->values()->get($currentSoal - 1) : null;
    @endphp

    <main class="flex-1 w-full h-full overflow-y-auto p-6 lg:p-8 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50 custom-scrollbar">
        <div class="max-w-5xl mx-auto">
            <div class="lg:hidden mb-4">
                <a href="{{ route('student.course.lesson', $exercise->session->course->course_slug) }}" class="inline-flex items-center gap-2 text-[9px] font-black text-slate-400 hover:text-[#A487F8] uppercase tracking-widest transition-colors">
                    <i class="fas fa-arrow-left"></i> Kembali ke Materi
                </a>
            </div>

            <div class="mb-6">
                <h1 class="text-2xl font-black text-slate-800 dark:text-white mb-2">Kuis: {{ $exercise->exercise_title }}</h1>
                <p class="text-xs text-slate-500 font-medium">{{ $exercise->exercise_description ?? 'Selesaikan kuis ini untuk mengukur pemahaman Anda.' }}</p>
            </div>

            @if(!$question)
                <div class="bg-amber-500/10 border border-amber-500/20 rounded-2xl p-8 text-center">
                    <p class="text-amber-500 font-bold">Kuis ini belum memiliki soal.</p>
                    <a href="{{ route('student.course.lesson', $exercise->session->course->course_slug) }}" class="mt-4 inline-block px-6 py-2 bg-primary text-white text-xs font-bold rounded-lg">Kembali ke Materi</a>
                </div>
            @else
            <form id="quiz-form" action="{{ route('student.exercise.submit', $exercise->id) }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 items-start">

                    {{-- AREA SOAL --}}
                    <div class="xl:col-span-8">
                        <div class="bg-white dark:bg-[#1c1826] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                            <div class="mb-6">
                                <span class="text-[9px] font-black text-[#A487F8] bg-[#A487F8]/10 px-2 py-0.5 rounded-md uppercase tracking-widest">Pertanyaan {{ $currentSoal }}</span>
                                <h2 class="text-sm font-bold mt-3 text-slate-800 dark:text-white">{{ $question->question_text }}</h2>
                            </div>

                            <div class="space-y-2">
                                @foreach($question->options as $opsi)
                                <label class="group block cursor-pointer">
                                    <input type="radio" name="answer[{{ $question->id }}]" value="{{ $opsi->id }}" class="peer sr-only option-radio"
                                        data-q="{{ $currentSoal }}">
                                    <div class="flex items-center gap-3 p-3 rounded-xl border-2 border-slate-100 dark:border-slate-800 transition-all peer-checked:border-[#A487F8] peer-checked:bg-[#A487F8]/5 hover:border-[#A487F8]/30">
                                        <div class="w-7 h-7 rounded-lg flex items-center justify-center font-black text-[9px] bg-slate-100 dark:bg-slate-800 text-slate-500 peer-checked:bg-[#A487F8] peer-checked:text-white group-hover:bg-[#A487F8]/20 group-hover:text-[#A487F8] transition-colors">
                                            {{ chr(64 + $loop->iteration) }}
                                        </div>
                                        <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $opsi->option_text }}</span>
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            <div class="hidden">
                                @foreach($exercise->questions as $q)
                                    <input type="hidden" name="answer[{{ $q->id }}]" id="hidden-answer-{{ $loop->iteration }}" value="">
                                @endforeach
                            </div>

                            <div class="mt-8 flex justify-between items-center pt-4 border-t border-slate-100 dark:border-slate-800">
                                <a href="?q={{ max(1, $currentSoal - 1) }}" onclick="saveCurrentAnswer()" class="text-[9px] font-black uppercase tracking-widest text-slate-400 hover:text-[#A487F8] transition-colors">Sebelumnya</a>
                                @if(!$isLast)
                                    <a href="?q={{ $currentSoal + 1 }}" onclick="saveCurrentAnswer()" class="px-5 py-2 bg-[#A487F8] hover:bg-[#8B6FE8] text-white text-[9px] font-black rounded-lg uppercase tracking-widest shadow-lg shadow-[#A487F8]/20 transition-all">
                                        Selanjutnya <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- AREA NAVIGASI KANAN --}}
                    <div class="xl:col-span-4">
                        <div class="bg-white dark:bg-[#1c1826] p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                            <div class="grid grid-cols-5 gap-2 mb-5">
                                @for($i = 1; $i <= $totalSoal; $i++)
                                    <a id="nav-{{ $i }}" href="?q={{ $i }}" onclick="saveCurrentAnswer()"
                                    class="aspect-square flex items-center justify-center text-[11px] font-black rounded-lg border transition-all
                                    {{ $i == $currentSoal ? 'border-2 border-[#A487F8] text-[#A487F8] ring-2 ring-[#A487F8]/20' : 'bg-slate-50 dark:bg-slate-800 text-slate-400 border-slate-100 dark:border-slate-700' }}">
                                        {{ $i }}
                                    </a>
                                @endfor
                            </div>

                            <button type="button" onclick="submitForm()" class="w-full py-3 bg-[#A487F8] hover:bg-[#8B6FE8] text-white text-[9px] font-black rounded-lg uppercase tracking-widest transition-all shadow-lg shadow-[#A487F8]/20">
                                Kumpulkan Semua Jawaban
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            @endif
        </div>
    </main>
</div>

@if($question)
<script>
    const exerciseId = {{ $exercise->id }};

    function saveCurrentAnswer() {
        const checkedRadio = document.querySelector('.option-radio:checked');
        if (checkedRadio) {
            localStorage.setItem(`exercise_${exerciseId}_q_${checkedRadio.dataset.q}`, checkedRadio.value);
        }
    }

    function submitForm() {
        saveCurrentAnswer();

        for (let i = 1; i <= {{ $totalSoal }}; i++) {
            const savedAns = localStorage.getItem(`exercise_${exerciseId}_q_${i}`);
            if (savedAns) {
                const hiddenInput = document.getElementById(`hidden-answer-${i}`);
                if (hiddenInput) {
                    hiddenInput.value = savedAns;
                }
            }
        }

        if (confirm('Apakah Anda yakin ingin mengirimkan jawaban? Anda tidak bisa mengulanginya lagi.')) {
            for (let i = 1; i <= {{ $totalSoal }}; i++) {
                localStorage.removeItem(`exercise_${exerciseId}_q_${i}`);
            }
            document.getElementById('quiz-form').submit();
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const savedAns = localStorage.getItem(`exercise_${exerciseId}_q_${{ $currentSoal }}`);
        if (savedAns) {
            const radio = document.querySelector(`.option-radio[value="${savedAns}"]`);
            if (radio) radio.checked = true;
        }

        for (let i = 1; i <= {{ $totalSoal }}; i++) {
            if (localStorage.getItem(`exercise_${exerciseId}_q_${i}`)) {
                const btn = document.getElementById('nav-' + i);
                if (btn && i != {{ $currentSoal }}) {
                    btn.classList.remove('bg-slate-50', 'dark:bg-slate-800', 'text-slate-400', 'border-slate-100');
                    btn.classList.add('bg-[#A487F8]', 'text-white', 'border-[#A487F8]');
                }
            }
        }

        document.querySelectorAll('.option-radio').forEach(radio => {
            radio.addEventListener('change', () => {
                saveCurrentAnswer();
                const btn = document.getElementById('nav-' + {{ $currentSoal }});
                if (btn) {
                    btn.classList.remove('border-2', 'ring-2', 'ring-[#A487F8]/20');
                    btn.classList.add('bg-[#A487F8]', 'text-white', 'border-[#A487F8]');
                }
            });
        });
    });
</script>
@endif
@endsection