@extends('layouts.dashboard')

@section('title', 'CLEARN │ Verifikasi Kursus')

@section('content')

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}" />

<main class="flex-1 p-5 lg:p-8">
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <header class="mb-6 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
            <div>
                <h1 class="text-lg font-bold dark:text-white text-slate-800">
                    Verifikasi Kursus
                </h1>
                <p class="text-[10px] dark:text-slate-500 text-slate-400 font-medium mt-0.5">
                    Kelola persetujuan materi pembelajaran dari instruktur.
                </p>
            </div>

            <div class="px-4 py-2 rounded-xl bg-white dark:bg-[#1A1625] border border-slate-200 dark:border-white/5 shadow-sm">
                <p class="text-[9px] uppercase tracking-widest font-black text-slate-400">
                    Total Menunggu
                </p>
                <p class="text-sm font-black text-[#A487F8]">
                    {{ method_exists($pendingCourses, 'total') ? $pendingCourses->total() : $pendingCourses->count() }} Kursus
                </p>
            </div>
        </header>

        {{-- Alert Success --}}
        @if (session('success'))
            <div class="mb-5 px-4 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-600 text-xs font-bold">
                {{ session('success') }}
            </div>
        @endif

        {{-- Alert Error --}}
        @if ($errors->any())
            <div class="mb-5 px-4 py-3 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 text-xs font-bold">
                {{ $errors->first() }}
            </div>
        @endif

        {{-- Grid Kursus --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

            @forelse($pendingCourses as $course)

            <div class="bg-white dark:bg-[#1A1625] border dark:border-white/5 border-slate-200 rounded-2xl shadow-sm hover:shadow-lg transition-all overflow-hidden flex flex-col">

                {{-- Thumbnail --}}
                <a href="{{ route('admin.courses.show', $course->id) }}"
                    class="relative w-full h-40 bg-slate-100 dark:bg-[#0F0B1A] overflow-hidden group flex-shrink-0">

                    <img
                        src="{{ $course->course_thumbnail ? asset('storage/' . $course->course_thumbnail) : asset('images/default-course.png') }}"
                        alt="Thumbnail Kursus"
                        class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">

                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all flex items-center justify-center">
                        <span class="opacity-0 group-hover:opacity-100 text-white text-[9px] font-black uppercase tracking-widest bg-black/50 px-3 py-1.5 rounded-lg transition-all">
                            Lihat Detail
                        </span>
                    </div>

                    <div class="absolute top-2 left-2">
                        <span class="text-[8px] px-2 py-1 rounded-lg bg-amber-500 text-white font-black uppercase tracking-widest shadow">
                            {{ $course->status_review ?? 'pending' }}
                        </span>
                    </div>
                </a>

                {{-- Info Kursus --}}
                <div class="p-4 flex flex-col flex-1">

                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[9px] font-bold text-slate-400 uppercase">
                            <i class="fa-solid fa-user-circle mr-1 text-[#A487F8]"></i>
                            {{ $course->mentor?->name ?? 'Pengajar tidak ditemukan' }}
                        </span>
                        <span class="text-[8px] font-bold text-slate-400">
                            {{ $course->created_at?->format('d M Y') ?? '-' }}
                        </span>
                    </div>

                    <a href="{{ route('admin.courses.show', $course->id) }}">
                        <h2 class="text-sm font-black dark:text-white text-slate-800 leading-tight hover:text-[#A487F8] transition-colors line-clamp-2 mb-2">
                            {{ $course->course_title ?? 'Judul kursus tidak tersedia' }}
                        </h2>
                    </a>

                    <p class="text-[10px] text-slate-400 dark:text-slate-500 font-medium leading-relaxed line-clamp-2 mb-3">
                        {{ $course->course_description ?? 'Belum ada deskripsi kursus.' }}
                    </p>

                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="text-[#A487F8] text-[9px] font-black uppercase tracking-wider">
                            {{ $course->category?->category_name ?? 'Tanpa Kategori' }}
                        </span>
                        <span class="text-[9px] font-bold text-slate-400">
                            • {{ $course->sessions?->count() ?? 0 }} Pertemuan
                        </span>
                        <span class="text-[9px] font-bold text-slate-400">
                            • #{{ $course->id }}
                        </span>
                    </div>

                    <div class="mt-auto flex items-center gap-1 w-fit px-2 py-1 {{ $course->course_price == 0 ? 'bg-green-500/5 dark:bg-green-500/10 text-green-600 border-green-500/10 dark:border-green-500/20' : 'bg-emerald-500/5 dark:bg-emerald-500/10 text-emerald-600 border-emerald-500/10 dark:border-emerald-500/20' }} rounded-lg border font-black">
                        @if($course->course_price == 0)
                            <span class="text-[10px]">Gratis</span>
                        @else
                            <span class="text-[8px] uppercase">Rp</span>
                            <span class="text-[10px]">{{ number_format($course->course_price ?? 0, 0, ',', '.') }}</span>
                        @endif
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="px-4 pb-4 pt-3 border-t border-slate-100 dark:border-white/5">
                    <a href="{{ route('admin.courses.show', $course->id) }}"
                        class="block w-full py-2 bg-[#A487F8] text-white font-black text-center rounded-xl hover:opacity-90 active:scale-95 transition-all text-[9px] uppercase tracking-widest">
                        Detail
                    </a>
                </div>

            </div>

            @empty

            <div class="col-span-full py-16 text-center border-2 border-dashed border-slate-200 dark:border-white/5 rounded-2xl">
                <div class="w-12 h-12 mx-auto mb-3 rounded-2xl bg-slate-100 dark:bg-white/5 flex items-center justify-center">
                    <i class="fa-solid fa-folder-open text-slate-400"></i>
                </div>
                <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">
                    Tidak ada kursus pending
                </p>
            </div>

            @endforelse

        </div>

        {{-- Pagination --}}
        @if(method_exists($pendingCourses, 'links'))
            <div class="mt-6">
                {{ $pendingCourses->links() }}
            </div>
        @endif

    </div>
</main>

{{-- Modal Reject --}}
<div id="rejectModal" class="fixed inset-0 z-[150] hidden items-center justify-center bg-black/50 backdrop-blur-md p-4">
    <div class="bg-white dark:bg-[#1A1625] w-full max-w-sm p-6 rounded-2xl border dark:border-white/5 shadow-2xl">

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
                class="w-full px-4 py-3 bg-slate-50 dark:bg-[#1A1625] border dark:border-white/5 border-slate-200 rounded-xl h-24 text-xs mb-6 outline-none dark:text-white resize-none"
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