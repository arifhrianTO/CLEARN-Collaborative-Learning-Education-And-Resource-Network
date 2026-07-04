<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" type="image/png" href="{{ asset('images/logo-clearn.png') }}">
    <title>CLEARN │ Daftar Pengajar</title>

    <button
        onclick="window.location='{{ route('tutorial') }}'"
        class="absolute left-6 top-6 z-50 text-white hover:opacity-80 transition">
        <i class="fa-solid fa-arrow-left-long text-2xl -translate-x-1"></i>
    </button>
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Anti-Flash Script --}}
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        .input-custom {
            padding-left: 3rem !important;
        }

        /* Warna Biru Muda khusus untuk field tertentu sesuai permintaan */
        .input-active {
            background-color: #eef2ff !important;
            color: #1a1a1a !important;
            border: none !important;
        }

        .dark .input-active {
            background-color: #1a1423 !important;
            /* Kembali ke gelap saat dark mode */
            color: white !important;
        }

        /* Styling tombol upload file agar tetap konsisten */
        input[type="file"]::file-selector-button {
            background-color: #2d2438;
            color: #a855f7;
            padding: 0.4rem 1rem;
            border-radius: 0.5rem;
            border: none;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            margin-right: 10px;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-50 text-black dark:bg-deep-dark dark:text-white p-6 lg:p-12">

    <button type="button" id="theme-toggle" class="fixed top-5 right-5 z-50 w-11 h-11 flex items-center justify-center rounded-xl bg-white dark:bg-card-dark text-primary shadow-xl border border-gray-200 dark:border-gray-800 hover:scale-110 active:scale-95">
        <i id="theme-icon" class="fas fa-cog text-lg"></i>
    </button>

    <div class="max-w-5xl mx-auto">
        <div class="mb-4">
            <img
                src="{{ asset('images/logo-light.png') }}"
                alt="logo"
                class="w-30 h-30 object-contain dark:hidden">

            <img
                src="{{ asset('images/logo-dark.png') }}"
                alt="logo"
                class="w-30 h-30 object-contain hidden dark:block">
        </div>

        <div class="mb-10">
            <h1 class="text-4xl font-extrabold mb-3 tracking-tight">Daftar sebagai pengajar</h1>
            <p class="text-gray-400 text-lg font-medium">Mulai bagikan keahlian Anda dan jadilah bagian dari perjalanan belajar para pelajar</p>
        </div>

        <form action="{{ route('register.mentor.post') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            {{-- Error bag --}}
            @if ($errors->any())
            <div>
                @foreach ($errors->all() as $error)
                <p style="color:red">{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-x-10 gap-y-6">

                <div class="space-y-5">
                    <div>
                        <label class="text-sm font-bold mb-2.5 block">Alamat Email *</label>
                        <div class="relative">
                            <i class="fas fa-envelope absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="email@gmail.com" class="input-custom w-full py-3 bg-white dark:bg-card-dark border border-gray-200 dark:border-none rounded-xl outline-none text-sm dark:text-white font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-bold mb-2.5 block">Bidang Keahlian</label>
                        <div class="relative">
                            <i class="fas fa-award absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="expertise" value="{{ old('expertise') }}" placeholder="Keahlian Anda" class="input-custom w-full py-3 bg-white dark:bg-card-dark border border-gray-200 dark:border-none rounded-xl outline-none text-sm dark:text-white font-medium">
                        </div>
                    </div>

                    <div class="grid grid-cols-4 gap-1">

                        <div class="col-span-1">
                            <label class="text-sm font-bold mb-2.5 block">Gelar Depan</label>
                            <input type="text" name="front_title" value="{{ old('front_title') }}" placeholder="Contoh: Ir." class="w-full px-5 py-3 bg-white dark:bg-card-dark border border-gray-200 dark:border-none rounded-xl outline-none text-xs dark:text-white font-medium">
                        </div>

                        <div class="col-span-2">
                            <label class="text-sm font-bold mb-2.5 block">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama Lengkap Anda" required class="w-full px-5 py-3 bg-white dark:bg-card-dark border border-gray-200 dark:border-none rounded-xl outline-none text-xs dark:text-white font-medium">
                        </div>

                        <div class="col-span-1">
                            <label class="text-sm font-bold mb-2.5 block">Gelar Belakang</label>
                            <input type="text" name="back_title" value="{{ old('back_title') }}" placeholder="Contoh: M.Pd" class="w-full px-5 py-3 bg-white dark:bg-card-dark border border-gray-200 dark:border-none rounded-xl outline-none text-xs dark:text-white font-medium">
                        </div>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="text-sm font-bold mb-2.5 block">Link LinkedIn</label>
                        <div class="relative">
                            <i class="fab fa-linkedin absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="url" name="linkedin_link" value="{{ old('linkedin_link') }}" placeholder="https://linkedin.com/in/username" class="input-custom w-full py-3 bg-white dark:bg-card-dark border border-gray-200 dark:border-none rounded-xl outline-none text-sm dark:text-white font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-bold mb-2.5 block">Link Scopus</label>
                        <div class="relative">
                            <i class="fas fa-link absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="url" name="scopus_link" value="{{ old('scopus_link') }}" placeholder="https://scopus.com/..." class="input-custom w-full py-3 bg-white dark:bg-card-dark border border-gray-200 dark:border-none rounded-xl outline-none text-sm dark:text-white font-medium">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm font-bold mb-2.5 block">Link Sinta</label>
                        <div class="relative">
                            <i class="fas fa-graduation-cap absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="url" name="sinta_link" value="{{ old('sinta_link') }}" placeholder="https://sinta.kemdikbud.go.id/..." class="input-custom w-full py-3 bg-white dark:bg-card-dark border border-gray-200 dark:border-none rounded-xl outline-none text-sm dark:text-white font-medium">
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-2">
                <div class="flex items-center gap-2 mb-4">
                    <i class="fas fa-book text-primary"></i>
                    <h2 class="text-2xl font-bold">Upload Dokumen Pendukung</h2>
                </div>
                <div class="mb-6 space-y-2 text-sm text-gray-400 italic">
                    <p>• Gabungkan seluruh sertifikat pendukung ke dalam <span class="text-primary font-bold">satu file PDF.</span></p>
                    <p>• Batas ukuran masing-masing file adalah <span class="text-primary font-bold">minimal 1MB</span> dan <span class="text-primary font-bold">maksimal 5MB.</span></p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="p-6 rounded-2xl bg-white dark:bg-card-dark border border-gray-100 dark:border-transparent shadow-sm">
                        @error('ijazah') <span>{{ $message }}</span> @enderror
                        <label class="text-[10px] font-black text-primary uppercase block mb-1 tracking-widest">Ijazah Terakhir *</label>
                        <input type="file" name="ijazah" class="text-[10px] text-gray-400 w-full font-bold mb-1">
                        <p class="text-[9px] text-gray-500 italic">Format: PDF, Maks 5MB</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-white dark:bg-card-dark border border-gray-100 dark:border-transparent shadow-sm">
                        @error('certificate_file') <span>{{ $message }}</span> @enderror
                        <label class="text-[10px] font-black text-primary uppercase block mb-1 tracking-widest">Gabungan Sertifikat *</label>
                        <input type="file" name="certificate_file" class="text-[10px] text-gray-400 w-full font-bold mb-1">
                        <p class="text-[9px] text-gray-500 italic">Format: PDF, Maks 5MB</p>
                    </div>
                    <div class="p-6 rounded-2xl bg-white dark:bg-card-dark border border-gray-100 dark:border-transparent shadow-sm">
                        @error('cv') <span>{{ $message }}</span> @enderror
                        <label class="text-[10px] font-black text-primary uppercase block mb-1 tracking-widest">CV & Identitas *</label>
                        <input type="file" name="cv" class="text-[10px] text-gray-400 w-full font-bold mb-1">
                        <p class="text-[9px] text-gray-500 italic">Format: PDF, Maks 5MB</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div>
                    <label class="text-sm font-bold mb-2.5 block">Foto Profil *</label>
                    <div class="relative">
                        <div class="p-6 rounded-2xl bg-white dark:bg-card-dark border border-gray-100 dark:border-transparent shadow-sm">
                            @error('profile_picture') <span>{{ $message }}</span> @enderror
                            <input type="file" name="profile_picture" class="text-[10px] text-gray-400 w-full font-bold mb-1">
                            <p class="text-[9px] text-gray-500 italic">Format: JPG, PNG, & JPEG, Maks 1MB</p>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="text-sm font-bold mb-2.5 block">Kata Sandi *</label>
                    <div class="relative">
                        <i class="fas fa-lock absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>

                        <input
                            type="password"
                            name="password"
                            placeholder="Masukkan Password"
                            class="input-custom w-full py-3 pl-12 pr-12 bg-white dark:bg-card-dark border border-gray-200 dark:border-none rounded-xl outline-none text-sm dark:text-white font-medium">

                        <i
                            class="fas fa-eye password-toggle absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-xs cursor-pointer"
                            data-target="password"></i>
                    </div>
                    @error('password') <span>{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="text-sm font-bold mb-2.5 block">Konfirmasi Sandi *</label>
                    <div class="relative">
                        <i class="fas fa-check-circle absolute left-5 top-1/2 -translate-y-1/2 text-gray-400"></i>

                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Ulangi Password"
                            class="input-custom w-full py-3 pl-12 pr-12 bg-white dark:bg-card-dark border border-gray-200 dark:border-none rounded-xl outline-none text-sm dark:text-white font-bold">

                        <i
                            class="fas fa-eye password-toggle absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-xs cursor-pointer"
                            data-target="password_confirmation"></i>
                    </div>
                </div>
            </div>

            <div class="flex justify-center pt-2">
                <div class="flex items-start gap-3 max-w-2xl">
                    <input type="checkbox" id="terms" class="mt-1 w-4 h-4 accent-primary rounded cursor-pointer">
                    <label for="terms" class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed font-medium cursor-pointer">
                        Saya mengonfirmasi bahwa data yang diisi adalah benar, dan saya menyetujui
                        <button type="button"
                            onclick="document.getElementById('termsModal').classList.remove('hidden')"
                            class="text-[#A487F8] font-bold hover:underline">
                            Syarat Layanan
                        </button> serta
                        <button type="button"
                            onclick="document.getElementById('conModal').classList.remove('hidden')"
                            class="text-[#A487F8] font-bold hover:underline">
                            Kebijakan Privasi
                        </button> clearn.
                    </label>
                </div>
            </div>

            <div class="flex flex-col items-center gap-5 pt-4">
                <button type="submit" class="w-full md:w-72 py-4 bg-[#A487F8] text-white rounded-xl font-bold text-base hover:bg-[#A487F8] transition-all shadow-lg active:scale-95">
                    Buat Akun
                </button>
                <p class="text-sm text-gray-500 font-medium">
                    Sudah memiliki akun? <a href="{{route('login')}}" class="text-primary font-bold hover:underline">Masuk Sekarang</a>
                </p>
            </div>
        </form>
    </div>

    <script>
        const themeToggle = document.getElementById('theme-toggle');
        const html = document.documentElement;

        themeToggle.addEventListener('click', () => {
            html.classList.toggle('dark');
            localStorage.setItem('theme', html.classList.contains('dark') ? 'dark' : 'light');
        });
    </script>
    {{-- Modal Syarat Layanan --}}
    <div id="termsModal"
        class="fixed inset-0 z-[200] flex items-center justify-center hidden p-4 bg-black/30 backdrop-blur-sm">

        <div
            class="overflow-y-auto scrollbar-hide bg-white dark:bg-[#161525] p-6 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 w-full max-w-lg max-h-[80vh] flex flex-col">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold dark:text-white">Syarat Layanan</h2>
                <button onclick="document.getElementById('termsModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-[#A487F8]">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto pr-2 space-y-5 text-sm text-slate-600 dark:text-gray-400 leading-relaxed font-medium">

                <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
                    <p class="text-xs text-slate-500 italic">Terakhir diperbarui: 27-04-2026</p>
                    <p class="mt-2 text-xs">
                        Dengan menggunakan platform clearn, pengguna dianggap telah membaca, memahami, dan menyetujui
                        syarat layanan berikut.
                    </p>
                </div>

                <section>
                    <h4 class="font-bold text-slate-800 dark:text-white mb-2">1. Penggunaan Layanan</h4>
                    <ul class="list-disc ml-4 space-y-1 text-xs">
                        <li>Layanan digunakan sesuai hukum yang berlaku</li>
                        <li>Dilarang menyalahgunakan sistem</li>
                        <li>Menjaga keamanan akun dan kata sandi</li>
                    </ul>
                </section>

                <section>
                    <h4 class="font-bold text-slate-800 dark:text-white mb-2">2. Akun & Pendaftaran</h4>
                    <p class="text-xs">
                        Pengguna wajib memberikan data yang benar dan bertanggung jawab atas aktivitas akun.
                    </p>
                </section>

                <section>
                    <h4 class="font-bold text-slate-800 dark:text-white mb-2">3. Penangguhan Akun</h4>
                    <p class="text-xs">
                        clearn berhak menangguhkan atau menghapus akun yang melanggar ketentuan.
                    </p>
                </section>

            </div>

            <button type="button"
                onclick="document.getElementById('termsModal').classList.add('hidden')"
                class="mt-6 w-full py-2.5 bg-[#A487F8] text-white rounded-xl font-bold text-xs hover:bg-[#8B6FE8] transition-all">
                Saya Mengerti
            </button>
        </div>
    </div>

    {{-- Modal Kebijakan Privasi --}}
    <div id="conModal"
        class="fixed inset-0 z-[200] flex items-center justify-center hidden p-4 bg-black/30 backdrop-blur-sm">

        <div
            class="overflow-y-auto scrollbar-hide bg-white dark:bg-[#161525] p-6 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 w-full max-w-lg max-h-[80vh] flex flex-col">

            <div class="flex justify-between items-center mb-4">
                <h2 class="text-lg font-bold dark:text-white">Kebijakan Privasi</h2>
                <button onclick="document.getElementById('conModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-[#A487F8]">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="overflow-y-auto pr-2 space-y-5 text-sm text-slate-600 dark:text-gray-400 leading-relaxed font-medium">

                <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
                    <p class="text-xs text-slate-500 italic">Terakhir diperbarui: 27-04-2026</p>
                    <p class="mt-2 text-xs">
                        clearn menghargai privasi pengguna dan berkomitmen melindungi data pribadi.
                    </p>
                </div>

                <section>
                    <h4 class="font-bold text-slate-800 dark:text-white mb-2">1. Data yang Dikumpulkan</h4>
                    <p class="text-xs">
                        Nama, email, data akun, dan aktivitas pembelajaran.
                    </p>
                </section>

                <section>
                    <h4 class="font-bold text-slate-800 dark:text-white mb-2">2. Penggunaan Data</h4>
                    <p class="text-xs">
                        Data digunakan untuk pengelolaan akun dan peningkatan layanan.
                    </p>
                </section>

                <section>
                    <h4 class="font-bold text-slate-800 dark:text-white mb-2">3. Keamanan</h4>
                    <p class="text-xs">
                        Data dilindungi dengan sistem keamanan dan enkripsi.
                    </p>
                </section>

            </div>

            <button type="button"
                onclick="document.getElementById('conModal').classList.add('hidden')"
                class="mt-6 w-full py-2.5 bg-[#A487F8] text-white rounded-xl font-bold text-xs hover:bg-[#8B6FE8] transition-all">
                Saya Mengerti
            </button>
        </div>
    </div>
</body>

</html>