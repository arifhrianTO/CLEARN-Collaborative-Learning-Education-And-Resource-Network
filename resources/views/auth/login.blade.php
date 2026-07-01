@extends('layouts.auth')

@section('title', 'Masuk ke situs – Clearn - Platform Pembelajaran Online')

@section('content')
<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    {{-- LEFT --}}
    <div class="flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">

            <img
                src="{{ asset('images/logo-light.png') }}"
                alt="logo"
                class="w-40 h-40 mb-8 object-contain dark:hidden">

            <img
                src="{{ asset('images/logo-dark.png') }}"
                alt="logo"
                class="w-40 h-40 mb-8 object-contain hidden dark:block">

            <h1 class="text-3xl font-bold mb-8">Selamat Datang Kembali</h1>

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                @if ($errors->any())
                <div class="p-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20">
                    @foreach ($errors->all() as $error)
                    <p class="text-xs text-red-500 font-semibold">{{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <div>
                    <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">
                        Alamat Email
                    </label>

                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-4 py-3 rounded-xl outline-none
                            bg-slate-50 dark:bg-slate-800
                            border border-slate-200 dark:border-slate-700
                            focus:ring-2 focus:ring-primary/40 transition">

                    @error('email')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">
                        Kata Sandi
                    </label>

                    <div class="relative">
                        <input type="password" name="password"
                            class="w-full px-4 py-3 pr-12 rounded-xl outline-none
                                bg-slate-50 dark:bg-slate-800
                                border border-slate-200 dark:border-slate-700
                                focus:ring-2 focus:ring-primary/40 transition">

                        <i class="fas fa-eye password-toggle absolute right-4 top-1/2 -translate-y-1/2
                                text-slate-400 hover:text-slate-600 cursor-pointer"
                            data-target="password"></i>
                    </div>

                    @error('password')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                        <input type="checkbox" name="remember" id="remember"
                            class="rounded border-slate-300 text-primary">
                        <span>Ingat saya</span>
                    </label>

                    <a href="{{ route('password.request') }}"
                        class="text-primary font-semibold hover:underline">
                        Lupa sandi?
                    </a>
                </div>

                <button type="submit"
                    class="w-full py-3 rounded-xl font-bold text-white bg-primary hover:opacity-90 transition">
                    Masuk
                </button>
            </form>

            <p class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
                Belum punya akun?
                <a href="{{ route('choose_role') }}" class="text-primary font-bold hover:underline">
                    Daftar Gratis
                </a>
            </p>

        </div>
    </div>

    {{-- RIGHT --}}
    <div class="hidden lg:flex items-center justify-center p-12
            bg-slate-100 dark:bg-slate-900 border-l border-slate-200 dark:border-slate-800">
        <div class="max-w-lg text-center">
            <div class="w-24 h-24 mx-auto mb-8 rounded-3xl
                    bg-primary flex items-center justify-center text-4xl text-white">
                🎓
            </div>

            <h2 class="text-4xl font-extrabold mb-4">
                Lanjutkan <br> Perjalanan Belajarmu
            </h2>

            <p class="text-slate-600 dark:text-slate-400 text-lg">
                Akses kursusmu, pantau progres, dan capai tujuan belajarmu.
            </p>
        </div>
    </div>

</div>
@endsection