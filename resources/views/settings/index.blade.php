@extends('layouts.dashboard')

@section('title', 'CLEARN │ Pengaturan')

@section('content')

@php
$user = auth()->user();
$role = $user->role ?? null;

$isMentor = $role === 'mentor';
$isAdmin = $role === 'admin';
$isStudent = in_array($role, ['student', 'students']);

$isVerified = $isMentor && $user->status === 'active';

$hideUsername = $isAdmin || $isStudent;
@endphp

@push('styles')
<style>
    .file-drop-zone.drag-over {
        border-color: var(--color-primary) !important;
        background: color-mix(in srgb, var(--color-primary) 8%, transparent) !important;
    }
</style>
@endpush

<x-dashboard.sidebar
    role="{{ auth()->user()->role }}"
    name="{{ auth()->user()->name }}"
    initials="{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}"
    photo="{{ auth()->user()->profile_picture }}" />

{{-- Content --}}
<main class="flex-1 p-6 lg:p-10">
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="mb-8 flex items-center gap-4">
            <div class="relative shrink-0">
                <div id="avatar-header"
                    class="w-14 h-14 rounded-full bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white text-xl font-extrabold overflow-hidden ring-2 dark:ring-white/10 ring-slate-200">
                    @if(!empty($user->profile_picture))
                    <img id="avatar-header-img" src="{{ asset('storage/'.$user->profile_picture) }}" class="w-full h-full object-cover" alt="Foto Profil">
                    @else
                    <span id="avatar-header-initials">{{ strtoupper(substr($user?->name ?? 'U', 0, 2)) }}</span>
                    @endif
                </div>

                {{-- Tombol hapus foto (×) — hanya muncul jika ada foto --}}
                <button type="button" id="btn-remove-photo"
                    onclick="removePhoto()"
                    class="{{ empty($user->profile_picture) ? 'hidden' : '' }} absolute -top-1 -right-1 w-5 h-5 rounded-full bg-slate-600 hover:bg-red-500 flex items-center justify-center transition shadow-md z-10"
                    title="Hapus foto">
                    <i class="fa-solid fa-xmark text-white text-[9px]"></i>
                </button>

                {{-- Tombol kamera --}}
                <label for="photo"
                    class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-green-500 hover:bg-green-400 flex items-center justify-center cursor-pointer transition shadow-md"
                    title="Ganti foto">
                    <i class="fa-solid fa-camera text-white text-[10px]"></i>
                </label>
            </div>

            <div>
                <p class="dark:text-slate-500 text-slate-400 font-bold text-sm mb-0.5">Halo,</p>
                <h1 class="text-2xl font-extrabold tracking-tight dark:text-white text-slate-800 leading-tight">
                    {{ $user?->name ?? 'User' }}
                </h1>
            </div>
        </div>

        {{-- Tab Navigation --}}
        <div class="flex dark:border-white/10 border-slate-200 border-b mb-8 overflow-x-auto">
            <button
                type="button"
                onclick="switchTab('profile')"
                id="tab-btn-profile"
                class="tab-btn flex items-center gap-2 px-5 py-3 text-[13px] font-semibold text-primary border-b-2 border-primary whitespace-nowrap transition">
                <i class="fa-regular fa-circle-user"></i>
                Profil
            </button>

            @if($isMentor)
            <button
                type="button"
                onclick="switchTab('bank')"
                id="tab-btn-bank"
                class="tab-btn flex items-center gap-2 px-5 py-3 text-[13px] font-semibold dark:text-slate-500 text-slate-400 dark:hover:text-slate-300 hover:text-slate-700 border-b-2 border-transparent whitespace-nowrap transition">
                <i class="fa-solid fa-building-columns"></i>
                Rekening Bank
            </button>
            @endif

            <button
                type="button"
                onclick="switchTab('password')"
                id="tab-btn-password"
                class="tab-btn flex items-center gap-2 px-5 py-3 text-[13px] font-semibold dark:text-slate-500 text-slate-400 dark:hover:text-slate-300 hover:text-slate-700 border-b-2 border-transparent whitespace-nowrap transition">
                <i class="fa-solid fa-key"></i>
                Ganti Kata Sandi
            </button>
        </div>

        {{-- ================= TAB PROFIL ================= --}}
        <div id="tab-profile" class="tab-content">
            <div class="dark:bg-[#1A1625] bg-white border dark:border-white/10 border-slate-200 rounded-[20px] p-8 shadow-sm">
                <h2 class="text-base font-bold dark:text-white text-slate-800 mb-1">
                    Informasi Pribadi
                </h2>
                <p class="dark:text-slate-500 text-slate-400 text-sm mb-7">
                    Informasi ini digunakan untuk personalisasi akun Anda.
                </p>

                <form method="POST" action="{{ route('settings.profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    {{-- Form Grid --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                Nama Lengkap
                            </label>
                            <input
                                type="text"
                                name="name"
                                value="{{ old('name', $user?->name) }}"
                                class="w-full px-4 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                placeholder="Nama lengkap kamu">
                            @error('name')
                            <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                Email
                            </label>
                            <input
                                type="email"
                                name="email"
                                value="{{ old('email', $user?->email) }}"
                                class="w-full px-4 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                placeholder="email@kamu.com">
                            @error('email')
                            <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                Nomor Telepon
                            </label>
                            <input
                                type="text"
                                name="phone"
                                value="{{ old('phone', $user->phone ?? '') }}"
                                class="w-full px-4 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                placeholder="08xxxxxxxxxx">
                            @error('phone')
                            <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>


                    </div>

                    {{-- ============ PENGAJAR ONLY ============ --}}
                    @if($isMentor)
                    @php $mentor = $user->profileAccount ?? null; @endphp

                    <div class="dark:border-white/10 border-slate-200 border-t pt-6 mt-2 mb-6">
                        <h3 class="text-sm font-bold dark:text-white text-slate-800 mb-1">
                            Informasi Pengajar
                        </h3>
                        <p class="dark:text-slate-500 text-slate-400 text-xs mb-5">
                            Lengkapi profil keahlian dan dokumen pendukung kamu sebagai pengajar.
                        </p>

                        @if($isVerified)
                        <div class="flex items-center gap-2 px-4 py-3 mb-5 rounded-xl bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 text-sm font-semibold">
                            <i class="fa-solid fa-check-circle"></i>
                            Informasi pengajar telah diverifikasi. Hanya bio yang dapat diubah.
                        </div>
                        @endif

                        <div class="mb-5">
                            <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                Bio
                            </label>
                            <textarea
                                name="bio"
                                rows="4"
                                class="w-full px-4 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                placeholder="Ceritakan tentang dirimu...">{{ old('bio', $mentor?->bio) }}</textarea>
                            @error('bio')
                            <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                    Bidang Keahlian
                                </label>
                                <input
                                    type="text"
                                    name="expertise"
                                    value="{{ old('expertise', $mentor?->expertise) }}"
                                    @if($isVerified) readonly @endif
                                    class="w-full px-4 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none @if($isVerified) opacity-60 cursor-not-allowed @else focus:border-primary/50 focus:ring-4 focus:ring-primary/10 @endif transition"
                                    placeholder="Misal: Web Development, Data Science">
                                @error('expertise')
                                <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                    LinkedIn
                                </label>
                                <input
                                    type="url"
                                    name="linkedin_link"
                                    value="{{ old('linkedin_link', $mentor?->linkedin_link) }}"
                                    @if($isVerified) readonly @endif
                                    class="w-full px-4 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none @if($isVerified) opacity-60 cursor-not-allowed @else focus:border-primary/50 focus:ring-4 focus:ring-primary/10 @endif transition"
                                    placeholder="https://linkedin.com/in/username">
                                @error('linkedin_link')
                                <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                    SINTA
                                </label>
                                <input
                                    type="url"
                                    name="sinta_link"
                                    value="{{ old('sinta_link', $mentor?->sinta_link) }}"
                                    @if($isVerified) readonly @endif
                                    class="w-full px-4 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none @if($isVerified) opacity-60 cursor-not-allowed @else focus:border-primary/50 focus:ring-4 focus:ring-primary/10 @endif transition"
                                    placeholder="https://sinta.kemdikbud.go.id/...">
                                @error('sinta_link')
                                <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                    Scopus
                                </label>
                                <input
                                    type="url"
                                    name="scopus_link"
                                    value="{{ old('scopus_link', $mentor?->scopus_link) }}"
                                    @if($isVerified) readonly @endif
                                    class="w-full px-4 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none @if($isVerified) opacity-60 cursor-not-allowed @else focus:border-primary/50 focus:ring-4 focus:ring-primary/10 @endif transition"
                                    placeholder="https://www.scopus.com/...">
                                @error('scopus_link')
                                <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                    Gelar Depan
                                </label>
                                <input
                                    type="text"
                                    name="front_title"
                                    value="{{ old('front_title', $mentor?->front_title) }}"
                                    @if($isVerified) readonly @endif
                                    class="w-full px-4 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none @if($isVerified) opacity-60 cursor-not-allowed @else focus:border-primary/50 focus:ring-4 focus:ring-primary/10 @endif transition"
                                    placeholder="Misal: Dr.">
                                @error('front_title')
                                <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                    Gelar Belakang
                                </label>
                                <input
                                    type="text"
                                    name="back_title"
                                    value="{{ old('back_title', $mentor?->back_title) }}"
                                    @if($isVerified) readonly @endif
                                    class="w-full px-4 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none @if($isVerified) opacity-60 cursor-not-allowed @else focus:border-primary/50 focus:ring-4 focus:ring-primary/10 @endif transition"
                                    placeholder="Misal: M.Kom., Ph.D.">
                                @error('back_title')
                                <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- ===== DRAG & DROP FILE UPLOAD ===== --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                            @foreach([
                            ['id' => 'cv', 'name' => 'cv_file', 'label' => 'CV', 'icon' => 'fas fa-file-lines', 'file' => $mentor?->cv_file],
                            ['id' => 'cert', 'name' => 'certificate_file', 'label' => 'Sertifikat', 'icon' => 'fas fa-award', 'file' => $mentor?->certificate_file],
                            ['id' => 'ijazah', 'name' => 'diploma_file', 'label' => 'Ijazah', 'icon' => 'fas fa-graduation-cap','file' => $mentor?->diploma_file],
                            ] as $doc)
                            <div>
                                <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                    {{ $doc['label'] }}
                                </label>

                                @if($isVerified)
                                    @if($doc['file'])
                                    <a href="{{ asset('storage/'.$doc['file']) }}" target="_blank"
                                        class="flex items-center gap-2 px-3 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl text-[12px] text-primary hover:underline truncate">
                                        <i class="fa-regular fa-file text-sm shrink-0"></i>
                                        <span class="truncate">{{ $doc['file'] }}</span>
                                    </a>
                                    @else
                                    <div class="px-3 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl text-[12px] dark:text-slate-500 text-slate-400">
                                        Tidak ada file
                                    </div>
                                    @endif
                                @else
                                {{-- Drop Zone --}}
                                <div
                                    id="zone-{{ $doc['id'] }}"
                                    class="file-drop-zone border-2 border-dashed dark:border-white/20 border-slate-300 rounded-xl p-5 text-center cursor-pointer transition hover:border-primary/60 hover:bg-primary/5"
                                    onclick="document.getElementById('inp-{{ $doc['id'] }}').click()">
                                    <i class="fa-regular {{ $doc['icon'] }} text-2xl dark:text-slate-500 text-slate-400 mb-2 block"></i>
                                    <p class="text-[12px] dark:text-slate-400 text-slate-500 mb-0.5">Seret file ke sini</p>
                                    <p class="text-[11px] dark:text-slate-600 text-slate-400">atau <span class="text-primary font-semibold">klik untuk pilih</span></p>
                                    <p class="text-[10px] dark:text-slate-600 text-slate-400 mt-2">PDF, DOC, DOCX</p>
                                </div>

                                {{-- Hidden file input --}}
                                <input
                                    type="file"
                                    id="inp-{{ $doc['id'] }}"
                                    name="{{ $doc['name'] }}"
                                    accept=".pdf,.doc,.docx"
                                    class="hidden"
                                    onchange="handleFileSelect('{{ $doc['id'] }}', this)">

                                {{-- File pill (tampil setelah file dipilih) --}}
                                <div id="pill-{{ $doc['id'] }}" class="hidden mt-2 flex items-center gap-2 px-3 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl">
                                    <i class="fa-regular fa-file-lines text-primary text-sm shrink-0"></i>
                                    <span id="pill-name-{{ $doc['id'] }}" class="text-[12px] dark:text-slate-300 text-slate-700 truncate flex-1"></span>
                                    <button
                                        type="button"
                                        onclick="clearFile('{{ $doc['id'] }}')"
                                        class="dark:text-slate-500 text-slate-400 hover:text-red-400 transition shrink-0 leading-none">
                                        <i class="fa-solid fa-xmark text-xs"></i>
                                    </button>
                                </div>

                                {{-- Link file existing --}}
                                @if($doc['file'])
                                <a href="{{ asset('storage/'.$doc['file']) }}" target="_blank"
                                    class="block text-[11px] text-primary hover:underline mt-1.5 truncate">
                                    <i class="fa-regular fa-file text-[10px] mr-1"></i>Lihat file saat ini
                                </a>
                                @endif

                                @error($doc['name'])
                                <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                @endif
                            </div>
                            @endforeach

                        </div>
                        {{-- ===== END DRAG & DROP ===== --}}
                    
                    </div>
                    @endif
                    
                    {{-- Validasi Error Photo --}}
                    @error('photo')
                    <div class="text-red-500 text-[10px] mt-1">{{ $message }}</div>
                    @enderror

                    <input type="file" id="photo" name="photo" class="hidden" accept="image/*">
                    <input type="hidden" id="remove_photo" name="remove_photo" value="0">

                    <button
                        type="submit"
                        class="bg-primary text-white px-6 py-2.5 rounded-xl text-[13px] font-bold hover:brightness-110 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/30 transition">
                        Simpan Perubahan
                    </button>
                </form>
            </div>
        </div>

        {{-- ================= TAB BANK (PENGAJAR ONLY) ================= --}}
        @if($isMentor)
        @php $bank = $user->banks ?? null; @endphp
        <div id="tab-bank" class="tab-content hidden">
            <div class="dark:bg-[#1A1625] bg-white border dark:border-white/10 border-slate-200 rounded-[20px] p-8 shadow-sm">

                <div class="flex items-center justify-between mb-7 gap-4 flex-wrap">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center text-primary text-sm bg-primary/10">
                            <i class="fa-solid fa-building-columns"></i>
                        </div>
                        <div>
                            <h2 class="text-base font-bold dark:text-white text-slate-800">
                                Rekening Bank
                            </h2>
                            <p class="dark:text-slate-500 text-slate-400 text-sm mt-1">
                                Rekening ini digunakan untuk pencairan honor mentor.
                            </p>
                        </div>
                    </div>

                    @if($bank)
                    <span class="px-3 py-1.5 rounded-full text-[11px] font-bold whitespace-nowrap
                        @if($bank->bank_account_status === 'active') dark:bg-green-500/10 bg-green-50 dark:text-green-400 text-green-600
                        @elseif($bank->bank_account_status === 'inactive') dark:bg-slate-500/10 bg-slate-100 dark:text-slate-400 text-slate-600
                        @else dark:bg-red-500/10 bg-red-50 dark:text-red-400 text-red-600
                        @endif">
                        {{ ucfirst($bank->bank_account_status) }}
                    </span>
                    @endif
                </div>

                <form method="POST" action="{{ route('settings.bank.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                Nama Bank
                            </label>
                            <input
                                type="text"
                                name="bank_name"
                                value="{{ old('bank_name', $bank?->bank_name) }}"
                                class="w-full px-4 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                placeholder="Misal: BCA, Mandiri, BNI">
                            @error('bank_name')
                            <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                Nomor Rekening
                            </label>
                            <input
                                type="text"
                                name="bank_account"
                                value="{{ old('bank_account', $bank?->bank_account) }}"
                                class="w-full px-4 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                placeholder="Nomor rekening">
                            @error('bank_account')
                            <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                Nama Pemilik Rekening
                            </label>
                            <input
                                type="text"
                                name="bank_holder"
                                value="{{ old('bank_holder', $bank?->bank_holder) }}"
                                class="w-full px-4 py-2.5 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                placeholder="Sesuai buku tabungan">
                            @error('bank_holder')
                            <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-xl text-[13px] font-bold hover:brightness-110 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/30 transition">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Simpan Rekening
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- ================= TAB PASSWORD ================= --}}
        <div id="tab-password" class="tab-content hidden">
            <div class="w-full dark:bg-[#1A1625] bg-white border dark:border-white/10 border-slate-200 rounded-[20px] p-8 shadow-sm">

                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-primary text-sm bg-primary/10">
                        <i class="fa-solid fa-key"></i>
                    </div>
                    <div>
                        <h2 class="text-base font-bold dark:text-white text-slate-800">
                Ganti Kata Sandi
                        </h2>
                        <p class="dark:text-slate-500 text-slate-400 text-sm mt-1">
                            Pastikan password baru kamu kuat dan unik.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('settings.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="grid md:grid-cols-1 gap-5 mb-6">

                        {{-- Password Saat Ini --}}
                        <div>
                            <label for="current_password" class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                Kata Sandi Saat Ini
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="current_password"
                                    name="current_password"
                                    class="w-full px-4 py-2.5 pr-11 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                    placeholder="Masukkan kata sandi saat ini">
                                <button
                                    type="button"
                                    onclick="togglePassword('current_password', 'icon-current-password')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 dark:text-slate-500 text-slate-400 hover:text-primary transition">
                                    <i id="icon-current-password" class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                            <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div>
                            <label for="password" class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password"
                                    name="password"
                                    class="w-full px-4 py-2.5 pr-11 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                    placeholder="Masukkan kata sandi baru">
                                <button
                                    type="button"
                                    onclick="togglePassword('password', 'icon-password')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 dark:text-slate-500 text-slate-400 hover:text-primary transition">
                                    <i id="icon-password" class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            <p class="text-[11px] dark:text-slate-500 text-slate-400 mt-1">
                                Gunakan minimal 8 karakter agar lebih aman.
                            </p>
                            @error('password')
                            <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div>
                            <label for="password_confirmation" class="block text-[11px] font-bold uppercase tracking-wide dark:text-slate-400 text-slate-500 mb-1.5">
                                Konfirmasi Kata Sandi Baru
                            </label>
                            <div class="relative">
                                <input
                                    type="password"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    class="w-full px-4 py-2.5 pr-11 dark:bg-white/5 bg-slate-50 border dark:border-white/10 border-slate-200 rounded-xl dark:text-slate-200 text-slate-800 dark:placeholder:text-slate-600 placeholder:text-slate-400 text-[13px] outline-none focus:border-primary/50 focus:ring-4 focus:ring-primary/10 transition"
                                    placeholder="Ulangi kata sandi baru">
                                <button
                                    type="button"
                                    onclick="togglePassword('password_confirmation', 'icon-password-confirmation')"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 dark:text-slate-500 text-slate-400 hover:text-primary transition">
                                    <i id="icon-password-confirmation" class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                            <p class="dark:text-red-400 text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 bg-primary text-white px-6 py-2.5 rounded-xl text-[13px] font-bold hover:brightness-110 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-primary/30 transition">
                        <i class="fa-solid fa-floppy-disk"></i>
                        Perbarui Kata Sandi
                    </button>
                </form>
            </div>
        </div>

    </div>
</main>

{{-- ===== MODAL LOGIN ULANG ===== --}}
<div id="modal-relogin" class="hidden fixed inset-0 z-[9998] flex items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="dark:bg-[#1A1625] bg-white border dark:border-white/10 border-slate-200 rounded-2xl p-8 max-w-sm w-full mx-4 shadow-2xl text-center">
        <div class="w-14 h-14 rounded-full bg-green-500/10 flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-key text-green-400 text-2xl"></i>
        </div>
        <h3 class="dark:text-white text-slate-800 font-bold text-lg mb-2">Kata Sandi Berhasil Diperbarui</h3>
        <p class="dark:text-slate-400 text-slate-500 text-sm mb-6">
            Demi keamanan akun Anda, silakan login ulang menggunakan kata sandi baru.
        </p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="w-full bg-primary text-white px-6 py-2.5 rounded-xl text-[13px] font-bold hover:brightness-110 transition">
                <i class="fa-solid fa-door-open mr-2"></i>Login Ulang Sekarang
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    // ─── Tab list (role-aware) ────────────────────────────────────────
    @if($isMentor)
    const tabs = ['profile', 'bank', 'password'];
    @else
    const tabs = ['profile', 'password'];
    @endif

    // ─── Toast Notification (pojok kanan atas, auto-hide 2 detik) ────
    function showToast(message, type = 'success') {
        // Hapus toast lama jika ada
        const old = document.getElementById('toast-notif');
        if (old) old.remove();

        const colors = {
            success: 'bg-green-500/10 border-green-500/20 text-green-500',
            error: 'bg-red-500/10 border-red-500/20 text-red-500',
        };
        const icons = {
            success: 'fa-circle-check',
            error: 'fa-circle-xmark',
        };

        const toast = document.createElement('div');
        toast.id = 'toast-notif';
        toast.className = [
            'fixed top-5 right-5 z-[9999] flex items-center gap-2',
            'px-4 py-3 rounded-xl text-sm border shadow-lg',
            'transition-all duration-300 opacity-0 translate-y-[-8px]',
            colors[type] ?? colors.success,
        ].join(' ');
        toast.innerHTML = `<i class="fa-solid ${icons[type] ?? icons.success}"></i> ${message}`;
        document.body.appendChild(toast);

        // Animasi masuk
        requestAnimationFrame(() => {
            toast.style.opacity = '1';
            toast.style.transform = 'translateY(0)';
        });

        // Auto-hide 2 detik
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-8px)';
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }

    // ─── Tab switching ────────────────────────────────────────────────
    function switchTab(name) {
        tabs.forEach(function(t) {
            const content = document.getElementById('tab-' + t);
            const btn = document.getElementById('tab-btn-' + t);
            if (content) {
                content.classList.add('hidden');
                content.classList.remove('block');
            }
            if (btn) {
                btn.classList.remove('text-primary', 'border-primary');
                btn.classList.add('dark:text-slate-500', 'text-slate-400', 'border-transparent');
            }
        });

        const activeContent = document.getElementById('tab-' + name);
        const activeBtn = document.getElementById('tab-btn-' + name);
        if (activeContent) {
            activeContent.classList.remove('hidden');
            activeContent.classList.add('block');
        }
        if (activeBtn) {
            activeBtn.classList.remove('dark:text-slate-500', 'text-slate-400', 'border-transparent');
            activeBtn.classList.add('text-primary', 'border-primary');
        }

        history.replaceState(null, '', '#' + name);
    }

    // ─── Password toggle ──────────────────────────────────────────────
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input || !icon) return;
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // ─── Avatar preview + update sidebar ─────────────────────────────
    function previewAvatar(e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function(ev) {
            const src = ev.target.result;

            // Update avatar di header halaman settings
            const headerAvatar = document.getElementById('avatar-header');
            if (headerAvatar) {
                headerAvatar.innerHTML = `<img id="avatar-header-img" src="${src}" class="w-full h-full object-cover" alt="Preview Foto">`;
            }

            // Update avatar di sidebar
            const sidebarImg = document.getElementById('sidebar-avatar-img');
            const sidebarInitials = document.getElementById('sidebar-avatar-initials');
            if (sidebarImg) {
                sidebarImg.src = src;
            } else if (sidebarInitials) {
                // Sebelumnya tidak ada foto — ganti inisial jadi img
                sidebarInitials.outerHTML = `<img id="sidebar-avatar-img" src="${src}" class="w-full h-full object-cover rounded-full" alt="Preview Foto">`;
            }

            // Tampilkan tombol hapus foto
            const btnRemove = document.getElementById('btn-remove-photo');
            if (btnRemove) btnRemove.classList.remove('hidden');

            // Reset flag hapus
            const removeInput = document.getElementById('remove_photo');
            if (removeInput) removeInput.value = '0';
        };
        reader.readAsDataURL(file);
    }

    // ─── Remove photo ─────────────────────────────────────────────────
    function removePhoto() {
        const initials = '{{ strtoupper(substr($user?->name ?? "U", 0, 2)) }}';

        // Reset avatar header
        const header = document.getElementById('avatar-header');
        if (header) header.innerHTML = `<span id="avatar-header-initials">${initials}</span>`;

        // Reset avatar sidebar
        const sidebarImg = document.getElementById('sidebar-avatar-img');
        if (sidebarImg) {
            sidebarImg.outerHTML = `<span id="sidebar-avatar-initials">${initials}</span>`;
        }

        // Reset file input
        const photoInput = document.getElementById('photo');
        if (photoInput) photoInput.value = '';

        // Set flag hapus
        const removeInput = document.getElementById('remove_photo');
        if (removeInput) removeInput.value = '1';

        // Sembunyikan tombol ×
        const btn = document.getElementById('btn-remove-photo');
        if (btn) btn.classList.add('hidden');
    }

    // ─── Drag & Drop File Upload ──────────────────────────────────────
    const docIds = ['cv', 'cert', 'ijazah'];

    function handleFileSelect(id, input) {
        const file = input.files[0];
        if (!file) return;
        showFilePill(id, file.name);
    }

    function showFilePill(id, fileName) {
        const zone = document.getElementById('zone-' + id);
        const pill = document.getElementById('pill-' + id);
        const pillName = document.getElementById('pill-name-' + id);
        if (zone) zone.classList.add('hidden');
        if (pillName) pillName.textContent = fileName;
        if (pill) pill.classList.remove('hidden');
    }

    function clearFile(id) {
        const zone = document.getElementById('zone-' + id);
        const pill = document.getElementById('pill-' + id);
        const input = document.getElementById('inp-' + id);
        if (zone) zone.classList.remove('hidden');
        if (pill) pill.classList.add('hidden');
        if (input) input.value = '';
    }

    docIds.forEach(function(id) {
        const zone = document.getElementById('zone-' + id);
        const input = document.getElementById('inp-' + id);
        if (!zone || !input) return;

        zone.addEventListener('dragover', e => {
            e.preventDefault();
            zone.classList.add('drag-over');
        });
        zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
        zone.addEventListener('drop', e => {
            e.preventDefault();
            zone.classList.remove('drag-over');
            const file = e.dataTransfer.files[0];
            if (!file) return;
            try {
                const dt = new DataTransfer();
                dt.items.add(file);
                input.files = dt.files;
            } catch (err) {
                console.warn('DataTransfer not supported');
                return;
            }
            showFilePill(id, file.name);
        });
    });

    // ─── Modal Login Ulang (setelah ganti password) ───────────────────
    function showReloginModal() {
        const modal = document.getElementById('modal-relogin');
        if (modal) modal.classList.remove('hidden');
    }

    // ─── Init ─────────────────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', function() {
        // Buka tab dari hash atau session
        const hash = location.hash.replace('#', '');
        const sessionTab = '{{ session("tab", "") }}';
        const active = tabs.includes(hash) ? hash : (tabs.includes(sessionTab) ? sessionTab : 'profile');
        switchTab(active);

        // Hubungkan input foto ke previewAvatar
        const photoInput = document.getElementById('photo');
        if (photoInput) photoInput.addEventListener('change', previewAvatar);

        // Tampilkan toast dari session (hanya di tab yang sesuai)
        @if(session('status') === 'profile-updated')
        if (active === 'profile') showToast('Profil berhasil diperbarui.');
        @elseif(session('status') === 'bank-updated')
        if (active === 'bank') showToast('Informasi rekening berhasil diperbarui.');
        @elseif(session('status') === 'password-updated')
        showReloginModal();
        @endif
    });
</script>
@endpush
@endsection