@extends('layouts.dashboard')

@section('title', 'Perbarui Kursus | Dashboard Mentor | Clearn - Platform Pembelajaran Online')

@section('content')

<main class="flex-1 p-5 lg:p-8 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('mentor.courses.show', $course->id) }}"
                class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-primary transition">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Detail Kursus
            </a>

            <h1 class="text-2xl font-extrabold dark:text-white text-slate-800 tracking-tight mt-4">
                Perbarui Kursus
            </h1>

            <p class="text-[11px] dark:text-slate-400 text-slate-500 mt-1 italic font-medium uppercase tracking-widest">
                Ubah informasi dasar kursus kamu.
            </p>
        </div>

        {{-- Error Validation --}}
        @if ($errors->any())
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl text-xs font-semibold">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('mentor.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="card-bg rounded-3xl p-6 lg:p-8">

                {{-- Title Card --}}
                <div class="mb-7">
                    <div class="flex items-center gap-3">
                        <span class="w-1.5 h-5 rounded-full bg-primary"></span>

                        <h2 class="text-sm font-black dark:text-white text-slate-800 uppercase tracking-widest">
                            Informasi Dasar
                        </h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    {{-- Judul Course --}}
                    <div class="md:col-span-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Judul Kursus
                        </label>

                        <input type="text"
                            name="course_title"
                            value="{{ old('course_title', $course->course_title) }}"
                            required
                            placeholder="Contoh: Laravel Dasar untuk Pemula"
                            class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#0f0a19] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">
                    </div>

                    {{-- Deskripsi --}}
                    <div class="md:col-span-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Deskripsi
                        </label>

                        <textarea name="course_description"
                            rows="5"
                            required
                            placeholder="Jelaskan isi dan tujuan course ini..."
                            class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#0f0a19] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition overflow-hidden resize-none"
                            oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ old('course_description', $course->course_description) }}</textarea>
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Kategori
                        </label>

                        <select name="category_id"
                            required
                            class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#0f0a19] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">
                            <option value="">Pilih Kategori</option>

                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $course->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jumlah Session --}}
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Jumlah Pertemuan
                        </label>

                        <input type="number"
                            name="session_count"
                            value="{{ old('session_count', $course->sessions->count()) }}"
                            min="{{ $course->sessions->count() }}"
                            max="50"
                            required
                            placeholder="Contoh: 3"
                            class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#0f0a19] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">

                        <p class="text-[10px] text-slate-400 mt-2">
                            Pertemuan saat ini: {{ $course->sessions->count() }}. Hanya bisa menambah Pertemuan.
                        </p>
                    </div>

                    {{-- Harga --}}
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Harga (RP)
                        </label>

                        <input type="number"
                            name="course_price"
                            value="{{ old('course_price', $course->course_price) }}"
                            min="0"
                            required
                            placeholder="Contoh: 50000"
                            class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#0f0a19] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">
                    </div>

                    {{-- Thumbnail --}}
                    <div class="md:col-span-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Thumbnail Baru
                        </label>

                        @if($course->course_thumbnail)
                        <img src="{{ asset('storage/' . $course->course_thumbnail) }}"
                            alt="Thumbnail Course"
                            class="w-full h-56 object-cover rounded-2xl mb-4 border border-slate-200 dark:border-white/10">
                        @endif

                        <input type="file"
                            name="course_thumbnail"
                            accept="image/*"
                            class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#0f0a19] border border-slate-200 dark:border-white/10 dark:text-white text-slate-500 text-sm outline-none focus:border-primary transition">

                        <p class="text-[10px] text-slate-400 mt-2">
                            Kosongkan jika tidak ingin mengganti thumbnail. Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.
                        </p>
                    </div>

                </div>

                {{-- Button --}}
                <div class="flex items-center justify-end gap-3 mt-8">
                    <a href="{{ route('mentor.courses.show', $course->id) }}"
                        class="px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest bg-slate-200 dark:bg-white/5 dark:text-slate-300 text-slate-600 hover:opacity-80 transition">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest bg-primary text-white shadow-md shadow-primary/20 hover:scale-105 transition">
                        Simpan Perubahan
                    </button>
                </div>

            </div>
        </form>

    </div>
</main>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Cari textarea berdasarkan namanya
        let descTextarea = document.querySelector('textarea[name="course_description"]');
        
        // Jika ditemukan dan ada isinya, sesuaikan tinggi kotak dengan tinggi teks (scrollHeight)
        if (descTextarea) {
            descTextarea.style.height = 'auto';
            descTextarea.style.height = descTextarea.scrollHeight + 'px';
        }
    });
</script>
@endpush

@endsection