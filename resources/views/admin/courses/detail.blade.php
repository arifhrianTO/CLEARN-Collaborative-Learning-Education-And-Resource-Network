@extends('layouts.dashboard')

@section('title', 'CLEARN │ Detail Verifikasi')

@section('content')

@php
    $latestReject = $course->verifications
        ->where('action', 'rejected')
        ->sortByDesc('verify_at')
        ->first();
@endphp

<main class="flex-1 bg-white dark:bg-[#0F0B1A] text-slate-900 dark:text-white min-h-screen transition-colors duration-300 relative overflow-x-hidden">
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-[#A487F8] opacity-10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-6xl mx-auto p-5 lg:p-8 relative z-10">

        <header class="mb-6 flex flex-col md:flex-row md:items-center gap-4">
            <a href="{{ route('admin.courses.index') }}"
               class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-[#1A1625]/60 border border-gray-100 dark:border-gray-800 hover:scale-105 transition-all shadow-sm group">
                <i class="fas fa-arrow-left text-[#A487F8] group-hover:-translate-x-0.5 transition-transform text-sm"></i>
            </a>

            <div>
                <div class="flex items-center gap-2 mb-1">
                    @if ($course->status_review === 'approved')
                        <span class="text-[9px] bg-emerald-600 text-white px-2 py-0.5 rounded-lg font-black uppercase tracking-widest">
                            Disetujui
                        </span>
                    @elseif ($course->status_review === 'rejected')
                        <span class="text-[9px] bg-rose-600 text-white px-2 py-0.5 rounded-lg font-black uppercase tracking-widest">
                            Ditolak
                        </span>
                    @elseif ($course->status_review === 'pending')
                        <span class="text-[9px] bg-amber-500 text-white px-2 py-0.5 rounded-lg font-black uppercase tracking-widest">
                            Menunggu Verifikasi
                        </span>
                    @else
                        <span class="text-[9px] bg-slate-600 text-white px-2 py-0.5 rounded-lg font-black uppercase tracking-widest">
                            {{ $course->status_review ?? 'Draft' }}
                        </span>
                    @endif
                </div>

                <h1 class="text-xl lg:text-2xl font-bold tracking-tight">
                    Detail Verifikasi Kursus
                </h1>

                <p class="text-slate-500 dark:text-gray-400 text-xs mt-0.5 font-medium">
                    Tinjau kelayakan materi, kuis, dan silabus yang diajukan oleh mentor.
                </p>
            </div>
        </header>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-600 dark:text-rose-400 text-xs font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white dark:bg-[#1A1625]/50 backdrop-blur-xl border border-gray-100 dark:border-gray-800/80 rounded-3xl p-6 shadow-sm">
                    <h2 class="text-md font-bold mb-6 flex items-center gap-3">
                        <span class="w-1.5 h-5 bg-[#A487F8] rounded-full"></span>
                        Informasi Dasar Kursus
                    </h2>

                    <div class="space-y-5">
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1.5 block">
                                Judul Kursus
                            </label>

                            <p class="text-base font-bold leading-tight">
                                {{ $course->course_title ?? 'Judul kursus belum diisi' }}
                            </p>
                        </div>

                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1.5 block">
                                Deskripsi / Ringkasan Kursus
                            </label>

                            <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed font-medium">
                                {{ $course->course_description ?? 'Deskripsi kursus belum diisi.' }}
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1.5 block">
                                    Kategori
                                </label>

                                <span class="text-xs font-bold bg-slate-50 dark:bg-[#1A1625] border border-gray-100 dark:border-gray-800/50 px-3 py-1.5 rounded-xl inline-block">
                                    {{ $course->category?->category_name ?? 'Tanpa Kategori' }}
                                </span>
                            </div>

                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1.5 block">
                                    Harga Kursus
                                </label>

                                <span class="text-xs font-black text-[#A487F8] bg-[#A487F8]/10 px-3 py-1.5 rounded-xl inline-block">
                                    Rp {{ number_format($course->course_price ?? 0, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-gray-100 dark:border-gray-800/60">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 block">
                                Thumbnail Kursus
                            </label>

                            <div class="aspect-video rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-800 bg-slate-50 dark:bg-slate-900 relative">
                                @if ($course->course_thumbnail)
                                    <img src="{{ asset('storage/' . $course->course_thumbnail) }}"
                                         class="w-full h-full object-cover"
                                         alt="{{ $course->course_title }}">
                                @else
                                    <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 gap-2">
                                        <i class="fa-solid fa-image text-3xl"></i>
                                        <span class="text-xs font-bold">Thumbnail belum tersedia</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if ($latestReject)
                            <div class="p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20">
                                <label class="text-[10px] font-black uppercase text-rose-500 tracking-widest mb-1.5 block">
                                    Alasan Penolakan Terakhir
                                </label>

                                <p class="text-xs text-rose-600 dark:text-rose-400 font-medium leading-relaxed">
                                    {{ $latestReject->course_rejection_reason }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="bg-white dark:bg-[#1A1625]/50 backdrop-blur-xl border border-gray-100 dark:border-gray-800/80 rounded-3xl p-6 shadow-sm">
                    <h2 class="text-md font-extrabold mb-6 flex items-center gap-3">
                        <span class="w-1.5 h-5 bg-[#A487F8] rounded-full"></span>
                        Kurikulum & Konten yang Diajukan
                    </h2>

                    <div class="space-y-6">
                        @forelse ($course->sessions as $index => $session)
                            <div class="bg-slate-50 dark:bg-[#1A1625]/50 border border-gray-100 dark:border-gray-800 rounded-3xl p-5">

                                <div class="mb-4">
                                    <label class="text-[9px] font-black uppercase text-[#A487F8] tracking-widest block mb-0.5">
                                        Session {{ $index + 1 }}
                                    </label>

                                    <h3 class="font-bold text-sm">
                                        {{ $session->sessions_title ?? 'Judul session belum diisi' }}
                                    </h3>

                                    @if ($session->sessions_description)
                                        <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">
                                            {{ $session->sessions_description }}
                                        </p>
                                    @endif
                                </div>

                                <div class="space-y-3 mb-6">
                                    <h4 class="text-[10px] font-black uppercase text-slate-400 tracking-widest">
                                        Materi Pembelajaran
                                    </h4>

                                    @forelse ($session->lessons as $lesson)
                                        <div class="p-4 bg-white dark:bg-[#1A1625] border border-gray-100 dark:border-gray-800 rounded-2xl">
                                            <div class="mb-3">
                                                <h4 class="text-xs font-bold">
                                                    {{ $lesson->lessons_title ?? 'Judul materi belum diisi' }}
                                                </h4>

                                                <p class="text-[10px] text-slate-400 font-medium mt-1">
                                                    {{ $lesson->lessons_description ?? 'Tidak ada deskripsi materi.' }}
                                                </p>
                                            </div>

                                            <div class="space-y-2">
                                                @forelse ($lesson->materials as $material)
                                                    @php
                                                        $path = $material->file_path ?? null;
                                                        $url = $material->url ?? null;
                                                        $type = $material->type ?? 'file';
                                                        $target = $url ?: ($path ? asset('storage/' . $path) : null);
                                                    @endphp

                                                    <div class="flex items-center justify-between gap-3 p-3 bg-slate-50 dark:bg-[#1A1625] border border-gray-100 dark:border-gray-800 rounded-xl">
                                                        <div class="flex items-center gap-3 min-w-0">
                                                            <div class="w-8 h-8 rounded-xl bg-blue-500/10 flex items-center justify-center flex-shrink-0 text-blue-500">
                                                                @if ($type === 'video')
                                                                    <i class="fas fa-play text-xs"></i>
                                                                @elseif ($type === 'pdf')
                                                                    <i class="fas fa-file-pdf text-xs"></i>
                                                                @elseif ($type === 'url')
                                                                    <i class="fas fa-link text-xs"></i>
                                                                @else
                                                                    <i class="fas fa-file-lines text-xs"></i>
                                                                @endif
                                                            </div>

                                                            <div class="min-w-0">
                                                                <h5 class="text-xs font-bold truncate">
                                                                    {{ strtoupper($type) }}
                                                                </h5>

                                                                <p class="text-[10px] text-slate-400 font-medium truncate">
                                                                    {{ $url ?? $path ?? 'Materi belum tersedia' }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                        @if ($target)
                                                            <a href="{{ $target }}"
                                                               target="_blank"
                                                               class="px-3 py-1.5 bg-white dark:bg-white/5 text-[10px] font-black uppercase tracking-wider rounded-lg text-slate-500 dark:text-slate-300 hover:text-[#A487F8] transition-colors border border-gray-100 dark:border-transparent">
                                                                Buka
                                                            </a>
                                                        @endif
                                                    </div>
                                                @empty
                                                    <p class="text-[11px] text-slate-400 font-bold">
                                                        Belum ada file / URL materi.
                                                    </p>
                                                @endforelse
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-4 bg-white dark:bg-[#1A1625] border border-dashed border-gray-200 dark:border-gray-800 rounded-2xl">
                                            <p class="text-[11px] text-slate-400 font-bold">
                                                Belum ada lesson pada session ini.
                                            </p>
                                        </div>
                                    @endforelse
                                </div>

                                <div class="mb-4">
                                    <h3 class="font-bold text-sm">
                                        Kuis Kualifikasi Kompetensi
                                    </h3>
                                </div>

                                <div class="space-y-4">
                                    @forelse ($session->exercises as $exercise)
                                        <div class="p-4 bg-white dark:bg-[#1A1625] border border-gray-100 dark:border-gray-800 rounded-2xl">
                                            <div class="flex justify-between mb-3">
                                                <span class="text-[9px] bg-emerald-500 text-white px-2 py-0.5 rounded font-black uppercase tracking-wider">
                                                    Kuis Pilihan Ganda
                                                </span>
                                            </div>

                                            <h4 class="text-xs font-bold mb-4">
                                                {{ $exercise->exercise_title ?? 'Judul kuis belum diisi' }}
                                            </h4>

                                            <div class="space-y-5">
                                                @forelse ($exercise->questions as $question)
                                                    <div class="space-y-3">
                                                        <p class="text-xs font-bold">
                                                            {{ $question->question_text ?? 'Pertanyaan belum diisi' }}
                                                        </p>

                                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2.5 pt-1">
                                                            @forelse ($question->options as $optionIndex => $option)
                                                                <div class="flex items-center gap-2 p-2.5 rounded-xl border
                                                                    {{ $option->is_correct
                                                                        ? 'bg-emerald-500/5 dark:bg-emerald-500/10 border-emerald-500/20 text-emerald-600 dark:text-emerald-400'
                                                                        : 'bg-slate-50 dark:bg-[#1A1625] border-gray-100 dark:border-gray-800 text-slate-600 dark:text-slate-400' }}">

                                                                    <span class="text-[10px] font-black w-4">
                                                                        {{ chr(65 + $optionIndex) }}
                                                                    </span>

                                                                    <span class="text-xs {{ $option->is_correct ? 'font-bold' : 'font-medium' }}">
                                                                        {{ $option->option_text ?? '-' }}
                                                                    </span>
                                                                </div>
                                                            @empty
                                                                <p class="text-[11px] text-slate-400 font-bold">
                                                                    Belum ada opsi jawaban.
                                                                </p>
                                                            @endforelse
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-[11px] text-slate-400 font-bold">
                                                        Belum ada soal pada kuis ini.
                                                    </p>
                                                @endforelse
                                            </div>
                                        </div>
                                    @empty
                                        <div class="p-4 bg-white dark:bg-[#1A1625] border border-dashed border-gray-200 dark:border-gray-800 rounded-2xl">
                                            <p class="text-[11px] text-slate-400 font-bold">
                                                Belum ada kuis pada session ini.
                                            </p>
                                        </div>
                                    @endforelse
                                </div>

                                @if ($session->finalProjects->count() > 0)
                                    <div class="mt-6">
                                        <h3 class="font-bold text-sm mb-3">
                                            Final Project
                                        </h3>

                                        <div class="space-y-3">
                                            @foreach ($session->finalProjects as $project)
                                                <div class="p-4 bg-white dark:bg-[#1A1625] border border-gray-100 dark:border-gray-800 rounded-2xl">
                                                    <span class="text-[9px] bg-[#A487F8] text-white px-2 py-0.5 rounded font-black uppercase tracking-wider">
                                                        Final Project
                                                    </span>

                                                    <h4 class="text-xs font-bold mt-3">
                                                        {{ $project->project_title ?? 'Final Project' }}
                                                    </h4>

                                                    <p class="text-[11px] text-slate-400 mt-1 leading-relaxed">
                                                        {{ $project->project_description ?? '-' }}
                                                    </p>

                                                    <div class="mt-3 space-y-2">
                                                        @forelse ($project->materials as $material)
                                                            @php
                                                                $path = $material->file_path ?? null;
                                                                $url = $material->url ?? null;
                                                                $type = $material->type ?? 'file';
                                                                $target = $url ?: ($path ? asset('storage/' . $path) : null);
                                                            @endphp

                                                            <div class="flex items-center justify-between gap-3 p-3 bg-slate-50 dark:bg-[#1A1625] border border-gray-100 dark:border-gray-800 rounded-xl">
                                                                <span class="text-[10px] font-bold text-slate-500 dark:text-slate-300 truncate">
                                                                    {{ strtoupper($type) }} - {{ $url ?? $path ?? 'Materi final project' }}
                                                                </span>

                                                                @if ($target)
                                                                    <a href="{{ $target }}"
                                                                       target="_blank"
                                                                       class="px-3 py-1.5 bg-white dark:bg-white/5 text-[10px] font-black uppercase tracking-wider rounded-lg text-slate-500 dark:text-slate-300 hover:text-[#A487F8] transition-colors border border-gray-100 dark:border-transparent">
                                                                        Buka
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        @empty
                                                            <p class="text-[11px] text-slate-400 font-bold">
                                                                Belum ada lampiran final project.
                                                            </p>
                                                        @endforelse
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                            </div>
                        @empty
                            <div class="bg-slate-50 dark:bg-[#1A1625]/50 border border-dashed border-gray-200 dark:border-gray-800 rounded-3xl p-8 text-center">
                                <div class="w-12 h-12 mx-auto rounded-2xl bg-white dark:bg-[#1A1625] flex items-center justify-center text-slate-400 mb-3">
                                    <i class="fa-solid fa-layer-group"></i>
                                </div>

                                <p class="text-xs font-bold text-slate-400">
                                    Kursus ini belum memiliki session atau kurikulum.
                                </p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-white dark:bg-[#1A1625]/50 backdrop-blur-xl border border-gray-100 dark:border-gray-800/80 rounded-3xl p-6 shadow-sm lg:sticky lg:top-6">

                    <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 pb-2 border-b border-gray-100 dark:border-gray-800/60">
                        Tindakan
                    </h2>

                    <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-[#1A1625] border border-gray-100 dark:border-gray-800/60 rounded-2xl mb-6">
                        <div class="w-9 h-9 rounded-xl bg-[#A487F8] flex items-center justify-center text-white text-xs font-black uppercase">
                            {{ strtoupper(substr($course->mentor?->name ?? 'M', 0, 2)) }}
                        </div>

                        <div class="min-w-0">
                            <h4 class="text-xs font-bold truncate">
                                {{ $course->mentor?->name ?? 'Nama Mentor' }}
                            </h4>

                            <p class="text-[9px] text-slate-400 uppercase tracking-wider font-extrabold">
                                ID Mentor: #{{ $course->mentor?->id ?? '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="space-y-3 mb-6 text-[11px] text-slate-400 font-medium px-1">
                        <div class="flex justify-between">
                            <span>Tanggal Diajukan:</span>
                            <span class="font-bold text-slate-800 dark:text-white">
                                {{ $course->created_at ? $course->created_at->format('d M Y') : '-' }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span>Status Review:</span>
                            <span class="font-bold text-slate-800 dark:text-white uppercase">
                                {{ $course->status_review ?? '-' }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span>Status Publish:</span>
                            <span class="font-bold text-slate-800 dark:text-white uppercase">
                                {{ $course->status_publish ?? '-' }}
                            </span>
                        </div>

                        <div class="flex justify-between">
                            <span>Total Session:</span>
                            <span class="font-bold text-slate-800 dark:text-white">
                                {{ $course->sessions->count() }}
                            </span>
                        </div>
                    </div>

                    <form
                        action="{{ route('admin.courses.approve', $course->id) }}"
                        method="POST"
                        class="mb-3"
                        onsubmit="return confirm('Yakin ingin menyetujui dan menerbitkan kursus ini?')">
                        @csrf
                        @method('PATCH')

                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest rounded-xl transition-all gap-2 shadow-sm active:scale-[0.99]">
                            <i class="fa-solid fa-check text-[10px]"></i>
                            Setujui & Terbitkan
                        </button>
                    </form>

                    <form
                        action="{{ route('admin.courses.reject', $course->id) }}"
                        method="POST"
                        class="space-y-3"
                        onsubmit="return confirm('Yakin ingin menolak pengajuan kursus ini?')">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-1.5 block">
                                Alasan Penolakan
                            </label>

                            <textarea
                                name="course_rejection_reason"
                                rows="4"
                                required
                                placeholder="Isi alasan jika ingin menolak kursus..."
                                class="w-full rounded-xl border border-gray-200 dark:border-gray-800 bg-white dark:bg-[#1A1625] text-slate-700 dark:text-white text-xs px-3 py-2 focus:ring-[#A487F8] focus:border-[#A487F8] resize-none">{{ old('course_rejection_reason') }}</textarea>
                        </div>

                        <button
                            type="submit"
                            class="w-full inline-flex items-center justify-center px-4 py-3 bg-rose-500/10 hover:bg-rose-600 hover:text-white text-rose-500 text-[10px] font-black uppercase tracking-widest rounded-xl border border-rose-500/20 transition-all gap-2 active:scale-[0.99]">
                            <i class="fa-solid fa-xmark text-[10px]"></i>
                            Tolak Pengajuan
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</main>

@endsection