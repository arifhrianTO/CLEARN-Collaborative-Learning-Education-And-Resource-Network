@extends('layouts.sections')

@section('title', 'CLEARN │ Pengajar')

@section('content')

<!-- Beranda Pengajar -->
<section id="beranda" class="relative py-28 px-4 text-center overflow-hidden transition-colors duration-300 dark:bg-[#0F0B1A]">
    <button
        onclick="window.location='{{ route('home') }}'"
        class="absolute left-6 top-6 z-50 text-white hover:opacity-80 transition">
        <i class="fa-solid fa-arrow-left-long text-2xl -translate-x-1"></i>
    </button>

    <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-[#A487F8]/15 blur-[120px] rounded-full -z-10 opacity-0 dark:opacity-100 transition-opacity"></div>
    <h1 class="text-4xl md:text-6xl font-inter font-normal leading-[1.15] mb-8 tracking-tighter">
        Belajarlah dari yang Terbaik
    </h1>

    <p class="text-slate-600 dark:text-gray-400 max-w-xl mx-auto mb-12 text-base leading-relaxed">
        Pengajar kami adalah pakar industri dari perusahaan-perusahaan ternama di indonesia
    </p>

</section>

<div class="w-full bg-slate-100 dark:bg-[#0F0B1A] py-6 border-y border-slate-200 dark:border-white/5 transition-colors">
    <div class="max-w-7xl mx-auto px-4 md:px-10 flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="relative w-full md:max-w-x6">
            <form action="{{ route('mentor') }}" method="GET" class="w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input
                    id="mentorSearch"
                    name="search"
                    type="text"
                    value="{{ request('search') }}"
                    placeholder="Cari Pengajar..."
                    class="w-full bg-white dark:bg-[#0f0b1a] text-slate-900 dark:text-gray-200 text-sm rounded-full py-3 pl-12 pr-4 border border-slate-200 dark:border-white/10 focus:outline-none focus:ring-2 focus:ring-[#A487F8]/50 transition-all placeholder:text-gray-500" />
            </form>
        </div>
    </div>
</div>

<!-- Pengajar -->
<section id="pengajar" class="py-24 px-6 bg-slate-100 dark:bg-[#0F0B1A] transition-colors duration-300">
    <div class="text-center mb-16">
        <h3 class="text-3xl md:text-4xl font-extrabold text-[#A487F8] dark:text-[#A487F8]">{{ $totalMentors }}</h3>
        <p class="text-slate-500 text-xs mt-2 uppercase tracking-widest font-semibold">Pengajar Ahli</p>
    </div>

    <div class="flex justify-between items-end mb-12 max-w-[1200px] mx-auto transition-colors">
        <div>
            @if(request('search'))
                <p class="text-slate-500 dark:text-gray-400 text-sm">Menemukan {{ $mentors->count() }} pengajar untuk pencarian "<span class="font-bold text-primary">{{ request('search') }}</span>"</p>
            @else
                <p class="text-slate-500 dark:text-gray-400 text-sm">Menampilkan {{ $mentors->count() }} pengajar</p>
            @endif
        </div>
    </div>

    @if($mentors->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-[1200px] mx-auto">
        @foreach($mentors as $mentor)
            <x-landing.mentor-card
                photo="{{ $mentor->profile_picture ? asset('storage/' . $mentor->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($mentor->name) . '&background=random' }}"
                name="{{ $mentor->name }}"
                title="{{ $mentor->occupation ?? 'Instruktur' }}"
                description="{{ $mentor->profileAccount->bio ?? 'Pengajar berpengalaman di Clearn.' }}"
                :tags="[]"
                rating="4.9"
                students="{{ $mentor->student_count ?? 0 }}"
                courses="{{ $mentor->courses_count ?? 0 }}" />
        @endforeach
    </div>
    @else
    <div class="w-full max-w-[1200px] mx-auto bg-white dark:bg-[#111116] border border-slate-200 dark:border-white/5 rounded-3xl p-12 text-center">
        <div class="w-20 h-20 bg-slate-100 dark:bg-[#1a1625] rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-search text-2xl text-slate-400"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Pengajar Tidak Ditemukan</h3>
        <p class="text-slate-500 dark:text-gray-400 text-sm mb-6 max-w-md mx-auto">Maaf, kami tidak dapat menemukan pengajar yang cocok dengan pencarian Anda. Coba gunakan kata kunci lain.</p>
        <a href="{{ route('mentor') }}" class="inline-block px-6 py-2.5 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-primary/20 hover:-translate-y-1 transition-all">Lihat Semua Pengajar</a>
    </div>
    @endif
</section>

<section class="px-6 pb-24">
    <div class="max-w-[1100px] mx-auto text-center p-12 rounded-[30px] bg-[#A487F8] text-white">

        <h2 class="text-2xl md:text-3xl font-bold mb-4">
            Bagikan Pengetahuan Anda
        </h2>

        <p class="text-sm opacity-90 mb-8 max-w-xl mx-auto">
            Bergabunglah dengan komunitas instruktur ahli kami dan inspirasi jutaan pelajar di seluruh dunia.
        </p>

        <div class="flex justify-center gap-4">
        </div>
    </div>
</section>

@endsection