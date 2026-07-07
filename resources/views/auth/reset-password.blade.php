@extends('layouts.guest')

@section('title', 'CLEARN ", Atur Ulang Kata Sandi')

@section('content')
<div class="bg-white dark:bg-[#1A1625] border border-slate-100 dark:border-white/5 p-8 rounded-3xl shadow-2xl w-full">
    <div class="mb-8 text-center sm:text-left">
        <h2 class="text-slate-800 dark:text-white text-xl font-black tracking-tight mb-2">Atur Ulang Kata Sandi</h2>
        <p class="text-xs font-medium leading-relaxed text-slate-500 dark:text-slate-400">
            {{ __('Silakan buat kata sandi baru untuk akun Anda. Pastikan kata sandi baru Anda kuat dan aman.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Alamat Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-envelope text-xs"></i>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus readonly
                    class="block w-full pl-11 pr-4 py-3.5 text-sm bg-slate-100 dark:bg-[#111116] text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-white/10 rounded-xl focus:outline-none cursor-not-allowed font-medium">
            </div>
            @error('email')
            <p class="text-xs text-rose-500 font-bold mt-2 ml-1 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
            </p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Kata Sandi Baru</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-lock text-xs"></i>
                </span>
                <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Masukkan kata sandi baru"
                    class="block w-full pl-11 pr-12 py-3.5 text-sm bg-slate-50 dark:bg-[#1A1625] text-slate-800 dark:text-white border border-slate-200 dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#A487F8]/50 focus:border-[#A487F8] transition-all font-medium placeholder:text-slate-400">
                <i class="fas fa-eye password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer" data-target="password"></i>
            </div>
            @error('password')
            <p class="text-xs text-rose-500 font-bold mt-2 ml-1 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
            </p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Konfirmasi Kata Sandi</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-lock text-xs"></i>
                </span>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi baru"
                    class="block w-full pl-11 pr-12 py-3.5 text-sm bg-slate-50 dark:bg-[#1A1625] text-slate-800 dark:text-white border border-slate-200 dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#A487F8]/50 focus:border-[#A487F8] transition-all font-medium placeholder:text-slate-400">
                <i class="fas fa-eye password-toggle absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 cursor-pointer" data-target="password_confirmation"></i>
            </div>
            @error('password_confirmation')
            <p class="text-xs text-rose-500 font-bold mt-2 ml-1 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
            </p>
            @enderror
        </div>

        <div class="flex flex-col gap-4 pt-4">
            <button type="submit"
                class="w-full flex items-center justify-center px-4 py-3.5 bg-[#A487F8] text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-[#A487F8]/20 hover:bg-[#8B6FE8] hover:-translate-y-0.5 active:translate-y-0 transition-all gap-2">
                Simpan Kata Sandi <i class="fa-solid fa-check"></i>
            </button>
        </div>
    </form>
</div>
@endsection
