@extends('layouts.dashboard')

@section('title', 'CLEARN │ Keuangan')

@section('content')

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}" />

<style>
    .grid-pattern {
        background-image: radial-gradient(circle, currentColor 1px, transparent 1px);
        background-size: 30px 30px;
    }
</style>

<main class="flex-1 p-5 lg:p-8">
    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <header class="mb-8 animate-fade-down">
            <div>
                <h1 class="text-2xl font-abold tracking-tight">Pendapatan Mentor</h1>
                <p class="text-slate-500 dark:text-gray-400 text-xs mt-0.5 font-medium">
                    Pantau total penjualan kursus Anda dan status keuangan.
                </p>
            </div>
        </header>

        {{-- Grid Statistik Ringkas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            {{-- Card 1: Total Gross Revenue --}}
            <div class="bg-white dark:bg-[#1A1625]/50 backdrop-blur-xl rounded-3xl border border-gray-100 dark:border-gray-800/80 p-6 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Penjualan</span>
                    <div class="w-8 h-8 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500">
                        <i class="fas fa-wallet text-sm"></i>
                    </div>
                </div>

                <h3 class="text-2xl font-black tracking-tight mb-1">
                    Rp {{ number_format($totalGross ?? 0, 0, ',', '.') }}
                </h3>

                <p class="text-slate-400 dark:text-gray-500 text-[10px] font-medium">
                    Akumulasi seluruh transaksi bruto
                </p>
            </div>

            {{-- Card 2: Net Platform Share --}}
            <div class="bg-white dark:bg-[#1A1625]/50 backdrop-blur-xl rounded-3xl border border-gray-100 dark:border-gray-800/80 p-6 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-[#A487F8] uppercase tracking-wider">Pendapatan Bersih (80%)</span>
                    <div class="w-8 h-8 rounded-xl bg-[#A487F8]/10 flex items-center justify-center text-[#A487F8]">
                        <i class="fas fa-chart-line text-sm"></i>
                    </div>
                </div>

                <h3 class="text-2xl font-black tracking-tight text-[#A487F8] mb-1">
                    Rp {{ number_format($totalNet ?? 0, 0, ',', '.') }}
                </h3>

                <p class="text-slate-400 dark:text-gray-500 text-[10px] font-medium">
                    Pendapatan bersih milik Anda
                </p>
            </div>

            {{-- Card 3: Pending Payouts --}}
            <div class="bg-white dark:bg-[#1A1625]/50 backdrop-blur-xl rounded-3xl border border-gray-100 dark:border-gray-800/80 p-6 shadow-sm hover:shadow-md transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-xs font-bold text-amber-500 uppercase tracking-wider">Menunggu Penarikan</span>
                    <div class="w-8 h-8 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-500">
                        <i class="fas fa-clock text-sm"></i>
                    </div>
                </div>

                <h3 class="text-2xl font-black tracking-tight text-amber-500 mb-1">
                    Rp {{ number_format($pendingPayouts ?? 0, 0, ',', '.') }}
                </h3>

                <p class="text-slate-400 dark:text-gray-500 text-[10px] font-medium">
                    Dana instruktur belum dicairkan
                </p>
            </div>

        </div>

        {{-- Tabel Riwayat Transaksi --}}
        <div class="bg-white dark:bg-[#1A1625]/50 backdrop-blur-xl rounded-3xl border border-gray-100 dark:border-gray-800/80 p-1.5 shadow-sm">
            <div class="p-5 flex items-center justify-between border-b border-gray-100 dark:border-gray-800/60">
                <h2 class="text-md font-bold tracking-tight">Aktivitas Transaksi Terbaru</h2>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider bg-slate-50 dark:bg-[#1A1625] px-3 py-1 rounded-lg border border-gray-100 dark:border-gray-800/50">
                    Real-time
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-50 dark:border-gray-800 text-slate-400 uppercase text-[9px] font-black tracking-widest">
                            <th class="p-4 pl-6">ID Transaksi</th>
                            <th class="p-4">Instruktur / Pembeli</th>
                            <th class="p-4">Jenis</th>
                            <th class="p-4">Nominal</th>
                            <th class="p-4">Potongan (20%)</th>
                            <th class="p-4 pr-6 text-right">Status</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-50 dark:divide-gray-800/40 text-xs font-medium">

                        @forelse ($transactions ?? [] as $transaction)

                        @php
                        $status = strtolower($transaction['status'] ?? 'pending');
                        $type = $transaction['type'] ?? 'Pembelian';

                        $isSuccess = in_array($status, ['success', 'settlement', 'paid', 'sukses']);
                        $isPending = $status === 'pending';
                        $isFailed = in_array($status, ['failed', 'cancel', 'expire', 'rejected']);

                        if ($isSuccess) {
                        $statusTextClass = 'text-emerald-500';
                        $statusDotClass = 'bg-emerald-500';
                        $statusLabel = 'Sukses';
                        } elseif ($isPending) {
                        $statusTextClass = 'text-amber-500';
                        $statusDotClass = 'bg-amber-500';
                        $statusLabel = 'Pending';
                        } elseif ($isFailed) {
                        $statusTextClass = 'text-red-500';
                        $statusDotClass = 'bg-red-500';
                        $statusLabel = ucfirst($status);
                        } else {
                        $statusTextClass = 'text-slate-500';
                        $statusDotClass = 'bg-slate-500';
                        $statusLabel = ucfirst($status);
                        }

                        if ($type === 'Penarikan') {
                        $typeBadgeClass = 'bg-amber-500/10 text-amber-500';
                        } else {
                        $typeBadgeClass = 'bg-emerald-500/10 text-emerald-500';
                        }
                        @endphp

                        <tr class="hover:bg-slate-50/50 dark:hover:bg-[#0f0a19]/40 transition-colors">
                            <td class="p-4 pl-6 font-mono text-slate-400 text-[11px]">
                                {{ $transaction['id'] ?? '-' }}
                            </td>

                            <td class="p-4">
                                <div class="font-bold text-slate-800 dark:text-slate-200">
                                    {{ $transaction['name'] ?? 'Pembeli' }}
                                </div>
                                <div class="text-[10px] text-slate-400">
                                    {{ $transaction['description'] ?? '-' }}
                                </div>
                            </td>

                            <td class="p-4">
                                <span class="px-2.5 py-1 {{ $typeBadgeClass }} text-[9px] font-black uppercase rounded-lg">
                                    {{ $type }}
                                </span>
                            </td>

                            <td class="p-4 font-bold text-slate-800 dark:text-slate-200">
                                Rp {{ number_format($transaction['amount'] ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="p-4 text-red-500 font-bold">
                                @if (($transaction['platform_fee'] ?? 0) > 0)
                                -Rp {{ number_format($transaction['platform_fee'], 0, ',', '.') }}
                                @else
                                -
                                @endif
                            </td>

                            <td class="p-4 pr-6 text-right">
                                <span class="inline-flex items-center gap-1 {{ $statusTextClass }} font-bold text-[10px]">
                                    <span class="w-1.5 h-1.5 {{ $statusDotClass }} rounded-full {{ $isPending ? 'animate-pulse' : '' }}"></span>
                                    {{ $statusLabel }}
                                </span>
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="6" class="p-8 text-center text-slate-400 text-xs font-bold">
                                Belum ada transaksi.
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