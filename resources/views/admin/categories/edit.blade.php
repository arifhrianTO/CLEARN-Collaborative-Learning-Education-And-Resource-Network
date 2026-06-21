@extends('layouts.dashboard')

@section('title', 'Perbarui Kategori Kursus | Dashboard Admin | Clearn - Platform Pembelajaran Online')

@section('content')

<main class="flex-1 p-5 lg:p-8 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <header class="mb-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="text-lg font-bold dark:text-white text-slate-800">
                        Edit Kategori
                    </h1>
                    <p class="text-[10px] dark:text-slate-500 text-slate-400 font-medium mt-0.5">
                        Ubah detail kategori yang sudah ada.
                    </p>
                </div>

                <a href="{{ route('admin.categories.index') }}"
                    class="px-4 py-2.5 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-white/10 transition-all">
                    Kembali
                </a>
            </div>
        </header>

        {{-- Error global --}}
        @if ($errors->any())
        <div class="mb-5 p-4 rounded-xl bg-red-100 text-red-700 text-xs font-bold">
            <p class="mb-2">Ada data yang belum benar:</p>
            <ul class="list-disc list-inside space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="bg-white dark:bg-[#161525] border dark:border-white/5 border-slate-200 p-6 rounded-2xl shadow-sm">
            <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                    {{-- Kolom Kiri: Form --}}
                    <div class="md:col-span-2 space-y-6">

                        {{-- Nama Kategori --}}
                        <div>
                            <label for="category_name"
                                class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                Nama Kategori
                            </label>

                            <input type="text"
                                id="category_name"
                                name="category_name"
                                value="{{ old('category_name', $category->category_name) }}"
                                class="w-full bg-slate-50 dark:bg-[#0f0a19] border border-slate-200 dark:border-white/5 rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-[#7C3AED] transition-all @error('category_name') border-red-500 @enderror"
                                placeholder="Masukkan nama kategori">

                            @error('category_name')
                            <p class="text-[10px] text-red-500 font-bold mt-2">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Icon dan Warna --}}
                        <div class="grid grid-cols-4 gap-4">
                            <div class="col-span-3">
                                <label for="iconInput"
                                    class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                    Nama Ikon Font Awesome
                                </label>

                                <input type="text"
                                    id="iconInput"
                                    name="category_icon"
                                    value="{{ old('category_icon', $category->category_icon ?? 'fa-book') }}"
                                    class="w-full bg-slate-50 dark:bg-[#0f0a19] border border-slate-200 dark:border-white/5 rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-[#7C3AED] transition-all @error('category_icon') border-red-500 @enderror"
                                    placeholder="Contoh: fa-code"
                                    oninput="updatePreview()">

                                <p class="text-[9px] text-slate-500 mt-2">
                                    Masukkan class ikon, contoh:
                                    <code class="text-[#7C3AED] font-bold">fa-code</code>,
                                    <code class="text-[#7C3AED] font-bold">fa-book</code>,
                                    <code class="text-[#7C3AED] font-bold">fa-laptop-code</code>.
                                </p>

                                @error('category_icon')
                                <p class="text-[10px] text-red-500 font-bold mt-2">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>

                            <div class="flex flex-col items-center">
                                <label for="colorInput"
                                    class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2 text-center">
                                    Warna
                                </label>

                                <input type="color"
                                    id="colorInput"
                                    name="category_color"
                                    value="{{ old('category_color', $category->category_color ?? '#7C3AED') }}"
                                    class="w-10 h-10 rounded-full cursor-pointer border-0 bg-transparent"
                                    oninput="updatePreview()">

                                @error('category_color')
                                <p class="text-[10px] text-red-500 font-bold mt-2 text-center">
                                    {{ $message }}
                                </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="category_description"
                                class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">
                                Deskripsi Kategori
                            </label>

                            <textarea
                                id="category_description"
                                name="category_description"
                                rows="3"
                                class="w-full bg-slate-50 dark:bg-[#0f0a19] border border-slate-200 dark:border-white/5 rounded-xl px-4 py-3 text-sm text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-[#7C3AED] transition-all resize-none @error('category_description') border-red-500 @enderror"
                                placeholder="Berikan deskripsi singkat kategori...">{{ old('category_description', $category->category_description) }}</textarea>

                            @error('category_description')
                            <p class="text-[10px] text-red-500 font-bold mt-2">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Kolom Kanan: Pratinjau & Panduan --}}
                    <div class="md:col-span-1 flex flex-col gap-6 h-full">

                        {{-- Preview --}}
                        <div class="flex flex-col items-center justify-center p-4 bg-slate-50 dark:bg-[#0f0a19] border-2 border-dashed border-slate-200 dark:border-white/10 rounded-2xl flex-grow">
                            <label class="block text-[9px] font-black text-slate-400 uppercase tracking-widest mb-4">
                                Pratinjau
                            </label>

                            <div class="w-20 h-20 bg-white dark:bg-[#161525] rounded-xl flex items-center justify-center border dark:border-white/5 shadow-sm">
                                <i id="iconPreview"
                                    class="fas {{ old('category_icon', $category->category_icon ?? 'fa-book') }} text-3xl"
                                    style="color: {{ old('category_color', $category->category_color ?? '#7C3AED') }};">
                                </i>
                            </div>

                            <p class="text-[10px] text-slate-400 text-center mt-4">
                                Icon akan berubah otomatis sesuai input.
                            </p>
                        </div>

                        {{-- Petunjuk --}}
                        <div class="p-4 bg-slate-50 dark:bg-[#0f0a19] rounded-xl border dark:border-white/5">
                            <h4 class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-2">
                                Petunjuk:
                            </h4>

                            <ol class="text-[9px] text-slate-400 list-decimal list-inside space-y-1">
                                <li>
                                    Buka
                                    <a href="https://fontawesome.com/icons"
                                        target="_blank"
                                        class="text-[#7C3AED] underline">
                                        fontawesome.com
                                    </a>
                                </li>
                                <li>Cari ikon dan filter bagian Free.</li>
                                <li>Salin nama ikon, contoh: <code>fa-book</code>.</li>
                                <li>Paste di kolom Nama Ikon.</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Tombol --}}
                <div class="flex gap-3 pt-6 border-t border-slate-100 dark:border-white/5 mt-6">
                    <a href="{{ route('admin.categories.index') }}"
                        class="flex-1 py-3 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 text-[10px] font-black text-center uppercase tracking-widest rounded-xl hover:bg-slate-200 dark:hover:bg-white/10 transition-all">
                        Batal
                    </a>

                    <button type="submit"
                        class="flex-1 py-3 bg-[#7C3AED] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all shadow-lg shadow-[#7C3AED]/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

@push('scripts')
<script>
    function updatePreview() {
        const iconInput = document.getElementById('iconInput');
        const colorInput = document.getElementById('colorInput');
        const preview = document.getElementById('iconPreview');

        let icon = iconInput.value.trim();
        let color = colorInput.value;

        if (icon === '') {
            icon = 'fa-book';
        }

        preview.className = 'fas ' + icon + ' text-3xl';
        preview.style.color = color;
    }

    function setIcon(iconName) {
        const iconInput = document.getElementById('iconInput');
        iconInput.value = iconName;
        updatePreview();
    }

    document.addEventListener('DOMContentLoaded', function() {
        updatePreview();
    });
</script>
@endpush

@endsection