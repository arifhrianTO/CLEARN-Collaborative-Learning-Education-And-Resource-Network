@extends('layouts.dashboard')

@section('title', 'Tambah Final Project | Dashboard Mentor | Clearn')

@section('content')

<main class="flex-1 p-5 lg:p-8 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50 min-h-screen">

    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('mentor.courses.sessions.edit', $session->course_id) }}"
                class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-primary transition">

                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Kurikulum
            </a>

            <div class="mt-5">
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-emerald-500">
                    Session Terakhir
                </p>

                <h1 class="text-2xl font-extrabold dark:text-white text-slate-800 tracking-tight mt-2">
                    Tambah Final Project
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
                <p class="font-black uppercase tracking-widest text-[10px]">
                    Periksa kembali data berikut
                </p>
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

                {{-- Informasi session --}}
                <div class="p-6 lg:p-8 border-b dark:border-white/5 border-slate-200">

                    <div class="flex items-start gap-4">

                        <div class="w-11 h-11 rounded-2xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center shrink-0">
                            <i class="fa-solid fa-graduation-cap"></i>
                        </div>

                        <div>
                            <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">
                                Final Project Session
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

                            Judul Final Project
                            <span class="text-red-500">*</span>
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

                            Panduan dan Deskripsi Tugas
                            <span class="text-red-500">*</span>
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

                    {{-- Waktu --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label for="start_date"
                                class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">

                                Tanggal Mulai
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="datetime-local"
                                id="start_date"
                                name="start_date"
                                value="{{ old('start_date') }}"
                                required
                                class="w-full px-4 py-3.5 rounded-xl dark:bg-[#0f0a19] bg-slate-50 border dark:border-white/10 border-slate-200 dark:text-white text-slate-800 text-xs font-bold outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                        </div>

                        <div>
                            <label for="due_date"
                                class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">

                                Batas Pengumpulan
                                <span class="text-red-500">*</span>
                            </label>

                            <input type="datetime-local"
                                id="due_date"
                                name="due_date"
                                value="{{ old('due_date') }}"
                                required
                                class="w-full px-4 py-3.5 rounded-xl dark:bg-[#0f0a19] bg-slate-50 border dark:border-white/10 border-slate-200 dark:text-white text-slate-800 text-xs font-bold outline-none focus:border-primary focus:ring-4 focus:ring-primary/10 transition">
                        </div>

                    </div>

                    {{-- Material tambahan --}}
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

                                <option value="pdf"
                                    {{ old('material_type') === 'pdf' ? 'selected' : '' }}>
                                    Upload File
                                </option>

                                <option value="link"
                                    {{ old('material_type') === 'link' ? 'selected' : '' }}>
                                    Link Eksternal
                                </option>

                            </select>
                        </div>

                        {{-- Upload file --}}
                        <div id="material_file_wrapper"
                            class="mt-5 hidden">

                            <label for="material_file"
                                class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                                File Panduan
                            </label>

                            <label for="material_file"
                                class="flex flex-col items-center justify-center min-h-40 rounded-2xl border-2 border-dashed dark:border-white/10 border-slate-300 dark:bg-[#0f0a19] bg-slate-50 cursor-pointer hover:border-primary hover:bg-primary/5 transition">

                                <div class="w-11 h-11 rounded-2xl bg-primary/10 text-primary flex items-center justify-center mb-3">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>

                                <p class="text-xs font-bold dark:text-slate-300 text-slate-600">
                                    Klik untuk memilih file
                                </p>

                                <p class="text-[9px] dark:text-slate-500 text-slate-400 mt-1 uppercase tracking-widest">
                                    PDF, DOC, DOCX, ZIP, RAR • Maksimal 10 MB
                                </p>

                                <input type="file"
                                    id="material_file"
                                    name="material_file"
                                    accept=".pdf,.doc,.docx,.zip,.rar"
                                    class="hidden">
                            </label>

                            <p id="selected_file_name"
                                class="text-[10px] font-bold text-primary mt-2">
                            </p>

                        </div>

                        {{-- Link --}}
                        <div id="material_url_wrapper"
                            class="mt-5 hidden">

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
                        Simpan Final Project
                    </button>

                </div>

            </div>

        </form>

    </div>

</main>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const materialType = document.getElementById('material_type');
        const fileWrapper = document.getElementById('material_file_wrapper');
        const urlWrapper = document.getElementById('material_url_wrapper');

        const materialFile = document.getElementById('material_file');
        const selectedFileName = document.getElementById('selected_file_name');

        function toggleMaterialInput() {
            const selectedType = materialType.value;

            fileWrapper.classList.add('hidden');
            urlWrapper.classList.add('hidden');

            if (selectedType === 'pdf') {
                fileWrapper.classList.remove('hidden');
            }

            if (selectedType === 'link') {
                urlWrapper.classList.remove('hidden');
            }
        }

        materialType.addEventListener('change', toggleMaterialInput);

        materialFile.addEventListener('change', function() {
            if (this.files.length > 0) {
                selectedFileName.textContent =
                    'File dipilih: ' + this.files[0].name;
            } else {
                selectedFileName.textContent = '';
            }
        });

        toggleMaterialInput();
    });
</script>
@endpush

@endsection