@extends('layouts.dashboard')

@section('title', 'Kategori Kursus | Dashboard Admin | Clearn - Platform Pembelajaran Online')

@section('content')

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}" />

<main class="flex-1 p-5 lg:p-8">
    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <div class="flex justify-between items-end mb-8">
            <div>
                <h1 class="text-lg font-bold dark:text-white text-slate-800">Daftar Kategori</h1>
                <p class="text-[10px] dark:text-slate-500 text-slate-400 font-medium mt-0.5">
                    Kelola kategori kursus Anda dalam satu tempat.
                </p>
            </div>

            <a href="{{ route('admin.categories.create') }}"
                class="px-5 py-2.5 bg-[#7C3AED] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:opacity-90 transition-all shadow-lg shadow-[#7C3AED]/20">
                + Tambah Kategori
            </a>
        </div>

        {{-- Alert sukses --}}
        @if (session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-100 text-green-700 text-xs font-bold">
            {{ session('success') }}
        </div>
        @endif

        {{-- Alert error --}}
        @if (session('error'))
        <div class="mb-5 p-4 rounded-xl bg-red-100 text-red-700 text-xs font-bold">
            {{ session('error') }}
        </div>
        @endif

        {{-- Tabel Daftar Kategori --}}
        <div class="bg-white dark:bg-[#161525] border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b dark:border-white/5 border-slate-100">
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase">Ikon</th>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase">Nama</th>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase">Deskripsi</th>
                        <th class="p-5 text-[9px] font-black text-slate-400 uppercase text-right">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y dark:divide-white/5 divide-slate-100">
                    @forelse ($categories as $category)
                    <tr>
                        <td class="p-5">
                            <div class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-50 dark:bg-[#0f0a19] border dark:border-white/5">
                                <i class="fas {{ $category->category_icon ?? 'fa-book' }} text-xl"
                                    style="color: {{ $category->category_color ?? '#7C3AED' }};">
                                </i>
                            </div>
                        </td>

                        <td class="p-5 text-sm font-bold dark:text-white text-slate-800">
                            {{ $category->category_name }}
                        </td>

                        <td class="p-5 text-[11px] text-slate-500">
                            {{ $category->category_description ?? '-' }}
                        </td>

                        <td class="p-5 text-right">
                            <div class="flex items-center justify-end gap-3">

                                {{-- Tombol edit --}}
                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                    class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-50 dark:bg-[#0f0a19] border dark:border-white/5 hover:border-[#7C3AED] transition-all">
                                    <i class="fas fa-pen text-[10px] text-slate-400 hover:text-[#7C3AED]"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-8 text-center">
                            <div class="flex flex-col items-center justify-center gap-2">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 dark:bg-[#0f0a19] flex items-center justify-center border dark:border-white/5">
                                    <i class="fas fa-tags text-slate-400"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-400">
                                    Belum ada kategori.
                                </p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            @if ($categories->hasPages())
            <div class="p-5 border-t dark:border-white/5 border-slate-100 flex items-center justify-between">

                {{-- Info --}}
                <p class="text-[10px] text-slate-400 font-medium">
                    Menampilkan {{ $categories->firstItem() }}–{{ $categories->lastItem() }} dari {{ $categories->total() }} kategori
                </p>

                {{-- Tombol navigasi --}}
                <div class="flex items-center gap-2">
                    {{-- Prev --}}
                    @if ($categories->onFirstPage())
                    <span class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-100 dark:bg-[#0f0a19] border dark:border-white/5 text-slate-300 cursor-not-allowed">
                        <i class="fas fa-chevron-left text-[10px]"></i>
                    </span>
                    @else
                    <a href="{{ $categories->previousPageUrl() }}"
                        class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-50 dark:bg-[#0f0a19] border dark:border-white/5 hover:border-[#7C3AED] transition-all">
                        <i class="fas fa-chevron-left text-[10px] text-slate-400"></i>
                    </a>
                    @endif

                    {{-- Nomor halaman --}}
                    @foreach ($categories->getUrlRange(1, $categories->lastPage()) as $page => $url)
                    @if ($page == $categories->currentPage())
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
                    @if ($categories->hasMorePages())
                    <a href="{{ $categories->nextPageUrl() }}"
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