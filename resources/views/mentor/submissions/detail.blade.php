@extends('layouts.dashboard')

@section('title', 'CLEARN │ Detail Penilaian')

@section('content')

<main class="flex-1 p-8" x-data="{ 
    openModal: false,
    skor: null,
    komentar: null,
    status: 'Menunggu',
    waktuPengumpulan: '25 Mei 2026, 10:20',
    namaSiswa: 'Alya Ghaitsa',
    fileProyek: 'project.zip',
    ukuranFile: '2.4 MB',
    
    simpanPenilaian(s, k) {
        this.skor = s;
        this.komentar = k;
        this.status = 'Dinilai'; 
        this.openModal = false;
    }
}">
    <div class="max-w-4xl mx-auto">
        
        {{-- Header --}}
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-white text-lg font-black uppercase tracking-widest">Detail Tugas Akhir</h1>
                <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1">Pelajar: Alya Ghaitsa</p>
            </div>
            <a href="{{ url('/mentor/final-projects/submissions') }}" class="text-[10px] text-slate-400 hover:text-white font-bold uppercase underline decoration-dotted transition-all">Kembali</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
           {{-- Info Proyek --}}
            <div class="md:col-span-2 space-y-6">
                {{-- Card Informasi Tugas --}}
                <div class="dark:bg-[#1A1625] p-6 rounded-2xl border border-white/5">
                    <h2 class="text-xs font-black text-white uppercase mb-6 tracking-widest border-b border-white/5 pb-4">Informasi Tugas Akhir</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Detail Kiri --}}
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Waktu Pengumpulan</p>
                                <p class="text-[11px] text-white font-bold mt-1" x-text="waktuPengumpulan"></p>
                            </div>
                            <div>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Status</p>
                                <span :class="status == 'Menunggu' ? 'bg-amber-500/10 text-amber-500' : 'bg-emerald-500/10 text-emerald-500'" 
                                    class="inline-block mt-1 px-3 py-1 rounded-full font-bold uppercase text-[9px]" 
                                    x-text="status"></span>
                            </div>
                        </div>

                        {{-- Detail Kanan --}}
                        <div class="space-y-4">
                            <div>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Nama File</p>
                                <p class="text-[11px] text-white font-bold mt-1">project.zip</p>
                            </div>
                            <div>
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Ukuran File</p>
                                <p class="text-[11px] text-white font-bold mt-1">2.4 MB</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Download --}}
                    <div class="mt-8 pt-6 border-t border-white/5">
                        <a href="{{ asset('storage/projects/project.zip') }}" 
                        download="Project_Ahmad_Fauzi.zip"
                        class="flex items-center justify-center gap-2 w-full py-3 bg-white/5 hover:bg-white/10 text-white text-[10px] font-bold rounded-lg uppercase transition-all border border-white/10 hover:border-white/20">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh File Proyek
                        </a>
                    </div>
                </div>

            </div>
        
            {{-- Card Aksi & Hasil Terintegrasi --}}
            <div class="md:col-span-1">
                <div class="dark:bg-[#1A1625] p-6 rounded-2xl border border-white/5 shadow-xl transition-all duration-500">
                    
                    {{-- Header & Tombol --}}
                    <div class="text-center">
                        <h2 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Aksi Mentor</h2>
                        
                        <button @click="openModal = true" 
                                class="w-full py-3 bg-primary text-white text-[10px] font-black uppercase rounded-lg hover:bg-[#8B6FE8] transition-all active:scale-[0.98]"
                                x-text="skor !== null ? 'Ubah Penilaian' : 'Berikan Penilaian'">
                        </button>
                    </div>

                    {{-- Hasil Penilaian --}}
                    <div x-show="skor !== null" 
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-4"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        x-cloak
                        class="mt-8 pt-8 border-t border-white/5 space-y-6">
                        
                        <div class="text-center">
                            <h2 class="text-[10px] font-black text-primary uppercase tracking-[0.2em]">Hasil Penilaian</h2>
                        </div>
                        
                        {{-- Statistik Skor --}}
                        <div class="bg-[#0b0a1a] p-4 rounded-xl border border-white/5">
                            <p class="text-[9px] text-slate-500 uppercase font-bold tracking-widest">Skor Akhir</p>
                            <p class="text-white font-black text-3xl mt-1" x-text="(skor ?? 0) + '/100'"></p>
                        </div>
                        
                        {{-- Komentar --}}
                        <div>
                            <p class="text-[9px] text-slate-500 uppercase font-bold tracking-widest mb-2">Catatan Pengajar</p>
                            <div class="text-slate-300 text-[11px] italic leading-relaxed bg-white/5 p-3 rounded-lg border border-white/5" x-text="'“' + komentar + '”'"></div>
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
        
        <div class="bg-[#161525] w-full max-w-sm rounded-2xl p-8 border border-white/10" 
            @click.away="openModal = false">
            
            <h2 class="text-white font-black uppercase text-sm mb-6">Form Penilaian</h2>
            
            <form @submit.prevent="simpanPenilaian($el.querySelector('input[type=number]').value, $el.querySelector('textarea').value)">
                <div class="mb-4">
                    <input type="number" required placeholder="Skor (0-100)" class="w-full bg-[#0b0a1a] p-3 text-white text-sm rounded-lg border border-white/10">
                </div>
                <div class="mb-6">
                    <textarea required placeholder="Tuliskan komentar..." class="w-full bg-[#0b0a1a] p-3 text-white text-sm rounded-lg border border-white/10" rows="3"></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="button" @click="openModal = false" class="flex-1 py-3 text-slate-500 text-[10px] uppercase">Batal</button>
                    <button type="submit" class="flex-1 py-3 bg-primary text-white font-bold text-[10px] uppercase rounded-lg">Simpan Nilai</button>
                </div>
            </form>
        </div>
    </div>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</main>
@endsection