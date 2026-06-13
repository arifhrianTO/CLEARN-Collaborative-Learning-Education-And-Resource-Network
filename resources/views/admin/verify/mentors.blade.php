@extends('layouts.dashboard')

@section('title', 'Daftar Mentor | Dashboard Admin | Clearn - Platform Pembelajaran Online')

@section('content')

<!-- Component Sidebar -->
<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}"
    active="mentor-verification" 
/>

<!-- Content -->
<main class="flex-1 p-6 pt-10">
    <div class="max-w-5xl mx-auto">

        {{-- Header --}}
        <header class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-xl font-bold">
                    Verifikasi Pengajar
                </h1>
                <p class="text-[11px] text-slate-500">
                    Tinjau dokumen calon pengajar sebelum memberikan akses dashboard.
                </p>
            </div>

            @if(session('success'))
            <div class="bg-emerald-500/10 text-emerald-500 px-4 py-2.5 rounded-xl text-[12px] font-bold border border-emerald-500/20 flex items-center gap-2">
                <i class="fa-solid fa-circle-check"></i>
                {{ session('success') }}
            </div>
            @endif
        </header>

        {{-- Content Area --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

            @forelse($pendingMentors as $item)

            {{-- Card --}}
            <div class="card-bg p-7 rounded-[2rem] flex flex-col transition-all hover:border-primary/30 group">

                {{-- Profil --}}
                <div class="flex items-center gap-4 mb-7">
                    <div class="w-14 h-14 rounded-2xl bg-primary flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-primary/20 overflow-hidden">
                        @if($item->profile_picture)
                        <img
                            src="{{ asset('storage/' . $item->profile_picture) }}"
                            alt="{{ $item->name }}"
                            class="w-full h-full object-cover">
                        @else
                        {{ strtoupper(substr($item->name ?? 'M', 0, 1)) }}
                        @endif
                    </div>

                    <div class="flex-1 min-w-0">
                        <h3 class="font-bold text-[15px] truncate">
                            {{ trim(($item->profileAccount?->front_title ?? '') . ' ' . ($item->name ?? 'Tanpa Nama') . ' ' . ($item->profileAccount?->back_title ?? '')) }}
                        </h3>

                        <p class="text-[10px] text-primary font-extrabold uppercase tracking-[0.15em] mt-0.5">
                            {{ $item->profileAccount?->expertise ?? 'PENDING MENTOR' }}
                        </p>
                    </div>
                </div>

                {{-- Links --}}
                <div class="grid grid-cols-3 gap-2 mb-7">
                    <a href="{{ $item->profileAccount?->linkedin_link ?? '#' }}"
                        target="_blank"
                        class="flex flex-col items-center p-3 rounded-xl bg-slate-100 dark:bg-white/5 hover:bg-blue-500/10 transition-colors">
                        <i class="fab fa-linkedin text-blue-500 mb-1"></i>
                        <span class="text-[8px] font-bold text-slate-400">LINKEDIN</span>
                    </a>

                    <a href="{{ $item->profileAccount?->sinta_link ?? '#' }}"
                        target="_blank"
                        class="flex flex-col items-center p-3 rounded-xl bg-slate-100 dark:bg-white/5 hover:bg-emerald-500/10 transition-colors">
                        <i class="fas fa-graduation-cap text-emerald-500 mb-1"></i>
                        <span class="text-[8px] font-bold text-slate-400">SINTA</span>
                    </a>

                    <a href="{{ $item->profileAccount?->scopus_link ?? '#' }}"
                        target="_blank"
                        class="flex flex-col items-center p-3 rounded-xl bg-slate-100 dark:bg-white/5 hover:bg-orange-500/10 transition-colors">
                        <i class="fas fa-link text-orange-500 mb-1"></i>
                        <span class="text-[8px] font-bold text-slate-400">SCOPUS</span>
                    </a>
                </div>

                {{-- Dokumen --}}
                <div class="space-y-2 mb-8">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">
                        Dokumen Pendukung
                    </label>

                    @if($item->profileAccount?->cv_file)
                    <a href="{{ asset('storage/' . $item->profileAccount->cv_file) }}"
                        target="_blank"
                        class="flex items-center justify-between p-3.5 px-4 rounded-xl bg-slate-100 dark:bg-white/5 text-[11px] font-bold text-slate-600 dark:text-slate-400 hover:text-primary transition-all">
                        <span>CV & Identitas</span>
                        <i class="fa-solid fa-file-pdf"></i>
                    </a>
                    @else
                    <div class="flex items-center justify-between p-3.5 px-4 rounded-xl bg-slate-100 dark:bg-white/5 text-[11px] font-bold text-slate-400">
                        <span>CV belum diupload</span>
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    @endif

                    @if($item->profileAccount?->diploma_file)
                    <a href="{{ asset('storage/' . $item->profileAccount->diploma_file) }}"
                        target="_blank"
                        class="flex items-center justify-between p-3.5 px-4 rounded-xl bg-slate-100 dark:bg-white/5 text-[11px] font-bold text-slate-600 dark:text-slate-400 hover:text-primary transition-all">
                        <span>Ijazah Terakhir</span>
                        <i class="fa-solid fa-file-pdf"></i>
                    </a>
                    @else
                    <div class="flex items-center justify-between p-3.5 px-4 rounded-xl bg-slate-100 dark:bg-white/5 text-[11px] font-bold text-slate-400">
                        <span>Ijazah belum diupload</span>
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    @endif

                    @if($item->profileAccount?->certificate_file)
                    <a href="{{ asset('storage/' . $item->profileAccount->certificate_file) }}"
                        target="_blank"
                        class="flex items-center justify-between p-3.5 px-4 rounded-xl bg-slate-100 dark:bg-white/5 text-[11px] font-bold text-slate-600 dark:text-slate-400 hover:text-primary transition-all">
                        <span>Gabungan Sertifikat</span>
                        <i class="fa-solid fa-file-pdf"></i>
                    </a>
                    @else
                    <div class="flex items-center justify-between p-3.5 px-4 rounded-xl bg-slate-100 dark:bg-white/5 text-[11px] font-bold text-slate-400">
                        <span>Sertifikat belum diupload</span>
                        <i class="fa-solid fa-circle-xmark"></i>
                    </div>
                    @endif
                </div>

                {{-- Buttons --}}
                <div class="mt-auto grid grid-cols-2 gap-3">
                    <button type="button"
                        onclick="confirmApprove('{{ $item->id }}')"
                        class="w-full py-3.5 bg-primary text-white text-[11px] font-extrabold rounded-xl shadow-lg shadow-primary/20 hover:shadow-primary/40 hover:-translate-y-0.5 transition-all active:scale-95">
                        TERIMA
                    </button>

                    <button type="button"
                        onclick="confirmReject('{{ $item->id }}')"
                        class="w-full py-3.5 bg-slate-100 dark:bg-white/5 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white text-[11px] font-extrabold rounded-xl hover:bg-red-500 hover:text-white hover:border-red-500 transition-all active:scale-95">
                        TOLAK
                    </button>
                </div>
            </div>

            @empty

            <div class="col-span-full card-bg flex flex-col items-center justify-center py-24 rounded-[2rem]">
                <div class="w-20 h-20 rounded-full bg-slate-100 dark:bg-white/5 flex items-center justify-center mb-4 border border-dashed border-slate-200 dark:border-white/10">
                    <i class="fa-solid fa-user-clock text-2xl text-slate-500"></i>
                </div>
                <p class="font-bold text-slate-500">
                    Tidak ada antrean verifikasi.
                </p>
            </div>

            @endforelse

        </div>

    </div>
</main>

@push('library')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@push('scripts')
<script>
    function confirmApprove(userId) {
        Swal.fire({
            title: 'Setujui Pengajar?',
            text: 'User akan diberikan akses sebagai pengajar aktif.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#8B5CF6',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Setujui!',
            cancelButtonText: 'Batal',
            background: document.documentElement.classList.contains('dark') ? '#161525' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.action = `/admin/mentor/${userId}/approve`;
                form.method = 'POST';

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';

                form.appendChild(csrfInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    function confirmReject(userId) {
        Swal.fire({
            title: 'Tolak Pendaftaran?',
            text: 'Berikan alasan agar calon pengajar bisa memperbaikinya:',
            icon: 'warning',
            input: 'textarea',
            inputPlaceholder: 'Contoh: Berkas tidak lengkap...',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal',
            background: document.documentElement.classList.contains('dark') ? '#161525' : '#ffffff',
            color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#0f172a',
            inputValidator: (value) => {
                if (!value) {
                    return 'Alasan penolakan wajib diisi!';
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/mentor/${userId}/reject`;

                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                const reasonInput = document.createElement('input');
                reasonInput.type = 'hidden';
                reasonInput.name = 'reason';
                reasonInput.value = result.value;
                form.appendChild(reasonInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
</script>
@endpush

@endsection