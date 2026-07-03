@extends('layouts.sections')

@section('title', 'kursus')

@section('content')

<section id="kursus" class="relative py-10 px-4 text-center overflow-hidden transition-colors duration-300 dark:bg-[#120d22]">
    <button
        onclick="window.location='{{ route('home') }}'"
        class="absolute left-6 top-6 z-50 text-white hover:opacity-80 transition">
        <i class="fa-solid fa-arrow-left-long text-2xl -translate-x-1"></i>
    </button>

    <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-purple-900/15 blur-[120px] rounded-full -z-10 opacity-0 dark:opacity-100 transition-opacity"></div>

    <h1 class="text-4xl md:text-5xl font-inter font-normal leading-[1.15] mb-8 tracking-tighter">
        Jelajahi Kursus Kami
    </h1>

    <p class="text-slate-600 dark:text-gray-400 max-w-2xl mx-auto text-base leading-relaxed">
        Temukan ribuan kursus dari para ahli industri dan tingkatkan keterampilan Anda.
    </p>
</section>

<div class="w-full bg-slate-100 dark:bg-[#1A1625] py-6 border-y border-slate-200 dark:border-white/5 transition-colors">
    <div class="max-w-7xl mx-auto px-4 md:px-10 flex flex-col md:flex-row items-center justify-between gap-4">

        <div class="relative w-full md:max-w-xl">
            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500">
                <i class="fa-solid fa-magnifying-glass"></i>
            </span>
            <input
                id="courseSearch"
                type="text"
                placeholder="Cari Kursus..."
                class="w-full bg-white dark:bg-[#0f0b1a] text-slate-900 dark:text-gray-200 text-sm rounded-full py-3 pl-12 pr-4 border border-slate-200 dark:border-white/10 focus:outline-none focus:ring-2 focus:ring-purple-500/50 transition-all placeholder:text-gray-500" />
        </div>

        <div class="flex items-center gap-3 overflow-x-auto">
            <button data-category="all" class="category-filter px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition-all bg-[#A487F8] text-white">Semua</button>
            @foreach($categories as $category)
            <button data-category="{{ $category->id }}" class="category-filter px-4 py-2 rounded-xl text-xs font-bold uppercase tracking-widest transition-all bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-slate-300 hover:bg-slate-300 dark:hover:bg-white/20">{{ $category->category_name }}</button>
            @endforeach
        </div>
    </div>
</div>

<section id="" class="py-20 px-10 max-w-[1400px] mx-auto">
    <div class="flex justify-between items-end mb-12 transition-colors">
        <div>
            <p class="text-slate-500 dark:text-gray-400 text-sm result-count">Menampilkan {{ $courses->count() }} kursus</p>
        </div>
    </div>

    <div id="courseGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($courses as $course)
        <div class="course-card" data-title="{{ strtolower($course->course_title) }}" data-category="{{ $course->category_id }}">
            <x-landing.course :course="$course" />
        </div>
        @endforeach
    </div>
</section>

</main>
<section class="px-6 pb-24">
    <div class="max-w-[1100px] mx-auto text-center p-12 rounded-[30px] bg-[#A487F8] text-white">
        <h2 class="text-2xl md:text-3xl font-bold mb-4">
            Tidak Menemukan Kursus Anda?
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
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('courseSearch');
        const categoryBtns = document.querySelectorAll('.category-filter');
        const courseCards = document.querySelectorAll('.course-card');
        const resultCount = document.querySelector('.result-count');

        function filterCourses() {
            const query = searchInput.value.toLowerCase().trim();
            const activeCategory = document.querySelector('.category-filter.bg-[#A487F8]')?.dataset.category |'all';

            let visible = 0;
            courseCards.forEach(card => {
                const title = card.dataset.title |'';
                const category = card.dataset.category;
                const matchSearch = !query || title.includes(query);
                const matchCategory = activeCategory === 'all' || category === activeCategory;

                if (matchSearch && matchCategory) {
                    card.style.display = '';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            if (resultCount) {
                resultCount.textContent = 'Menampilkan ' + visible + ' kursus';
            }
        }

        searchInput.addEventListener('input', filterCourses);

        categoryBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                categoryBtns.forEach(b => {
                    b.classList.remove('bg-[#A487F8]', 'text-white');
                    b.classList.add('bg-slate-200', 'dark:bg-white/10', 'text-slate-600', 'dark:text-slate-300');
                });
                btn.classList.remove('bg-slate-200', 'dark:bg-white/10', 'text-slate-600', 'dark:text-slate-300');
                btn.classList.add('bg-[#A487F8]', 'text-white');
                filterCourses();
            });
        });
    });
</script>
@endsection