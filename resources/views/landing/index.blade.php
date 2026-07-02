@extends('layouts.landing')

@section('title', 'Clearn - Platform Pembelajaran Online')

@section('content')

<!-- HERO -->
<section id="beranda" class="relative py-28 px-4 text-center overflow-hidden transition-colors duration-300 dark:bg-[#120d22]">
    @include('partials.landing.hero')
</section>

<!-- KURSUS -->
<section id="kursus" class="py-20 px-10 max-w-[1400px] mx-auto">
    <div class="flex justify-between items-end mb-12 transition-colors">
        <div>
            <h2 class="text-4xl font-bold mb-3 tracking-tight">Kursus Unggulan</h2>
            <p class="text-slate-500 dark:text-gray-400 text-sm">Pilihan kursus terbaik yang paling diminati pengguna baru</p>
        </div>
       <a href="{{route('course') }}" class="absolute right-10 bottom--2 text-purple-600 dark:text-purple-400 text-sm font-semibold flex items-center group">
            Lihat Semua <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($courses as $course)
        <x-landing.course :course="$course" />
        @endforeach
    </div>
</section>

<!-- ── KATEGORI + ULASAN ── -->
<section id="kategori" class="py-24 px-10 transition-colors duration-300 dark:bg-[#120d22]">
    <div class="relative text-center mb-16">
         <h2 class="text-4xl font-bold mb-3">Jelajahi Kategori Populer</h2>
        <p class="text-slate-500 dark:text-gray-400">
           Temukan kelas yang sesuai minat dan tujuan karier Anda
        </p>
        <a href="{{route('category') }}" class="absolute right-0 bottom-2 text-purple-600 dark:text-purple-400 text-sm font-semibold flex items-center group">
            Lihat Semua <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-32">
        @php
        $categories = \App\Models\Category::withCount('courses')->limit(6)->get();
        @endphp

        @foreach ($categories as $category)
        <x-landing.category
            variant="preview"
            icon="{{ $category->category_icon }}"
            color="{{ $category->category_color }}"
            title="{!! $category->category_name !!}"
            desc="{{ $category->category_description }}"
            count="{{ $category->courses_count ?? 0 }} Kursus"
            href="{{ route('category') }}" />
        @endforeach
    </div>

    </div>

    <!-- ULASAN -->
    <div id="ulasan" class="text-center max-w-7xl mx-auto">
      
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-left">

        </div>
    </div>
</section>

<!-- ── PENGAJAR ── -->
<section id="pengajar" class="py-24 px-10 bg-slate-100 dark:bg-[#0d0c13] transition-colors duration-300">
    <div class="text-center mb-16 relative">
        <h2 class="text-4xl font-bold mb-3">Belajar Dari Yang Terbaik</h2>
        <p class="text-slate-500 dark:text-gray-400">
            Mentor berpengalaman siap membantu proses belajar Anda
        </p>
        <a href="{{ route('mentor') }}"
            class="absolute right-0 top-1  text-purple-600 dark:text-purple-400 text-sm font-semibold flex items-center group">
            Lihat Semua
            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 max-w-[1300px] mx-auto">
        @foreach($mentors as $mentor)
        <div class="bg-white dark:bg-[#13111a] p-8 rounded-3xl text-center border border-slate-200 dark:border-gray-800 hover:border-purple-600 transition-all shadow-md">
            <img src="{{ $mentor->profile_picture ? asset('storage/' . $mentor->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($mentor->name) . '&background=random' }}" class="w-28 h-28 rounded-full mx-auto mb-6 object-cover border-4 border-slate-200 dark:border-gray-800" alt="{{ $mentor->name }}">
            <h6 class="font-bold text-lg mb-1.5">{{ $mentor->name }}</h6>
            <p class="text-xs text-purple-600 dark:text-purple-400 mb-6 font-semibold tracking-wider">{{ $mentor->occupation ?? 'Mentor' }}</p>
            <div class="text-xs text-slate-500 border-t border-slate-100 dark:border-gray-800/50 pt-5 space-y-1">
                <p>{{ $mentor->student_count ?? 0 }} Pelajar</p>
                <p>{{ $mentor->courses_count ?? 0 }} Kursus</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

@endsection