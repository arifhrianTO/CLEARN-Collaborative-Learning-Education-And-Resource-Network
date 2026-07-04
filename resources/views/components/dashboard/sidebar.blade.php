@props([
'role' => 'student',
'name' => 'User',
'initials' => 'U',
'photo' => null,
])

{{-- Mobile hamburger toggle --}}
<button id="sidebar-hamburger" onclick="toggleDashboardSidebar()"
    class="lg:hidden fixed top-4 left-4 z-40 w-10 h-10 rounded-xl bg-white dark:bg-[#1A1625] border dark:border-white/10 border-slate-200 shadow-md flex items-center justify-center hover:bg-slate-50 dark:hover:bg-white/5 transition">
    <i class="fa-solid fa-bars text-base text-primary"></i>
</button>

{{-- Desktop sidebar --}}
<aside id="desktop-sidebar" class="w-72 hidden lg:flex flex-col sticky top-0 h-screen sidebar-bg p-6 pt-10 border-r border-transparent dark:border-white/5 transition-all">
    <div class="flex flex-col h-full gap-6">

        <div class="flex items-center gap-3 pb-6">
            <div class="w-10 h-10 rounded-full overflow-hidden bg-[#A487F8] flex items-center justify-center text-white text-base font-bold shadow-lg shadow-[#A487F8]/20 shrink-0">
                @if(!empty($photo))
                <img
                    id="sidebar-avatar-img"
                    src="{{ asset('storage/' . $photo) }}"
                    alt="{{ $name }}"
                    class="w-full h-full object-cover rounded-full">
                @else
                <span id="sidebar-avatar-initials">{{ $initials }}</span>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-sm leading-none mb-1 truncate">{{ $name }}</h4>
                <p class="text-[10px] text-slate-500 font-semibold tracking-wide uppercase">
                    @if($role == 'admin' || $role == 'Admin')
                    Admin
                    @elseif($role == 'mentor' || $role == 'Pengajar')
                    Pengajar
                    @else
                    Pelajar
                    @endif
                </p>
            </div>
            <button onclick="toggleTheme()" class="p-2 rounded-xl hover:bg-gray-500/10 transition">
                <i id="theme-icon" class="fa-solid fa-moon text-base text-primary"></i>
            </button>
        </div>

        <div class="space-y-1.5 flex-1">
            @if(strtolower($role) == 'admin')
            <x-dashboard.sidebar-item
                href="{{ route('admin.dashboard') }}"
                icon="fa-chart-pie"
                label="Beranda"
                :active="request()->routeIs('admin.dashboard')" />

            <x-dashboard.sidebar-item
                href="{{ route('admin.verify.mentors') }}"
                icon="fa-user-check"
                label="Verifikasi Pengajar"
                :active="request()->routeIs('admin.verify.mentors')" />

            <x-dashboard.sidebar-item
                href="{{ route('admin.courses.index') }}"
                icon="fa-book-open"
                label="Verifikasi Kursus"
                :active="request()->routeIs('admin.courses.index')" />

             <x-dashboard.sidebar-item
                href="{{ route('admin.categories.index')}}"
                icon="fa-layer-group"
                label="Kategori"
                :active="request()->routeIs('admin.categories.index')" />

             <x-dashboard.sidebar-item
                href="{{ route('admin.users.index')}}"
                icon="fa-users"
                label="Pengguna"
                :active="request()->routeIs('admin.users.*')" />

            <x-dashboard.sidebar-item
                href="{{ route('admin.finance.index')}}"
                icon="fa-wallet"
                label="Keuangan"
                :active="request()->routeIs('admin.finance.index')" />

            <x-dashboard.sidebar-item
                href="/settings"
                icon="fa-gear"
                label="Pengaturan"
                :active="request()->is('settings*')" />


            @elseif($role == 'mentor' || $role == 'Pengajar')
            <x-dashboard.sidebar-item
                href="{{ route('mentor.dashboard')}}"
                icon="fa-chart-pie"
                label="Beranda"
                :active="request()->routeIs('mentor.dashboard')" />

            <x-dashboard.sidebar-item
                href="{{ route('mentor.courses.index') }}"
                icon="fa-book"
                label="Kursus Saya"
                :active="request()->routeIs('mentor.courses.*')" />

            <x-dashboard.sidebar-item
                href="{{ route('mentor.student.index')}}"
                icon="fa-users"
                label="Pelajar"
                :active="request()->routeIs('mentor.student.*')" />

            <x-dashboard.sidebar-item
                href="{{ route('mentor.finance.index') }}"
                icon="fa-wallet"
                label="Pendapatan"
                :active="request()->routeIs('mentor.finance.*')" />

            <x-dashboard.sidebar-item
                href="/settings"
                icon="fa-gear"
                label="Pengaturan"
                :active="request()->is('settings*')" />

            @else
            {{-- Bagian Pelajar/Student --}}
            <x-dashboard.sidebar-item
                href="{{ route('student.dashboard') }}"
                icon="fa-chart-pie"
                label="Beranda"
                :active="request()->routeIs('student.dashboard')" />

            <x-dashboard.sidebar-item
                href="{{ route('student.course.index') }}"
                icon="fa-book-open"
                label="Kursus Saya"
                :active="request()->routeIs('student.course.*')" />

            <x-dashboard.sidebar-item
                href="{{ route('student.progress') }}"
                icon="fa-chart-line"
                label="Progres"
                :active="request()->routeIs('student.progress')" />

            <x-dashboard.sidebar-item
                href="{{ route('student.certif') }}"
                icon="fa-trophy"
                label="Sertifikat"
                :active="request()->routeIs('student.certif')" />

            <x-dashboard.sidebar-item
                href="{{ route('student.history') }}"
                icon="fa-clock-rotate-left"
                label="Riwayat"
                :active="request()->routeIs('student.history')" />

            <x-dashboard.sidebar-item
                href="/settings"
                icon="fa-gear"
                label="Pengaturan"
                :active="request()->is('settings*')" />
            @endif
        </div>

        <div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="button"
                    onclick="document.getElementById('logoutModal').classList.remove('hidden')"
                    class="w-full bg-red-500/10 text-red-500 px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-red-500 hover:text-white transition-all flex items-center justify-center gap-2 group">
                    <i class="fa-solid fa-door-open transition-transform group-hover:translate-x-1"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Mobile sidebar overlay --}}
