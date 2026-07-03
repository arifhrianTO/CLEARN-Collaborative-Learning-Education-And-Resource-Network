@extends('layouts.dashboard')

@section('title', 'Daftar Pengumpulan | Dashboard Mentor')

@section('content')

{{-- Sidebar --}}
<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}"
    active="submissions" />

<main class="flex-1 p-5 lg:p-8">
    <div class="max-w-5xl mx-auto">

        {{-- Dummy Data --}}
        @php
            $submissions = [
                ['id' => '1', 'name' => 'Alya Ghaitsa', 'file' => 'project_Alya.zip', 'date' => '25 Mei 2026, 10:20', 'status' => 'pending'],
                ['id' => '2', 'name' => 'Pelajar1', 'file' => 'tailwind.zip', 'date' => '25 Mei 2026, 14:30', 'status' => 'graded'],
                ['id' => '3', 'name' => 'Pelajar2', 'file' => 'final.pdf', 'date' => '26 Mei 2026, 09:15', 'status' => 'pending'],
                ['id' => '4', 'name' => 'Pelajar3', 'file' => 'web.zip', 'date' => '26 Mei 2026, 11:00', 'status' => 'graded'],
            ];
            
            $total = count($submissions);
            $pending = count(array_filter($submissions, fn($s) => $s['status'] == 'pending'));
            $graded = count(array_filter($submissions, fn($s) => $s['status'] == 'graded'));
        @endphp

        {{-- Header Section --}}
        <div class="mb-6">
            <h1 class="text-lg font-bold dark:text-white text-slate-800">Daftar Pengumpulan</h1>
            <p class="text-[11px] dark:text-slate-500 text-slate-400 font-medium">
                Tugas Akhir (Praktikum) • {{ $total }} Siswa Mengumpulkan
            </p>
        </div>

        {{-- Navigation Tabs --}}
        <div class="flex items-center gap-6 mb-6 border-b border-slate-200 dark:border-white/5">
            <a href="#" class="pb-3 text-[10px] font-black uppercase tracking-widest border-b-2 border-primary text-primary">
                Semua ({{ $total }})
            </a>
            <a href="#" class="pb-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-white transition-all">
                Menunggu ({{ $pending }})
            </a>
            <a href="#" class="pb-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-white transition-all">
                Sudah Dinilai ({{ $graded }})
            </a>
        </div>

        {{-- Table Card --}}
        <div class="w-full dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b dark:border-white/5 border-slate-200 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                            <th class="px-8 py-6">Nama Siswa</th>
                            <th class="px-8 py-6">File Proyek</th>
                            <th class="px-8 py-6">Waktu Kumpul</th>
                            <th class="px-8 py-6">Status</th>
                            <th class="px-8 py-6 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-[11px] dark:text-slate-300 text-slate-600 divide-y dark:divide-white/5 divide-slate-200">
                        @foreach($submissions as $sub)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                            <td class="px-8 py-5 font-bold">{{ $sub['name'] }}</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2 text-slate-400">
                                    <i class="fas fa-file-archive"></i> <span>{{ $sub['file'] }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">{{ $sub['date'] }}</td>
                            <td class="px-8 py-5">
                                @if($sub['status'] == 'graded')
                                    <span class="px-3 py-1 bg-emerald-500/10 text-emerald-500 font-bold rounded-full text-[9px] uppercase">Sudah Dinilai</span>
                                @else
                                    <span class="px-3 py-1 bg-amber-500/10 text-amber-500 font-bold rounded-full text-[9px] uppercase">Menunggu</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ url('/mentor/final-projects/submissions/' . $sub['id']) }}" 
                                class="text-primary hover:text-white font-bold transition-all underline decoration-dotted">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>
@endsection