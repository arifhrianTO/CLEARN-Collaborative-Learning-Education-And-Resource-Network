@extends('layouts.guest')

@section('title', 'CLEARN │ Lupa Password')

@section('content')
<div class="bg-white dark:bg-[#161525] border border-slate-100 dark:border-white/5 p-8 rounded-3xl shadow-2xl w-full">
    <div class="mb-8 text-center sm:text-left">
        <h2 class="text-slate-800 dark:text-white text-xl font-black tracking-tight mb-2">Lupa Password?</h2>
        <p class="text-xs font-medium leading-relaxed text-slate-500 dark:text-slate-400">
            {{ __('Jangan khawatir. Masukkan alamat email Anda di bawah ini, dan kami akan mengirimkan tautan untuk mereset password Anda.') }}
        </p>
    </div>

    @if (session('status'))
    <div class="mb-6 text-xs font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 dark:text-emerald-400 dark:bg-emerald-500/10 dark:border-emerald-500/20 p-4 rounded-xl flex items-start gap-3">
        <i class="fa-solid fa-check-circle mt-0.5"></i>
        <span>{{ session('status') }}</span>
    </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <div>
            <label for="email" class="block text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest mb-2 ml-1">Alamat Email Terdaftar</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 dark:text-slate-500">
                    <i class="fa-solid fa-envelope text-xs"></i>
                </span>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com"
                    class="block w-full pl-11 pr-4 py-3.5 text-sm bg-slate-50 dark:bg-[#0f0a19] text-slate-800 dark:text-white border border-slate-200 dark:border-white/10 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#A487F8]/50 focus:border-[#A487F8] transition-all font-medium placeholder:text-slate-400">
            </div>
            @error('email')
            <p class="text-xs text-rose-500 font-bold mt-2 ml-1 flex items-center gap-1.5">
                <i class="fa-solid fa-circle-exclamation"></i> {{ $message }}
            </p>
            @enderror
        </div>

        <div class="flex flex-col gap-4 pt-2">
            <button type="submit"
                class="w-full flex items-center justify-center px-4 py-3.5 bg-[#A487F8] text-white text-xs font-bold uppercase tracking-widest rounded-xl shadow-lg shadow-[#A487F8]/20 hover:bg-[#8B6FE8] hover:-translate-y-0.5 active:translate-y-0 transition-all gap-2">
                Kirim Tautan Reset <i class="fa-solid fa-arrow-right"></i>
            </button>

            <a href="{{ route('login') }}" class="text-center text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-[#A487F8] dark:hover:text-[#A487F8] transition-colors py-2 flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-left"></i> Kembali ke Halaman Login
            </a>
        </div>
    </form>
</div>
@endsection