<div id="mobile-sidebar" class="fixed inset-0 z-50 hidden lg:hidden">
    <div id="mobile-sidebar-backdrop" class="absolute inset-0 bg-black/40 backdrop-blur-sm" onclick="toggleDashboardSidebar()"></div>
    <aside id="mobile-sidebar-panel" class="absolute top-0 left-0 h-full w-72 max-w-[85vw] sidebar-bg p-6 pt-10 border-r border-transparent dark:border-white/5 shadow-2xl -translate-x-full transition-transform duration-300">
        <div class="flex flex-col h-full gap-6">
            {{-- Header with avatar & close --}}
            <div class="flex items-center justify-between pb-6 border-b dark:border-white/10 border-slate-200">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full overflow-hidden bg-[#A487F8] flex items-center justify-center text-white text-base font-bold shrink-0">
                        @if(!empty($photo))
                        <img src="{{ asset('storage/' . $photo) }}" alt="{{ $name }}" class="w-full h-full object-cover rounded-full">
                        @else
                        <span>{{ $initials }}</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-sm leading-none mb-1 truncate dark:text-white text-slate-800">{{ $name }}</h4>
                        <p class="text-[10px] text-slate-500 font-semibold tracking-wide uppercase">
                            @if($role == 'admin' || $role == 'Admin') Admin
                            @elseif($role == 'mentor' || $role == 'Pengajar') Pengajar
                            @else Pelajar
                            @endif
                        </p>
                    </div>
                </div>
                <button onclick="toggleDashboardSidebar()" class="p-2 rounded-xl hover:bg-slate-500/10 transition">
                    <i class="fa-solid fa-xmark text-lg dark:text-white text-slate-800"></i>
                </button>
            </div>

            {{-- Nav items --}}
            <div class="space-y-1.5 flex-1">
                @if(strtolower($role) == 'admin')
                <x-dashboard.sidebar-item href="{{ route('admin.dashboard') }}" icon="fa-chart-pie" label="Beranda" :active="request()->routeIs('admin.dashboard')" />
                <x-dashboard.sidebar-item href="{{ route('admin.verify.mentors') }}" icon="fa-user-check" label="Verifikasi Pengajar" :active="request()->routeIs('admin.verify.mentors')" />
                <x-dashboard.sidebar-item href="{{ route('admin.courses.index') }}" icon="fa-book-open" label="Verifikasi Kursus" :active="request()->routeIs('admin.courses.index')" />
                <x-dashboard.sidebar-item href="{{ route('admin.finance.index')}}" icon="fa-wallet" label="Keuangan" :active="request()->routeIs('admin.finance.*')" />
                <x-dashboard.sidebar-item href="{{ route('admin.categories.index')}}" icon="fa-layer-group" label="Kategori" :active="request()->routeIs('admin.categories.*')" />
                <x-dashboard.sidebar-item href="{{ route('admin.users.index')}}" icon="fa-users" label="Pengguna" :active="request()->routeIs('admin.users.*')" />
                <x-dashboard.sidebar-item href="/settings" icon="fa-gear" label="Pengaturan" :active="request()->is('settings*')" />
                @elseif($role == 'mentor' || $role == 'Pengajar')
                <x-dashboard.sidebar-item href="{{ route('mentor.dashboard')}}" icon="fa-chart-pie" label="Beranda" :active="request()->routeIs('mentor.dashboard')" />
                <x-dashboard.sidebar-item href="{{ route('mentor.courses.index') }}" icon="fa-book" label="Kursus Saya" :active="request()->routeIs('mentor.courses.*')" />
                <x-dashboard.sidebar-item href="{{ route('mentor.student.index')}}" icon="fa-users" label="Pelajar" :active="request()->routeIs('mentor.student.*')" />
                <x-dashboard.sidebar-item href="{{ route('mentor.finance.index') }}" icon="fa-wallet" label="Pendapatan" :active="request()->routeIs('mentor.finance.*')" />
                <x-dashboard.sidebar-item href="/settings" icon="fa-gear" label="Pengaturan" :active="request()->is('settings*')" />
                @else
                <x-dashboard.sidebar-item href="{{ route('student.dashboard') }}" icon="fa-chart-pie" label="Beranda" :active="request()->routeIs('student.dashboard')" />
                <x-dashboard.sidebar-item href="{{ route('student.course.index') }}" icon="fa-book-open" label="Kursus Saya" :active="request()->routeIs('student.course.*')" />
                <x-dashboard.sidebar-item href="{{ route('student.progress') }}" icon="fa-chart-line" label="Progres" :active="request()->routeIs('student.progress')" />
                <x-dashboard.sidebar-item href="{{ route('student.certif') }}" icon="fa-trophy" label="Sertifikat" :active="request()->routeIs('student.certif')" />
                <x-dashboard.sidebar-item href="{{ route('student.history') }}" icon="fa-clock-rotate-left" label="Riwayat" :active="request()->routeIs('student.history')" />
                <x-dashboard.sidebar-item href="/settings" icon="fa-gear" label="Pengaturan" :active="request()->is('settings*')" />
                @endif
            </div>

            {{-- Logout --}}
            <div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="button"
                        onclick="document.getElementById('logoutModal').classList.remove('hidden')"
                        class="w-full bg-red-500/10 text-red-500 px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-red-500 hover:text-white transition-all flex items-center justify-center gap-2 group">
                        <i class="fa-solid fa-door-open transition-transform group-hover:translate-x-1"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </aside>
</div>

{{-- Modal Logout Konfirmasi --}}
<div id="logoutModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-4 bg-black/20 backdrop-blur-sm transition-all">
    <div class="bg-white dark:bg-[#1A1625] p-8 rounded-2xl shadow-2xl border dark:border-white/5 w-full max-w-sm text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-50 dark:bg-red-500/10 flex items-center justify-center">
            <i class="fas fa-door-open text-2xl text-red-500"></i>
        </div>
        <h3 class="text-lg font-black text-slate-800 dark:text-white mb-2">Yakin Ingin Keluar?</h3>
        <p class="text-xs text-slate-400 mb-8 font-medium">Keluar dari sesi ini? Anda harus login kembali untuk masuk ke dasbor.</p>

        <div class="flex gap-3">
            <button onclick="document.getElementById('logoutModal').classList.add('hidden')"
                class="flex-1 py-3 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 transition-all">
                Batal
            </button>
            <form action="{{ route('logout') }}" method="POST" class="flex-1">
                @csrf
                <button type="submit"
                    class="w-full py-3 bg-red-500 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-red-600 transition-all shadow-lg shadow-red-500/20">
                    Keluar
                </button>
            </form>
        </div>
    </div>
</div>