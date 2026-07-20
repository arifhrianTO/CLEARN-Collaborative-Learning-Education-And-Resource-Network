<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-clearn.png') }}">
    <title>CLEARN │ Sertifikat</title>

    {{-- Vite Assets for Tailwind CSS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font Awesome untuk Ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- Fonts: Plus Jakarta Sans --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Script Anti-Flash untuk Dark Mode --}}
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="min-h-screen font-sans antialiased bg-white dark:bg-[#0f0a19] text-slate-900 dark:text-white transition-colors duration-300">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-green-500 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-green-500/30 animate-fade-down">
            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="fixed top-5 left-1/2 -translate-x-1/2 z-50 bg-red-500 text-white px-6 py-3 rounded-xl text-sm font-bold shadow-lg shadow-red-500/30 animate-fade-down">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
        </div>
    @endif

    {{-- Tombol Kembali --}}
    <div class="fixed top-5 left-5 z-50">
        <a href="{{ route('student.certif') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-[#1c1826] border border-gray-100 dark:border-gray-800 hover:scale-110 transition-all shadow-sm group">
            <i class="fas fa-arrow-left text-[#7C3AED] group-hover:-translate-x-1 transition-transform"></i>
        </a>
    </div>

    {{-- Container Utama: Flex untuk memusatkan secara vertikal --}}
    <main class="flex items-center justify-center min-h-screen p-6 md:p-12">
        <div class="w-full max-w-5xl">

            {{-- Bagian Atas: Verifikasi Status (Sisi Luar Kertas) --}}
            <div class="mb-10 text-center animate-fade-down">
                <div class="inline-flex items-center gap-2 mb-2 text-green-500 font-bold">
                    <i class="fas fa-check-circle text-lg"></i>
                    <span class="text-[12px] uppercase tracking-widest">Sertifikat Kelulusan</span>
                </div>
                <p class="text-sm text-slate-500 dark:text-gray-400 max-w-md mx-auto">
                    Sertifikat ini memverifikasi bahwa siswa telah berhasil menyelesaikan kursus.
                </p>
            </div>

            {{-- Kertas Sertifikat Utama --}}
            <div class="bg-white rounded-2xl shadow-2xl shadow-[#7C3AED]/10 ring-1 ring-slate-200 dark:ring-0 p-10 md:p-16 mb-8 relative overflow-hidden animate-fade-scale">

                {{-- Efek Glow Halus di Background Kertas --}}
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-[#7C3AED] opacity-10 rounded-full blur-[100px]"></div>

                {{-- Konten Kertas --}}
                <div class="relative z-10 text-center text-[#1c1826]">

                    {{-- Logo Clearn (Dark) --}}
                    <div class="mx-auto mb-8 flex justify-center">
                        <img src="{{ asset('images/logo-light.png') }}" alt="Logo Clearn" class="h-16 object-contain">
                    </div>

                    {{-- Judul Besar --}}
                    <h2 class="text-3xl font-extrabold mb-5 tracking-tight border-b border-gray-100 pb-5 inline-block mx-auto">
                        Sertifikat Penghargaan
                    </h2>

                    <p class="text-sm text-gray-500 mb-6 font-medium">Dengan ini menyatakan bahwa</p>

                    {{-- Nama Siswa (Ungu/Active Color) --}}
                    <h1 class="text-5xl font-black mb-6 tracking-tighter text-[#7C3AED]">
                        {{ $certificate->enrollment->student->name }}
                    </h1>

                    <p class="text-sm text-gray-500 mb-6 font-medium">telah berhasil menyelesaikan kursus</p>

                    {{-- Nama Kursus --}}
                    <h3 class="text-3xl font-extrabold mb-10 tracking-tight leading-tight max-w-xl mx-auto">
                        {{ $certificate->enrollment->course->course_title }}
                    </h3>

                    <p class="text-xs text-gray-500 mb-10">Selesai pada {{ $certificate->issue_date->translatedFormat('d F Y') }}</p>

                    {{-- Garis Putus-putus Dekoratif --}}
                    <div class="w-full h-px border-t border-dashed border-gray-200 mb-10"></div>

                    {{-- Bagian Tanda Tangan --}}
                    <div class="grid grid-cols-2 gap-8 text-center max-w-3xl mx-auto mb-10">
                        <div>
                            <p class="font-extrabold text-sm mb-1.5">{{ $certificate->enrollment->course->mentor->name ?? 'Pengajar Clearn' }}</p>
                            <p class="text-xs text-gray-400 font-medium tracking-wide">Tanda Tangan Pengajar</p>
                        </div>
                        <div>
                            <p class="font-extrabold text-sm mb-1.5">Admin Clearn</p>
                            <p class="text-xs text-gray-400 font-medium tracking-wide">Administrator Platform</p>
                        </div>
                    </div>

                    {{-- Footer Kertas: ID dan QR --}}
                    <div class="flex justify-between items-end border-t border-gray-100 pt-10 text-left">
                        <div>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">ID Sertifikat</p>
                            <p class="text-sm font-extrabold">{{ $certificate->certificate_number }}</p>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="w-16 h-16 rounded-lg bg-white p-1">
                                {!! app('qrcode')->format('svg')->size(56)->generate(route('student.certificate.show', $certificate->id)) !!}
                            </div>
                            <p class="text-[10px] font-medium text-gray-500 max-w-[80px]">Pindai untuk verifikasi</p>
                        </div>
                    </div>
                </div>
            </div> {{-- Akhir Kertas --}}

            {{-- Info Verifikasi Platform (Kecil di bawah kertas) --}}
            <div class="p-3 bg-violet-100 rounded-lg text-center text-[#7C3AED] text-[11px] font-medium flex items-center justify-center gap-2 mb-10 max-w-sm mx-auto">
                <i class="fas fa-info-circle"></i>
                Diverifikasi oleh CLEARN
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col md:flex-row justify-center gap-4 animate-fade-up">
                <a href="{{ route('student.certificate.download', $certificate->id) }}" class="bg-[#9F67F2] text-white font-bold px-8 py-3.5 rounded-xl flex items-center justify-center gap-3 transition-all hover:bg-[#8B5CF6] shadow-lg shadow-violet-500/20 active:scale-95 uppercase tracking-widest text-[10px]">
                    <i class="fas fa-download"></i> Unduh Sertifikat (PDF)
                </a>
            </div>

        </div>
    </main>

</body>

</html>