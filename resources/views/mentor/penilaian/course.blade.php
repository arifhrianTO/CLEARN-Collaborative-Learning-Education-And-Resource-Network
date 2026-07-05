@extends('layouts.dashboard')

@section('title', 'CLEARN │ Penilaian - ' . $course->course_title)

@section('content')

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}"
    active="penilaian" />

<main class="flex-1 p-5 lg:p-8">
    <div class="max-w-6xl mx-auto">

        <div class="mb-6">
            <a href="{{ route('mentor.penilaian.index') }}"
                class="inline-flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-primary transition">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>
            <h1 class="text-lg font-bold dark:text-white text-slate-800 mt-3">{{ $course->course_title }}</h1>
            <p class="text-[11px] dark:text-slate-500 text-slate-400 font-medium">
                {{ $results->count() }} pengumpulan tugas akhir
            </p>
        </div>

        @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-xs font-bold">
            {{ session('success') }}
        </div>
        @endif

        @php
        $pending = $results->whereNull('final_project_score')->count();
        $graded = $results->whereNotNull('final_project_score')->count();
        @endphp

        <div class="flex items-center gap-6 mb-6 border-b border-slate-200 dark:border-white/5">
            <a href="#" class="filter-btn active pb-3 text-[10px] font-black uppercase tracking-widest border-b-2 border-primary text-primary" data-filter="all">
                Semua ({{ $results->count() }})
            </a>
            <a href="#" class="filter-btn pb-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-white transition-all" data-filter="pending">
                Menunggu ({{ $pending }})
            </a>
            <a href="#" class="filter-btn pb-3 text-[10px] font-bold uppercase tracking-widest text-slate-400 hover:text-white transition-all" data-filter="graded">
                Sudah Dinilai ({{ $graded }})
            </a>
        </div>

        <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden shadow-sm">
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
                        @forelse($results as $result)
                        <tr class="result-row hover:bg-slate-50 dark:hover:bg-white/5 transition-all" data-status="{{ $result->final_project_score !== null ? 'graded' : 'pending' }}">
                            <td class="px-8 py-5 font-bold">{{ $result->enrollment->student->name ?? '-' }}</td>
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-2 text-slate-400">
                                    <i class="fas fa-file-archive"></i>
                                    <span>{{ basename($result->submission_file ?? '-') }}</span>
                                </div>
                            </td>
                            <td class="px-8 py-5">{{ $result->created_at ? $result->created_at->format('d M Y, H:i') : '-' }}</td>
                            <td class="px-8 py-5">
                                @if($result->final_project_score !== null)
                                <span class="px-3 py-1 bg-emerald-500/10 text-emerald-500 font-bold rounded-full text-[9px] uppercase">
                                    {{ $result->final_project_score }}/100
                                </span>
                                @else
                                <span class="px-3 py-1 bg-amber-500/10 text-amber-500 font-bold rounded-full text-[9px] uppercase">Menunggu</span>
                                @endif
                            </td>
                            <td class="px-8 py-5 text-right">
                                <a href="{{ route('mentor.penilaian.show', $result) }}"
                                    class="text-primary hover:text-white font-bold transition-all underline decoration-dotted">
                                    {{ $result->final_project_score !== null ? 'Lihat' : 'Nilai' }}
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-12 text-center text-slate-400 italic">
                                Belum ada pengumpulan untuk kursus ini.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</main>

@push('scripts')
<script>
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.filter-btn').forEach(b => {
                b.classList.remove('border-b-2', 'border-primary', 'text-primary');
                b.classList.add('text-slate-400');
            });
            this.classList.remove('text-slate-400');
            this.classList.add('border-b-2', 'border-primary', 'text-primary');

            const filter = this.dataset.filter;
            document.querySelectorAll('.result-row').forEach(row => {
                if (filter === 'all') {
                    row.style.display = '';
                } else {
                    row.style.display = row.dataset.status === filter ? '' : 'none';
                }
            });
        });
    });
</script>
@endpush
@endsection