<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-clearn.png') }}">
    <title>CLEARN │ Halaman Tidak Ditemukan</title>

    <script>
        if (
            localStorage.theme === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-[#0F0B1A] min-h-screen selection:bg-primary selection:text-white">
    <div class="min-h-screen flex flex-col items-center justify-center p-6 text-center">

        <div class="mb-6 animate-fade-down">
            <a href="{{ route('home') }}" class="block transition-transform hover:scale-105">
                <img src="{{ asset('images/logo-light.png') }}" alt="Clearn Logo" class="h-10 dark:hidden">
                <img src="{{ asset('images/logo-dark.png') }}" alt="Clearn Logo" class="h-10 hidden dark:block">
            </a>
        </div>

        <div class="animate-fade-up">
            <div class="w-24 h-24 mx-auto mb-6 rounded-2xl bg-primary/10 flex items-center justify-center">
                <i class="fa-solid fa-map-pin text-4xl text-primary"></i>
            </div>

            <h1 class="text-7xl md:text-8xl font-black text-primary tracking-tighter mb-2">404</h1>
            <h2 class="text-xl md:text-2xl font-bold mb-3">Halaman Tidak Ditemukan</h2>
            <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8 text-sm">
                Halaman yang Anda cari mungkin telah dipindahkan, dihapus, atau tidak pernah ada.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <a href="{{ route('home') }}"
                    class="px-6 py-3 bg-primary hover:brightness-110 text-white text-xs font-black uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-primary/20">
                    <i class="fas fa-home mr-2"></i> Kembali ke Beranda
                </a>
                <button onclick="history.back()"
                    class="px-6 py-3 bg-white dark:bg-[#1A1625] border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-400 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Halaman Sebelumnya
                </button>
            </div>
        </div>

        <div class="mt-12 animate-fade-in">
            <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">
                &copy; {{ date('Y') }} Clearn. All rights reserved.
            </p>
        </div>
    </div>

    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .animate-fade-down { animation: fadeDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-fade-up { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; }
    </style>
</body>
</html>