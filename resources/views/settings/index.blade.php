@extends('layouts.dashboard')

@section('title', 'Pengaturan | Clearn - Platform Pembelajaran Online')

@section('content')

@php
$user = auth()->user();
@endphp

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}" 
/>

{{-- Content --}}
<main class="flex-1 p-6 lg:p-10">
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <p class="text-slate-500 text-sm mb-1">
                Halo,
            </p>

            <h1 class="text-2xl font-extrabold tracking-tight text-white">
                {{ $user?->name ?? 'User' }}
            </h1>
        </div>

        {{-- Tab Navigation --}}
        <div class="flex border-b border-white/10 mb-8 overflow-x-auto">
            <button
                type="button"
                onclick="switchTab('profile')"
                id="tab-btn-profile"
                class="tab-btn flex items-center gap-2 px-5 py-3 text-[13px] font-semibold text-purple-400 border-b-2 border-primary whitespace-nowrap transition">
                <i class="fa-regular fa-circle-user"></i>
                Profil
            </button>

            <button
                type="button"
                onclick="switchTab('password')"
                id="tab-btn-password"
                class="tab-btn flex items-center gap-2 px-5 py-3 text-[13px] font-semibold text-slate-500 hover:text-slate-300 border-b-2 border-transparent whitespace-nowrap transition">
                <i class="fa-solid fa-key"></i>
                Ganti Password
            </button>

            <button
                type="button"
                onclick="switchTab('delete-account')"
                id="tab-btn-delete-account"
                class="tab-btn flex items-center gap-2 px-5 py-3 text-[13px] font-semibold text-slate-500 hover:text-red-400 border-b-2 border-transparent whitespace-nowrap transition">
                <i class="fa-regular fa-trash-can"></i>
                Hapus Akun
            </button>
        </div>

        {{-- Alert Profile --}}
        @if(session('status') === 'profile-updated')
        <div class="mb-5 px-4 py-3 rounded-xl text-sm flex items-center gap-2 bg-green-500/10 border border-green-500/20 text-green-400">
            <i class="fa-solid fa-circle-check"></i>
            Profil berhasil diperbarui.
        </div>
        @endif

        {{-- Alert Password --}}
        @if(session('status') === 'password-updated')
        <div class="mb-5 px-4 py-3 rounded-xl text-sm flex items-center gap-2 bg-green-500/10 border border-green-500/20 text-green-400">
            <i class="fa-solid fa-circle-check"></i>
            Password berhasil diperbarui.
        </div>
        @endif

        {{-- ================= TAB PROFIL ================= --}}
        <div id="tab-profile" class="tab-content">
            <div class="bg-[#1a1828] border border-white/10 rounded-[20px] p-8 shadow-sm">
                <h2 class="text-base font-bold text-white mb-1">
                    Informasi Personal
                </h2>

                <p class="text-slate-500 text-sm mb-7">
                    Informasi ini digunakan untuk personalisasi akun kamu.
                </p>

                <form method="POST" action="{{ route('settings.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Avatar --}}
                    <div class="flex items-center gap-5 mb-7">
                        <div
                            id="avatar-wrap"
                            class="w-16 h-16 rounded-full bg-gradient-to-br from-primary to-purple-500 flex items-center justify-center text-white text-xl font-extrabold overflow-hidden shrink-0">
                            @if(!empty($user->photo))
                            <img
                                src="{{ asset('storage/'.$user->photo) }}"
                                class="w-full h-full object-cover"
                                alt="Foto Profil">
                            @else
                            {{ strtoupper(substr($user?->name ?? 'U', 0, 2)) }}
                            @endif
                        </div>

                        <div>
                            <label
                                for="photo"
                                class="cursor-pointer inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-bold bg-primary/10 text-purple-400 hover:bg-primary hover:text-white transition">
                                <i class="fa-solid fa-upload"></i>
                                Ganti Foto
                            </label>

                            <input
                                type="file"
                                id="photo"
                                name="photo"
                                class="hidden"
                                accept="image/*"
                                onchange="previewAvatar(event)">

                            <p class="text-[11px] text-slate-500 mt-2">
                                JPG, PNG. Maks 2MB.
                            </p>

                            @error('photo')
                            <p class="text-red-400 text-xs mt-1">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Form Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wide text-slate-400 mb-1.5">
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user?->name) }}"
                                class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-slate-200 placeholder:text-slate-600 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                placeholder="Nama lengkap kamu">

                            @error('name')
                            <p class="text-red-400 text-xs mt-1">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wide text-slate-400 mb-1.5">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user?->email) }}"
                                class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-slate-200 placeholder:text-slate-600 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                placeholder="email@kamu.com">

                            @error('email')
                            <p class="text-red-400 text-xs mt-1">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wide text-slate-400 mb-1.5">
                                Nomor Telepon
                            </label>

                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $user->phone ?? '') }}"
                                class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-slate-200 placeholder:text-slate-600 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                placeholder="08xxxxxxxxxx">

                            @error('phone')
                            <p class="text-red-400 text-xs mt-1">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wide text-slate-400 mb-1.5">
                                Username
                            </label>

                            <input
                                type="text"
                                name="username"
                                value="{{ old('username', $user->username ?? '') }}"
                                class="w-full px-4 py-2.5 bg-white/5 border border-white/10 rounded-xl text-slate-200 placeholder:text-slate-600 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                placeholder="username_kamu">

                            <p class="text-[11px] text-slate-500 mt-1">
                                Gunakan huruf kecil, angka, titik, atau underscore.
                            </p>

                            @error('username')
                            <p class="text-red-400 text-xs mt-1">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="bg-primary text-white px-6 py-2.5 rounded-xl text-[13px] font-bold hover:bg-purple-700 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/30 transition">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        {{-- ================= TAB PASSWORD ================= --}}
        <div id="tab-password" class="tab-content hidden">
            <div class="w-full bg-[#1a1828] border border-white/10 rounded-[20px] p-8 shadow-sm">

                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-purple-400 text-sm bg-[#7c3aed]/10">
                        <i class="fa-solid fa-key"></i>
                    </div>

                    <div>
                        <h2 class="text-base font-bold text-white">
                            Ganti Password
                        </h2>

                        <p class="text-slate-500 text-sm mt-1">
                            Pastikan password baru kamu kuat dan unik.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('settings.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">

                        {{-- Password Saat Ini --}}
                        <div>
                            <label for="current_password" class="block text-[11px] font-bold uppercase tracking-wide text-slate-400 mb-1.5">
                                Password Saat Ini
                            </label>

                            <div class="relative">
                                <input
                                    type="password"
                                    id="current_password"
                                    name="current_password"
                                    class="w-full px-4 py-2.5 pr-11 bg-white/5 border border-white/10 rounded-xl text-slate-200 placeholder:text-slate-600 text-[13px] outline-none focus:border-[#7c3aed]/50 focus:ring-4 focus:ring-[#7c3aed]/10 transition"
                                    placeholder="Masukkan password saat ini">

                                <button
                                    type="button"
                                    onclick="togglePassword('current_password', 'icon-current-password')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-purple-400 transition">
                                    <i id="icon-current-password" class="fa-regular fa-eye"></i>
                                </button>
                            </div>

                            @error('current_password')
                            <p class="text-red-400 text-xs mt-1">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div>
                            <label for="password" class="block text-[11px] font-bold uppercase tracking-wide text-slate-400 mb-1.5">
                                Password Baru
                            </label>

                            <div class="relative">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="w-full px-4 py-2.5 pr-11 bg-white/5 border border-white/10 rounded-xl text-slate-200 placeholder:text-slate-600 text-[13px] outline-none focus:border-[#7c3aed]/50 focus:ring-4 focus:ring-[#7c3aed]/10 transition"
                                    placeholder="Masukkan password baru">

                                <button
                                    type="button"
                                    onclick="togglePassword('password', 'icon-password')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-purple-400 transition">
                                    <i id="icon-password" class="fa-regular fa-eye"></i>
                                </button>
                            </div>

                            <p class="text-[11px] text-slate-500 mt-1">
                                Gunakan minimal 8 karakter agar lebih aman.
                            </p>

                            @error('password')
                            <p class="text-red-400 text-xs mt-1">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div>
                            <label for="password_confirmation" class="block text-[11px] font-bold uppercase tracking-wide text-slate-400 mb-1.5">
                                Konfirmasi Password Baru
                            </label>

                            <div class="relative">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="w-full px-4 py-2.5 pr-11 bg-white/5 border border-white/10 rounded-xl text-slate-200 placeholder:text-slate-600 text-[13px] outline-none focus:border-[#7c3aed]/50 focus:ring-4 focus:ring-[#7c3aed]/10 transition"
                                    placeholder="Ulangi password baru">

                                <button
                                    type="button"
                                    onclick="togglePassword('password_confirmation', 'icon-password-confirmation')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-purple-400 transition">
                                    <i id="icon-password-confirmation" class="fa-regular fa-eye"></i>
                                </button>
                            </div>

                            @error('password_confirmation')
                            <p class="text-red-400 text-xs mt-1">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>

                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 bg-[#7c3aed] text-white px-6 py-2.5 rounded-xl text-[13px] font-bold hover:bg-purple-700 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-[#7c3aed]/30 transition">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Perbarui Password
                    </button>
                </form>
            </div>
        </div>


        {{-- ================= TAB HAPUS AKUN ================= --}}
        <div id="tab-delete-account" class="tab-content hidden">
            <div class="w-full bg-[#1a1828] border border-white/10 rounded-[20px] p-8 shadow-sm">

                <div class="flex items-start gap-3 mb-6">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-red-400 text-sm bg-red-500/10 shrink-0">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>

                    <div>
                        <h2 class="text-base font-bold text-red-400">
                            Hapus Akun
                        </h2>

                        <p class="text-slate-500 text-sm mt-1 leading-relaxed">
                            Setelah akun dihapus, semua data akun akan hilang secara permanen.
                        </p>
                    </div>
                </div>

                <div class="mb-6 rounded-2xl bg-red-500/10 border border-red-500/20 p-4">
                    <div class="flex gap-3">
                        <div class="text-red-400 mt-0.5">
                            <i class="fa-solid fa-circle-info"></i>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-red-300 mb-1">
                                Tindakan ini tidak bisa dibatalkan
                            </h3>

                            <p class="text-xs text-slate-400 leading-relaxed">
                                Pastikan kamu benar-benar ingin menghapus akun ini. Setelah dihapus,
                                data profil, riwayat, dan informasi terkait akun tidak bisa dipulihkan.
                            </p>
                        </div>
                    </div>
                </div>

                <form
                    method="POST"
                    action="{{ route('settings.account.destroy') }}"
                    onsubmit="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan.')">
                    @csrf
                    @method('DELETE')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label for="delete_password" class="block text-[11px] font-bold uppercase tracking-wide text-slate-400 mb-1.5">
                                Masukkan Password untuk Konfirmasi
                            </label>

                            <div class="relative">
                                <input
                                    type="password"
                                    id="delete_password"
                                    name="password"
                                    class="w-full px-4 py-2.5 pr-11 bg-red-500/5 border border-red-500/20 rounded-xl text-slate-200 placeholder:text-slate-600 text-[13px] outline-none focus:border-red-500/50 focus:ring-4 focus:ring-red-500/10 transition"
                                    placeholder="Password kamu">

                                <button
                                    type="button"
                                    onclick="togglePassword('delete_password', 'icon-delete-password')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-500 hover:text-red-400 transition">
                                    <i id="icon-delete-password" class="fa-regular fa-eye"></i>
                                </button>
                            </div>

                            @error('password', 'userDeletion')
                            <p class="text-red-400 text-xs mt-1">
                                {{ $message }}
                            </p>
                            @enderror
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 bg-red-500 text-white px-6 py-2.5 rounded-xl text-[13px] font-bold hover:bg-red-600 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-red-500/20 transition">
                        <i class="fa-regular fa-trash-can"></i>
                        Hapus Akun Saya
                    </button>
                </form>
            </div>
        </div>

    </div>
</main>

@endsection

@push('scripts')
<script>
    const tabs = ['profile', 'password', 'notifications', 'delete-account'];

    function switchTab(name) {
        tabs.forEach(t => {
            const content = document.getElementById('tab-' + t);
            const btn = document.getElementById('tab-btn-' + t);

            if (content) {
                content.classList.add('hidden');
                content.classList.remove('block');
            }

            if (btn) {
                btn.classList.remove(
                    'text-purple-400',
                    'text-red-400',
                    'border-primary',
                    'border-red-500'
                );

                btn.classList.add(
                    'text-slate-500',
                    'border-transparent'
                );
            }
        });

        const activeContent = document.getElementById('tab-' + name);
        const activeBtn = document.getElementById('tab-btn-' + name);

        if (activeContent) {
            activeContent.classList.remove('hidden');
            activeContent.classList.add('block');
        }

        if (activeBtn) {
            activeBtn.classList.remove(
                'text-slate-500',
                'border-transparent'
            );

            if (name === 'delete-account') {
                activeBtn.classList.add(
                    'text-red-400',
                    'border-red-500'
                );
            } else {
                activeBtn.classList.add(
                    'text-purple-400',
                    'border-primary'
                );
            }
        }

        history.replaceState(null, '', '#' + name);

        return false;
    }

    function previewAvatar(e) {
        const file = e.target.files[0];

        if (!file) return;

        const reader = new FileReader();

        reader.onload = ev => {
            document.getElementById('avatar-wrap').innerHTML =
                `<img src="${ev.target.result}" class="w-full h-full object-cover" alt="Preview Foto">`;
        };

        reader.readAsDataURL(file);
    }

    document.addEventListener('DOMContentLoaded', () => {
        const hash = location.hash.replace('#', '');
        const sessionTab = '{{ session("tab", "") }}';

        const active = tabs.includes(hash) ?
            hash :
            (tabs.includes(sessionTab) ? sessionTab : 'profile');

        switchTab(active);
    });
</script>
@endpush