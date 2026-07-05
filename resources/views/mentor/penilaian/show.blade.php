@extends('layouts.dashboard')

@section('title', 'CLEARN │ Detail Penilaian')

@section('content')

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}"
    active="penilaian" />

<main class="flex-1 p-5 lg:p-8">
    <div class="max-w-5xl mx-auto">

        <div class="mb-6">
            <a href="{{ route('mentor.penilaian.course', $result->enrollment->course_id) }}"
                class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-primary transition">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>
            <h1 class="text-lg font-bold dark:text-white text-slate-800 mt-3">Detail Tugas Akhir</h1>
            <p class="text-[11px] dark:text-slate-500 text-slate-400 font-medium">
                {{ $result->enrollment->student->name ?? '-' }} — {{ $result->enrollment->course->course_title }}
            </p>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-xs font-bold">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            {{-- Kiri: Info Proyek --}}
            <div class="md:col-span-2 space-y-6">
                <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl p-6">
                    <h2 class="text-xs font-black dark:text-white text-slate-800 uppercase mb-6 tracking-widest border-b dark:border-white/5 border-slate-200 pb-4">
                        Informasi Tugas Akhir
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Waktu Pengumpulan</p>
                                <p class="text-[11px] font-bold dark:text-white text-slate-800 mt-1">
                                    {{ $result->created_at ? $result->created_at->format('d M Y, H:i') : '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Status</p>
                                @if($result->final_project_score !== null)
                                <span class="inline-block mt-1 px-3 py-1 bg-emerald-500/10 text-emerald-500 font-bold rounded-full text-[9px] uppercase">
                                    Sudah Dinilai
                                </span>
                                @else
                                <span class="inline-block mt-1 px-3 py-1 bg-amber-500/10 text-amber-500 font-bold rounded-full text-[9px] uppercase">
                                    Menunggu
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Nama File</p>
                                <p class="text-[11px] font-bold dark:text-white text-slate-800 mt-1">{{ basename($result->submission_file ?? '-') }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Judul Tugas</p>
                                <p class="text-[11px] font-bold dark:text-white text-slate-800 mt-1">{{ $result->finalProject->project_title ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t dark:border-white/5 border-slate-200">
                        <a href="{{ $result->submission_file ? asset('storage/' . $result->submission_file) : '#' }}"
                            download
                            class="flex items-center justify-center gap-2 w-full py-3 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-white text-[10px] font-bold rounded-lg uppercase transition-all {{ !$result->submission_file ? 'opacity-50 pointer-events-none' : '' }}">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh File Proyek
                        </a>
                    </div>
                </div>
            </div>

            {{-- Kanan: Form Penilaian --}}
            <div class="md:col-span-1">
                <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl p-6">

                    <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 text-center">
                        {{ $result->final_project_score !== null ? 'Hasil Penilaian' : 'Aksi Pengajar' }}
                    </h2>

                    @if($result->final_project_score !== null)
                    {{-- Tampilkan Hasil --}}
                    <div class="text-center mb-6">
                        <div class="bg-slate-50 dark:bg-[#0b0a1a] p-4 rounded-xl border dark:border-white/5 border-slate-200">
                            <p class="text-[9px] text-slate-500 uppercase font-bold tracking-widest">Skor Akhir</p>
                            <p class="font-black text-3xl mt-1 {{ $result->final_project_score >= 70 ? 'text-emerald-500' : 'text-red-500' }}">
                                {{ $result->final_project_score }}/100
                            </p>
                        </div>
                    </div>

                    @if($result->mentor_notes)
                    <div class="mb-6">
                        <p class="text-[9px] text-slate-500 uppercase font-bold tracking-widest mb-2">Catatan Pengajar</p>
                        <div class="text-slate-600 dark:text-slate-300 text-[11px] italic leading-relaxed bg-slate-50 dark:bg-white/5 p-3 rounded-lg border dark:border-white/5 border-slate-200">
                            &ldquo;{{ $result->mentor_notes }}&rdquo;
                        </div>
                    </div>
                    @endif

                    <button onclick="toggleEdit()"
                        class="w-full py-3 bg-primary text-white text-[10px] font-black uppercase rounded-lg hover:opacity-90 transition">
                        Ubah Penilaian
                    </button>

                    <div id="edit-form" class="hidden mt-6 pt-6 border-t dark:border-white/5 border-slate-200">
                        <form action="{{ route('mentor.penilaian.grade', $result) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 block mb-2">Skor (0-100)</label>
                                <input type="number" name="final_project_score" value="{{ $result->final_project_score }}"
                                    min="0" max="100" required
                                    class="w-full bg-slate-50 dark:bg-[#0b0a1a] p-3 dark:text-white text-sm rounded-lg border dark:border-white/5 border-slate-200">
                            </div>
                            <div class="mb-4">
                                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 block mb-2">Catatan</label>
                                <textarea name="mentor_notes" rows="3" class="w-full bg-slate-50 dark:bg-[#0b0a1a] p-3 dark:text-white text-sm rounded-lg border dark:border-white/5 border-slate-200">{{ $result->mentor_notes }}</textarea>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" onclick="toggleEdit()"
                                    class="flex-1 py-3 text-slate-500 text-[10px] uppercase font-bold">Batal</button>
                                <button type="submit"
                                    class="flex-1 py-3 bg-primary text-white font-bold text-[10px] uppercase rounded-lg">Simpan</button>
                            </div>
                        </form>
                    </div>

                    @else
                    {{-- Form Penilaian --}}
                    <form action="{{ route('mentor.penilaian.grade', $result) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 block mb-2">Skor (0-100)</label>
                            <input type="number" name="final_project_score" placeholder="0-100"
                                min="0" max="100" required
                                class="w-full bg-slate-50 dark:bg-[#0b0a1a] p-3 dark:text-white text-sm rounded-lg border dark:border-white/5 border-slate-200">
                            @error('final_project_score')
                            <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-6">
                            <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 block mb-2">Catatan</label>
                            <textarea name="mentor_notes" rows="3" placeholder="Tulis catatan untuk siswa..."
                                class="w-full bg-slate-50 dark:bg-[#0b0a1a] p-3 dark:text-white text-sm rounded-lg border dark:border-white/5 border-slate-200"></textarea>
                            @error('mentor_notes')
                            <p class="text-[10px] text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                            class="w-full py-3 bg-primary text-white font-bold text-[10px] uppercase rounded-lg hover:opacity-90 transition">
                            Simpan Nilai
                        </button>
                    </form>
                    @endif

                </div>
            </div>

        </div>
    </div>
</main>

@push('scripts')
<script>
    function toggleEdit() {
        document.getElementById('edit-form').classList.toggle('hidden');
    }
</script>
@endpush
@endsection