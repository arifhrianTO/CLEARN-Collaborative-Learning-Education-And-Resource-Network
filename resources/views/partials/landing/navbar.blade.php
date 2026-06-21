<nav class="nav-landing">
    <div class="flex items-center">
        <img
            src="{{ asset('images/logo-light.png') }}"
            alt="logo"
            class="h-12 w-auto object-contain dark:hidden">

        <img
            src="{{ asset('images/logo-dark.png') }}"
            alt="logo"
            class="h-12 w-auto object-contain hidden dark:block">
    </div>

    <div class="hidden md:flex gap-8 text-sm font-semibold">
        <a href="#beranda" class="text-purple-600 dark:text-purple-400 border-b-2 border-purple-600 dark:border-purple-400 pb-1">Beranda</a>
        <a href="#kursus" class="text-muted-custom hover:text-purple-600 dark:hover:text-purple-400 transition">Kursus Unggulan</a>
        <a href="#kategori" class="text-muted-custom hover:text-purple-600 dark:hover:text-purple-400 transition">Kategori</a>
        <a href="#ulasan" class="text-muted-custom hover:text-purple-600 dark:hover:text-purple-400 transition">Ulasan</a>
        <a href="#pengajar" class="text-muted-custom hover:text-purple-600 dark:hover:text-purple-400 transition">Pengajar</a>
    </div>

    <div class="flex items-center gap-5">
        <!-- <div class="relative cursor-pointer group">
                <i class="fa-solid fa-cart-shopping text-muted-custom group-hover:text-purple-600"></i>
                <span class="absolute -top-2 -right-2 bg-purple-600 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold shadow-lg">2</span>
            </div> -->
        <button onclick="toggleTheme()" class="p-2 rounded-xl hover:bg-slate-500/10 transition">
            <i id="theme-icon" class="fa-solid fa-moon text-lg text-purple-600 dark:text-purple-400"></i>
        </button>
        <a href="{{route('login')}}" class="text-muted-custom hover:text-purple-600 dark:hover:text-purple-400 transition">
            Masuk
        </a>
        <button
            onclick="window.location='{{ route('choose_role') }}'"
            class="bg-primary text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90">
            Daftar
        </button>
    </div>
</nav>