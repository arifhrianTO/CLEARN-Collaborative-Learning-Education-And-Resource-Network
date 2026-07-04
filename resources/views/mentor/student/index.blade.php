@extends('layouts.dashboard')

@section('title', 'CLEARN │ Pelajar')

@section('content')

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}" />

<main class="flex-1 p-8 pt-10">
    <div class="max-w-6xl mx-auto">

        {{-- Header dengan Judul dan Pencarian --}}
        <header class="mb-8 flex justify-between items-center gap-4">
            <div>
                <h1 class="text-xl font-bold dark:text-white text-slate-800">
                    Data Pelajar
                </h1>

                <p class="text-[11px] dark:text-slate-500 text-slate-400 font-medium mt-0.5">
                    Daftar seluruh pelajar yang terdaftar dalam platform.
                </p>
            </div>

            {{-- Input Pencarian --}}
            <div class="relative w-64">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-500">
                    <i class="fa-solid fa-search text-[10px]"></i>
                </span>

                <input type="text" id="searchStudent" placeholder="Cari pelajar..."
                    class="w-full pl-9 pr-4 py-2 bg-white dark:bg-[#161525] border border-slate-200 dark:border-white/5 rounded-xl text-[10px] text-slate-800 dark:text-white focus:outline-none focus:ring-1 focus:ring-[#A487F8] transition-all placeholder:text-slate-400">
            </div>
        </header>

        {{-- Grid Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

            {{-- Total Pelajar --}}
            <div class="p-6 flex items-center justify-between dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div>
                    <h2 class="text-2xl font-black leading-none dark:text-white text-slate-800 mb-1">
                        {{ $totalStudents ?? 0 }}
                    </h2>

                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">
                        Total Pelajar
                    </span>
                </div>

                <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center text-blue-500">
                    <i class="fas fa-graduation-cap text-lg"></i>
                </div>
            </div>

            {{-- Pelajar Aktif --}}
            <div class="p-6 flex items-center justify-between dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div>
                    <h2 class="text-2xl font-black leading-none dark:text-white text-slate-800 mb-1">
                        {{ $activeStudents ?? 0 }}
                    </h2>

                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">
                        Pelajar Aktif
                    </span>
                </div>

                <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center text-primary">
                    <i class="fas fa-user-check text-lg"></i>
                </div>
            </div>

            {{-- Rata-rata Kursus --}}
            <div class="p-6 flex items-center justify-between dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div>
                    <h2 class="text-2xl font-black leading-none dark:text-white text-slate-800 mb-1">
                        {{ $avgCourses ?? 0 }}
                    </h2>

                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">
                        Rata-rata Kursus
                    </span>
                </div>

                <div class="w-10 h-10 rounded-lg bg-slate-100 dark:bg-white/5 flex items-center justify-center text-emerald-500">
                    <i class="fas fa-book-reader text-lg"></i>
                </div>
            </div>

        </div>

        {{-- Tabel Data Pelajar --}}
        <div class="bg-white dark:bg-[#161525] border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-white/5 text-slate-400 uppercase text-[9px] font-black tracking-widest">
                            <th class="p-4 pl-8">Profil Pelajar</th>
                            <th class="p-4">Tanggal Bergabung</th>
                            <th class="p-4">Kursus Diikuti</th>
                            <th class="p-4 pr-8">Status Akun</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-100 dark:divide-white/5 text-xs font-medium" id="studentTable">

                        @forelse($students ?? [] as $student)

                        @php
                        $name = is_array($student) ? ($student['name'] ?? '-') : ($student->name ?? '-');
                        $email = is_array($student) ? ($student['email'] ?? '-') : ($student->email ?? '-');
                        $date = is_array($student)
                        ? ($student['date'] ?? '-')
                        : optional($student->created_at)->format('d M Y');

                        $courses = is_array($student)
                        ? ($student['courses'] ?? 0)
                        : ($student->courses_count ?? 0);

                        $status = is_array($student)
                        ? ($student['status'] ?? 'Aktif')
                        : ($student->status ?? 'Aktif');

                        $status = strtolower($status) === 'active' ? 'Aktif' : $status;
                        $status = strtolower($status) === 'aktif' ? 'Aktif' : $status;
                        $status = strtolower($status) === 'nonaktif' ? 'Nonaktif' : $status;
                        $status = strtolower($status) === 'inactive' ? 'Nonaktif' : $status;

                        $initials = strtoupper(substr($name, 0, 2));
                        @endphp

                        <tr class="student-row hover:bg-slate-50/50 dark:hover:bg-white/5 transition-colors">
                            <td class="p-4 pl-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-[#A487F8]/10 text-[#A487F8] flex items-center justify-center font-bold text-sm uppercase">
                                        {{ $initials }}
                                    </div>

                                    <div>
                                        <div class="student-name font-bold dark:text-white text-slate-800">
                                            {{ $name }}
                                        </div>

                                        <div class="student-email text-[10px] text-slate-400">
                                            {{ $email }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <td class="p-4 dark:text-slate-400 text-slate-500">
                                {{ $date }}
                            </td>

                            <td class="p-4 font-bold dark:text-white text-slate-800">
                                {{ $courses }}
                                <span class="text-slate-400 font-normal">Kursus</span>
                            </td>

                            <td class="p-4 pr-8">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg {{ $status === 'Aktif' ? 'bg-emerald-500/10 text-emerald-500' : 'bg-slate-500/10 text-slate-400' }} font-bold text-[9px] uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 {{ $status === 'Aktif' ? 'bg-emerald-500' : 'bg-slate-400' }} rounded-full"></span>
                                    {{ $status }}
                                </span>
                            </td>
                        </tr>

                        @empty

                        <tr>
                            <td colspan="4" class="p-8 text-center text-slate-400 italic">
                                Belum ada data pelajar.
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
    const searchInput = document.getElementById('searchStudent');

    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            let input = this.value.toLowerCase();
            let rows = document.querySelectorAll('.student-row');

            rows.forEach(row => {
                let name = row.querySelector('.student-name')?.innerText.toLowerCase() || '';
                let email = row.querySelector('.student-email')?.innerText.toLowerCase() || '';

                row.style.display = name.includes(input) || email.includes(input) ? '' : 'none';
            });
        });
    }
</script>
@endpush

@endsection