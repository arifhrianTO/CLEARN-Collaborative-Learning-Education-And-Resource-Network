@extends('layouts.dashboard')

@section('title', 'CLEARN ", Detail Penilaian')

@section('content')

<main class="flex-1 p-8" x-data="{ 
    openModal: false,
    skor: {{ $result->final_project_score !== null ? $result->final_project_score : 'null' }},
    komentar: {{ $result->mentor_notes !== null ? json_encode($result->mentor_notes) : 'null' }},
    status: '{{ $result->final_project_score !== null ? ($result->final_project_score >= 70 ? 'Lulus' : 'Tidak Lulus') : 'Menunggu' }}',
    waktuPengumpulan: '{{ $result->started_at ? $result->started_at->translatedFormat('d M Y, H:i') : '-' }}',
    namaSiswa: '{{ $result->enrollment->student->name }}',
    fileProyek: '{{ basename($result->submission_file) }}',
    
    simpanPenilaian(s, k) {
        // Will submit natively using the form action instead of doing this client-side 
        // to ensure backend validation and persistence.
    }
}">
    <div class="max-w-4xl mx-auto">
        
        {{-- Header --}}
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-white text-lg font-black uppercase tracking-widest text-slate-800 dark:text-white">Detail Tugas Akhir</h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Pelajar: <span x-text="namaSiswa"></span></p>
            </div>
            <a href="{{ route('mentor.projects.submissions', $result->finalProject->id) }}" class="text-[10px] text-slate-400 hover:text-primary dark:hover:text-white font-bold uppercase underline decoration-dotted transition-all">Kembali</a>
        </div>
        
        {{-- Alert --}}
        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 dark:text-emerald-400 text-xs font-bold flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
        @endif
        
        @if ($errors->any())
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-5 py-4 rounded-2xl text-xs font-semibold">
            <div class="flex items-center gap-2 mb-2">
                <i class="fa-solid fa-circle-exclamation"></i>
                <p class="font-black uppercase tracking-widest text-[10px]">Gagal Menyimpan Penilaian</p>
            </div>
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
           {{-- Info Proyek --}}
            <div class="md:col-span-2 space-y-6">
                {{-- Card Informasi Tugas --}}
                <div class="dark:bg-[#1A1625] bg-white p-6 rounded-2xl border dark:border-white/5 border-slate-200">
                    <h2 class="text-xs font-black dark:text-white text-slate-800 uppercase mb-6 tracking-widest border-b dark:border-white/5 border-slate-200 pb-4">Informasi Tugas Akhir</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Detail Kiri --}}
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Waktu Pengumpulan</p>
                                <p class="text-[11px] dark:text-white text-slate-800 font-bold mt-1" x-text="waktuPengumpulan"></p>
                            </div>
                            <div>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Status</p>
                                <span :class="status == 'Menunggu' ? 'bg-amber-500/10 text-amber-500' : (status == 'Tidak Lulus' ? 'bg-red-500/10 text-red-500' : 'bg-emerald-500/10 text-emerald-500')" 
                                    class="inline-block mt-1 px-3 py-1 rounded-full font-bold uppercase text-[9px]" 
                                    x-text="status"></span>
                            </div>
                        </div>

                        {{-- Detail Kanan --}}
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Nama File</p>
                                <p class="text-[11px] dark:text-white text-slate-800 font-bold mt-1 truncate max-w-[200px]" title="{{ basename($result->submission_file) }}" x-text="fileProyek"></p>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Download --}}
                    <div class="mt-8 pt-6 border-t dark:border-white/5 border-slate-200">
                        <a href="{{ Storage::url($result->submission_file) }}" 
                        download
                        class="flex items-center justify-center gap-2 w-full py-3 dark:bg-white/5 bg-slate-100 hover:bg-slate-200 dark:hover:bg-white/10 dark:text-white text-slate-700 text-[10px] font-bold rounded-lg uppercase transition-all border dark:border-white/10 border-slate-200 dark:hover:border-white/20">
                            <i class="fa-solid fa-download"></i>
                            Unduh File Proyek
                        </a>
                    </div>
                </div>

            </div>
        
            {{-- Card Aksi & Hasil Terintegrasi --}}
            <div class="md:col-span-1">
                <div class="dark:bg-[#1A1625] bg-white p-6 rounded-2xl border dark:border-white/5 border-slate-200 shadow-sm transition-all duration-500">
                    
                    {{-- Header & Tombol --}}
                    <div class="text-center">
                        <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Aksi Pengajar</h2>
                        
                        <button @click="openModal = true" 
                                class="w-full py-3 bg-primary text-white text-[10px] font-black uppercase rounded-lg hover:bg-primary/90 transition-all active:scale-[0.98]"
                                x-text="skor !== null ? 'Ubah Penilaian' : 'Berikan Penilaian'">
                        </button>
                    </div>

                    {{-- Hasil Penilaian --}}
                    <div x-show="skor !== null" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-cloak
                        class="mt-8 pt-8 border-t dark:border-white/5 border-slate-200 space-y-6">
                        
                        <div class="text-center">
                            <h2 class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">Hasil Penilaian</h2>
                        </div>
                        
                        {{-- Statistik Skor --}}
                        <div class="dark:bg-[#0b0a1a] bg-slate-50 p-4 rounded-xl border dark:border-white/5 border-slate-200 text-center">
                            <p class="text-[9px] text-slate-500 uppercase font-bold tracking-widest">Skor Akhir</p>
                            <p class="dark:text-white text-slate-800 font-black text-3xl mt-1" x-text="(skor ?? 0) + '/100'"></p>
                        </div>
                        
                        {{-- Komentar --}}
                        <div x-show="komentar">
                            <p class="text-[9px] text-slate-500 uppercase font-bold tracking-widest mb-2">Catatan Pengajar</p>
                            <div class="dark:text-slate-300 text-slate-600 text-[11px] italic leading-relaxed dark:bg-white/5 bg-slate-50 p-3 rounded-lg border dark:border-white/5 border-slate-200" x-text="'o' + komentar + '?'"></div>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
    </div>

    {{-- Modal Penilaian --}}
    <div x-show="openModal" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm"
        @click.self="openModal = false"> 
        
        <div class="dark:bg-[#161525] bg-white w-full max-w-sm rounded-2xl p-8 border dark:border-white/10 border-slate-200 shadow-2xl" 
            @click.away="openModal = false">
            
            <h2 class="dark:text-white text-slate-800 font-black uppercase text-sm mb-6 flex items-center gap-2">
                <i class="fa-solid fa-clipboard-check text-primary"></i> 
                Form Penilaian
            </h2>
            
            <form action="{{ route('mentor.projects.submission.grade', $result->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">Skor Tugas</label>
                    <input type="number" name="skor" required min="0" max="100" 
                           :value="skor"
                           placeholder="Skor (0-100)" 
                           class="w-full dark:bg-[#0b0a1a] bg-slate-50 p-3 dark:text-white text-slate-800 text-sm font-bold rounded-lg border dark:border-white/10 border-slate-200 outline-none focus:border-primary transition-all">
                    <p class="text-[9px] text-slate-500 mt-1">Standar kelulusan: 70</p>
                </div>
                <div class="mb-6">
                    <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">Catatan Mentor (Opsional)</label>
                    <textarea name="komentar" placeholder="Tuliskan feedback untuk student..." 
                              class="w-full dark:bg-[#0b0a1a] bg-slate-50 p-3 dark:text-white text-slate-800 text-sm rounded-lg border dark:border-white/10 border-slate-200 outline-none focus:border-primary transition-all" rows="3"
                              x-text="komentar"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="openModal = false" class="flex-1 py-3 text-slate-500 hover:text-slate-700 dark:hover:text-white font-bold text-[10px] uppercase transition-all bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 rounded-lg">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-primary hover:bg-primary/90 text-white font-bold text-[10px] uppercase rounded-lg transition-all shadow-lg shadow-primary/20">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</main>
@endsection