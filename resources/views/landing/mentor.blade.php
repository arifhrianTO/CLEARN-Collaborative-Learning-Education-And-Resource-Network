@extends('layouts.sections')

@section('title', 'Mentor')

@section('content')

<!-- Beranda Mentor -->
<section id="beranda" class="relative py-28 px-4 text-center overflow-hidden transition-colors duration-300 dark:bg-[#120d22]">
    <button
        onclick="window.location='{{ route('home') }}'"
        class="absolute left-6 top-6 z-50 text-white hover:opacity-80 transition">
        <i class="fa-solid fa-arrow-left-long text-2xl -translate-x-1"></i>
    </button>

    <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-purple-900/15 blur-[120px] rounded-full -z-10 opacity-0 dark:opacity-100 transition-opacity"></div>
    <h1 class="text-4xl md:text-6xl font-inter font-normal leading-[1.15] mb-8 tracking-tighter">
        Belajarlah dari yang Terbaik
    </h1>

    <p class="text-slate-600 dark:text-gray-400 max-w-xl mx-auto mb-12 text-base leading-relaxed">
        Pengajar kami adalah pakar industri dari perusahaan-perusahaan ternama di indonesia
    </p>

</section>

<!-- Mentor -->
<section id="pengajar" class="py-24 px-6 bg-slate-100 dark:bg-[#0d0c13] transition-colors duration-300">
    <div class="grid grid-cols-3 gap-6 max-w-5xl mx-auto text-center mb-16">
        <div>
            <h3 class="text-3xl md:text-4xl font-extrabold text-[#A487F8] dark:text-[#A487F8]">{{ $totalMentors }}</h3>
            <p class="text-slate-500 text-xs mt-2 uppercase tracking-widest font-semibold">Pengajar Ahli</p>
        </div>
        <div>
            <h3 class="text-3xl md:text-4xl font-extrabold text-[#A487F8] dark:text-[#A487F8]">{{ $averageRating }}</h3>
            <p class="text-slate-500 text-xs mt-2 uppercase tracking-widest font-semibold">Rating Rata-rata</p>
        </div>
        <div>
            <h3 class="text-3xl md:text-4xl font-extrabold text-[#A487F8] dark:text-[#A487F8]">{{ $totalStudents }}</h3>
            <p class="text-slate-500 text-xs mt-2 uppercase tracking-widest font-semibold">Pelajar Diajar</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-[1200px] mx-auto">
        @foreach($mentors as $mentor)
            <x-landing.mentor-card
                photo="{{ $mentor->profile_picture ? asset('storage/' . $mentor->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($mentor->name) . '&background=random' }}"
                name="{{ $mentor->name }}"
                title="{{ $mentor->occupation ?? 'Instruktur' }}"
                description="{{ $mentor->profileAccount->bio ?? 'Mentor berpengalaman di Clearn.' }}"
                :tags="[]"
                rating="4.9"
                students="{{ $mentor->student_count ?? 0 }}"
                courses="{{ $mentor->courses_count ?? 0 }}" />
        @endforeach
    </div>
</section>

<section class="px-6 pb-24">
    <div class="max-w-[1100px] mx-auto text-center p-12 rounded-[30px] bg-[#A184F5] text-white">

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