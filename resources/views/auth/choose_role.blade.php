@extends('layouts.auth')

@section('title', 'Pilih Role – Clearn')

@section('bodyClass', 'min-h-screen bg-slate-50 text-slate-900 dark:bg-[#0f0a19] dark:text-white transition-colors duration-300 font-sans flex items-center justify-center p-4')

@push('styles')
<style>
    .role-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .role-card:hover {
        transform: translateY(-5px);
    }
</style>
@endpush

@section('content')
<div class="max-w-3xl w-full text-center">

    <div class="flex justify-center mb-1">
        <div class="mb-1">
            <img src="{{ asset('images/logo.png') }}" alt="logo" class="w-20 h-20 object-contain">
        </div>
    </div>

    <h1 class="text-3xl font-bold mb-2 tracking-tight">Daftar Sebagai</h1>
    <p class="text-slate-500 dark:text-slate-400 text-sm mb-12 max-w-md mx-auto font-medium">
        Mulai perjalanan belajar Anda bersama kami.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 justify-items-center">

        {{-- Card: Pengajar --}}
        <div class="role-card bg-white dark:bg-[#1c1826] p-8 rounded-[2rem] flex flex-col border border-slate-200 dark:border-white/5 shadow-sm">
            <div class="flex flex-col items-start text-left flex-grow">
                <div class="w-11 h-11 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-lg mb-4">
                    <i class="fas fa-chalkboard-teacher"></i>
                </div>
                <h3 class="text-lg font-bold mb-1">Pengajar</h3>
                <p class="text-slate-500 dark:text-slate-500 text-xs leading-relaxed mb-6 min-h-[40px]">
                    Buat kursus, kelola siswa, dan pantau penghasilan Anda.
                </p>
            </div>
            <a href="{{ route('register.mentor') }}" class="w-full py-2.5 bg-primary text-white text-sm font-bold rounded-xl text-center hover:brightness-110 transition-all">
                Lanjut sebagai Pengajar
            </a>
        </div>

        {{-- Card: Pelajar --}}
        <div class="role-card bg-white dark:bg-[#1c1826] p-8 rounded-[2rem] flex flex-col border border-slate-200 dark:border-white/5 shadow-sm">
            <div class="flex flex-col items-start text-left flex-grow">
                <div class="w-11 h-11 bg-primary/10 text-primary rounded-xl flex items-center justify-center text-lg mb-4">
                    <i class="fas fa-user-graduate"></i>
                </div>
                <h3 class="text-lg font-bold mb-1">Pelajar</h3>
                <p class="text-slate-500 dark:text-slate-500 text-xs leading-relaxed mb-6 min-h-[40px]">
                    Daftar kursus, belajar materi baru, dan raih sertifikat.
                </p>
            </div>
            <a href="{{ route('register.student') }}" class="w-full py-2.5 bg-primary text-white text-sm font-bold rounded-xl text-center hover:brightness-110 transition-all">
                Lanjut sebagai Pelajar
            </a>
        </div>

    </div>
</div>
@endsection