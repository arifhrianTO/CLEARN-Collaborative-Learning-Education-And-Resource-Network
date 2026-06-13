<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clearn - Riwayat Pesanan</title>
    
    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Fonts & Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Anti-Flash Script --}}
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
        }
    </style>
</head>
<body class="min-h-screen flex">

    {{-- Sidebar Pelajar --}}
    <x-dashboard.sidebar role="Student" name="User Pelajar" initials="UP" active="order-history" />

    <main class="flex-1 p-6 lg:p-10">
        <div class="max-w-5xl mx-auto">
            
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-2xl font-black tracking-tighter mb-1">Riwayat Pesanan</h1>
                <p class="text-muted-custom text-[12px]">Pantau semua transaksi dan akses kursus yang telah Anda beli.</p>
            </div>

            {{-- Stats Grid (Diselaraskan dengan halaman utama) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="card-bg p-5 flex items-center gap-4 group">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black leading-none">2</h2>
                        <span class="text-[9px] font-bold text-muted-custom uppercase tracking-widest">Total Pesanan</span>
                    </div>
                </div>

                <div class="card-bg p-5 flex items-center gap-4 group">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black leading-none text-nowrap">Rp270.000</h2>
                        <span class="text-[9px] font-bold text-muted-custom uppercase tracking-widest">Pengeluaran</span>
                    </div>
                </div>

                <div class="card-bg p-5 flex items-center gap-4 group">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black leading-none">2</h2>
                        <span class="text-[9px] font-bold text-muted-custom uppercase tracking-widest">Kursus Aktif</span>
                    </div>
                </div>
            </div>

            {{-- List Transaksi --}}
            <div class="space-y-4">
                
                {{-- Card Transaksi 1 --}}
                <div class="card-bg overflow-hidden group">
                    {{-- Header Transaksi --}}
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-[#2d2644] flex flex-wrap justify-between items-center gap-4 bg-slate-50/50 dark:bg-[#161226]">
                        <div class="flex gap-8">
                            <div>
                                <p class="text-[9px] font-bold text-muted-custom uppercase tracking-wider mb-0.5">ID Pesanan</p>
                                <p class="text-[12px] font-black tracking-tight">#CLN-882531</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-muted-custom uppercase tracking-wider mb-0.5">Tanggal</p>
                                <p class="text-[12px] font-bold">17 Jan 2026</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-green-500/10 text-green-500 border border-green-500/20">Berhasil</span>
                            <button class="text-muted-custom hover:text-primary transition-colors"><i class="fas fa-file-invoice text-sm"></i></button>
                        </div>
                    </div>
                    
                    {{-- Detail Transaksi --}}
                    <div class="p-5 flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-36 shrink-0">
                            <div class="aspect-video rounded-xl overflow-hidden border border-gray-100 dark:border-[#2d2644]">
                                <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=400" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="font-extrabold text-[15px] leading-tight tracking-tight group-hover:text-primary transition-colors">Dasar UI/UX Design untuk Pemula</h3>
                                <p class="text-[15px] font-black tracking-tight text-primary">Rp120.000</p>
                            </div>
                            <p class="text-[10px] text-muted-custom mb-5 font-bold tracking-widest uppercase">Mentor Desain Clearn</p>
                            
                            <div class="flex gap-2">
                                <button class="bg-primary text-white text-[10px] font-extrabold px-5 py-2.5 rounded-xl shadow-lg shadow-primary/20 hover:brightness-110 active:scale-95 transition-all uppercase tracking-widest">Mulai Belajar</button>
                                <button class="border border-gray-200 dark:border-[#2d2644] text-muted-custom text-[10px] font-extrabold px-5 py-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 active:scale-95 transition-all uppercase tracking-widest">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Card Transaksi 2 (Contoh Tambahan) --}}
                <div class="card-bg overflow-hidden group">
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-[#2d2644] flex flex-wrap justify-between items-center gap-4 bg-slate-50/50 dark:bg-[#161226]">
                        <div class="flex gap-8">
                            <div>
                                <p class="text-[9px] font-bold text-muted-custom uppercase tracking-wider mb-0.5">ID Pesanan</p>
                                <p class="text-[12px] font-black tracking-tight">#CLN-882530</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-muted-custom uppercase tracking-wider mb-0.5">Tanggal</p>
                                <p class="text-[12px] font-bold">10 Jan 2026</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-green-500/10 text-green-500 border border-green-500/20">Berhasil</span>
                            <button class="text-muted-custom hover:text-primary transition-colors"><i class="fas fa-file-invoice text-sm"></i></button>
                        </div>
                    </div>
                    <div class="p-5 flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-36 shrink-0">
                            <div class="aspect-video rounded-xl overflow-hidden border border-gray-100 dark:border-[#2d2644]">
                                <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=400" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="font-extrabold text-[15px] leading-tight tracking-tight group-hover:text-primary transition-colors">Laravel Fullstack Masterclass 2026</h3>
                                <p class="text-[15px] font-black tracking-tight text-primary">Rp150.000</p>
                            </div>
                            <p class="text-[10px] text-muted-custom mb-5 font-bold tracking-widest uppercase">Tim Mentor Clearn</p>
                            <div class="flex gap-2">
                                <button class="bg-primary text-white text-[10px] font-extrabold px-5 py-2.5 rounded-xl shadow-lg shadow-primary/20 hover:brightness-110 active:scale-95 transition-all uppercase tracking-widest">Mulai Belajar</button>
                                <button class="border border-gray-200 dark:border-[#2d2644] text-muted-custom text-[10px] font-extrabold px-5 py-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 active:scale-95 transition-all uppercase tracking-widest">Detail</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

</body>
</html>