@extends('layouts.auth')

@section('title', 'CLEARN │ Masuk')

@section('content')
<div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">

    {{-- LEFT --}}
    <div class="flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">

            <img
                src="{{ asset('images/logo-light.png') }}"
                alt="logo"
                class="w-40 h-27 mb-2 object-contain dark:hidden">

            <img
                src="{{ asset('images/logo-dark.png') }}"
                alt="logo"
                class="w-40 h-27 mb-2 object-contain hidden dark:block">

            <h1 class="text-3xl font-bold mb-8">Selamat Datang Kembali</h1>

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 flex items-start gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-500 mt-0.5"></i>
                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-semibold">{{ session('success') }}</p>
                </div>
                @endif

                @if (session('status'))
                <div class="p-4 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 flex items-start gap-3">
                    <i class="fa-solid fa-info-circle text-blue-500 mt-0.5"></i>
                    <p class="text-xs text-blue-600 dark:text-blue-400 font-semibold">{{ session('status') }}</p>
                </div>
                @endif

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
                        placeholder="contoh@email.com"
                        class="w-full px-4 py-3 rounded-xl outline-none
                            bg-slate-50 dark:bg-[#1A1625]
                            border border-slate-200 dark:border-slate-700
                            focus:ring-2 focus:ring-primary/40 transition">
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-300">
                        Kata Sandi
                    </label>

                    <div class="relative">
                        <input type="password" name="password"
                            placeholder="Masukkan kata sandi"
                            class="w-full px-4 py-3 pr-12 rounded-xl outline-none
                                bg-slate-50 dark:bg-[#1A1625]
                                border border-slate-200 dark:border-slate-700
                                focus:ring-2 focus:ring-primary/40 transition">

                        <i class="fas fa-eye password-toggle absolute right-4 top-1/2 -translate-y-1/2
                                text-slate-400 hover:text-slate-600 cursor-pointer"
                            data-target="password"></i>
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center gap-2 text-slate-600 dark:text-slate-400">
                        <input type="checkbox" name="remember" id="remember"
                            class="rounded border-slate-300 text-primary">
                        <span>Ingat saya</span>
                    </label>

                    <a href="{{ route('password.request') }}"
                        class="text-primary font-semibold hover:underline">
                        Lupa kata sandi?
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
            bg-slate-100 dark:bg-[#1A1625] border-l border-slate-200 dark:border-slate-800">
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