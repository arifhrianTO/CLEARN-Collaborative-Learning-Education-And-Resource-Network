<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-clearn.png') }}">
    <title>CLEARN │ Sertifikat</title>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- Anti-Flash Script --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style type="text/tailwindcss">
        @layer base {
            body { 
                @apply bg-slate-50 text-slate-900 transition-colors duration-300 antialiased font-sans;
                letter-spacing: -0.025em;
            }
            .dark body { @apply bg-[#090613] text-white; }
        }

        @layer components {
            .card-bg { @apply bg-white border border-gray-200 rounded-2xl transition-all duration-300 shadow-sm; }
            .dark .card-bg { @apply bg-[#110D1F] border-[#2d2644] shadow-none; }
            
            .text-muted-custom { @apply text-slate-500 font-medium; }
            .dark .text-muted-custom { @apply text-slate-400; }
        }
    </style>
</head>

<body class="min-h-screen flex">

    {{-- Sidebar Pelajar --}}
    <x-dashboard.sidebar
    role="Student"
    name="{{ auth()->user()->name ?? 'User Pelajar' }}"
    initials="{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}"
    photo="{{ auth()->user()->profile_picture }}"
    active="certificates" />

    <main class="flex-1 p-6 lg:p-10">
        <div class="max-w-5xl mx-auto">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-2xl font-black tracking-tighter mb-1">Sertifikat Saya</h1>
                <p class="text-muted-custom text-[12px]">Rayakan pencapaian Anda dan bagikan bukti keahlian Anda ke dunia.</p>
            </div>

            {{-- Ringkasan Statistik (Selaras dengan Dashboard/Riwayat) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="card-bg p-5 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center text-blue-500 text-sm">
                        <i class="fas fa-certificate"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black leading-none">{{ $totalCertificates }}</h2>
                        <span class="text-[9px] font-bold text-muted-custom uppercase tracking-widest">Total Sertifikat</span>
                    </div>
                </div>

                <div class="card-bg p-5 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center text-green-500 text-sm">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black leading-none">{{ $completedCourses }}</h2>
                        <span class="text-[9px] font-bold text-muted-custom uppercase tracking-widest">Kursus Selesai</span>
                    </div>
                </div>

                <div class="card-bg p-5 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-[#A487F8]/10 flex items-center justify-center text-[#A487F8] text-sm">
                        <i class="fas fa-share-nodes"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black leading-none">0</h2>
                        <span class="text-[9px] font-bold text-muted-custom uppercase tracking-widest">Dibagikan</span>
                    </div>
                </div>
            </div>

            {{-- Grid Sertifikat (Ukuran Selaras dengan Course Card) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($certificates as $cert)
                {{-- Sertifikat --}}
                <div class="card-bg overflow-hidden flex flex-col group">
                    <div class="p-8 bg-slate-100/50 dark:bg-[#161226] flex-1 relative overflow-hidden">
                        <i class="fas fa-award absolute -right-4 -bottom-4 text-8xl text-black/5 dark:text-white/5"></i>

                        <div class="flex justify-between items-start mb-6">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-graduation-cap text-primary text-xl"></i>
                                <span class="font-black text-lg tracking-tighter italic">clearn</span>
                            </div>
                            <p class="text-[8px] font-black text-muted-custom uppercase tracking-[0.2em]">Sertifikat Penyelesaian</p>
                        </div>

                        <h3 class="text-lg font-black mb-4 leading-tight tracking-tight group-hover:text-primary transition-colors">{{ $cert->enrollment->course->course_title }}</h3>

                        <div class="mb-6">
                            <p class="text-[9px] text-muted-custom font-bold uppercase mb-0.5">Diberikan kepada:</p>
                            <p class="text-lg font-extrabold border-b-2 border-primary/20 inline-block pb-0.5">{{ Auth::user()->name }}</p>
                        </div>

                        <div class="flex justify-between items-end text-[10px] font-bold">
                            <div>
                                <p class="text-muted-custom uppercase mb-0.5">Pengajar</p>
                                <p>{{ $cert->enrollment->course->mentor->name ?? 'Mentor Clearn' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-muted-custom uppercase mb-0.5">Tanggal Diterbitkan</p>
                                <p>{{ $cert->issue_date->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi (Kecil & Selaras) --}}
                    <div class="p-4 flex gap-3 bg-white dark:bg-[#110D1F] border-t border-gray-100 dark:border-[#2d2644]">
                        <button class="flex-1 bg-primary text-white text-[10px] font-extrabold py-2.5 rounded-xl shadow-lg shadow-primary/20 hover:brightness-110 transition-all uppercase tracking-widest active:scale-95">
                            <i class="fas fa-download mr-2"></i>Unduh
                        </button>
                        <button
                            type="button"
                            onclick="window.location='{{ route('student.certificate.show', $cert->id) }}'"
                            class="flex-1 border border-gray-200 dark:border-[#2d2644] text-muted-custom text-[10px] font-extrabold py-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition-all uppercase tracking-widest active:scale-95">
                            <i class="fas fa-solid fa-play mr-2"></i>Detail
                        </button>
                    </div>
                </div>
                @empty
                <div class="col-span-full card-bg p-10 text-center">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-[#1a1429] rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fas fa-certificate text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Belum ada sertifikat</h3>
                    <p class="text-muted-custom text-sm mb-6">Selesaikan kursus Anda hingga 100% untuk mendapatkan sertifikat.</p>
                    <a href="{{ route('student.course.index') }}" class="inline-block bg-primary text-white px-6 py-2 rounded-xl text-sm font-bold shadow-lg shadow-primary/20 hover:-translate-y-1 transition-all">Lanjutkan Belajar</a>
                </div>
                @endforelse

            </div>

            <div class="mt-6">
                {{ $certificates->links() }}
            </div>

            {{-- CTA Section (Dikecilkan agar lebih proporsional) --}}
            <div class="mt-12 bg-primary p-8 rounded-3xl text-center text-white shadow-xl shadow-primary/20 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-xl font-black mb-2">Terus Tingkatkan Keahlian Anda</h2>
                    <p class="text-white/80 text-[12px] mb-6 max-w-lg mx-auto font-medium">Selesaikan kursus lainnya dan kumpulkan sertifikat untuk memperkuat profil profesional Anda.</p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <button class="bg-white text-primary px-6 py-2.5 rounded-xl font-black text-[10px] uppercase hover:bg-slate-50 transition-all active:scale-95">
                            Cari Kursus Baru
                        </button>
                    </div>
                </div>
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/5 rounded-full translate-y-1/2 -translate-x-1/2"></div>
            </div>

        </div>
    </main>

</body>

</html>