@extends('layouts.sections')

@section('title', 'CLEARN │ ' . $course->course_title)

@section('content')
<div class="p-6 lg:p-10">

    <a href="{{ route('student.dashboard') }}" class="fixed top-8 left-8 z-50 w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-[#1A1625] border border-gray-200 dark:border-[#2d2644] hover:scale-110 transition-all shadow-sm group">
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
                                <span class="text-muted-custom">{{ $course->mentor->name ?? 'Tim Pengajar Clearn' }}</span>
                            </div>
                            <span class="text-muted-custom">| {{ $course->enrollments->count() }} Peserta</span>
                        </div>

                        <p class="text-muted-custom leading-relaxed text-[13px]">
                            {{ $course->course_description }}
                        </p>
                    </div>

                    <div class="relative w-full md:w-56 aspect-video bg-slate-900 rounded-2xl overflow-hidden group cursor-pointer border border-gray-200 dark:border-[#2d2644] shadow-lg shrink-0">
                        <img src="{{ $course->course_thumbnail ? asset('storage/' . $course->course_thumbnail) : asset('images/default-course.png') }}"
                            class="w-full h-full object-cover opacity-60 group-hover:scale-110 transition duration-700">
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-black/20">
                            <i class="far fa-play-circle text-4xl text-white mb-2 group-hover:scale-110 transition-transform"></i>
                            <span class="text-[8px] text-white font-black uppercase tracking-[0.2em]">Pratinjau</span>
                        </div>
                    </div>
                </div>

                <div class="card-bg p-6 md:p-8">
                    <h3 class="text-[15px] font-black mb-6 flex items-center gap-2">
                        <span class="w-1.5 h-5 bg-primary rounded-full"></span>
                        Materi Kursus
                    </h3>
                    <div class="space-y-6">
                        @forelse($course->sessions as $sIndex => $session)
                            <div>
                                <h4 class="text-[11px] font-black text-slate-400 dark:text-gray-500 uppercase tracking-widest mb-3">
                                    {{ $session->sessions_title }}
                                </h4>
                                <div class="space-y-2">
                                    @forelse($session->lessons as $lIndex => $lesson)
                                        <div class="inner-item flex items-center justify-between p-4 bg-gray-50/50 dark:bg-[#1A1625] border border-gray-100 dark:border-[#2d2644] rounded-xl hover:border-primary/30 transition-all">
                                            <div class="flex items-center gap-4">
                                                <i class="fas fa-play-circle text-primary text-sm"></i>
                                                <span class="text-[12px] font-extrabold uppercase tracking-tight">
                                                    {{ $sIndex + 1 }}.{{ $lIndex + 1 }} {{ $lesson->lessons_title }}
                                                </span>
                                            </div>
                                            <span class="text-[10px] text-muted-custom font-bold uppercase tracking-wider bg-gray-100 dark:bg-gray-800 px-2 py-0.5 rounded">Materi</span>
                                        </div>
                                    @empty
                                        <p class="text-[11px] text-muted-custom pl-4 italic">Belum ada materi di bab ini.</p>
                                    @endforelse

                                    @if($session->exercise)
                                        <a href="{{ route('student.quiz.show', $session->exercise->id) }}" class="inner-item flex items-center justify-between p-4 bg-gray-50/50 dark:bg-[#1A1625] border border-gray-100 dark:border-[#2d2644] rounded-xl hover:border-primary/30 transition-all group">
                                            <div class="flex items-center gap-4">
                                                <i class="fas fa-file-alt text-primary text-sm"></i>
                                                <span class="text-[12px] font-extrabold uppercase tracking-tight group-hover:text-primary transition-colors">
                                                    Latihan: {{ $session->exercise->exercise_title }}
                                                </span>
                                            </div>
                                            <span class="text-[10px] text-amber-500 font-bold uppercase tracking-wider bg-amber-500/10 px-2 py-0.5 rounded">Kuis</span>
                                        </a>
                                    @endif

                                    @foreach($session->finalProjects as $project)
                                        <div class="inner-item flex items-center justify-between p-4 bg-emerald-50/50 dark:bg-emerald-500/5 border border-emerald-100 dark:border-emerald-500/20 rounded-xl">
                                            <div class="flex items-center gap-4 min-w-0">
                                                <i class="fas fa-diagram-project text-emerald-500 text-sm shrink-0"></i>
                                                <div class="min-w-0">
                                                    <span class="text-[12px] font-extrabold uppercase tracking-tight">
                                                        {{ $project->project_title }}
                                                    </span>
                                                    @if($project->duration_days)
                                                        <p class="text-[10px] text-muted-custom font-medium mt-0.5">
                                                            <i class="fa-regular fa-clock"></i> Waktu Pengerjaan: {{ $project->duration_days }} Hari
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                            <span class="text-[10px] text-emerald-500 font-bold uppercase tracking-wider bg-emerald-500/10 px-2 py-0.5 rounded shrink-0">Tugas Akhir</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-[12px] text-muted-custom italic">Belum ada materi tersedia untuk kursus ini.</p>
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
                        @elseif(isset($hasPendingPayment) && $hasPendingPayment)
                            <a href="{{ route('student.checkout', ['course_id' => $course->id]) }}" class="w-full bg-amber-500 hover:brightness-110 text-white font-black py-4 rounded-xl flex items-center justify-center gap-3 transition-all active:scale-95 shadow-lg shadow-amber-500/20 uppercase tracking-[0.2em] text-[10px]">
                                <i class="fas fa-credit-card text-sm"></i>
                                Lanjutkan Pembayaran
                            </a>
                        @else
                            <a href="{{ auth()->check() ? route('public.course.enroll', $course->course_slug) : route('login') }}" 
                               @if(auth()->check()) onclick="event.preventDefault(); document.getElementById('enrollConfirmModal').classList.remove('hidden');" @endif
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

<!-- Modal Konfirmasi Pendaftaran -->
<div id="enrollConfirmModal" class="fixed inset-0 z-[200] flex items-center justify-center hidden p-4 bg-black/40 backdrop-blur-sm">
    <div class="bg-white dark:bg-[#1A1625] p-6 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 w-full max-w-sm flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-sm font-black dark:text-white uppercase tracking-wider">Konfirmasi Pendaftaran</h2>
            <button type="button" onclick="document.getElementById('enrollConfirmModal').classList.add('hidden')" class="text-gray-400 hover:text-primary">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <p class="text-xs text-slate-500 dark:text-gray-400 mb-6 leading-relaxed">
            Apakah Anda yakin ingin mendaftar di kursus <strong>"{{ $course->course_title }}"</strong>?
            @if($course->course_price > 0)
                <br><br>
                <span class="text-primary font-bold">Investasi Ilmu: Rp{{ number_format($course->course_price, 0, ',', '.') }}</span>
            @endif
        </p>
        <div class="flex gap-3">
            <button type="button" onclick="document.getElementById('enrollConfirmModal').classList.add('hidden')" class="flex-1 py-3 bg-gray-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 rounded-xl font-bold text-[10px] uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-white/10 transition-all">
                Batal
            </button>
            <button type="button" onclick="document.getElementById('enroll-form').submit();" class="flex-1 py-3 bg-primary text-white rounded-xl font-bold text-[10px] uppercase tracking-widest hover:opacity-90 transition-all shadow-lg shadow-primary/20">
                Ya, Daftar
            </button>
        </div>
    </div>
</div>
@endsection