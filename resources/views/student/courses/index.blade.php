@extends('layouts.dashboard')

@section('content')

<!-- Component Sidebar -->
<x-dashboard.sidebar
    role="student"
    name="{{ auth()->user()->name ?? 'User Pelajar' }}"
    initials="{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}"
    photo="{{ auth()->user()->profile_picture }}"
    active="dashboard" />

<!-- Content -->
<main class="flex-1 p-6 lg:p-10 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50">
    <div class="max-w-5xl mx-auto">

        {{-- Header Halaman --}}
        <div class="mb-6">
            <h1 class="text-xl font-bold dark:text-white text-slate-800">Pembelajaran Saya</h1>
            <p class="text-[11px] dark:text-slate-500 text-slate-400">Pantau kemajuan Anda dan lanjutkan perjalanan pembelajaran Anda.</p>
        </div>

        {{-- Ringkasan Statistik (4 Kolom) dengan bg-[#161525] --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
            <!-- Statistik 1: Kursus -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-[#A487F8]/10 flex items-center justify-center text-[#A487F8] text-sm">
                    <i class="fas fa-play"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">{{ $enrollments->total() }}</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">Kursus</span>
                </div>
            </div>

            <!-- Statistik 2: Belajar -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-orange-500/10 flex items-center justify-center text-orange-500 text-sm">
                    <i class="fas fa-chart-line"></i>
                </div>
                <div>
                    @php
                        $ongoing = collect($enrollments->items())->filter(function($enrollment) { return $enrollment->progress > 0 && $enrollment->progress < 100; })->count();
                    @endphp
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">{{ $ongoing }}</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">Belajar</span>
                </div>
            </div>

            <!-- Statistik 3: Selesai -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center text-green-500 text-sm">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    @php
                        $completed = collect($enrollments->items())->filter(function($enrollment) { return $enrollment->progress == 100; })->count();
                    @endphp
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">{{ $completed }}</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">Selesai</span>
                </div>
            </div>

            <!-- Statistik 4: Sertifikat -->
            <div class="p-5 flex items-center gap-4 dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm">
                <div class="w-10 h-10 rounded-xl bg-[#A487F8]/10 flex items-center justify-center text-[#A487F8] text-sm">
                    <i class="fas fa-award"></i>
                </div>
                <div>
                    @php
                        // certificates table tidak punya user_id langsung,
                        // relasinya lewat enrollment_id -> enrollments.student_id
                        $totalCertificates = \App\Models\Certificate::whereHas('enrollment', function ($q) {
                            $q->where('student_id', auth()->id());
                        })->count();
                    @endphp
                    <h2 class="text-xl font-black leading-none dark:text-white text-slate-800">{{ $totalCertificates }}</h2>
                    <span class="text-[9px] font-bold dark:text-slate-500 text-slate-400 uppercase tracking-widest">Sertifikat</span>
                </div>
            </div>
        </div>

        {{-- Filter Tab Konten --}}
        <div class="flex flex-wrap gap-2 mb-6">
            <button class="px-6 py-2 rounded-xl text-[11px] font-bold transition-all bg-primary text-white shadow-sm border border-primary active:scale-95">
                Semua Kursus
            </button>
            <button class="px-6 py-2 rounded-xl text-[11px] font-bold transition-all dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 dark:text-slate-400 text-slate-500 hover:border-primary active:scale-95">
                Sedang Berjalan
            </button>
            <button class="px-6 py-2 rounded-xl text-[11px] font-bold transition-all dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 dark:text-slate-400 text-slate-500 hover:border-primary active:scale-95">
                Selesai
            </button>
        </div>

        {{-- Grid Course List: 1 Baris 3 Kolom --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

            @foreach($enrollments as $enrollment)
                @php
                    $isCompleted = $enrollment->progress == 100;
                    $colorClass = $isCompleted ? 'emerald' : 'primary';
                    $coverImage = $enrollment->course->course_thumbnail
                        ? (Str::startsWith($enrollment->course->course_thumbnail, 'http')
                            ? $enrollment->course->course_thumbnail
                            : Storage::url($enrollment->course->course_thumbnail))
                        : 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?q=80&w=400&auto=format&fit=crop';
                @endphp
                <div class="dark:bg-[#161525] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden flex flex-col group shadow-sm hover:border-{{ $colorClass }}-500/50 transition-colors">
                    <div class="relative aspect-[16/10] overflow-hidden bg-slate-200 block" onclick="window.location='{{ route('public.course.show', $enrollment->course->course_slug) }}'">
                        <img src="{{ $coverImage }}" alt="{{ $enrollment->course->course_title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300 cursor-pointer">
                        <span class="absolute top-3 right-3 bg-{{ $isCompleted ? 'emerald' : 'primary' }} text-white text-[8px] font-black uppercase tracking-wider px-2 py-1 rounded-md">
                            {{ $isCompleted ? 'LULUS' : 'DIBELI' }}
                        </span>
                        @if($enrollment->course->category)
                            <div class="absolute bottom-3 left-3 bg-black/70 backdrop-blur-sm text-white text-[9px] font-bold px-2 py-1 rounded">
                                {{ $enrollment->course->category->category_name }}
                            </div>
                        @endif
                    </div>

                    <div class="p-5 flex-1 flex flex-col justify-between">
                        <div class="mb-4 cursor-pointer" onclick="window.location='{{ route('student.course.show', $enrollment->course->course_slug) }}'">
                            <h3 class="text-xs font-bold leading-tight tracking-tight dark:text-white text-slate-800 mb-1 line-clamp-2">{{ $enrollment->course->course_title }}</h3>
                            <p class="text-[10px] dark:text-slate-500 text-slate-400 font-medium">{{ $enrollment->course->mentor->name ?? 'Tim Mentor Clearn' }}</p>
                        </div>

                        <div class="space-y-3">
                            <div class="space-y-1">
                                <div class="flex justify-between text-[9px] font-bold uppercase tracking-wider dark:text-slate-500 text-slate-400">
                                    <span>{{ $isCompleted ? 'Selesai' : 'Progress Belajar' }}</span>
                                    <span class="text-{{ $isCompleted ? 'emerald' : 'primary' }}">{{ $enrollment->progress }}%</span>
                                </div>
                                <div class="w-full h-1.5 dark:bg-[#0b0a1a] bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-{{ $isCompleted ? 'emerald' : 'primary' }} rounded-full" style="width: {{ $enrollment->progress }}%"></div>
                                </div>
                            </div>

                            @if($isCompleted)
                                <div class="grid grid-cols-2 gap-2">
                                    <a href="{{ route('student.certif') }}" class="block text-center w-full bg-emerald-500 text-white text-[10px] font-bold py-2.5 rounded-xl shadow-lg shadow-emerald-500/10 hover:brightness-110 transition-all uppercase tracking-widest active:scale-95">
                                        Klaim
                                    </a>
                                    <button onclick="showReviewModal()" class="w-full bg-emerald-500 text-white text-[10px] font-bold py-2.5 rounded-xl shadow-lg shadow-emerald-500/10 hover:brightness-110 transition-all uppercase tracking-widest active:scale-95">
                                        Beri Nilai
                                    </button>
                                </div>
                            @else
                                <a href="{{ route('student.course.lesson', $enrollment->course->course_slug) }}" class="block text-center w-full bg-primary text-white text-[10px] font-bold py-2.5 rounded-xl shadow-lg shadow-primary/20 hover:brightness-110 transition-all uppercase tracking-widest active:scale-95">
                                    Lanjutkan
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Slot Kosong / Tambah Kursus Baru --}}
            <div class="dark:bg-[#161525]/40 bg-slate-100/50 border-2 border-dashed dark:border-white/5 border-slate-200 rounded-2xl flex flex-col items-center justify-center p-6 text-center group min-h-[330px]">
                <div class="w-12 h-12 rounded-xl dark:bg-[#161525] bg-slate-200/50 flex items-center justify-center text-slate-400 mb-3 group-hover:scale-110 transition-transform">
                    <i class="fas fa-plus text-sm"></i>
                </div>
                <p class="text-xs font-bold dark:text-slate-400 text-slate-600 mb-1">Ingin Belajar Hal Baru?</p>
                <p class="text-[10px] dark:text-slate-500 text-slate-400 max-w-[180px] mb-4">Temukan ratusan materi berkualitas lainnya.</p>
                <a href="{{ route('course') }}" class="px-5 py-2 inline-block border dark:border-white/5 border-slate-300 dark:text-white text-slate-700 text-[10px] font-bold rounded-xl uppercase tracking-widest hover:bg-white dark:hover:bg-[#161525] transition-all">
                    Jelajahi Kursus
                </a>
            </div>

        </div>

        <div class="mt-6">
            {{ collect($enrollments->items())->isEmpty() ? '' : $enrollments->links() }}
        </div>

    </div>
</main>

@push('library')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('scripts')
<script>
    function showReviewModal() {
        Swal.fire({
            title: 'Beri Penilaian',
            html: `
            <div class="flex flex-col gap-4 text-left">
                <div>
                    <label class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Rating</label>
                    <div class="flex justify-center gap-2 my-2 text-2xl text-amber-400" id="star-rating">
                        <i class="fas fa-star cursor-pointer" onclick="setRating(1)"></i>
                        <i class="fas fa-star cursor-pointer" onclick="setRating(2)"></i>
                        <i class="fas fa-star cursor-pointer" onclick="setRating(3)"></i>
                        <i class="fas fa-star cursor-pointer" onclick="setRating(4)"></i>
                        <i class="fas fa-star cursor-pointer" onclick="setRating(5)"></i>
                    </div>
                </div>
                <textarea id="review-comment" class="w-full p-3 bg-slate-50 dark:bg-[#0b0a1a] border dark:border-white/10 rounded-xl text-xs" placeholder="Ceritakan pengalaman Anda..."></textarea>
            </div>
        `,
            confirmButtonText: 'Kirim Ulasan',
            confirmButtonColor: '#A487F8',
            background: document.documentElement.classList.contains('dark') ? '#161525' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            preConfirm: () => {
                const comment = document.getElementById('review-comment').value;
                return {
                    comment: comment
                };
            }
        });
    }

    // Fungsi sederhana untuk interaksi bintang (opsional)
    function setRating(rating) {
        const stars = document.querySelectorAll('#star-rating i');
        stars.forEach((star, index) => {
            star.style.color = index < rating ? '#fbbf24' : '#d1d5db';
        });
    }
</script>
@endpush

@endsection