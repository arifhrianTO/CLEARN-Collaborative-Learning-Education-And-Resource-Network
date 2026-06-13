@extends('layouts.dashboard')

@section('title', 'Laporan Keuangan | Dashboard Mentor | Clearn - Platform Pembelajaran Online')

@section('content')

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}" />

<main class="flex-1 p-6 pt-10">
    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <header class="mb-6">
            <h1 class="text-xl font-bold">Laporan Keuangan</h1>
            <p class="text-[11px] text-slate-500">
                Monitor pendapatan kursus dan riwayat transaksi wallet mentor.
            </p>
        </header>

        {{-- Statistik Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

            {{-- Total Pemasukan --}}
            <div class="card-bg p-4">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Total Pemasukan
                    </span>

                    <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center text-emerald-500 text-xs">
                        <i class="fas fa-arrow-down"></i>
                    </div>
                </div>

                <h3 class="text-xl font-extrabold">
                    Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}
                </h3>
            </div>

            {{-- Total Pengeluaran --}}
            <div class="card-bg p-4">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Total Pengeluaran
                    </span>

                    <div class="w-8 h-8 rounded-lg bg-red-500/10 flex items-center justify-center text-red-500 text-xs">
                        <i class="fas fa-arrow-up"></i>
                    </div>
                </div>

                <h3 class="text-xl font-extrabold">
                    Rp {{ number_format($totalPengeluaran ?? 0, 0, ',', '.') }}
                </h3>
            </div>

            {{-- Net Profit --}}
            <div class="card-bg p-4">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                        Saldo Bersih
                    </span>

                    <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500 text-xs">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                </div>

                <h3 class="text-xl font-extrabold {{ ($netProfit ?? 0) < 0 ? 'text-red-500' : 'text-emerald-500' }}">
                    Rp {{ number_format($netProfit ?? 0, 0, ',', '.') }}
                </h3>
            </div>

        </div>

        {{-- Tabel Transaksi Gabungan --}}
        <div class="card-bg overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-sm">Riwayat Arus Kas</h2>
                    <p class="text-[10px] text-slate-500">
                        Data pemasukan dari pembelian kursus dan pengeluaran dari wallet mentor.
                    </p>
                </div>

                <button class="text-[8px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50 dark:bg-white/5 px-3 py-1 rounded-md border border-slate-100 dark:border-white/5 hover:border-primary transition-colors">
                    Download Report
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-white/5 text-slate-400 uppercase text-[8px] font-black tracking-widest">
                            <th class="p-3.5 pl-5">ID Transaksi</th>
                            <th class="p-3.5">Deskripsi</th>
                            <th class="p-3.5">Jenis</th>
                            <th class="p-3.5">Nominal</th>
                            <th class="p-3.5">Tanggal</th>
                            <th class="p-3.5 pr-5 text-right">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-white/5 text-[11px] font-medium">

                        @forelse ($cashFlows ?? [] as $cashFlow)
                        <tr class="hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">

                            <td class="p-3.5 pl-5 font-mono text-slate-400">
                                {{ $cashFlow['id_transaksi'] ?? '-' }}
                            </td>

                            <td class="p-3.5 font-bold">
                                {{ $cashFlow['deskripsi'] ?? '-' }}
                            </td>

                            <td class="p-3.5">
                                @if (($cashFlow['jenis'] ?? '') === 'PEMASUKAN')
                                <span class="px-2 py-0.5 bg-emerald-500/10 text-emerald-500 text-[8px] font-black uppercase rounded-md">
                                    Pemasukan
                                </span>
                                @elseif (($cashFlow['jenis'] ?? '') === 'PENGELUARAN')
                                <span class="px-2 py-0.5 bg-red-500/10 text-red-500 text-[8px] font-black uppercase rounded-md">
                                    Pengeluaran
                                </span>
                                @elseif (($cashFlow['jenis'] ?? '') === 'CREDIT')
                                <span class="px-2 py-0.5 bg-blue-500/10 text-blue-500 text-[8px] font-black uppercase rounded-md">
                                    Credit
                                </span>
                                @else
                                <span class="px-2 py-0.5 bg-slate-500/10 text-slate-500 text-[8px] font-black uppercase rounded-md">
                                    {{ $cashFlow['jenis'] ?? 'Transaksi' }}
                                </span>
                                @endif
                            </td>

                            <td class="p-3.5 font-bold {{ ($cashFlow['jenis'] ?? '') === 'PEMASUKAN' ? 'text-emerald-500' : 'text-red-500' }}">
                                {{ ($cashFlow['jenis'] ?? '') === 'PEMASUKAN' ? '+' : '-' }}
                                Rp {{ number_format($cashFlow['nominal'] ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="p-3.5 text-slate-400">
                                {{ !empty($cashFlow['tanggal']) ? $cashFlow['tanggal']->format('d M Y') : '-' }}
                            </td>

                            <td class="p-3.5 pr-5 text-right">
                                {{ ucfirst($cashFlow['status'] ?? '-') }}
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-slate-400 text-xs">
                                Belum ada data transaksi.
                            </td>
                        </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

@endsection