@extends('layouts.dashboard')

@section('title', 'CLEARN │ Riwayat')

@section('content')

<x-dashboard.sidebar
    role="student"
    name="{{ auth()->user()->name ?? 'User Pelajar' }}"
    initials="{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}"
    photo="{{ auth()->user()->profile_picture }}"
    active="history" />

<main class="flex-1 p-6 lg:p-10 transition-colors duration-300 dark:bg-[#0F0B1A] bg-slate-50">
    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <h1 class="text-2xl font-black dark:text-white text-slate-800 tracking-tighter mb-1">Riwayat Pesanan</h1>
            <p class="text-[12px] dark:text-slate-500 text-slate-400 font-medium">Pantau semua transaksi dan akses kursus yang telah Anda beli.</p>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                    <i class="fa-solid fa-box"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">{{ $totalOrders }}</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">Total Pesanan</span>
                </div>
            </div>

            <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800 text-nowrap">Rp{{ number_format($totalSpent, 0, ',', '.') }}</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">Pengeluaran</span>
                </div>
            </div>

            <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                    <i class="fa-solid fa-graduation-cap"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">{{ $activeCourses }}</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">Kursus Aktif</span>
                </div>
            </div>
        </div>

        {{-- List Transaksi --}}
        <div class="space-y-4">
            @forelse($payments as $payment)
            @php
                $thumb = $payment->enrollment->course->course_thumbnail
                    ? (Str::startsWith($payment->enrollment->course->course_thumbnail, 'http')
                        ? $payment->enrollment->course->course_thumbnail
                        : Storage::url($payment->enrollment->course->course_thumbnail))
                    : 'https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=400';
            @endphp
            <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden shadow-sm group">
                <div class="px-5 py-3 border-b dark:border-white/5 border-slate-100 flex flex-wrap justify-between items-center gap-4 dark:bg-[#1A1625]/80 bg-slate-50/50">
                    <div class="flex gap-8">
                        <div>
                            <p class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-wider mb-0.5">ID Pesanan</p>
                            <p class="text-[12px] font-black tracking-tight dark:text-white text-slate-800">{{ $payment->midtrans_order_id }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-wider mb-0.5">Tanggal</p>
                            <p class="text-[12px] font-bold dark:text-white text-slate-800">{{ $payment->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($payment->connection_status === 'success' || $payment->connection_status === 'settlement')
                            <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-green-500/10 text-green-500 border border-green-500/20">Berhasil</span>
                        @elseif($payment->connection_status === 'pending')
                            <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-yellow-500/10 text-yellow-500 border border-yellow-500/20">Menunggu</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-red-500/10 text-red-500 border border-red-500/20">{{ ucfirst($payment->connection_status) }}</span>
                        @endif
                    </div>
                </div>

                <div class="p-5 flex flex-col md:flex-row gap-6">
                    <div class="w-full md:w-36 shrink-0">
                        <div class="aspect-video rounded-xl overflow-hidden dark:bg-[#0F0B1A] bg-slate-100 border dark:border-white/5 border-slate-200">
                            <img src="{{ $thumb }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <h3 class="font-extrabold text-[15px] leading-tight tracking-tight dark:text-white text-slate-800">{{ $payment->enrollment->course->course_title }}</h3>
                            <p class="text-[15px] font-black tracking-tight text-primary">Rp{{ number_format($payment->gross_amount, 0, ',', '.') }}</p>
                        </div>
                        <p class="text-[10px] dark:text-slate-500 text-slate-400 mb-5 font-bold tracking-widest uppercase">{{ $payment->enrollment->course->mentor->name ?? 'Pengajar Clearn' }}</p>

                        <div class="flex gap-2">
                            @if($payment->connection_status === 'success' || $payment->connection_status === 'settlement')
                                <a href="{{ route('student.course.lesson', $payment->enrollment->course->course_slug) }}" class="inline-block bg-primary text-white text-[10px] font-extrabold px-5 py-2.5 rounded-xl shadow-lg shadow-primary/20 hover:brightness-110 active:scale-95 transition-all uppercase tracking-widest">Mulai Belajar</a>
                            @endif
                            <a href="{{ route('student.course.show', $payment->enrollment->course->course_slug) }}" class="inline-block border dark:border-white/5 border-slate-200 dark:text-slate-400 text-slate-500 text-[10px] font-extrabold px-5 py-2.5 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 active:scale-95 transition-all uppercase tracking-widest">Detail</a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl p-10 text-center">
                <div class="w-16 h-16 dark:bg-[#0F0B1A] bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 dark:text-slate-500 text-slate-400">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
                <h3 class="text-lg font-bold dark:text-white text-slate-800 mb-2">Belum ada transaksi</h3>
                <p class="text-sm dark:text-slate-500 text-slate-400 mb-6">Anda belum pernah melakukan pembelian kursus apapun.</p>
                <a href="{{ route('course') }}" class="inline-block bg-primary text-white px-6 py-2 rounded-xl text-sm font-bold shadow-lg shadow-primary/20 hover:brightness-110 active:scale-95 transition-all">Cari Kursus</a>
            </div>
            @endforelse

            <div class="mt-6">
                {{ $payments->links() }}
            </div>
        </div>
    </div>
</main>

@endsection
