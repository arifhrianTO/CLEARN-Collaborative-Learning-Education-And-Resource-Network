@extends('layouts.dashboard')

@section('title', 'Edit Lesson | Dashboard Mentor')

@section('content')

<main class="flex-1 p-5 lg:p-8 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto">

        <a href="{{ route('mentor.courses.sessions.edit', $lesson->session->course_id) }}"
            class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-primary transition">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke Isi Sessions
        </a>

        <div class="mt-6 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-3xl p-6 lg:p-8 shadow-sm">

            <div class="mb-6">
                <h1 class="text-xl font-black dark:text-white text-slate-800">
                    Edit Lesson
                </h1>

                <p class="text-[11px] dark:text-slate-500 text-slate-400 mt-1">
                    Lesson dari:
                    <span class="font-bold text-primary">
                        {{ $lesson->session->sessions_title }}
                    </span>
                </p>
            </div>

            @if ($errors->any())
            <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl text-xs font-semibold">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('mentor.lessons.update', $lesson->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                        Judul Lesson
                    </label>

                    <input type="text"
                        name="lessons_title"
                        value="{{ old('lessons_title', $lesson->lessons_title) }}"
                        required
                        class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#0f0a19] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">
                </div>

                <div class="mb-6">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                        Deskripsi Lesson
                    </label>

                    <textarea name="lessons_description"
                        rows="5"
                        class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#0f0a19] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">{{ old('lessons_description', $lesson->lessons_description) }}</textarea>
                </div>

                <div class="mb-6">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3">
                        Material Saat Ini
                    </p>

                    <div class="space-y-3">
                        @forelse($lesson->materials as $material)
                        @php
                        $source = $material->url ?: ($material->file_path ? asset('storage/' . $material->file_path) : null);
                        @endphp

                        <div class="flex items-center justify-between gap-3 p-4 rounded-2xl dark:bg-[#0f0a19] bg-slate-50 border dark:border-white/5 border-slate-200">
                            <div>
                                <p class="text-xs font-black dark:text-white text-slate-800 uppercase">
                                    {{ $material->type }}
                                </p>

                                @if($source)
                                <a href="{{ $source }}" target="_blank" class="text-[10px] text-primary font-bold break-all">
                                    {{ $source }}
                                </a>
                                @else
                                <p class="text-[10px] text-slate-400">
                                    Sumber tidak tersedia.
                                </p>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="p-4 rounded-xl bg-slate-100 dark:bg-white/5 text-slate-400 text-xs font-bold">
                            Belum ada material.
                        </div>
                        @endforelse
                    </div>
                </div>

                <div class="mb-5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                        Tambah File Materi Baru
                    </label>

                    <input type="file"
                        name="material_file"
                        accept=".pdf,.mp4,.mov,.avi,.mkv,.webm"
                        class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#0f0a19] border border-slate-200 dark:border-white/10 dark:text-white text-slate-500 text-sm outline-none focus:border-primary transition">

                    <p class="text-[10px] text-slate-400 mt-2">
                        Opsional. Jika diisi, material baru akan ditambahkan.
                    </p>
                </div>

                <div class="mb-6">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                        Tambah Link Materi Baru
                    </label>

                    <input type="url"
                        name="material_url"
                        value="{{ old('material_url') }}"
                        placeholder="https://youtube.com/watch?v=..."
                        class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#0f0a19] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">

                    <p class="text-[10px] text-slate-400 mt-2">
                        Opsional. Jika diisi, link baru akan ditambahkan.
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('mentor.courses.sessions.edit', $lesson->session->course_id) }}"
                        class="px-6 py-3 rounded-xl bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-8 py-3 rounded-xl bg-primary text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:brightness-110 transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
</main>

@endsection