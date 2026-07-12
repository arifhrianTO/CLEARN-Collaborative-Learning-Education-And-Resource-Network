@extends('layouts.sections')

@section('title', 'CLEARN │ Kursus')

@section('content')

<section id="kursus" class="relative py-10 px-4 text-center overflow-hidden transition-colors duration-300 dark:bg-[#0F0B1A]">
    <button
        onclick="window.location='{{ route('home') }}'"
        class="absolute left-6 top-6 z-50 text-white hover:opacity-80 transition">
        <i class="fa-solid fa-arrow-left-long text-2xl -translate-x-1"></i>
    </button>

    <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-[#A487F8]/15 blur-[120px] rounded-full -z-10 opacity-0 dark:opacity-100 transition-opacity"></div>

    <h1 class="text-4xl md:text-5xl font-inter font-normal leading-[1.15] mb-8 tracking-tighter">
        Jelajahi Kursus Kami
    </h1>

    <p class="text-slate-600 dark:text-gray-400 max-w-2xl mx-auto text-base leading-relaxed">
        Temukan ribuan kursus dari para ahli industri dan tingkatkan keterampilan Anda.
    </p>
</section>

<div class="w-full bg-slate-100 dark:bg-[#0F0B1A] py-6 border-y border-slate-200 dark:border-white/5 transition-colors">
    <div class="max-w-7xl mx-auto px-4 md:px-10 flex flex-col md:flex-row items-center justify-between gap-4">

        <div class="relative w-full md:max-w-x6">
            <form id="courseSearchForm" action="{{ route('course') }}" method="GET" class="w-full">
                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-gray-500">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </span>
                <input
                    id="courseSearch"
                    name="search"
                    type="text"
                    value="{{ request('search') }}"
                    placeholder="Cari Kursus atau Pengajar..."
                    class="w-full bg-white dark:bg-[#0f0b1a] text-slate-900 dark:text-gray-200 text-sm rounded-full py-3 pl-12 pr-4 border border-slate-200 dark:border-white/10 focus:outline-none focus:ring-2 focus:ring-[#A487F8]/50 transition-all placeholder:text-gray-500" />
            </form>
        </div>

        <div class="flex items-center gap-3">
            <div class="flex items-center bg-white dark:bg-[#111116] p-1.5 rounded-xl border border-slate-200 dark:border-white/5">


            </div>

        </div>
    </div>
</div>

<section id="" class="py-20 px-10 max-w-[1400px] mx-auto">
    <div class="flex justify-between items-end mb-12 transition-colors">
        <div>
            @if(request('search'))
                <p class="text-slate-500 dark:text-gray-400 text-sm">Menemukan {{ $courses->count() }} kursus untuk pencarian "<span class="font-bold text-primary">{{ request('search') }}</span>"</p>
            @else
                <p class="text-slate-500 dark:text-gray-400 text-sm">Menampilkan {{ $courses->count() }} kursus</p>
            @endif
        </div>
    </div>

    @if($courses->count() > 0)
    <div id="courseContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($courses as $course)
        <x-landing.course :course="$course" />
        @endforeach
    </div>
    @else
    <div id="courseContainerEmpty" class="w-full bg-white dark:bg-[#111116] border border-slate-200 dark:border-white/5 rounded-3xl p-12 text-center">
        <div class="w-20 h-20 bg-slate-100 dark:bg-[#1a1625] rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fa-solid fa-search text-2xl text-slate-400"></i>
        </div>
        <h3 class="text-xl font-bold text-slate-800 dark:text-white mb-2">Kursus Tidak Ditemukan</h3>
        <p class="text-slate-500 dark:text-gray-400 text-sm mb-6 max-w-md mx-auto">Maaf, kami tidak dapat menemukan kursus yang cocok dengan pencarian Anda. Coba gunakan kata kunci lain.</p>
    </div>
    @endif
</section>

</main>
<section class="px-6 pb-24">
    <div class="max-w-[1100px] mx-auto mt-12 px-8 py-10 rounded-[30px] bg-[#A487F8] text-white
                flex flex-col items-center text-center">
        <h2 class="text-2xl md:text-3xl font-bold mb-3">
            Tidak Menemukan Kursus Anda?
        </h2>
        <p class="text-sm opacity-90 mb-6 max-w-xl">
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('courseSearch');
        
        if (searchInput) {
            let timer;
            
            searchInput.addEventListener('input', function() {
                clearTimeout(timer);
                
                timer = setTimeout(function() {
                    const searchValue = searchInput.value;
                    const url = new URL(window.location.href);
                    url.searchParams.set('search', searchValue);
                    
                    fetch(url.toString(), {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        
                        // Update the course list
                        const currentSection = document.querySelector('section.py-20');
                        const newSection = doc.querySelector('section.py-20');
                        
                        if (currentSection && newSection) {
                            currentSection.innerHTML = newSection.innerHTML;
                        }
                        
                        // Update URL without reload
                        window.history.pushState({}, '', url);
                    })
                    .catch(error => console.error('Error fetching search results:', error));
                }, 300); // 300ms debounce
            });
        }
    });
</script>
@endpush

@endsection