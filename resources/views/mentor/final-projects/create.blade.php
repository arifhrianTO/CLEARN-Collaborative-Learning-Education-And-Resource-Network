@extends('layouts.dashboard')

@section('title', 'Tambah Tugas Akhir | Dashboard Mentor | Clearn')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/dark.css">
<style>
    
</style>
@endpush

@section('content')

<main class="flex-1 p-5 lg:p-8 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50 min-h-screen">

    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <button type="button"
    onclick="window.location='{{ route('mentor.courses.sessions.edit', $session->course_id) }}'"
    class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white text-sm font-semibold shadow-md hover:bg-primary/90 transition">
    <i class="fa-solid fa-arrow-left"></i>
    Kembali ke Kurikulum
</button>

            <div class="mt-5">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-500">
                    Pertemuan Terakhir
                </p>
                <h1 class="text-2xl font-extrabold dark:text-white text-slate-800 tracking-tight mt-2">
                    Tambah Tugas Akhir
                </h1>
                <p class="text-[11px] dark:text-slate-400 text-slate-500 mt-2">
                    Buat tugas akhir yang akan dikerjakan oleh student setelah menyelesaikan seluruh materi kursus.
                </p>
            </div>
        </div>

        {{-- Error --}}
        @if ($errors->any())
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-5 py-4 rounded-2xl text-xs font-semibold">
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                <p class="font-black uppercase tracking-widest text-[10px]">Periksa kembali data berikut</p>
            </div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('mentor.sessions.projects.store', $session->id) }}"
            method="POST"
            enctype="multipart/form-data">

            @csrf

            <div class="dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-3xl shadow-sm overflow-hidden">

                {{-- Info Session --}}
                <div class="p-6 lg:p-8 border-b dark:border-white/5 border-slate-200">
                    <div class="flex items-start gap-4">
                        <div class="w-11 h-11 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">
                                Tugas Akhir
                            </p>
                            <h2 class="text-sm font-black dark:text-white text-slate-800 mt-1">
                                {{ $session->sessions_title }}
                            </h2>
                            <p class="text-[11px] dark:text-slate-500 text-slate-400 mt-1">
                                {{ $session->course->course_title }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="p-6 lg:p-8 space-y-7">

                    {{-- Judul --}}
                    <div>
                        <label for="project_title"
                            class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Judul Tugas Akhir <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                            id="project_title"
                            name="project_title"
                            value="{{ old('project_title') }}"
                            required
                            placeholder="Contoh: Membangun Website Portfolio Responsif"
                            class="w-full px-4 py-3.5 rounded-xl dark:bg-[#0f0a19] bg-slate-50 border dark:border-white/10 border-slate-200 dark:text-white text-slate-800 text-sm font-bold outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                    </div>

                    {{-- Deskripsi --}}
                    <div>
                        <label for="project_description"
                            class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Panduan dan Deskripsi Tugas <span class="text-red-500">*</span>
                        </label>
                        <textarea id="project_description"
                            name="project_description"
                            rows="10"
                            required
                            placeholder="Tuliskan panduan tugas, ketentuan, struktur folder, teknologi yang digunakan, dan hasil yang harus dikumpulkan..."
                            class="w-full px-4 py-3.5 rounded-xl dark:bg-[#0f0a19] bg-slate-50 border dark:border-white/10 border-slate-200 dark:text-white text-slate-800 text-xs leading-relaxed outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">{{ old('project_description') }}</textarea>
                        <p class="text-[10px] dark:text-slate-500 text-slate-400 mt-2">
                            Tuliskan instruksi selengkap mungkin agar student memahami hasil akhir yang harus dibuat.
                        </p>
                    </div>

                    {{-- Durasi Pengerjaan + Format File (sejajar) --}}
                    <div class="pt-6 border-t dark:border-white/5 border-slate-200">
                        <div class="mb-5">
                            <h3 class="text-xs font-black dark:text-white text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                <i class="fa-solid fa-sliders text-primary"></i>
                                Aturan Pengumpulan Student
                            </h3>
                            <p class="text-[10px] dark:text-slate-500 text-slate-400 mt-1">
                                Tentukan durasi dan format file yang boleh diunggah oleh student. Ukuran maksimal ditentukan otomatis oleh sistem.
                            </p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start">

                            {{-- Durasi Pengerjaan --}}
                            <div>
                                <label for="duration_days"
                                    class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                                    Durasi Pengerjaan <span class="text-red-500">*</span>
                                </label>

                                <div class="duration-stepper flex items-center gap-2">
                                    <button type="button" id="btn-decrease"
                                        class="w-11 h-11 rounded-xl dark:bg-white/5 bg-slate-100 border dark:border-white/10 border-slate-200 dark:text-white text-slate-700 flex items-center justify-center hover:bg-primary/10 hover:border-primary hover:text-primary transition font-bold shrink-0">
                                        <i class="fa-solid fa-minus text-xs"></i>
                                    </button>

                                    <div class="relative flex-1">
                                        <input type="number"
                                            id="duration_days"
                                            name="duration_days"
                                            value="{{ old('duration_days', 7) }}"
                                            min="1"
                                            max="365"
                                            required
                                            class="w-full text-center px-4 py-3.5 rounded-xl dark:bg-[#0f0a19] bg-slate-50 border dark:border-white/10 border-slate-200 dark:text-white text-slate-800 text-sm font-black outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                                        <span class="absolute inset-y-0 right-4 flex items-center text-[10px] font-black uppercase tracking-widest dark:text-slate-500 text-slate-400 pointer-events-none">
                                            Hari
                                        </span>
                                    </div>

                                    <button type="button" id="btn-increase"
                                        class="w-11 h-11 rounded-xl dark:bg-white/5 bg-slate-100 border dark:border-white/10 border-slate-200 dark:text-white text-slate-700 flex items-center justify-center hover:bg-primary/10 hover:border-primary hover:text-primary transition font-bold shrink-0">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>

                                {{-- Preset --}}
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @foreach ([3 => '3 Hari', 7 => '1 Minggu', 14 => '2 Minggu', 30 => '1 Bulan'] as $val => $label)
                                    <button type="button"
                                        data-days="{{ $val }}"
                                        class="duration-preset px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest dark:bg-white/5 bg-slate-100 dark:text-slate-400 text-slate-500 dark:border-white/10 border border-slate-200 hover:border-primary hover:text-primary hover:bg-primary/5 transition">
                                        {{ $label }}
                                    </button>
                                    @endforeach
                                </div>

                                <p class="text-[10px] dark:text-slate-500 text-slate-400 mt-2">
                                    Dihitung sejak kursus selesai.
                                </p>
                            </div>

                            {{-- Format File --}}
                            <div>
                                <label for="allowed_extensions"
                                    class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                                    Format File Diizinkan <span class="text-red-500">*</span>
                                </label>
                                <select id="allowed_extensions"
                                    name="allowed_extensions"
                                    required
                                    class="w-full px-4 py-3.5 rounded-xl dark:bg-[#0f0a19] bg-slate-50 border dark:border-white/10 border-slate-200 dark:text-white text-slate-800 text-xs font-bold outline-none focus:border-primary transition cursor-pointer">
                                    <option value=".zip,.rar" {{ old('allowed_extensions') === '.zip,.rar' ? 'selected' : '' }}>
                                        File Kompresi (ZIP, RAR)
                                    </option>
                                    <option value=".pdf" {{ old('allowed_extensions') === '.pdf' ? 'selected' : '' }}>
                                        Dokumen (Hanya PDF)
                                    </option>
                                    <option value=".zip,.rar,.pdf" {{ old('allowed_extensions') === '.zip,.rar,.pdf' ? 'selected' : '' }}>
                                        Campuran (ZIP, RAR, PDF)
                                    </option>
                                </select>
                                <p class="text-[10px] dark:text-slate-500 text-slate-400 mt-2">
                                    Ukuran maksimal ditentukan otomatis oleh sistem.
                                </p>
                            </div>

                        </div>
                    </div>

                    {{-- Material Pendukung --}}
                    <div class="pt-6 border-t dark:border-white/5 border-slate-200">

                        <div class="mb-4">
                            <h3 class="text-xs font-black dark:text-white text-slate-800 uppercase tracking-widest">
                                Material Pendukung
                            </h3>
                            <p class="text-[10px] dark:text-slate-500 text-slate-400 mt-1">
                                Material tambahan bersifat opsional.
                            </p>
                        </div>

                        <div>
                            <label for="material_type"
                                class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                                Jenis Material
                            </label>
                            <select id="material_type"
                                name="material_type"
                                class="w-full px-4 py-3.5 rounded-xl dark:bg-[#0f0a19] bg-slate-50 border dark:border-white/10 border-slate-200 dark:text-white text-slate-800 text-xs font-bold outline-none focus:border-primary transition">
                                <option value="">Tanpa material tambahan</option>
                                <option value="pdf" {{ old('material_type') === 'pdf' ? 'selected' : '' }}>Unggah File </option>
                                <option value="link" {{ old('material_type') === 'link' ? 'selected' : '' }}>Tautan Eksternal</option>
                            </select>
                        </div>

                        {{-- Upload file --}}
                        <div id="material_file_wrapper" class="mt-5 hidden">
                            <label for="material_file"
                                class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                                File Panduan
                            </label>
                            <label for="material_file"
                                class="flex flex-col items-center justify-center min-h-40 rounded-2xl border-2 border-dashed dark:border-white/10 border-slate-300 dark:bg-[#0f0a19] bg-slate-50 cursor-pointer hover:border-primary hover:bg-primary/5 transition">
                                <div class="w-11 h-11 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <p class="text-xs font-bold dark:text-slate-300 text-slate-600">Klik untuk memilih file</p>
                                <p class="text-[9px] dark:text-slate-500 text-slate-400 mt-1 uppercase tracking-widest">
                                    PDF, DOC, DOCX, ZIP, RAR • Maksimal 10 MB
                                </p>
                                <input type="file"
                                    id="material_file"
                                    name="material_file"
                                    accept=".pdf,.doc,.docx,.zip,.rar"
                                    class="hidden">
                            </label>
                            <p id="selected_file_name" class="text-[10px] font-bold text-primary mt-2"></p>
                        </div>

                        {{-- Link --}}
                        <div id="material_url_wrapper" class="mt-5 hidden">
                            <label for="material_url"
                                class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                                Link Panduan
                            </label>
                            <input type="url"
                                id="material_url"
                                name="material_url"
                                value="{{ old('material_url') }}"
                                placeholder="https://drive.google.com/..."
                                class="w-full px-4 py-3.5 rounded-xl dark:bg-[#0f0a19] bg-slate-50 border dark:border-white/10 border-slate-200 dark:text-white text-slate-800 text-xs outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                        </div>

                    </div>

                </div>

                {{-- Action --}}
                <div class="px-6 lg:px-8 py-5 dark:bg-[#0f0a19]/50 bg-slate-50 border-t dark:border-white/5 border-slate-200 flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <a href="{{ route('mentor.courses.sessions.edit', $session->course_id) }}"
                        class="px-7 py-3 rounded-xl bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest text-center hover:bg-slate-300 dark:hover:bg-white/10 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-8 py-3 rounded-xl bg-emerald-500 text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-emerald-500/20 hover:brightness-110 transition">
                        <i class="fa-solid fa-floppy-disk mr-2"></i>
                        Simpan Tugas Akhir
                    </button>
                </div>

            </div>

        </form>

    </div>

</main>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- Duration Stepper ---
        const durationInput = document.getElementById('duration_days');
        const btnDecrease = document.getElementById('btn-decrease');
        const btnIncrease = document.getElementById('btn-increase');
        const presets = document.querySelectorAll('.duration-preset');

        function setDuration(val) {
            const min = parseInt(durationInput.min) || 1;
            const max = parseInt(durationInput.max) || 365;
            durationInput.value = Math.min(max, Math.max(min, parseInt(val) || min));
            highlightActivePreset();
        }

        function highlightActivePreset() {
            const current = parseInt(durationInput.value);
            presets.forEach(btn => {
                const isActive = parseInt(btn.dataset.days) === current;
                btn.classList.toggle('border-primary', isActive);
                btn.classList.toggle('text-primary', isActive);
                btn.classList.toggle('bg-primary/5', isActive);
            });
        }

        btnDecrease.addEventListener('click', () => setDuration(parseInt(durationInput.value) - 1));
        btnIncrease.addEventListener('click', () => setDuration(parseInt(durationInput.value) + 1));
        presets.forEach(btn => btn.addEventListener('click', () => setDuration(btn.dataset.days)));
        durationInput.addEventListener('input', highlightActivePreset);
        highlightActivePreset();

        // --- Material Pendukung ---
        const materialType = document.getElementById('material_type');
        const fileWrapper = document.getElementById('material_file_wrapper');
        const urlWrapper = document.getElementById('material_url_wrapper');
        const materialFile = document.getElementById('material_file');
        const selectedFileName = document.getElementById('selected_file_name');

        function toggleMaterialInput() {
            const val = materialType.value;
            fileWrapper.classList.add('hidden');
            urlWrapper.classList.add('hidden');
            if (val === 'pdf') fileWrapper.classList.remove('hidden');
            if (val === 'link') urlWrapper.classList.remove('hidden');
        }

        materialType.addEventListener('change', toggleMaterialInput);

        materialFile.addEventListener('change', function() {
            selectedFileName.textContent = this.files.length > 0 ?
                'File dipilih: ' + this.files[0].name :
                '';
        });

        toggleMaterialInput();

    });
</script>
@endpush