@extends('layouts.dashboard')

@section('title', 'CLEARN │ Pengumpulan')

@section('content')

<main class="flex-1 p-5 lg:p-8">
    <div class="max-w-5xl mx-auto">

        {{-- Header Section --}}
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold dark:text-white text-slate-800">Daftar Pengumpulan</h1>
                <p class="text-[11px] dark:text-slate-500 text-slate-400 font-medium">
                    Tugas Akhir: {{ $project->project_title }} ? {{ $totalSubmissions }} Siswa Mengumpulkan
                </p>
            </div>
            
            <a href="{{ route('mentor.courses.show ', $project->session->course_id) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 text-[10px] font-bold uppercase tracking-widest hover:bg-slate-200 dark:hover:bg-white/10 transition">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Kurikulum
            </a>
        </div>

        {{-- Navigation Tabs --}}
        <div class="flex items-center gap-6 mb-6 border-b border-slate-200 dark:border-white/5">
            <a href="{{ route('mentor.projects.submissions', ['project' => $project->id, 'filter' => 'all']) }}" 
               class="pb-3 text-[10px] font-black uppercase tracking-widest border-b-2 {{ $filter == 'all' ? 'border-primary text-primary' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-white' }} transition-all">
                Semua ({{ $totalSubmissions }})
            </a>
            <a href="{{ route('mentor.projects.submissions', ['project' => $project->id, 'filter' => 'pending']) }}" 
               class="pb-3 text-[10px] font-bold uppercase tracking-widest border-b-2 {{ $filter == 'pending' ? 'border-primary text-primary' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-white' }} transition-all">
                Menunggu ({{ $pendingCount }})
            </a>
            <a href="{{ route('mentor.projects.submissions', ['project' => $project->id, 'filter' => 'graded']) }}" 
               class="pb-3 text-[10px] font-bold uppercase tracking-widest border-b-2 {{ $filter == 'graded' ? 'border-primary text-primary' : 'border-transparent text-slate-400 hover:text-slate-600 dark:hover:text-white' }} transition-all">
                Sudah Dinilai ({{ $gradedCount }})
            </a>
        </div>

        {{-- Table Card --}}
        <div class="w-full dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden shadow-sm">
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
                        @forelse($submissions as $sub)
                        <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-all">
                            <td class="px-8 py-5 font-bold">{{ $sub->enrollment->student->name }}</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2 text-slate-400">
                                    <i class="fas fa-file-archive"></i> 
                                    <span class="truncate max-w-[150px]" title="{{ basename($sub->submission_file) }}">{{ basename($sub->submission_file) }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">{{ $sub->started_at ? $sub->started_at->translatedFormat('d M Y, H:i') : '-' }}</td>
                            <td class="px-8 py-5">
                                @if($sub->final_project_score !== null && $sub->final_project_score >= 70)
                                    <span class="px-3 py-1 bg-emerald-500/10 text-emerald-500 font-bold rounded-full text-[9px] uppercase">Lulus ({{ $sub->final_project_score }})</span>
                                @elseif($sub->final_project_score !== null)
                                    <span class="px-3 py-1 bg-red-500/10 text-red-500 font-bold rounded-full text-[9px] uppercase">Tidak Lulus ({{ $sub->final_project_score }})</span>
                                @else
                                    <span class="px-3 py-1 bg-amber-500/10 text-amber-500 font-bold rounded-full text-[9px] uppercase">Menunggu</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('mentor.projects.submission.detail', $sub->id) }}" 
                                class="text-primary hover:text-primary/80 font-bold transition-all underline decoration-dotted">
                                    Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-8 text-center text-slate-400 text-xs italic">
                                Belum ada pengumpulan yang sesuai dengan filter ini.
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