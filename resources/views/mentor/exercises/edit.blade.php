@extends('layouts.dashboard')

@section('title', 'CLEARN │ Atur Soal')

@section('content')

<main class="flex-1 p-5 lg:p-8 transition-colors duration-300 dark:bg-[#0F0B1A] bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto">

        <div class="mb-8">
            <a href="{{ route('mentor.courses.sessions.edit', $exercise->session->course_id) }}"
                class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-primary transition">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>

            <h1 class="text-2xl font-extrabold dark:text-white text-slate-800 tracking-tight mt-4">
                Rancang Kurikulum
            </h1>

            <p class="text-[11px] dark:text-slate-400 text-slate-500 mt-1 uppercase tracking-widest font-black">
                Langkah 2: Kurikulum & Materi
            </p>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-4 py-3 rounded-xl text-xs font-bold">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl text-xs font-bold">
            {{ session('error') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl text-xs font-semibold">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('mentor.sessions.exercises.update', $exercise->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-3xl p-6 lg:p-8 shadow-sm">

                <div class="mb-6 flex items-center justify-between gap-4">
                    <h2 class="text-sm font-black text-primary uppercase tracking-widest">
                        Atur Soal Kuis
                    </h2>

                    <button type="button"
                        onclick="addQuestion()"
                        class="px-4 py-2 rounded-xl bg-primary/10 text-primary text-[9px] font-black uppercase tracking-widest hover:bg-primary hover:text-white transition">
                        + Tambah Soal
                    </button>
                </div>

                <div class="dark:bg-[#1A1625] bg-slate-50 border dark:border-white/5 border-slate-200 rounded-2xl p-5">

                    <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-xl p-5 mb-6">
                        <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Judul Kuis
                        </label>

                        <input type="text"
                            name="exercise_title"
                            value="{{ old('exercise_title', $exercise->exercise_title) }}"
                            required
                            placeholder="Masukkan judul kuis..."
                            class="w-full bg-transparent border-b border-slate-300 dark:border-white/10 pb-3 text-sm font-bold dark:text-white text-slate-800 outline-none focus:border-primary transition">
                    </div>

                    <div id="questions-wrapper" class="space-y-5">
                        @php
                        $oldQuestions = old('questions');

                        if (!$oldQuestions) {
                        if ($exercise->questions->count() > 0) {
                        $oldQuestions = $exercise->questions->map(function ($question) {
                        $correctIndex = $question->options->values()->search(function ($option) {
                        return $option->is_correct == true;
                        });

                        return [
                        'id' => $question->id,
                        'question_text' => $question->question_text,
                        'correct_option' => $correctIndex === false ? null : $correctIndex,
                        'options' => $question->options->values()->map(function ($option) {
                        return [
                        'id' => $option->id,
                        'option_text' => $option->option_text,
                        ];
                        })->toArray(),
                        ];
                        })->toArray();
                        } else {
                        $oldQuestions = [
                        [
                        'id' => null,
                        'question_text' => '',
                        'correct_option' => null,
                        'options' => [
                        ['id' => null, 'option_text' => ''],
                        ['id' => null, 'option_text' => ''],
                        ['id' => null, 'option_text' => ''],
                        ['id' => null, 'option_text' => ''],
                        ],
                        ],
                        ];
                        }
                        }
                        @endphp

                        @foreach($oldQuestions as $questionIndex => $question)
                        <div class="question-item">
                            <input type="hidden"
                                name="questions[{{ $questionIndex }}][id]"
                                value="{{ $question['id'] ?? '' }}">

                            <div class="flex items-center justify-between mb-3">
                                <h3 class="question-title text-[10px] font-black text-primary uppercase tracking-widest">
                                    Soal {{ $questionIndex + 1 }}
                                </h3>

                                <button type="button"
                                    onclick="removeQuestion(this)"
                                    class="text-[9px] font-black text-red-500 uppercase tracking-widest hover:text-red-400">
                                    Hapus Soal
                                </button>
                            </div>

                            <textarea name="questions[{{ $questionIndex }}][question_text]"
                                rows="4"
                                required
                                placeholder="Masukkan pertanyaan..."
                                class="w-full mb-4 rounded-xl dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 p-4 text-xs dark:text-white text-slate-800 outline-none focus:border-primary transition">{{ $question['question_text'] ?? '' }}</textarea>

                            <div class="space-y-3">
                                @foreach(['A', 'B', 'C', 'D'] as $optionIndex => $label)
                                <label class="flex items-center gap-3 rounded-xl dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 px-4 py-3">
                                    <input type="hidden"
                                        name="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][id]"
                                        value="{{ $question['options'][$optionIndex]['id'] ?? '' }}">

                                    <input type="radio"
                                        name="questions[{{ $questionIndex }}][correct_option]"
                                        value="{{ $optionIndex }}"
                                        required
                                        {{ isset($question['correct_option']) && (string) $question['correct_option'] === (string) $optionIndex ? 'checked' : '' }}
                                        class="shrink-0">

                                    <input type="text"
                                        name="questions[{{ $questionIndex }}][options][{{ $optionIndex }}][option_text]"
                                        value="{{ $question['options'][$optionIndex]['option_text'] ?? '' }}"
                                        required
                                        placeholder="Pilihan {{ $label }}"
                                        class="w-full bg-transparent text-xs dark:text-white text-slate-800 outline-none">
                                </label>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <a href="{{ route('mentor.courses.sessions.edit', $exercise->session->course_id) }}"
                        class="px-8 py-3 rounded-xl bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest">
                        Kembali ke Sesi
                    </a>

                    <button type="submit"
                        class="px-10 py-3 rounded-xl bg-primary text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:brightness-110 transition">
                        Simpan Kuis
                    </button>
                </div>

            </div>
        </form>
    </div>
</main>

<script>
    function refreshQuestionIndexes() {
        const items = document.querySelectorAll('.question-item');

        items.forEach((item, questionIndex) => {
            item.querySelector('.question-title').innerText = 'Soal ' + (questionIndex + 1);

            item.querySelectorAll('input, textarea').forEach(input => {
                input.name = input.name.replace(/questions\[\d+\]/, 'questions[' + questionIndex + ']');
            });
        });
    }

    function addQuestion() {
        const wrapper = document.getElementById('questions-wrapper');
        const questionIndex = wrapper.querySelectorAll('.question-item').length;

        const html = `
            <div class="question-item">
                <input type="hidden" name="questions[${questionIndex}][id]" value="">

                <div class="flex items-center justify-between mb-3">
                    <h3 class="question-title text-[10px] font-black text-primary uppercase tracking-widest">
                        Soal ${questionIndex + 1}
                    </h3>

                    <button type="button"
                        onclick="removeQuestion(this)"
                        class="text-[9px] font-black text-red-500 uppercase tracking-widest hover:text-red-400">
                        Hapus Soal
                    </button>
                </div>

                <textarea name="questions[${questionIndex}][question_text]"
                    rows="4"
                    required
                    placeholder="Masukkan pertanyaan..."
                    class="w-full mb-4 rounded-xl dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 p-4 text-xs dark:text-white text-slate-800 outline-none focus:border-primary transition"></textarea>

                <div class="space-y-3">
                    ${['A', 'B', 'C', 'D'].map((label, optionIndex) => {
                        return `
                            <label class="flex items-center gap-3 rounded-xl dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 px-4 py-3">
                                <input type="hidden"
                                    name="questions[${questionIndex}][options][${optionIndex}][id]"
                                    value="">

                                <input type="radio"
                                    name="questions[${questionIndex}][correct_option]"
                                    value="${optionIndex}"
                                    required
                                    class="shrink-0">

                                <input type="text"
                                    name="questions[${questionIndex}][options][${optionIndex}][option_text]"
                                    required
                                    placeholder="Pilihan ${label}"
                                    class="w-full bg-transparent text-xs dark:text-white text-slate-800 outline-none">
                            </label>
                        `;
                    }).join('')}
                </div>
            </div>
        `;

        wrapper.insertAdjacentHTML('beforeend', html);
    }

    function removeQuestion(button) {
        const wrapper = document.getElementById('questions-wrapper');
        const items = wrapper.querySelectorAll('.question-item');

        if (items.length <= 1) {
            alert('Minimal harus ada 1 soal.');
            return;
        }

        button.closest('.question-item').remove();
        refreshQuestionIndexes();
    }
</script>

@endsection