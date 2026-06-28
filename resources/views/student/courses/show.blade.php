@extends('layouts.sections')

@section('title', $course->course_title . ' - Clearn')

@section('content')
<div class="p-6 lg:p-10">

    <a href="javascript:history.back()" class="fixed top-8 left-8 z-50 w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-[#110D1F] border border-gray-200 dark:border-[#2d2644] hover:scale-110 transition-all shadow-sm group">
        <i class="fas fa-arrow-left text-primary group-hover:-translate-x-1 transition-transform"></i>
    </a>

    <div class="max-w-5xl mx-auto">

        <div class="mb-8 mt-12">
            <h1 class="text-2xl font-black tracking-tighter flex items-center gap-3">
                <i class="fas fa-shopping-cart text-primary"></i> Ikuti Kursus Ini
            </h1>
            <p class="text-muted-custom text-[12px] mt-1">Mulai perjalanan Anda menjadi profesional bersama tim mentor kami.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <div class="lg:col-span-2 space-y-5">
                <div class="card-bg p-6 md:p-8 flex flex-col md:flex-row gap-8 items-start">
                    <div class="flex-1">
                        <div class="inline-flex items-center gap-2 bg-primary/10 px-3 py-1 rounded-lg text-primary text-[9px] font-black uppercase tracking-widest mb-4">
                            <i class="fas fa-code"></i> {{ $course->category->category_name ?? 'Kategori' }}
                        </div>
                        <h2 class="text-2xl font-black mb-4 leading-tight tracking-tight">
                            {{ $course->course_title }}
                        </h2>

                        <div class="flex items-center gap-4 text-[11px] font-bold mb-6">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-[8px] text-white font-black">
                                    {{ strtoupper(substr($course->mentor->name ?? 'CL', 0, 2)) }}
                                </div>
                                <span class="text-muted-custom">{{ $course->mentor->name ?? 'Tim Mentor Clearn' }}</span>
                            </div>
                            <span class="text-yellow-500 flex items-center gap-1">
                                <i class="fas fa-star"></i> 4.8
                            </span>
                            <span class="text-muted-custom">| {{ $course->enrollments->count() }} Peserta</span>
                        </div>

                        <p class="text-muted-custom leading-relaxed text-[13px]">
                            {{ $course->course_description }}
                        </p>
                    </div>

                    <div class="relative w-full md:w-56 aspect-video bg-slate-900 rounded-2xl overflow-hidden group cursor-pointer border border-gray-200 dark:border-[#2d2644] shadow-lg shrink-0">
                        <img src="{{ asset('storage/' . $course->course_thumbnail) }}"
                            class="w-full h-full object-cover opacity-60 group-hover:scale-110 transition duration-700">
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/20">
                            <i class="far fa-play-circle text-4xl text-white mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="text-[8px] text-white font-black uppercase tracking-[0.2em]">Preview</span>
                        </div>
                    </div>
                </div>

                <div class="card-bg p-6 md:p-8">
                    <h3 class="text-[15px] font-black mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-primary rounded-full"></span>
                        Apa yang Akan Anda Pelajari
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary mt-0.5 text-sm"></i>
                            <p class="text-[12px] font-bold">Membuat struktur website menggunakan HTML5</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary mt-0.5 text-sm"></i>
                            <p class="text-[12px] font-bold">Mendesain tampilan modern dengan Tailwind</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary mt-0.5 text-sm"></i>
                            <p class="text-[12px] font-bold">Interaksi menggunakan JavaScript ES6</p>
                        </div>
                        <div class="flex items-start gap-3">
                            <i class="fas fa-check-circle text-primary mt-0.5 text-sm"></i>
                            <p class="text-[12px] font-bold">Membangun aplikasi dengan Laravel</p>
                        </div>
                    </div>
                </div>

                <div class="card-bg p-6 md:p-8">
                    <h3 class="text-[15px] font-black mb-6">Materi Kursus</h3>
                    <div class="space-y-3">
                        @forelse($course->materis ?? [] as $i => $materi)
                        <div class="inner-item flex items-center justify-between p-4 {{ !$materi->is_free ? 'opacity-50' : '' }}">
                            <div class="flex items-center gap-4">
                                <i class="fas {{ $materi->is_free ? 'fa-file-alt' : 'fa-lock' }} text-primary"></i>
                                <span class="text-[12px] font-extrabold uppercase tracking-tight">
                                    {{ $i + 1 }}. {{ $materi->title }}
                                </span>
                            </div>
                            @if($materi->is_free)
                            <span class="bg-primary/10 text-primary text-[8px] px-2 py-0.5 rounded font-black uppercase tracking-widest">Gratis</span>
                            @else
                            <i class="fas fa-video-slash text-[10px] text-muted-custom"></i>
                            @endif
                        </div>
                        @empty
                        <p class="text-[12px] text-muted-custom">Belum ada materi tersedia.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Sidebar Pembayaran --}}
            <div class="lg:col-span-1">
                <div class="card-bg p-6 sticky top-10">
                    <h3 class="text-[10px] font-black mb-6 pb-4 border-b border-gray-100 dark:border-[#2d2644] uppercase tracking-[0.2em] text-muted-custom">
                        Ringkasan Kursus
                    </h3>

                    <div class="space-y-4 mb-8">
                        <div class="flex justify-between text-[12px]">
                            <span class="text-muted-custom font-bold"><i class="fas fa-layer-group w-6 text-primary"></i> Materi</span>
                            <span class="font-black">{{ $course->sessions_count ?? 0 }} Modul</span>
                        </div>
                        <div class="flex justify-between text-[12px]">
                            <span class="text-muted-custom font-bold"><i class="fas fa-infinity w-6 text-primary"></i> Akses</span>
                            <span class="font-black">Selamanya</span>
                        </div>
                        <div class="flex justify-between text-[12px]">
                            <span class="text-muted-custom font-bold"><i class="fas fa-certificate w-6 text-primary"></i> Sertifikat</span>
                            <span class="font-black">Ya</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-gray-100 dark:border-[#2d2644]">
                        <p class="text-[9px] text-muted-custom mb-1 font-black uppercase tracking-[0.2em]">Investasi Ilmu</p>
                        <div class="text-3xl font-black text-primary mb-8 tracking-tighter">
                            Rp{{ number_format($course->course_price, 0, ',', '.') }}
                        </div>

                        @if(isset($sudahEnroll) && $sudahEnroll && isset($isPaid) && $isPaid)
                            <a href="{{ route('student.course.lesson', $course->course_slug) }}" class="w-full bg-primary hover:brightness-110 text-white font-black py-4 rounded-xl flex items-center justify-center gap-3 transition-all active:scale-95 shadow-lg shadow-primary/20 uppercase tracking-[0.2em] text-[10px]">
                                <i class="fas fa-play text-sm"></i>
                                Mulai Belajar
                            </a>
                        @else
                            <a href="{{ auth()->check() ? route('public.course.enroll', $course->course_slug) : route('login') }}" 
                               @if(auth()->check()) onclick="event.preventDefault(); document.getElementById('enroll-form').submit();" @endif
                               class="w-full bg-primary hover:brightness-110 text-white font-black py-4 rounded-xl flex items-center justify-center gap-3 transition-all active:scale-95 shadow-lg shadow-primary/20 uppercase tracking-[0.2em] text-[10px]">
                                <i class="fas fa-shopping-bag text-sm"></i>
                                Daftar Sekarang
                            </a>
                            @if(auth()->check())
                            <form id="enroll-form" action="{{ route('public.course.enroll', $course->course_slug) }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection