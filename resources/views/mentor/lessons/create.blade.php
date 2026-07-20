@extends('layouts.dashboard')

@section('title', 'CLEARN │ Tambah Materi')

@section('content')

<main class="flex-1 p-5 lg:p-8 transition-colors duration-300 dark:bg-[#0F0B1A] bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto">

        <a href="{{ route('mentor.courses.sessions.edit', $session->course_id) }}"
            class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-primary transition">
            <i class="fa-solid fa-arrow-left"></i>
            Kembali ke isi Pertemuan
        </a>

        <div class="mt-6 dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-3xl p-6 lg:p-8 shadow-sm">

            <div class="mb-6">
                <h1 class="text-xl font-black dark:text-white text-slate-800">
                    Tambah Pelajaran
                </h1>

                <p class="text-[11px] dark:text-slate-500 text-slate-400 mt-1">
                    Pelajaran ini akan ditambahkan ke:
                    <span class="font-bold text-primary">
                        {{ $session->sessions_title }}
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

            <form action="{{ route('mentor.sessions.lessons.store', $session->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                        Judul Pelajaran
                    </label>

                    <input type="text"
                        name="lessons_title"
                        value="{{ old('lessons_title') }}"
                        required
                        placeholder="Contoh: Pengenalan Laravel"
                        class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1A1625] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">
                </div>

                <div class="mb-6">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                        Deskripsi Pelajaran
                    </label>

                    <textarea name="lessons_description"
                        rows="5"
                        placeholder="Jelaskan isi pelajaran ini..."
                        class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1A1625] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">{{ old('lessons_description') }}</textarea>
                </div>

                <div class="mb-5">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                        Unggah File Materi
                    </label>

                    <input type="file"
                        name="material_file"
                        accept=".pdf,.mp4,.mov,.avi,.mkv,.webm"
                        class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1A1625] border border-slate-200 dark:border-white/10 dark:text-white text-slate-500 text-sm outline-none focus:border-primary transition">

                    <p class="text-[10px] text-slate-400 mt-2">
                        Upload PDF atau video. Sistem akan membaca tipe file otomatis.
                    </p>
                </div>

                <div class="mb-6">
                    <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                        Tautan Materi
                    </label>

                    <input type="url"
                        name="material_url"
                        value="{{ old('material_url') }}"
                        placeholder="https://youtube.com/watch?v=..."
                        class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1A1625] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">

                    <p class="text-[10px] text-slate-400 mt-2">
                        Masukkan tautan apabila materi berupa tautan YouTube, Google Drive, atau website.
                    </p>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('mentor.courses.sessions.edit', $session->course_id) }}"
                        class="px-6 py-3 rounded-xl bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-8 py-3 rounded-xl bg-primary text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:brightness-110 transition">
                        Simpan Pelajaran
                    </button>
                </div>
            </form>

        </div>
    </div>
</main>

@endsection