@extends('layouts.dashboard')

@section('title', 'Detail Kursus ' . $course->course_title . ' | Dashboard Mentor | Clearn - Platform Pembelajaran Online')

@section('content')

<main class="flex-1 p-5 lg:p-8">
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
    <button type="button"
    onclick="window.location='{{ route('mentor.courses.index') }}'"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white text-sm font-semibold shadow-md hover:bg-primary/90 transition">
    <i class="fa-solid fa-arrow-left-long"></i>
    Kembali ke Beranda
</button>

            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-4 mt-4">
                <div>
                    <h1 class="text-2xl font-extrabold dark:text-white text-slate-800 tracking-tight">
                        {{ $course->course_title }}
                    </h1>

                    <p class="text-[12px] dark:text-slate-400 text-slate-500 mt-1 italic font-medium">
                        Tambahkan pelajaran, kuis, dan proyek akhir untuk kursus ini.
                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <span class="px-3 py-2 rounded-xl bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest">
                        {{ $course->category->category_name ?? 'Tanpa Kategori' }}
                    </span>

                    @if($course->status_review == 'approved')
                    <span class="px-3 py-2 rounded-xl bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest">
                        Disetujui
                    </span>
                    @elseif($course->status_review == 'rejected')
                    <span class="px-3 py-2 rounded-xl bg-red-500/10 text-red-500 text-[10px] font-black uppercase tracking-widest">
                        Ditolak
                    </span>
                    @elseif($course->status_review == 'pending')
                    <span class="px-3 py-2 rounded-xl bg-amber-500/10 text-amber-500 text-[10px] font-black uppercase tracking-widest">
                        Menunggu
                    </span>
                    @else
                    <span class="px-3 py-2 rounded-xl bg-blue-500/10 text-blue-500 text-[10px] font-black uppercase tracking-widest">
                        Draf
                    </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Alert --}}
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

        {{-- Course Info --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

            <div class="lg:col-span-2 card-bg rounded-3xl overflow-hidden">
                <div class="h-64 bg-slate-200 dark:bg-[#161525] overflow-hidden">
                    @if($course->course_thumbnail)
                    <img src="{{ asset('storage/' . $course->course_thumbnail) }}"
                        class="w-full h-full object-cover"
                        alt="{{ $course->course_title }}">
                    @else
                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                        <i class="fa-solid fa-image text-3xl"></i>
                    </div>
                    @endif
                </div>

                <div class="p-6">
                    <h2 class="text-sm font-black dark:text-white text-slate-800 uppercase tracking-widest mb-3 ">
                        Deskripsi Kursus
                    </h2>

                    <p class="text-sm dark:text-slate-400 text-slate-600 leading-relaxed whitespace-pre-wrap">{{ $course->course_description }}
                    </p>
                </div>
            </div>

            <div class="card-bg rounded-3xl p-6 h-fit">
                <h2 class="text-sm font-black dark:text-white text-slate-800 uppercase tracking-widest mb-5">
                    Ringkasan
                </h2>

                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-[11px] dark:text-slate-400 text-slate-500 font-bold">
                            Harga
                        </span>
                        <span class="text-sm font-black text-primary">
                            Rp {{ number_format($course->course_price ?? 0, 0, ',', '.') }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-[11px] dark:text-slate-400 text-slate-500 font-bold">
                            Total Pertemuan
                        </span>
                        <span class="text-sm font-black dark:text-white text-slate-800">
                            {{ $course->sessions->count() }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-[11px] dark:text-slate-400 text-slate-500 font-bold">
                            Total Pelajaran
                        </span>
                        <span class="text-sm font-black dark:text-white text-slate-800">
                            {{ $course->sessions->sum(fn($session) => $session->lessons->count()) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-[11px] dark:text-slate-400 text-slate-500 font-bold">
                            Total Latihan
                        </span>
                        <span class="text-sm font-black dark:text-white text-slate-800">
                            {{ $course->sessions->sum(fn($session) => $session->exercises->count()) }}
                        </span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span class="text-[11px] dark:text-slate-400 text-slate-500 font-bold">
                            Total Soal
                        </span>
                        <span class="text-sm font-black dark:text-white text-slate-800">
                            {{
                                $course->sessions->sum(function ($session) {
                                    return $session->exercises->sum(function ($exercise) {
                                        return $exercise->questions->count();
                                    });
                                })
                            }}
                        </span>
                    </div>
                </div>
                {{-- Aksi hanya tersedia saat status draft atau rejected --}}
                @if(in_array($course->status_review, ['draft', 'rejected']))

                <div class="mt-6 pt-6 border-t border-slate-200 dark:border-white/5">
                    <a href="{{ route('mentor.courses.edit', $course->id) }}"
                        class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest hover:bg-primary hover:text-white transition">
                        <i class="fa-solid fa-pen mr-2"></i>
                        Perbarui Informasi Kursus
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-200 dark:border-white/5">
                    <a href="{{ route('mentor.courses.sessions.edit', $course->id) }}"
                        class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-primary/10 text-primary text-[10px] font-black uppercase tracking-widest hover:bg-primary hover:text-white transition">
                        <i class="fa-solid fa-layer-group mr-2"></i>
                        Isi Kurikulum
                    </a>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-200 dark:border-white/5 space-y-3">
                    <form action="{{ route('mentor.courses.submit', $course->id) }}"
                        method="POST"
                        onsubmit="return confirm('Ajukan course ini untuk diverifikasi admin?')">
                        @csrf
                        @method('PATCH')

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl text-emerald-400 text-[10px] font-black uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition">
                            <i class="fa-solid fa-paper-plane mr-2"></i>
                            Ajukan Verifikasi
                        </button>
                    </form>

                    <form action="{{ route('mentor.courses.destroy', $course->id) }}"
                        method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus course ini? Semua session, lesson, kuis, dan project akan ikut terhapus.')">
                        @csrf
                        @method('DELETE')

                        <button type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl text-red-400 text-[10px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition">
                            <i class="fa-solid fa-trash mr-2"></i>
                            Hapus Course
                        </button>
                    </form>
                </div>

                @elseif($course->status_review === 'pending')

                <div class="mt-6 pt-6 border-t border-slate-200 dark:border-white/5">
                    <div class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-amber-500/10 text-amber-500 text-[10px] font-black uppercase tracking-widest">
                        <i class="fa-solid fa-clock mr-2"></i>
                        Sedang dalam proses verifikasi
                    </div>
                </div>

                @elseif($course->status_review === 'approved')

                <div class="mt-6 pt-6 border-t border-slate-200 dark:border-white/5">
                    <div class="w-full inline-flex items-center justify-center px-4 py-3 rounded-xl bg-emerald-500/10 text-emerald-500 text-[10px] font-black uppercase tracking-widest">
                        <i class="fa-solid fa-circle-check mr-2"></i>
                        Course Telah Diverifikasi
                    </div>
                </div>

                @endif
            </div>
        </div>

        {{-- Content Grid --}}
        <div id="sessions" class="mt-8">

            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-lg font-black dark:text-white text-slate-800">
                        Pratinjau Pertemuan
                    </h2>
                    <p class="text-[11px] dark:text-slate-500 text-slate-400">
                        Tampilan materi, kuis, dan proyek akhir yang akan dilihat oleh pelajar.
                    </p>
                </div>

                <button type="button"
                    onclick="collapseAllSessions()"
                    class="text-xs font-bold text-primary hover:underline">
                    Sembunyikan
                </button>
            </div>

            <div class="space-y-4">
                @forelse($course->sessions as $index => $session)

                @php
                $isLastSession = $loop->last;
                $sessionId = 'session-preview-' . $session->id;
                $totalQuestions = $session->exercises->sum(function ($exercise) {
                return $exercise->questions->count();
                });
                @endphp

                <div class="card-bg rounded-3xl overflow-hidden border dark:border-white/5 border-slate-200">

                    {{-- Header Accordion --}}
                    <button type="button"
                        onclick="toggleSession('{{ $sessionId }}')"
                        class="w-full px-5 py-5 flex items-center justify-between gap-4 text-left hover:bg-slate-50 dark:hover:bg-white/5 transition">

                        <div class="flex items-center gap-4 min-w-0">
                            <div id="{{ $sessionId }}-icon"
                                class="w-11 h-11 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 transition-transform duration-300">
                                <i class="fa-solid fa-chevron-right"></i>
                            </div>

                            <div class="min-w-0">
                                <span class="text-[10px] font-black uppercase tracking-widest {{ $isLastSession ? 'text-emerald-500' : 'text-primary' }}">
                                    {{ $isLastSession ? 'Final Project Session' : 'Pertemuan ' . ($index + 1) }}
                                </span>

                                <h3 class="text-lg lg:text-xl font-black dark:text-white text-slate-800 truncate">
                                    {{ $session->sessions_title ?? 'Pertemuan ' . ($index + 1) }}
                                </h3>

                                @if($session->sessions_description)
                                <p class="text-[11px] dark:text-slate-400 text-slate-500 mt-1 line-clamp-1">
                                    {{ $session->sessions_description }}
                                </p>
                                @endif
                            </div>
                        </div>

                        <div class="shrink-0 text-right hidden sm:block">
                            <p class="text-[10px] font-black dark:text-white text-slate-800">
                                {{ $session->lessons->count() }} Pelajaran
                            </p>

                            @if(!$isLastSession)
                            <p class="text-[10px] text-slate-400">
                                {{ $session->exercises->count() }} Latihan • {{ $totalQuestions }} Soal
                            </p>
                            @else
                            <p class="text-[10px] text-slate-400">
                                {{ $session->finalProjects->count() }} Proyek Akhir
                            </p>
                            @endif
                        </div>
                    </button>

                    {{-- Isi Accordion --}}
                    <div id="{{ $sessionId }}" class="hidden border-t dark:border-white/5 border-slate-200">
                        <div class="p-5 lg:p-6">

                            @if(!$isLastSession)

                            {{-- Lessons --}}
                            @forelse($session->lessons as $lesson)

                            <div class="mb-8 last:mb-0">
                                <h4 class="text-base lg:text-lg font-black dark:text-white text-slate-900 mb-2">
                                    {{ $lesson->lessons_title }}
                                </h4>

                                @if($lesson->lessons_description)
                                <p class="text-sm dark:text-slate-400 text-slate-600 mb-4 leading-relaxed">
                                    {{ $lesson->lessons_description }}
                                </p>
                                @endif

                                {{-- Materials --}}
                                <div class="space-y-4">

                                    @forelse($lesson->materials as $material)

                                    @php
                                    $type = strtolower($material->type ?? '');
                                    $file = $material->file_path ?? null;
                                    $url = $material->url ?? null;

                                    $fileSource = $file ? asset('storage/' . $file) : null;
                                    $source = $url ?: $fileSource;
                                    @endphp

                                    {{-- VIDEO --}}
                                    @if($type === 'video')
                                    <div class="w-full">

                                        @if($url)
                                        <div class="aspect-video w-full max-w-3xl mx-auto overflow-hidden rounded-2xl bg-black border dark:border-white/10 border-slate-200">
                                            <iframe
                                                src="{{ $url }}"
                                                class="w-full h-full"
                                                allowfullscreen
                                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture">
                                            </iframe>
                                        </div>

                                        @elseif($fileSource)
                                        <div class="aspect-video w-full max-w-3xl mx-auto overflow-hidden rounded-2xl bg-black border dark:border-white/10 border-slate-200">
                                            <video controls class="w-full h-full object-contain bg-black">
                                                <source src="{{ $fileSource }}">
                                                Browser kamu tidak mendukung video.
                                            </video>
                                        </div>

                                        @else
                                        <div class="p-4 rounded-xl bg-amber-500/10 text-amber-500 text-xs font-bold">
                                            Video belum tersedia.
                                        </div>
                                        @endif

                                    </div>

                                    {{-- PDF --}}
                                    @elseif($type === 'pdf')

                                    @if($source)
                                    <a href="{{ $source }}"
                                        target="_blank"
                                        class="flex items-center gap-4 p-4 rounded-2xl border dark:border-white/5 border-slate-200 dark:bg-[#0f0a19] bg-slate-50 hover:border-primary hover:bg-primary/5 transition">

                                        <div class="w-10 h-10 rounded-xl bg-cyan-500/10 text-cyan-500 flex items-center justify-center shrink-0">
                                            <i class="fa-regular fa-file-pdf text-lg"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-primary truncate">
                                                {{ $lesson->lessons_title }}
                                            </p>

                                            <p class="text-[11px] dark:text-slate-500 text-slate-400">
                                                PDF
                                            </p>
                                        </div>
                                    </a>
                                    @else
                                    <div class="p-4 rounded-xl bg-amber-500/10 text-amber-500 text-xs font-bold">
                                        File PDF belum tersedia.
                                    </div>
                                    @endif

                                    {{-- LINK BIASA --}}
                                    @elseif($type === 'link')

                                    @if($source)
                                    <a href="{{ $source }}"
                                        target="_blank"
                                        class="flex items-center gap-4 p-4 rounded-2xl border dark:border-white/5 border-slate-200 dark:bg-[#0f0a19] bg-slate-50 hover:border-primary hover:bg-primary/5 transition">

                                        <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0">
                                            <i class="fa-solid fa-link text-lg"></i>
                                        </div>

                                        <div class="min-w-0">
                                            <p class="text-sm font-bold text-primary truncate">
                                                {{ $lesson->lessons_title }}
                                            </p>

                                            <p class="text-[11px] dark:text-slate-500 text-slate-400 truncate">
                                                {{ $source }}
                                            </p>
                                        </div>
                                    </a>
                                    @else
                                    <div class="p-4 rounded-xl bg-amber-500/10 text-amber-500 text-xs font-bold">
                                        Link belum tersedia.
                                    </div>
                                    @endif

                                    {{-- FORMAT LAIN --}}
                                    @else
                                    <div class="p-4 rounded-xl dark:bg-[#0f0a19] bg-slate-50 border dark:border-white/5 border-slate-200">
                                        <p class="text-xs font-bold dark:text-white text-slate-800">
                                            {{ $lesson->lessons_title }}
                                        </p>

                                        <p class="text-[10px] text-slate-400 mt-1 uppercase">
                                            {{ $material->type ?? 'unknown' }}
                                        </p>
                                    </div>
                                    @endif

                                    @empty
                                    <div class="p-4 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-400 text-xs font-bold">
                                        Belum ada material untuk lesson ini.
                                    </div>
                                    @endforelse

                                </div>
                            </div>

                            @empty
                            <div class="p-6 rounded-2xl bg-slate-100 dark:bg-white/5 text-center">
                                <p class="text-xs text-slate-400 font-bold">
                                    Belum ada lesson di pertemuan ini.
                                </p>
                            </div>
                            @endforelse

                            {{-- Exercise / Kuis Preview --}}
                            @if($session->exercises->count() > 0)
                            <div class="mt-8 pt-6 border-t dark:border-white/5 border-slate-200">
                                <div class="flex items-center justify-between gap-4 mb-4">
                                    <div>
                                        <h4 class="text-sm font-black dark:text-white text-slate-800 uppercase tracking-widest">
                                            Kuis Pertemuan
                                        </h4>
                                        <p class="text-[11px] dark:text-slate-500 text-slate-400 mt-1">
                                            Preview kuis yang akan dikerjakan student setelah menyelesaikan materi.
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    @foreach($session->exercises as $exercise)
                                    @php
                                    $exercisePreviewId = 'exercise-preview-' . $exercise->id;
                                    $questionCount = $exercise->questions->count();
                                    @endphp

                                    <div class="rounded-2xl overflow-hidden border border-purple-500/20 dark:bg-purple-500/10 bg-purple-50">

                                        {{-- Header Kuis --}}
                                        <div class="p-5 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                                            <div class="flex items-start gap-4">
                                                <div class="w-11 h-11 rounded-xl bg-purple-500/10 text-[#A487F8] flex items-center justify-center shrink-0">
                                                    <i class="fa-solid fa-circle-question"></i>
                                                </div>

                                                <div>
                                                    <p class="text-[9px] font-black uppercase tracking-widest text-[#A487F8]">
                                                        Kuis
                                                    </p>

                                                    <h5 class="text-sm font-black dark:text-white text-slate-800 mt-1">
                                                        {{ $exercise->exercise_title ?? 'Kuis Pertemuan ' . ($index + 1) }}
                                                    </h5>

                                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                                        <span class="px-2.5 py-1 rounded-lg bg-white/70 dark:bg-white/5 text-[9px] font-black uppercase tracking-widest dark:text-slate-300 text-slate-600">
                                                            {{ $questionCount }} Soal
                                                        </span>

                                                        @if($questionCount > 0)
                                                        <span class="px-2.5 py-1 rounded-lg bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase tracking-widest">
                                                            Siap Dikerjakan
                                                        </span>
                                                        @else
                                                        <span class="px-2.5 py-1 rounded-lg bg-amber-500/10 text-amber-500 text-[9px] font-black uppercase tracking-widest">
                                                            Belum Ada Soal
                                                        </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-2">
                                                <a href="{{ route('mentor.sessions.exercises.edit', $exercise->id) }}"
                                                    class="px-4 py-2 rounded-xl bg-primary text-white text-[9px] font-black uppercase tracking-widest hover:brightness-110 transition">
                                                    Edit Kuis
                                                </a>

                                                @if($questionCount > 0)
                                                <button type="button"
                                                    onclick="toggleExercisePreview('{{ $exercisePreviewId }}')"
                                                    class="px-4 py-2 rounded-xl bg-white/70 dark:bg-white/5 dark:text-white text-slate-700 text-[9px] font-black uppercase tracking-widest hover:bg-[#A487F8] hover:text-white transition">
                                                    Preview Soal
                                                </button>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Isi Preview Soal --}}
                                        @if($questionCount > 0)
                                        <div id="{{ $exercisePreviewId }}" class="hidden border-t border-purple-500/20 p-5 dark:bg-[#0f0a19]/60 bg-white/60">
                                            <div class="space-y-4">
                                                @foreach($exercise->questions as $questionIndex => $question)
                                                <div class="rounded-2xl dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 p-4">
                                                    <div class="flex items-start gap-3 mb-4">
                                                        <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0 text-[10px] font-black">
                                                            {{ $questionIndex + 1 }}
                                                        </div>

                                                        <div>
                                                            <p class="text-sm font-bold dark:text-white text-slate-800 leading-relaxed">
                                                                {{ $question->question_text }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div class="space-y-2">
                                                        @foreach($question->options as $optionIndex => $option)
                                                        @php
                                                        $optionLabel = ['A', 'B', 'C', 'D'][$optionIndex] ?? $optionIndex + 1;
                                                        @endphp

                                                        <div class="flex items-center justify-between gap-3 rounded-xl px-4 py-3
                                                                                        {{ $option->is_correct
                                                                                            ? 'bg-emerald-500/10 border border-emerald-500/20'
                                                                                            : 'dark:bg-[#0f0a19] bg-slate-50 border dark:border-white/5 border-slate-200'
                                                                                        }}">

                                                            <div class="flex items-center gap-3 min-w-0">
                                                                <div class="w-7 h-7 rounded-full flex items-center justify-center shrink-0 text-[10px] font-black
                                                                                                {{ $option->is_correct
                                                                                                    ? 'bg-emerald-500 text-white'
                                                                                                    : 'bg-slate-200 dark:bg-white/10 dark:text-slate-300 text-slate-600'
                                                                                                }}">
                                                                    {{ $optionLabel }}
                                                                </div>

                                                                <p class="text-xs font-semibold dark:text-slate-300 text-slate-700">
                                                                    {{ $option->option_text }}
                                                                </p>
                                                            </div>

                                                            @if($option->is_correct)
                                                            <span class="text-[9px] font-black uppercase tracking-widest text-emerald-500 shrink-0">
                                                                Jawaban Benar
                                                            </span>
                                                            @endif
                                                        </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            @else

                            {{-- Final Project --}}
                            @forelse($session->finalProjects as $project)
                            <div class="p-5 rounded-2xl bg-emerald-500/10 border border-emerald-500/20">
                                <div class="flex items-start gap-4">
                                    <div class="w-11 h-11 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0">
                                        <i class="fa-solid fa-diagram-project"></i>
                                    </div>

                                    <div>
                                        <h4 class="text-sm font-black text-emerald-500">
                                            {{ $project->project_title }}
                                        </h4>

                                        <p class="text-xs dark:text-slate-400 text-slate-600 mt-2 leading-relaxed">
                                            {{ $project->project_description }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="p-6 rounded-2xl bg-slate-100 dark:bg-white/5 text-center">
                                <p class="text-xs text-slate-400 font-bold">
                                    Final project belum dibuat.
                                </p>
                            </div>
                            @endforelse

                            @endif

                        </div>
                    </div>
                </div>

                @empty
                <div class="card-bg rounded-3xl p-10 text-center">
                    <div class="w-16 h-16 mx-auto rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-5">
                        <i class="fa-solid fa-layer-group text-xl"></i>
                    </div>

                    <h3 class="text-lg font-black dark:text-white text-slate-800">
                        Belum Ada Session
                    </h3>

                    <p class="text-xs dark:text-slate-500 text-slate-400 mt-2 max-w-md mx-auto">
                        Session akan otomatis dibuat saat kamu menambahkan course.
                    </p>
                </div>
                @endforelse
            </div>

        </div>
    </div>
</main>

@push('scripts')
<script>
    function toggleSession(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById(id + '-icon');

        if (!content || !icon) return;

        content.classList.toggle('hidden');
        icon.classList.toggle('rotate-90');
    }

    function collapseAllSessions() {
        document.querySelectorAll('[id^="session-preview-"]').forEach(function(element) {
            if (!element.id.endsWith('-icon')) {
                element.classList.add('hidden');
            }
        });

        document.querySelectorAll('[id$="-icon"]').forEach(function(icon) {
            icon.classList.remove('rotate-90');
        });
    }

    function toggleExercisePreview(id) {
        const content = document.getElementById(id);

        if (!content) return;

        content.classList.toggle('hidden');
    }
</script>
@endpush

@endsection