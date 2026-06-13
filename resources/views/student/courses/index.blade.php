@extends('layouts.dashboard')

@section('content')

<!-- Component Sidebar -->
<x-dashboard.sidebar role="student" name="User Pelajar" active="dashboard" />

<!-- Content -->
<main class="flex-1 p-6 lg:p-10 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50">
    <div class="max-w-5xl mx-auto">

        {{-- Header Halaman --}}
        <div class="mb-6">
            <h1 class="text-xl font-bold dark:text-white text-slate-800">Pembelajaran Saya</h1>
            <p class="text-[11px] dark:text-slate-500 text-slate-400">Pantau kemajuan Anda dan lanjutkan perjalanan pembelajaran Anda.</p>
        </div>

        {{-- Ringkasan Statistik (4 Kolom) dengan bg-[#161525] --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <!-- Statistik 1: Kursus -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-500 text-sm">
                    <i class="fas fa-play"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">2</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">Kursus</span>
                </div>
            </div>

            <!-- Statistik 2: Belajar -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500 text-sm">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">1</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">Belajar</span>
                </div>
            </div>

            <!-- Statistik 3: Selesai -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center text-green-500 text-sm">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">1</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">Selesai</span>
                </div>
            </div>

            <!-- Statistik 4: Sertifikat -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-purple-500/10 flex items-center justify-center text-purple-500 text-sm">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">1</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">Sertifikat</span>
                </div>
            </div>
        </div>

        {{-- Filter Tab Konten: Meniru Gaya & Kelengkungan Tab di Halaman Setting --}}
        <div class="flex flex-wrap gap-2 mb-6">
            <button class="px-6 py-2 rounded-xl text-[11px] font-bold transition-all bg-primary text-white shadow-sm border border-primary active:scale-95">
                Semua Kursus
            </button>
            <button class="px-6 py-2 rounded-xl text-[11px] font-bold transition-all dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 dark:text-slate-400 text-slate-500 hover:border-primary active:scale-95">
                Sedang Berjalan
            </button>
            <button class="px-6 py-2 rounded-xl text-[11px] font-bold transition-all dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 dark:text-slate-400 text-slate-500 hover:border-primary active:scale-95">
                Selesai
            </button>
        </div>

        {{-- Grid Course List: 1 Baris 3 Kolom --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

            {{-- Kursus 1: Program Pelatihan Lengkap --}}
            <div class="dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden flex flex-col group shadow-sm">
                <div class="relative aspect-[16/10] overflow-hidden bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=400&auto=format&fit=crop" alt="Web Dev" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-3 right-3 bg-primary text-white text-[8px] font-black uppercase tracking-wider px-2 py-1 rounded-md">DIBELI</span>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div class="mb-4">
                        <h3 class="text-xs font-bold leading-tight tracking-tight dark:text-white text-slate-800 mb-1 line-clamp-2">Program Pelatihan Lengkap Pengembangan Web</h3>
                        <p class="text-[10px] dark:text-slate-500 text-slate-400 font-medium">Tim Mentor Clearn</p>
                    </div>

                    <div class="space-y-3">
                        <div class="space-y-1">
                            <div class="flex justify-between text-[9px] font-bold uppercase tracking-wider dark:text-slate-500 text-slate-400">
                                <span>Progress Belajar</span>
                                <span class="text-primary">35%</span>
                            </div>
                            <div class="w-full h-1.5 dark:bg-[#0b0a1a] bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-primary rounded-full" style="width: 35%"></div>
                            </div>
                        </div>

                        <button class="w-full bg-primary text-white text-[10px] font-bold py-2.5 rounded-xl shadow-lg shadow-primary/20 hover:brightness-110 transition-all uppercase tracking-widest active:scale-95">
                            Lanjutkan Kursus
                        </button>
                    </div>
                </div>
            </div>

            {{-- Kursus 2: UI/UX Design --}}
            <div class="dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden flex flex-col group shadow-sm">
                <div class="relative aspect-[16/10] overflow-hidden bg-slate-200">
                    <img src="https://images.unsplash.com/photo-1586717791821-3f44a563fa4c?q=80&w=600" alt="UI/UX" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <span class="absolute top-3 right-3 bg-emerald-500 text-white text-[8px] font-black uppercase tracking-wider px-2 py-1 rounded-md">LULUS</span>
                </div>

                <div class="p-5 flex-1 flex flex-col justify-between">
                    <div class="mb-4">
                        <h3 class="text-xs font-bold leading-tight tracking-tight dark:text-white text-slate-800 mb-1 line-clamp-2">Dasar UI/UX Design untuk Pemula</h3>
                        <p class="text-[10px] dark:text-slate-500 text-slate-400 font-medium">Mentor Desain Clearn</p>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-center gap-2 py-1.5 rounded-xl border dark:border-emerald-500/10 border-emerald-100 bg-emerald-500/5 text-emerald-500 text-[9px] font-bold uppercase tracking-wider">
                            <i class="fas fa-shield-alt"></i> Sertifikat Tersedia
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            {{-- Tombol Klaim Sertifikat --}}
                            <button class="w-full bg-emerald-500 text-white text-[10px] font-bold py-2.5 rounded-xl shadow-lg shadow-emerald-500/10 hover:brightness-110 transition-all uppercase tracking-widest active:scale-95">
                                Klaim Sertifikat
                            </button>

                            {{-- Tombol Beri Nilai (Dibuat Hijau senada) --}}
                            <button onclick="showReviewModal()" class="w-full bg-emerald-500 text-white text-[10px] font-bold py-2.5 rounded-xl shadow-lg shadow-emerald-500/10 hover:brightness-110 transition-all uppercase tracking-widest active:scale-95">
                                Beri Nilai
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Slot Kosong / Tambah Kursus Baru --}}
            <div class="dark:bg-[#161525]/40 bg-slate-100/50 border-2 border-dashed dark:border-white/5 border-slate-200 rounded-2xl flex flex-col items-center justify-center p-6 text-center group min-h-[330px]">
                <div class="w-12 h-12 rounded-xl dark:bg-[#161525] bg-slate-200/50 flex items-center justify-center text-slate-400 mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-plus text-sm"></i>
                </div>
                <p class="text-xs font-bold dark:text-slate-400 text-slate-600 mb-1">Ingin Belajar Hal Baru?</p>
                <p class="text-[10px] dark:text-slate-500 text-slate-400 max-w-[180px] mb-4">Temukan ratusan materi berkualitas lainnya.</p>
                <button class="px-5 py-2 border dark:border-white/5 border-slate-300 dark:text-white text-slate-700 text-[10px] font-bold rounded-xl uppercase tracking-widest hover:bg-white dark:hover:bg-[#161525] transition-all">
                    Jelajahi Kursus
                </button>
            </div>

        </div>

    </div>
</main>

@push('library')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('scripts')
<script>
    function showReviewModal() {
        Swal.fire({
            title: 'Beri Penilaian',
            html: `
            <div class="flex flex-col gap-4 text-left">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Rating</label>
                    <div class="flex justify-center gap-2 my-2 text-2xl text-amber-400" id="star-rating">
                        <i class="fas fa-star cursor-pointer" onclick="setRating(1)"></i>
                        <i class="fas fa-star cursor-pointer" onclick="setRating(2)"></i>
                        <i class="fas fa-star cursor-pointer" onclick="setRating(3)"></i>
                        <i class="fas fa-star cursor-pointer" onclick="setRating(4)"></i>
                        <i class="fas fa-star cursor-pointer" onclick="setRating(5)"></i>
                    </div>
                </div>
                <textarea id="review-comment" class="w-full p-3 bg-slate-50 dark:bg-[#0b0a1a] border dark:border-white/10 rounded-xl text-xs" placeholder="Ceritakan pengalaman Anda..."></textarea>
            </div>
        `,
            confirmButtonText: 'Kirim Ulasan',
            confirmButtonColor: '#7C3AED',
            background: document.documentElement.classList.contains('dark') ? '#161525' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            preConfirm: () => {
                const comment = document.getElementById('review-comment').value;
                return {
                    comment: comment
                };
            }
        });
    }

    // Fungsi sederhana untuk interaksi bintang (opsional)
    function setRating(rating) {
        const stars = document.querySelectorAll('#star-rating i');
        stars.forEach((star, index) => {
            star.style.color = index < rating ? '#fbbf24' : '#d1d5db';
        });
    }
</script>
@endpush

@endsection