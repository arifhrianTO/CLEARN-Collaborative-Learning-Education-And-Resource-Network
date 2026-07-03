@extends('layouts.sections')

@section('title', 'Kategori Kursus | Clearn - Platform Pembelajaran Online')

@section('content')

<section class="relative py-28 px-4 text-center overflow-hidden transition-colors duration-300 dark:bg-[#120d22]">
    <button
        onclick="window.location='{{ route('home') }}'"
        class="absolute left-6 top-6 z-50 text-white hover:opacity-80 transition">
        <i class="fa-solid fa-arrow-left-long text-2xl -translate-x-1"></i>
    </button>

    <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[800px] h-[400px]
                    bg-purple-900/15 blur-[120px] rounded-full -z-10
                    opacity-0 dark:opacity-100 transition-opacity"></div>

    <h1 class="text-4xl md:text-6xl font-inter font-normal leading-[1.15] mb-8 tracking-tighter">
        Telusuri berdasarkan Kategori
    </h1>
    <p class="text-slate-600 dark:text-gray-400 max-w-xl mx-auto mb-12 text-base leading-relaxed">
        Jelajahi kursus di lebih dari 12 kategori dan temukan jalur pembelajaran
        yang sempurna untuk tujuan Anda.
    </p>
</section>

<section class="py-20 px-6 max-w-[1200px] mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        @foreach ($categories as $category)
        <x-landing.category
            variant="full"
            icon="{{ $category->category_icon }}"
            color="{{ $category->category_color }}"
            title="{!! $category->category_name !!}"
            desc="{{ $category->category_description }}"
            count="{{ $category->courses_count ?? 0 }}"
            students="0"
            href="{{ route('category') }}" />
        @endforeach
    </div>
</section>

<section class="px-6 pb-24">
    <div class="max-w-[1100px] mx-auto text-center p-12 rounded-[30px] bg-[#A487F8] text-white">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">
            Tidak Menemukan Kategori Anda?
        </h2>
        <p class="text-sm opacity-90 mb-8 max-w-xl mx-auto">
            Kami terus menambahkan kursus dan kategori baru. Jelajahi semua kursus
            atau hubungi kami dengan saran.
        </p>
        <div class="flex justify-center gap-4 flex-wrap">
            <button class="px-6 py-3 rounded-xl bg-white/20 backdrop-blur
                               text-white font-semibold hover:bg-white/30 transition">
                Hubungi Kami
            </button>
        </div>
    </div>
</section>

@endsection