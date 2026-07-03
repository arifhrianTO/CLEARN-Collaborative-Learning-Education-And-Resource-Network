@extends('layouts.dashboard')

@section('title', 'Verifikasi Kursus | Dashboard Admin')

@section('content')

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}" />

<main class="flex-1 p-5 lg:p-8">
    <div class="max-w-6xl mx-auto">

        <header class="mb-6 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold dark:text-white text-slate-800">
                    Verifikasi Kursus
                </h1>
                <p class="text-[10px] dark:text-slate-500 text-slate-400 font-medium mt-0.5">
                    Kelola persetujuan materi pembelajaran dari instruktur.
                </p>
            </div>

            <div class="px-4 py-2 rounded-xl bg-white dark:bg-[#161525] border border-slate-200 dark:border-white/5 shadow-sm">
                <p class="text-[9px] uppercase tracking-widest font-black text-slate-400">
                    Total Pending
                </p>
                <p class="text-sm font-black text-[#A487F8]">
                    {{ method_exists($pendingCourses, 'total') ? $pendingCourses->total() : $pendingCourses->count() }} Kursus
                </p>
            </div>
        </header>

        @if (session('success'))
        <div class="mb-5 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs font-bold">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-5 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 text-xs font-bold">
            {{ $errors->first() }}
        </div>
        @endif

        <div class="grid grid-cols-1 gap-4">

            @forelse($pendingCourses as $course)
            <div class="bg-white dark:bg-[#161525] border dark:border-white/5 border-slate-200 p-1.5 rounded-2xl shadow-sm hover:shadow-lg transition-all">
                <div class="flex flex-col md:flex-row gap-4 p-3">

                    <a href="{{ route('admin.courses.show', $course->id) }}"
                        class="relative w-full md:w-44 h-32 rounded-xl overflow-hidden flex-shrink-0 bg-slate-100 dark:bg-[#0b0a1a] border dark:border-white/5 border-slate-200 group">

                        <img
                            src="{{ $course->course_thumbnail ? asset('storage/' . $course->course_thumbnail) : asset('images/default-course.png') }}"
                            alt="Thumbnail Kursus"
                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all flex items-center justify-center">
                            <span class="opacity-0 group-hover:opacity-100 text-white text-[9px] font-black uppercase tracking-widest bg-black/50 px-3 py-1.5 rounded-lg transition-all">
                                Lihat Detail
                            </span>
                        </div>
                    </a>

                    <div class="flex-1 min-w-0 flex flex-col justify-center">

                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-[8px] px-2 py-1 rounded-lg bg-amber-500 text-white font-black uppercase tracking-widest">
                                {{ $course->status_review ?? 'pending' }}
                            </span>

                            <span class="text-[9px] font-bold text-slate-400">
                                Diajukan {{ $course->created_at ? $course->created_at->format('d M Y') : '-' }}
                            </span>
                        </div>

                        <span class="text-[9px] font-bold text-slate-400 uppercase mb-1">
                            <i class="fa-solid fa-user-circle mr-1 text-[#A487F8]"></i>
                            {{ $course->mentor?->name ?? 'Mentor tidak ditemukan' }}
                        </span>

                        <a href="{{ route('admin.courses.show', $course->id) }}">
                            <h2 class="text-sm font-black dark:text-white text-slate-800 mb-2 leading-tight hover:text-[#A487F8] transition-colors">
                                {{ $course->course_title ?? 'Judul kursus tidak tersedia' }}
                            </h2>
                        </a>

                        <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium leading-relaxed mb-2 line-clamp-2">
                            {{ $course->course_description ?? 'Belum ada deskripsi kursus.' }}
                        </p>

                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <span class="text-[#A487F8] text-[9px] font-black uppercase tracking-wider">
                                {{ $course->category?->category_name ?? 'Tanpa Kategori' }}
                            </span>

                            <span class="text-[9px] font-bold text-slate-400">
                                • {{ $course->sessions?->count() ?? 0 }} Session
                            </span>

                            <span class="text-[9px] font-bold text-slate-400">
                                • ID Kursus #{{ $course->id }}
                            </span>
                        </div>

                        <div class="flex items-center gap-1 w-fit px-2 py-1 bg-emerald-500/5 dark:bg-emerald-500/10 rounded-lg border border-emerald-500/10 dark:border-emerald-500/20 text-emerald-600 font-black">
                            <span class="text-[8px] uppercase">Rp</span>
                            <span class="text-[10px]">
                                {{ number_format($course->course_price ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                    <div class="flex md:flex-col justify-center gap-2 w-full md:w-36 border-t md:border-t-0 md:border-l border-slate-200 dark:border-white/5 pt-4 md:pt-0 md:pl-4">

                        <a href="{{ route('admin.courses.show', $course->id) }}"
                            class="flex-1 md:w-full py-2 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 font-black text-center rounded-xl hover:bg-[#7C3AED] hover:text-white transition-all text-[9px] uppercase tracking-widest">
                            Detail
                        </a>

                        <form
                            action="{{ route('admin.courses.approve', $course->id) }}"
                            method="POST"
                            class="flex-1 md:w-full"
                            onsubmit="return confirm('Yakin ingin menyetujui kursus ini?')">
                            @csrf
                            @method('PATCH')

                            <button
                                type="submit"
                                class="w-full py-2 bg-[#A487F8] text-white font-black text-center text-[9px] uppercase tracking-widest rounded-xl hover:opacity-90 active:scale-95 transition-all">
                                Approve
                            </button>
                        </form>

                        <button
                            type="button"
                            onclick="openRejectModal({{ $course->id }})"
                            class="flex-1 md:w-full py-2 bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-400 font-black rounded-xl hover:bg-red-500 hover:text-white transition-all text-[9px] uppercase tracking-widest">
                            Reject
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="py-16 text-center border-2 border-dashed border-slate-200 dark:border-white/5 rounded-2xl">
                <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center">
                    <i class="fa-solid fa-folder-open text-slate-400"></i>
                </div>

                <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">
                    Tidak ada kursus pending
                </p>
            </div>
            @endforelse

        </div>

        @if(method_exists($pendingCourses, 'links'))
        <div class="mt-6">
            {{ $pendingCourses->links() }}
        </div>
        @endif

    </div>
</main>

<div id="rejectModal" class="fixed inset-0 z-[150] hidden items-center justify-center bg-black/50 backdrop-blur-md p-4">
    <div class="bg-white dark:bg-[#161525] w-full max-w-sm p-6 rounded-2xl border dark:border-white/5 shadow-2xl">

        <h3 class="text-lg font-black mb-2 dark:text-white text-slate-800">
            Tolak Kursus
        </h3>

        <p class="text-slate-500 text-xs mb-6 font-medium">
            Berikan alasan penolakan agar instruktur dapat memperbaiki kursusnya.
        </p>

        <form id="rejectForm" action="" method="POST">
            @csrf
            @method('PATCH')

            <textarea
                name="course_rejection_reason"
                required
                class="w-full px-4 py-3 bg-slate-50 dark:bg-[#0f0a19] border dark:border-white/5 border-slate-200 rounded-xl h-24 text-xs mb-6 outline-none dark:text-white resize-none"
                placeholder="Contoh: Materi belum lengkap, thumbnail tidak sesuai, atau deskripsi kurang jelas...">{{ old('course_rejection_reason') }}</textarea>

            <div class="flex flex-col gap-2">
                <button
                    type="submit"
                    class="w-full py-3 bg-red-500 text-white font-black rounded-xl text-[10px] uppercase tracking-widest hover:bg-red-600 active:scale-95 transition-all">
                    Kirim Penolakan
                </button>

                <button
                    type="button"
                    onclick="closeRejectModal()"
                    class="w-full py-2.5 font-bold text-slate-400 text-[10px] uppercase tracking-widest hover:text-slate-600 dark:hover:text-white transition-all">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    function openRejectModal(courseId) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');

        form.action = "{{ url('/admin/courses') }}/" + courseId + "/reject";

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeRejectModal() {
        const modal = document.getElementById('rejectModal');

        modal.classList.remove('flex');
        modal.classList.add('hidden');
    }
</script>
@endpush