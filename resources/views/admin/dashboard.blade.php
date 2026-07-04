@extends('layouts.dashboard')

@section('title', 'CLEARN │ Dashboard Admin')

@section('content')

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}" />

<main class="flex-1 p-5 lg:p-8">
    <div class="max-w-5xl mx-auto">

        <div class="mb-6">
            <h1 class="text-lg font-bold">Dasboard Admin</h1>
            <p class="text-[10px] text-slate-500">Selamat datang kembali, berikut adalah ringkasan data platform hari ini.</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="card-bg p-4">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary mb-3 text-xs">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h3 class="text-xl font-extrabold">{{ $totalPelajar ?? 0 }}</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Jumlah Pelajar</p>
            </div>

            <div class="card-bg p-4">
                <div class="w-8 h-8 rounded-lg bg-primary/10 flex items-center justify-center text-primary mb-3 text-xs">
                    <i class="fa-solid fa-user-graduate"></i>
                </div>
                <h3 class="text-xl font-extrabold">{{ $totalMentor ?? 0 }}</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Jumlah Mentor</p>
            </div>

            <div class="card-bg p-4">
                <div class="w-8 h-8 rounded-lg bg-[#A487F8]/10 flex items-center justify-center text-[#A487F8] mb-3 text-xs">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
                <h3 class="text-xl font-extrabold">{{ $totalKursus ?? 0 }}</h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Jumlah Kursus</p>
            </div>

            <div class="card-bg p-4">
                <div class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center text-green-500 mb-3 text-xs">
                    <i class="fa-solid fa-wallet"></i>
                </div>
                <h3 class="text-xl font-extrabold">
                    Rp {{ number_format($totalPemasukan ?? 0, 0, ',', '.') }}
                </h3>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Pemasukan Total</p>
            </div>
        </div>

        <div class="card-bg p-5">
            <div class="flex justify-between items-center mb-4">
                <div>
                    <h4 class="font-bold text-sm">Penjualan berdasarkan Kategori</h4>
                    <p class="text-[10px] text-slate-500">Rincian Pembelian berdasarkan jenis kursus</p>
                </div>
            </div>
            <div class="chart-container" style="position: relative; height: 250px; width: 100%;">
                <canvas id="barChart"></canvas>
            </div>
        </div>

    </div>
</main>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chartElement = document.getElementById('barChart');

        if (!chartElement) {
            return;
        }

        const ctxBar = chartElement.getContext('2d');

        const categoryLabels = @json($categoryLabels ?? []);
        const categorySales = @json($categorySales ?? []);

        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: categoryLabels,
                datasets: [{
                    label: 'Jumlah Penjualan',
                    data: categorySales,
                    backgroundColor: '#A487F8',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            color: '#94a3b8',
                            font: {
                                size: 10
                            }
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw ?? 0;
                                return value + ' penjualan';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#94a3b8',
                            font: {
                                size: 10
                            },
                            callback: function(value) {
                                return value + 'x';
                            }
                        },
                        grid: {
                            color: 'rgba(148, 163, 184, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush

@endsection