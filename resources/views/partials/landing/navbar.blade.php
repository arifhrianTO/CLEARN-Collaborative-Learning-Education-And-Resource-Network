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
        <a href="#beranda" class="text-muted-custom hover:text-[#A487F8] dark:hover:text-[#A487F8] transition">Beranda</a>
        <a href="#kursus" class="text-muted-custom hover:text-[#A487F8] dark:hover:text-[#A487F8] transition">Kursus</a>
        <a href="#kategori" class="text-muted-custom hover:text-[#A487F8] dark:hover:text-[#A487F8] transition">Kategori</a>
        <a href="#pengajar" class="text-muted-custom hover:text-[#A487F8] dark:hover:text-[#A487F8] transition">Pengajar</a>
    </div>

    <div class="flex items-center gap-3">
        <button onclick="toggleTheme()" class="p-2 rounded-xl hover:bg-slate-500/10 transition">
            <i id="theme-icon" class="fa-solid fa-moon text-lg text-[#A487F8] dark:text-[#A487F8]"></i>
        </button>

        {{-- Hamburger --}}
        <button onclick="toggleMobileNav()" class="md:hidden p-2 rounded-xl hover:bg-slate-500/10 transition" aria-label="Menu">
            <i id="hamburger-icon" class="fa-solid fa-bars text-lg text-[#A487F8] dark:text-[#A487F8]"></i>
        </button>

        @auth
        <div class="relative group hidden md:block">
            <button class="flex items-center gap-2 text-muted-custom hover:text-[#A487F8] dark:hover:text-[#A487F8] transition font-semibold">
                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="Profile" class="w-8 h-8 rounded-full border border-gray-200 dark:border-gray-700">
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
        <a href="{{route('login')}}" class="hidden md:inline text-muted-custom hover:text-[#A487F8] dark:hover:text-[#A487F8] transition">
            Masuk
        </a>
        <button
            onclick="window.location='{{ route('choose_role') }}'"
            class="hidden md:inline-block bg-gradient-to-r from-[#7B65BA] to-[#A487F8] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:opacity-90">
            Daftar
        </button>
        @endauth
    </div>
</nav>

{{-- Mobile Menu Overlay --}}
<div id="mobile-nav-overlay" class="fixed inset-0 z-50 hidden md:hidden">
    <div id="mobile-nav-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleMobileNav()"></div>
    <div id="mobile-nav-panel" class="absolute top-0 right-0 h-full w-72 max-w-[85vw] bg-white dark:bg-[#110D1F] border-l border-gray-200 dark:border-[#2d2644] shadow-2xl translate-x-full transition-transform duration-300">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-[#2d2644]">
            <span class="font-bold text-sm dark:text-white text-slate-800">Menu</span>
            <button onclick="toggleMobileNav()" class="p-2 rounded-xl hover:bg-slate-500/10 transition">
                <i class="fa-solid fa-xmark text-lg dark:text-white text-slate-800"></i>
            </button>
        </div>
        <div class="p-5 space-y-2">
            <a href="#beranda" onclick="toggleMobileNav()" class="block px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-primary/10 hover:text-primary transition">Beranda</a>
            <a href="#kursus" onclick="toggleMobileNav()" class="block px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-primary/10 hover:text-primary transition">Kursus</a>
            <a href="#kategori" onclick="toggleMobileNav()" class="block px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-primary/10 hover:text-primary transition">Kategori</a>
            <a href="#pengajar" onclick="toggleMobileNav()" class="block px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-primary/10 hover:text-primary transition">Pengajar</a>
        </div>
        <div class="border-t border-gray-100 dark:border-[#2d2644] p-5 space-y-3">
            @auth
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-50 dark:bg-white/5">
                <img src="{{ auth()->user()->profile_picture ? asset('storage/' . auth()->user()->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name).'&color=7F9CF5&background=EBF4FF' }}" alt="" class="w-8 h-8 rounded-full">
                <span class="text-sm font-semibold dark:text-white text-slate-800 truncate">{{ auth()->user()->name }}</span>
            </div>
            <a href="{{ route('dashboard') }}" onclick="toggleMobileNav()" class="block w-full text-left px-4 py-3 rounded-xl text-sm font-semibold text-primary hover:bg-primary/10 transition">
                <i class="fas fa-tachometer-alt w-5 text-center"></i> Dashboard
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="block w-full text-left px-4 py-3 rounded-xl text-sm font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/10 transition">
                    <i class="fas fa-sign-out-alt w-5 text-center"></i> Keluar
                </button>
            </form>
            @else
            <a href="{{ route('login') }}" onclick="toggleMobileNav()" class="block w-full text-center px-4 py-3 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-[#2d2644] hover:bg-primary/10 hover:text-primary transition">
                Masuk
            </a>
            <button onclick="window.location='{{ route('choose_role') }}'" class="block w-full text-center px-4 py-3 rounded-xl text-sm font-semibold bg-gradient-to-r from-[#7B65BA] to-[#A487F8] text-white hover:opacity-90 transition">
                Daftar
            </button>
            @endauth
        </div>
    </div>
</div>
