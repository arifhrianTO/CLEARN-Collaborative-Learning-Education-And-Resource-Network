@extends('layouts.dashboard')

@section('title', 'CLEARN │ Dashboard Pengajar')

@section('content')

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}" />

<main class="flex-1 p-8 pt-10">
    <div class="max-w-6xl mx-auto">

        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold">Dashboard Pengajar</h1>
                <p class="text-sm text-slate-500">
                    Kelola kursus Anda dan pantau kinerja Anda
                </p>
            </div>

            @if(auth()->user()->status === 'pending')
            <button onclick="showPendingAlert()"
                class="bg-primary text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 text-xs transition hover:scale-105 shadow-lg shadow-primary/20 inline-flex cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Buat Kursus Baru</span>
            </button>
            @elseif(auth()->user()->status === 'rejected')
            <button onclick="showRejectedAlert()"
                class="bg-primary text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 text-xs transition hover:scale-105 shadow-lg shadow-primary/20 inline-flex cursor-pointer">
                <i class="fa-solid fa-plus"></i>
                <span>Buat Kursus Baru</span>
            </button>
            @else
            <a href="{{ route('mentor.courses.create') }}"
                class="bg-primary text-white px-5 py-2.5 rounded-xl font-bold flex items-center gap-2 text-xs transition hover:scale-105 shadow-lg shadow-primary/20 inline-flex">
                <i class="fa-solid fa-plus"></i>
                <span>Buat Kursus Baru</span>
            </a>
            @endif
        </div>

        {{-- Statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">

            {{-- Total Pendapatan --}}
            <div class="card-bg p-5">
                <div class="w-8 h-8 rounded-lg bg-green-500/10 flex items-center justify-center text-green-500 mb-3 text-sm">
                    <i class="fa-solid fa-dollar-sign"></i>
                </div>

                <h3 class="text-xl font-bold">
                    Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                </h3>

                <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">
                    Total Pendapatan
                </p>
            </div>

            {{-- Total Pelajar --}}
            <div class="card-bg p-5">
                <div class="w-8 h-8 rounded-lg bg-blue-500/10 flex items-center justify-center text-blue-500 mb-3 text-sm">
                    <i class="fa-solid fa-users"></i>
                </div>

                <h3 class="text-xl font-bold">
                    {{ $totalPelajar ?? 0 }}
                </h3>

                <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">
                    Total Pelajar
                </p>
            </div>

            {{-- Kursus Aktif --}}
            <div class="card-bg p-5">
                <div class="w-8 h-8 rounded-lg bg-[#A487F8]/10 flex items-center justify-center text-[#A487F8] mb-3 text-sm">
                    <i class="fa-solid fa-book-open"></i>
                </div>

                <h3 class="text-xl font-bold">
                    {{ $kursusAktif ?? 0 }}
                </h3>

                <p class="text-[10px] font-bold text-slate-400 uppercase mt-1">
                    Kursus Aktif
                </p>
            </div>

        </div>

        {{-- Chart --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

            <div class="card-bg p-6">
                <h4 class="font-bold text-sm mb-6">Ringkasan Pendapatan</h4>
                <div class="chart-container">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <div class="card-bg p-6">
                <h4 class="font-bold text-sm mb-6">Pendaftaran Peserta</h4>
                <div class="chart-container">
                    <canvas id="enrollmentChart"></canvas>
                </div>
            </div>

        </div>

    </div>
</main>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const chartLabels = @json($chartLabels ?? []);
        const revenueChartData = @json($revenueChartData ?? []);
        const enrollmentChartData = @json($enrollmentChartData ?? []);

        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        };

        const revenueCanvas = document.getElementById('revenueChart');
        const enrollmentCanvas = document.getElementById('enrollmentChart');

        if (revenueCanvas) {
            new Chart(revenueCanvas, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        data: revenueChartData,
                        borderColor: '#A487F8',
                        tension: 0.4,
                        fill: true,
                        backgroundColor: 'rgba(164, 135, 248, 0.1)'
                    }]
                },
                options: commonOptions
            });
        }

        if (enrollmentCanvas) {
            new Chart(enrollmentCanvas, {
                type: 'line',
                data: {
                    labels: chartLabels,
                    datasets: [{
                        data: enrollmentChartData,
                        borderColor: '#A487F8',
                        tension: 0.4
                    }]
                },
                options: commonOptions
            });
        }
    });
</script>
@endpush

@endsection