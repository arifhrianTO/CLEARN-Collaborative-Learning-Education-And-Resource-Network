@extends('layouts.landing')

@section('title', 'CLEARN │ Beranda')

@section('content')

<!-- HERO -->
<section id="beranda" class="relative min-h-screen flex flex-col justify-center px-5 md:px-10 pt-24 text-center overflow-x-hidden transition-colors duration-300 dark:bg-[#0F0B1A]">
    @include('partials.landing.hero')
</section>

<!-- KURSUS -->
<section id="kursus" class="py-24 px-5 md:px-10 transition-colors duration-300 dark:bg-[#1A1625]">
    <div class="max-w-[1400px] mx-auto">
    <div class="relative text-center mb-12 transition-colors">
        <h2 class="text-4xl font-bold mb-3 tracking-tight">Kursus Unggulan</h2>
        <p class="text-slate-500 dark:text-gray-400 text-sm">Pilihan kursus terbaik yang paling diminati pengguna baru</p>
        <a href="{{route('course') }}" class="md:absolute md:right-0 md:bottom-2 mt-4 md:mt-0 text-[#A487F8] dark:text-[#A487F8] text-sm font-semibold inline-flex items-center group">
            Lihat Semua <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($courses as $course)
        <x-landing.course :course="$course" />
        @endforeach
    </div>
    </div>
</section>

<!-- ── KATEGORI ── -->
<section id="kategori" class="py-24 px-5 md:px-10 transition-colors duration-300 dark:bg-[#0F0B1A]">
    <div class="max-w-[1400px] mx-auto">
    <div class="relative text-center mb-12">
         <h2 class="text-4xl font-bold mb-3">Jelajahi Kategori Populer</h2>
        <p class="text-slate-500 dark:text-gray-400 text-sm">
           Temukan kelas yang sesuai minat dan tujuan karier Anda
        </p>
        <a href="{{route('category') }}" class="md:absolute md:right-0 md:bottom-2 mt-4 md:mt-0 text-[#A487F8] dark:text-[#A487F8] text-sm font-semibold inline-flex items-center group">
            Lihat Semua <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
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
</section>

<!-- ── PENGAJAR ── -->
<section id="pengajar" class="py-24 px-5 md:px-10 bg-slate-100 dark:bg-[#1A1625] transition-colors duration-300">
    <div class="max-w-[1400px] mx-auto">
    <div class="text-center mb-12 relative">
        <h2 class="text-4xl font-bold mb-3">Belajar Dari Yang Terbaik</h2>
        <p class="text-slate-500 dark:text-gray-400 text-sm">
            Pengajar berpengalaman siap membantu proses belajar Anda
        </p>
        <a href="{{ route('mentor') }}"
            class="md:absolute md:right-0 md:bottom-2 mt-4 md:mt-0 text-[#A487F8] dark:text-[#A487F8] text-sm font-semibold inline-flex items-center group">
            Lihat Semua <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
        </a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        @foreach($mentors as $mentor)
        <div class="bg-white dark:bg-[#0F0B1A] p-8 rounded-3xl text-center border border-slate-200 dark:border-gray-800 hover:border-[#A487F8] transition-all shadow-md">
            <img src="{{ $mentor->profile_picture ? asset('storage/' . $mentor->profile_picture) : 'https://ui-avatars.com/api/?name=' . urlencode($mentor->name) . '&background=random' }}" class="w-28 h-28 rounded-full mx-auto mb-6 object-cover border-4 border-slate-200 dark:border-gray-800" alt="{{ $mentor->name }}">
            <h6 class="font-bold text-lg mb-1.5">{{ $mentor->name }}</h6>
            <p class="text-xs text-[#A487F8] dark:text-[#A487F8] mb-6 font-semibold tracking-wider">{{ $mentor->occupation ?? 'Pengajar' }}</p>
            <div class="flex items-center justify-center gap-1 mb-3 text-yellow-400">
                @for($i = 1; $i <= 5; $i++)
                    <i class="fas fa-star text-xs {{ $i <= round($mentor->rating) ? '' : 'text-slate-300 dark:text-slate-600' }}"></i>
                @endfor
                <span class="text-slate-500 dark:text-slate-400 text-xs font-semibold ml-1">{{ number_format($mentor->rating, 1) }}</span>
                <span class="text-slate-400 text-[10px]">({{ $mentor->rating_count }})</span>
            </div>
            <div class="text-xs text-slate-500 border-t border-slate-100 dark:border-gray-800/50 pt-4 space-y-1">
                <p>{{ $mentor->student_count ?? 0 }} Pelajar</p>
                <p>{{ $mentor->courses_count ?? 0 }} Kursus</p>
            </div>
        </div>
        @endforeach
    </div>
    </div>
</section>

@endsection