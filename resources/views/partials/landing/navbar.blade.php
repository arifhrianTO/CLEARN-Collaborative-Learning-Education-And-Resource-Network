<nav class="nav-landing">
    <div class="flex items-center">
        <img
            src="{{ asset('images/logo-light.png') }}"
            alt="logo"
            class="h-10 w-auto object-contain dark:hidden">

        <img
            src="{{ asset('images/logo-dark.png') }}"
            alt="logo"
            class="h-10 w-auto object-contain hidden dark:block">
    </div>

    <div class="hidden md:flex gap-8 text-sm font-semibold">
        <a href="#beranda" class="text-muted-custom hover:text-purple-600 dark:hover:text-purple-400 transition">Beranda</a>
        <a href="#kursus" class="text-muted-custom hover:text-purple-600 dark:hover:text-purple-400 transition">Kursus</a>
        <a href="#kategori" class="text-muted-custom hover:text-purple-600 dark:hover:text-purple-400 transition">Kategori</a>
        <a href="#pengajar" class="text-muted-custom hover:text-purple-600 dark:hover:text-purple-400 transition">Pengajar</a>
    </div>

    <div class="flex items-center gap-5">
        <button onclick="toggleTheme()" class="p-2 rounded-xl hover:bg-slate-500/10 transition">
            <i id="theme-icon" class="fa-solid fa-moon text-lg text-purple-600 dark:text-purple-400"></i>
        </button>
        @auth
        <div class="relative group">
            <button class="flex items-center gap-2 text-muted-custom hover:text-purple-600 dark:hover:text-purple-400 transition font-semibold">
                <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="Profile" class="w-8 h-8 rounded-full border border-gray-200 dark:border-gray-700">
                <span class="hidden md:block">{{ auth()->user()->name }}</span>
                <i class="fas fa-chevron-down text-[10px]"></i>
            </button>
            <div class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#110D1F] border border-gray-200 dark:border-[#2d2644] rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 overflow-hidden">
                <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-[#1a1429] transition-colors">
                    <i class="fas fa-tachometer-alt w-5 text-center text-primary"></i> Dashboard
                </a>
                <div class="border-t border-gray-100 dark:border-[#2d2644]"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 transition-colors font-semibold">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
        @else
        <a href="{{route('login')}}" class="text-muted-custom hover:text-purple-600 dark:hover:text-purple-400 transition">
            Masuk
        </a>
        <button
            onclick="window.location='{{ route('choose_role') }}'"
            class="bg-gradient-to-r from-purple-700 to-purple-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90">
            Daftar
        </button>
        @endauth
    </div>
</nav>