@props([
'role' => 'student',
'name' => 'User',
'initials' => 'U',
'photo' => null,
])

<aside class="w-72 hidden lg:flex flex-col sticky top-0 h-screen sidebar-bg p-6 pt-10 border-r border-transparent dark:border-white/5 transition-all">
    <div class="flex flex-col h-full gap-6">

        <div class="flex items-center gap-3 pb-6">
            <div class="w-10 h-10 rounded-full overflow-hidden bg-purple-400 flex items-center justify-center text-white text-base font-bold shadow-lg shadow-purple-400/20 shrink-0">
                @if(!empty($photo))
                <img
                    src="{{ asset('storage/' . $photo) }}"
                    alt="{{ $name }}"
                    class="w-full h-full object-cover rounded-full">
                @else
                {{ $initials }}
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-sm leading-none mb-1 truncate">{{ $name }}</h4>
                <p class="text-[10px] text-slate-500 font-semibold tracking-wide uppercase">
                    @if($role == 'admin' || $role == 'Admin')
                    Admin
                    @elseif($role == 'mentor' || $role == 'Pengajar')
                    Mentor
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
                label="Verifikasi Mentor"
                :active="request()->routeIs('admin.verify.mentors')" />

            <x-dashboard.sidebar-item
                href="{{ route('admin.courses.index') }}"
                icon="fa-book-open"
                label="Verifikasi Kursus"
                :active="request()->routeIs('admin.verify.courses')" />

            <x-dashboard.sidebar-item
                href="{{ route('admin.finance.index')}}"
                icon="fa-wallet"
                label="Keuangan"
                :active="request()->routeIs('admin.revenue')" />

            <x-dashboard.sidebar-item
                href="{{ route('admin.categories.index')}}"
                icon="fa-layer-group"
                label="Kategori"
                :active="request()->routeIs('admin.revenue')" />

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
                :active="request()->is('dashboard-pengajar')" />

            <x-dashboard.sidebar-item
                href="{{ route('mentor.courses.index') }}"
                icon="fa-book"
                label="Kursus Saya"
                :active="request()->is('pengajar/kursus*')" />

            <x-dashboard.sidebar-item
                href="{{ route('mentor.student.index')}}"
                icon="fa-users"
                label="Pelajar"
                :active="request()->is('pengajar/pelajar*')" />

            <x-dashboard.sidebar-item
                href="{{ route('mentor.finance.index') }}"
                icon="fa-wallet"
                label="Pendapatan"
                :active="request()->is('pengajar/pendapatan*')" />

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
                :active="request()->is('dashboard-student')" />

            <x-dashboard.sidebar-item
                href="{{ route('student.courses') }}"
                icon="fa-book-open"
                label="Kursus Saya"
                :active="request()->is('courses*')" />

            <x-dashboard.sidebar-item
                href="{{ route('student.progress') }}"
                icon="fa-chart-line"
                label="Progres"
                :active="request()->is('progres*')" />

            <x-dashboard.sidebar-item
                href="{{ route('student.certif') }}"
                icon="fa-trophy"
                label="Sertifikat"
                :active="request()->is('certif*')" />

            <x-dashboard.sidebar-item
                href="{{ route('student.history') }}"
                icon="fa-clock-rotate-left"
                label="Riwayat"
                :active="request()->is('history*')" />

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
                    class="w-full bg-primary/10 text-primary px-4 py-2.5 rounded-xl text-xs font-bold hover:bg-primary hover:text-white transition-all flex items-center justify-center gap-2 group">
                    <i class="fa-solid fa-right-from-bracket transition-transform group-hover:translate-x-1"></i>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </div>
</aside>

{{-- Modal Logout Konfirmasi --}}
<div id="logoutModal" class="fixed inset-0 z-[100] flex items-center justify-center hidden p-4 bg-black/20 backdrop-blur-sm transition-all">
    {{-- Di sini saya mengubah rounded-3xl menjadi rounded-2xl --}}
    <div class="bg-white dark:bg-[#161525] p-8 rounded-2xl shadow-2xl border dark:border-white/5 w-full max-w-sm text-center">
        <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-red-50 dark:bg-red-500/10 flex items-center justify-center">
            <i class="fas fa-right-from-bracket text-2xl text-red-500"></i>
        </div>
        <h3 class="text-lg font-black text-slate-800 dark:text-white mb-2">Yakin Ingin Keluar?</h3>
        <p class="text-xs text-slate-400 mb-8 font-medium">Keluar dari sesi ini? Anda harus login kembali untuk masuk ke dasbor.</p>

        <div class="flex gap-3">
            {{-- Tombol Batal --}}
            <button onclick="document.getElementById('logoutModal').classList.add('hidden')"
                class="flex-1 py-3 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-slate-200 transition-all">
                Batal
            </button>
            {{-- Tombol Keluar --}}
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