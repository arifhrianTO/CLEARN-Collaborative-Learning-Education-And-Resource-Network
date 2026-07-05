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
    


    {{-- Script Anti-Flash untuk Dark Mode --}}
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="min-h-screen font-sans antialiased bg-white dark:bg-[#0F0B1A] text-slate-900 dark:text-white transition-colors duration-300">

    {{-- Tombol Kembali dan Toggle Tema --}}
    <div class="fixed top-5 left-5 right-5 z-50 flex justify-between items-center px-4">
        <a href="{{ route('student.certif') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white dark:bg-[#1A1625] border border-gray-100 dark:border-gray-800 hover:scale-110 transition-all shadow-sm group">
            <i class="fas fa-arrow-left text-[#A487F8] group-hover:-translate-x-1 transition-transform"></i>
        </a>

    </div>

    {{-- Container Utama: Flex untuk memusatkan secara vertikal --}}
    <main class="flex items-center justify-center min-h-screen p-6 md:p-12">
        <div class="w-full max-w-5xl">
            
            {{-- Bagian Atas: Verifikasi Status (Sisi Luar Kertas) --}}
            <div class="mb-10 text-center animate-fade-down">
                <div class="inline-flex items-center gap-2 mb-2 text-green-500 font-bold">
                    <i class="fas fa-check-circle text-lg"></i>
                    <span class="text-[12px] uppercase tracking-widest">Sertifikat Penyelesaian</span>
                </div>
                <p class="text-sm text-slate-500 dark:text-gray-400 max-w-md mx-auto">
                    Sertifikat ini memverifikasi bahwa siswa telah berhasil menyelesaikan kursus.
                </p>
            </div>

            {{-- Kertas Sertifikat Utama --}}
            <div class="bg-white rounded-2xl shadow-2xl shadow-[#A487F8]/10 ring-1 ring-slate-100 p-10 md:p-16 mb-8 relative overflow-hidden animate-fade-scale">
                
                {{-- Efek Glow Halus di Background Kertas --}}
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-[#A487F8] opacity-10 rounded-full blur-[100px]"></div>
                
                {{-- Konten Kertas --}}
                <div class="relative z-10 text-center text-[#1c1826]">
                    
                    {{-- Medali/Ikon Emas --}}
                    <div class="w-20 h-20 mx-auto mb-8 bg-gradient-to-br from-yellow-300 via-yellow-400 to-yellow-500 rounded-full flex items-center justify-center shadow-lg shadow-yellow-500/20 text-white text-4xl">
                        <i class="fas fa-medal"></i>
                    </div>

                    {{-- Judul Besar --}}
                    <h2 class="text-3xl font-extrabold mb-5 tracking-tight border-b border-gray-100 pb-5 inline-block mx-auto">
                        Sertifikat Prestasi
                    </h2>
                    
                    <p class="text-sm text-gray-500 mb-6 font-medium">Dengan ini menyatakan bahwa</p>
                    
                    {{-- Nama Siswa (Ungu/Active Color) --}}
                    <h1 class="text-5xl font-black mb-6 tracking-tighter text-[#A487F8]">
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
                            {{-- Placeholder QR Code (Replikasi Gambar) --}}
                            <div class="w-12 h-12 rounded-lg bg-[#A487F8] flex flex-wrap p-1 items-center justify-center gap-0.5 text-white/90">
                                <span class="text-[10px] font-bold">QR</span>
                                <span class="text-[10px] font-bold">CODE</span>
                            </div>
                            <p class="text-[10px] font-medium text-gray-500 max-w-[80px]">Scan to verify authenticity</p>
                        </div>
                    </div>
                </div>
            </div> {{-- Akhir Kertas --}}

            {{-- Info Verifikasi Platform (Kecil di bawah kertas) --}}
            <div class="p-3 bg-[#A487F8]/20 rounded-lg text-center text-[#A487F8] text-[11px] font-medium flex items-center justify-center gap-2 mb-10 max-w-sm mx-auto">
                <i class="fas fa-info-circle"></i>
                Diverifikasi oleh Platform Kursus – 
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex flex-col md:flex-row justify-center gap-4 animate-fade-up">
                <a href="{{ route('student.certificate.download', $certificate->id) }}" class="bg-[#A487F8] text-white font-bold px-8 py-3.5 rounded-xl flex items-center justify-center gap-3 transition-all hover:bg-[#8B6FE8] shadow-lg shadow-[#A487F8]/20 active:scale-95 uppercase tracking-widest text-[10px]">
                    <i class="fas fa-download"></i> Download Certificate (PDF)
                </a>
                <button class="bg-white text-[#A487F8] border border-gray-100 font-bold px-8 py-3.5 rounded-xl flex items-center justify-center gap-3 transition-all hover:bg-gray-50 shadow-sm active:scale-95 uppercase tracking-widest text-[10px]">
                    <i class="fas fa-share-alt"></i> Share Certificate
                </button>
            </div>

        </div>
    </main>

</body>
</html>