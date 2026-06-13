<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kursus - Clearn</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style type="text/tailwindcss">
        @layer base {
            body { 
                @apply bg-slate-50 text-slate-900 transition-colors duration-300 antialiased font-sans;
                letter-spacing: -0.025em;
            }
            .dark body { @apply bg-[#090613] text-white; }
        }

        @layer components {
            .card-bg { @apply bg-white border border-gray-200 rounded-2xl transition-all duration-300 shadow-sm; }
            .dark .card-bg { @apply bg-[#110D1F] border-[#2d2644] shadow-none; }
            
            .text-muted-custom { @apply text-slate-500 font-medium; }
            .dark .text-muted-custom { @apply text-slate-400; }

            .inner-item { @apply bg-slate-50 border border-gray-100 rounded-xl transition-all; }
            .dark .inner-item { @apply bg-[#161226] border-[#2d2644]; }
        }
    </style>
</head>
<body class="min-h-screen p-6 lg:p-10">

    <a href="javascript:history.back()" class="fixed top-8 left-8 z-50 w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-[#110D1F] border border-gray-200 dark:border-[#2d2644] hover:scale-110 transition-all shadow-sm group">
        <i class="fas fa-arrow-left text-primary group-hover:-translate-x-1 transition-transform"></i>
    </a>

    <div class="max-w-5xl mx-auto">

        <div class="mb-8 mt-12">
            <h1 class="text-2xl font-black tracking-tighter flex items-center gap-3">
                <i class="fas fa-shopping-cart text-primary"></i> Ikuti Kursus Ini
            </h1>
            <p class="text-muted-custom text-[12px] mt-1">Mulai perjalanan Anda menjadi Web Developer profesional bersama tim mentor kami.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-5">
                <div class="card-bg p-6 md:p-8 flex flex-col md:flex-row gap-8 items-start">
                    <div class="flex-1">
                        <div class="inline-flex items-center gap-2 bg-primary/10 px-3 py-1 rounded-lg text-primary text-[9px] font-black uppercase tracking-widest mb-4">
                            <i class="fas fa-code"></i> Pemrograman
                        </div>
                        <h2 class="text-2xl font-black mb-4 leading-tight tracking-tight">
                            Program Pelatihan Lengkap Pengembangan Web
                        </h2>

                        <div class="flex items-center gap-4 text-[11px] font-bold mb-6">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-[8px] text-white font-black">CL</div>
                                <span class="text-muted-custom">Tim Mentor Clearn</span>
                            </div>
                            <span class="text-yellow-500 flex items-center gap-1">
                                <i class="fas fa-star"></i> 4.8
                            </span>
                            <span class="text-muted-custom">| 12 Peserta</span>
                        </div>

                        <p class="text-muted-custom leading-relaxed text-[13px]">
                            Pelajari cara membangun website modern dari nol hingga mahir. 
                            Anda akan mempelajari HTML, CSS, JavaScript, Tailwind CSS, Laravel, 
                            database, hingga proses deploy project nyata ke internet.
                        </p>
                    </div>

                    <div class="relative w-full md:w-56 aspect-video bg-slate-900 rounded-2xl overflow-hidden group cursor-pointer border border-gray-200 dark:border-[#2d2644] shadow-lg shrink-0">
                        <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=400"
                             class="w-full h-full object-cover opacity-60 group-hover:scale-110 transition duration-700">
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/20">
                            <i class="far fa-play-circle text-4xl text-white mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="text-[8px] text-white font-black uppercase tracking-[0.2em]">Preview</span>
                        </div>
                    </div>
                </div>

                <div class="card-bg p-6 md:p-8">
                    <h3 class="text-[15px] font-black mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-primary rounded-full"></span>
                        Apa yang Akan Anda Pelajari
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary mt-0.5 text-sm"></i>
                            <p class="text-[12px] font-bold">Membuat struktur website menggunakan HTML5</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary mt-0.5 text-sm"></i>
                            <p class="text-[12px] font-bold">Mendesain tampilan modern dengan Tailwind</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary mt-0.5 text-sm"></i>
                            <p class="text-[12px] font-bold">Interaksi menggunakan JavaScript ES6</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary mt-0.5 text-sm"></i>
                            <p class="text-[12px] font-bold">Membangun aplikasi dengan Laravel</p>
                        </div>
                    </div>
                </div>

                <div class="card-bg p-6 md:p-8">
                    <h3 class="text-[15px] font-black mb-6">Materi Kursus</h3>
                    <div class="space-y-3">
                        <div class="inner-item flex items-center justify-between p-4">
                            <div class="flex items-center gap-4">
                                <i class="fas fa-file-alt text-primary"></i>
                                <span class="text-[12px] font-extrabold uppercase tracking-tight">1. Pengenalan Web Development</span>
                            </div>
                            <span class="bg-primary/10 text-primary text-[8px] px-2 py-0.5 rounded font-black uppercase tracking-widest">Gratis</span>
                        </div>
                        <div class="inner-item flex items-center justify-between p-4">
                            <div class="flex items-center gap-4">
                                <i class="fas fa-file-alt text-primary"></i>
                                <span class="text-[12px] font-extrabold uppercase tracking-tight">2. Dasar HTML & Struktur Halaman</span>
                            </div>
                            <span class="bg-primary/10 text-primary text-[8px] px-2 py-0.5 rounded font-black uppercase tracking-widest">Gratis</span>
                        </div>
                        <div class="inner-item flex items-center justify-between p-4 opacity-50">
                            <div class="flex items-center gap-4">
                                <i class="fas fa-lock text-muted-custom"></i>
                                <span class="text-[12px] font-extrabold uppercase tracking-tight">3. Styling Modern dengan CSS</span>
                            </div>
                            <i class="fas fa-video-slash text-[10px] text-muted-custom"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Sidebar Pembayaran (Ungu Atas Dihapus) --}}
            <div class="lg:col-span-1">
                <div class="card-bg p-6 sticky top-10">
                    <h3 class="text-[10px] font-black mb-6 pb-4 border-b border-gray-100 dark:border-[#2d2644] uppercase tracking-[0.2em] text-muted-custom">
                        Ringkasan Kursus
                    </h3>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-[12px]">
                            <span class="text-muted-custom font-bold"><i class="fas fa-layer-group w-6 text-primary"></i> Materi</span>
                            <span class="font-black">18 Modul</span>
                        </div>
                        <div class="flex justify-between text-[12px]">
                            <span class="text-muted-custom font-bold"><i class="far fa-clock w-6 text-primary"></i> Durasi</span>
                            <span class="font-black">6 Jam</span>
                        </div>
                        <div class="flex justify-between text-[12px]">
                            <span class="text-muted-custom font-bold"><i class="fas fa-infinity w-6 text-primary"></i> Akses</span>
                            <span class="font-black">Selamanya</span>
                        </div>
                        <div class="flex justify-between text-[12px]">
                            <span class="text-muted-custom font-bold"><i class="fas fa-certificate w-6 text-primary"></i> Sertifikat</span>
                            <span class="font-black">Ya</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 dark:border-[#2d2644]">
                        <p class="text-[9px] text-muted-custom mb-1 font-black uppercase tracking-[0.2em]">Investasi Ilmu</p>
                        <div class="text-3xl font-black text-primary mb-8 tracking-tighter">
                            Rp150.000
                        </div>

                        <button class="w-full bg-primary hover:brightness-110 text-white font-black py-4 rounded-xl flex items-center justify-center gap-3 transition-all active:scale-95 shadow-lg shadow-primary/20 uppercase tracking-[0.2em] text-[10px]">
                            <i class="fas fa-shopping-bag text-sm"></i>
                            Daftar Sekarang
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>