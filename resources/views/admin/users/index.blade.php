@extends('layouts.dashboard')

@section('title', 'Manajemen Pengguna | Dashboard Admin | Clearn - Platform Pembelajaran Online')

@section('content')

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}" />

<main class="flex-1 p-5 lg:p-8">
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4 mb-8">
            <div>
                <h1 class="text-lg font-bold dark:text-white text-slate-800">Daftar Pengguna</h1>
                <p class="text-[10px] dark:text-slate-500 text-slate-400 font-medium mt-0.5">
                    Kelola data mentor dan pelajar di platform Anda.
                </p>
            </div>

            {{-- Filter & Search Form --}}
            <form action="{{ route('admin.users.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-3">
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fas fa-search text-slate-400 text-[10px]"></i>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, email, username..." 
                        class="w-full sm:w-64 pl-8 pr-4 py-2 bg-white dark:bg-[#161525] border border-slate-200 dark:border-white/5 rounded-xl text-xs text-slate-600 dark:text-slate-300 focus:ring-[#7C3AED] focus:border-[#7C3AED] transition-all">
                </div>

                <div class="flex items-center gap-2 w-full sm:w-auto">
                    <select name="role" onchange="this.form.submit()" 
                        class="w-full sm:w-auto px-4 py-2 bg-white dark:bg-[#161525] border border-slate-200 dark:border-white/5 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300 focus:ring-[#7C3AED] focus:border-[#7C3AED] appearance-none cursor-pointer">
                        <option value="">Semua Peran</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="mentor" {{ request('role') == 'mentor' ? 'selected' : '' }}>Mentor</option>
                        <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>Pelajar</option>
                    </select>

                    @if(request('role') || request('search'))
                    <a href="{{ route('admin.users.index') }}" class="w-9 h-9 flex items-center justify-center bg-red-50 text-red-500 hover:bg-red-500 hover:text-white dark:bg-red-500/10 dark:text-red-500 dark:hover:bg-red-500 dark:hover:text-white rounded-xl transition-all" title="Reset Filter">
                        <i class="fas fa-times text-[10px]"></i>
                    </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabel Daftar Pengguna --}}
        <div class="bg-white dark:bg-[#161525] border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b dark:border-white/5 border-slate-100">
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase">Profil</th>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase">Kontak</th>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase">Peran (Role)</th>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase">Status</th>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase text-right">Terdaftar</th>
                    </tr>
                </thead>

                <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                    @forelse ($users as $user)
                    <tr>
                        <td class="p-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 flex-shrink-0 rounded-full bg-slate-100 dark:bg-[#0f0a19] overflow-hidden border border-slate-200 dark:border-gray-800">
                                    @if($user->profile_picture)
                                        <img src="{{ Storage::url($user->profile_picture) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-xs font-bold text-slate-400">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-bold dark:text-white text-slate-800">{{ $user->name }}</div>
                                    <div class="text-[10px] text-slate-500">{{ '@' . ($user->username ?? 'user') }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="p-5">
                            <div class="text-xs font-medium dark:text-slate-300 text-slate-600">{{ $user->email }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $user->phone ?? 'Tidak ada telepon' }}</div>
                        </td>

                        <td class="p-5">
                            @if($user->role === 'admin')
                                <span class="px-2.5 py-1 bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400 text-[10px] font-bold uppercase rounded-lg shadow-sm">Admin</span>
                            @elseif($user->role === 'mentor')
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400 text-[10px] font-bold uppercase rounded-lg shadow-sm">Mentor</span>
                            @else
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400 text-[10px] font-bold uppercase rounded-lg shadow-sm">Pelajar</span>
                            @endif
                        </td>

                        <td class="p-5">
                            @if($user->status === 'active')
                                <div class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase">Aktif</span>
                                </div>
                            @elseif($user->status === 'pending')
                                <div class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    <span class="text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase">Menunggu</span>
                                </div>
                            @elseif($user->status === 'rejected')
                                <div class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                    <span class="text-[10px] font-bold text-red-600 dark:text-red-400 uppercase">Ditolak</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    <span class="text-[10px] font-bold text-slate-500 uppercase">{{ $user->status }}</span>
                                </div>
                            @endif
                        </td>

                        <td class="p-5 text-right text-[11px] text-slate-500">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-[#0f0a19] flex items-center justify-center border dark:border-white/5">
                                    <i class="fas fa-users text-slate-400"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-400">
                                    Belum ada pengguna.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if ($users->hasPages())
            <div class="p-5 border-t dark:border-white/5 border-slate-100 flex items-center justify-between">

                {{-- Info --}}
                <p class="text-[10px] text-slate-400 font-medium">
                    Menampilkan {{ $users->firstItem() }}—{{ $users->lastItem() }} dari {{ $users->total() }} pengguna
                </p>

                {{-- Tombol navigasi --}}
                <div class="flex items-center gap-2">
                    {{-- Prev --}}
                    @if ($users->onFirstPage())
                    <span class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-100 dark:bg-[#0f0a19] border dark:border-white/5 text-slate-300 cursor-not-allowed">
                        <i class="fas fa-chevron-left text-[10px]"></i>
                    </span>
                    @else
                    <a href="{{ $users->previousPageUrl() }}"
                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-50 dark:bg-[#0f0a19] border dark:border-white/5 hover:border-[#7C3AED] transition-all">
                        <i class="fas fa-chevron-left text-[10px] text-slate-400"></i>
                    </a>
                    @endif

                    {{-- Nomor halaman --}}
                    @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                    @if ($page == $users->currentPage())
                    <span class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-[#7C3AED] text-white text-[10px] font-bold">
                        {{ $page }}
                    </span>
                    @else
                    <a href="{{ $url }}"
                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-50 dark:bg-[#0f0a19] border dark:border-white/5 hover:border-[#7C3AED] text-slate-400 text-[10px] font-bold transition-all">
                        {{ $page }}
                    </a>
                    @endif
                    @endforeach

                    {{-- Next --}}
                    @if ($users->hasMorePages())
                    <a href="{{ $users->nextPageUrl() }}"
                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-50 dark:bg-[#0f0a19] border dark:border-white/5 hover:border-[#7C3AED] transition-all">
                        <i class="fas fa-chevron-right text-[10px] text-slate-400"></i>
                    </a>
                    @else
                    <span class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-100 dark:bg-[#0f0a19] border dark:border-white/5 text-slate-300 cursor-not-allowed">
                        <i class="fas fa-chevron-right text-[10px]"></i>
                    </span>
                    @endif
                </div>
            </div>
            @endif
        </div>

    </div>
</main>

@endsection