@extends('layouts.auth')

@section('title', 'Daftar Pelajar – Clearn - Platform Pembelajaran Online')

@section('bodyClass', 'bg-white dark:bg-[#0f0a19] text-slate-900 dark:text-white min-h-screen overflow-x-hidden')

@section('content')
<div class="h-screen grid grid-cols-1 lg:grid-cols-2 overflow-hidden">

    {{-- SISI KIRI --}}
    <div class="hidden lg:flex items-center justify-center p-12
            bg-slate-100 dark:bg-slate-900 border-l border-slate-200 dark:border-slate-800">
        <div class="max-w-lg text-center">
            <div class="w-24 h-24 mx-auto mb-8 rounded-3xl
                    bg-primary flex items-center justify-center text-4xl text-white">
                🚀
            </div>

            <h2 class="text-4xl font-bold mb-4">
                Mulai Belajar Hari Ini
            </h2>

            <p class="text-slate-600 dark:text-slate-400 text-lg">
                Bergabunglah dengan ribuan pelajar lainnya.
            </p>
        </div>
    </div>

    {{-- SISI KANAN --}}
    <div class="flex items-center justify-center px-6 py-4 lg:px-10 bg-white dark:bg-[#0f0a19] overflow-y-auto">

        <div class="w-full max-w-md animate-fade-up">

            {{-- Logo --}}
            <div class="flex mb-0 justify-center lg:justify-start">
                <img
                    src="{{ asset('images/logo-light.png') }}"
                    alt="logo"
                    class="w-40 h-40 object-contain dark:hidden">

                <img
                    src="{{ asset('images/logo-dark.png') }}"
                    alt="logo"
                    class="w-40 h-40 object-contain hidden dark:block">
            </div>

            {{-- Header --}}
            <div class="mb-5 text-center lg:text-left">
                <h1 class="text-2xl font-bold mb-1 dark:text-white tracking-tight">
                    Buat Akun
                </h1>
            </div>

            <form action="{{ route('register.student.post') }}" method="POST" class="space-y-3.5">
                @csrf

                @if($errors->any())
                <div class="py-2 px-3 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg text-xs">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                {{-- Username --}}
                <div>
                    <label class="text-xs font-semibold mb-1.5 block text-gray-700 dark:text-gray-300">Nama Lengkap</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan Nama "
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#1c1826] border border-gray-200 dark:border-gray-800 dark:text-white rounded-xl outline-none focus:ring-2 focus:ring-[#7C3AED]/50 text-sm transition-all">
                </div>

                {{-- Email --}}
                <div>
                    <label class="text-xs font-semibold mb-1.5 block text-gray-700 dark:text-gray-300">Alamat Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="Budi123@gmail.com"
                        class="w-full px-4 py-2.5 bg-gray-50 dark:bg-[#1c1826] border border-gray-200 dark:border-gray-800 dark:text-white rounded-xl outline-none focus:ring-2 focus:ring-[#7C3AED]/50 text-sm transition-all">
                </div>

                {{-- Password --}}
                <div>
                    <label class="text-xs font-semibold mb-1.5 block text-gray-700 dark:text-gray-300">Kata Sandi</label>
                    <div class="relative">
                        <input id="password" type="password" name="password" placeholder="Masukkan Password "
                            class="w-full px-4 py-2.5 pr-10 bg-gray-50 dark:bg-[#1c1826] border border-gray-200 dark:border-gray-800 dark:text-white rounded-xl outline-none focus:ring-2 focus:ring-[#7C3AED]/50 text-sm transition-all">
                        <i
                            class="fas fa-eye password-toggle absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-xs cursor-pointer"
                            data-target="password"></i>
                    </div>
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="text-xs font-semibold mb-1.5 block text-gray-700 dark:text-gray-300">Konfirmasi Kata Sandi</label>
                    <div class="relative">
                        <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Ulangi Password"
                            class="w-full px-4 py-2.5 pr-10 bg-gray-50 dark:bg-[#1c1826] border border-gray-200 dark:border-gray-800 dark:text-white rounded-xl outline-none focus:ring-2 focus:ring-[#7C3AED]/50 text-sm transition-all">
                        <i
                            class="fas fa-eye password-toggle absolute right-5 top-1/2 -translate-y-1/2 text-gray-400 text-xs cursor-pointer"
                            data-target="password_confirmation"></i>
                    </div>
                </div>

                {{-- Terms & Conditions --}}
                <div class="flex items-start gap-2 py-1">
                    <input type="checkbox" required
                        class="w-3.5 h-3.5 mt-0.5 rounded border-gray-300 text-[#A487F8] focus:ring-[#7C3AED]">

                    <label class="text-xs text-slate-600 dark:text-gray-400 leading-tight">
                        Saya menyetujui
                        <button type="button" onclick="document.getElementById('termsModal').classList.remove('hidden')"
                            class="text-[#A487F8] font-bold hover:underline">
                            Syarat
                        </button>
                        &
                        <button type="button" onclick="document.getElementById('conModal').classList.remove('hidden')"
                            class="text-[#A487F8] font-bold hover:underline">
                            Kebijakan
                        </button>
                    </label>
                </div>

                {{-- Submit Button --}}
              <button type="submit"
                    class="w-full py-3 rounded-xl font-bold text-white bg-primary hover:opacity-90 transition">
                    Daftar
                </button>
            </form>

            {{-- Login Link --}}
            <div class="mt-8 text-center text-sm text-slate-500 dark:text-slate-400">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="text-primary font-bold hover:underline ml-1">
                    Masuk
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Modal Syarat --}}
<div id="termsModal" class="fixed inset-0 z-[200] flex items-center justify-center hidden p-4 bg-black/30 backdrop-blur-sm">
    <div class="overflow-y-auto scrollbar-hide bg-white dark:bg-[#161525] p-6 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 w-full max-w-lg max-h-[80vh] flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold dark:text-white">Syarat Layanan</h2>
            <button onclick="document.getElementById('termsModal').classList.add('hidden')" class="text-gray-400 hover:text-[#A487F8]">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="overflow-y-auto scrollbar-hide pr-2 space-y-6 text-sm text-slate-600 dark:text-gray-400 leading-relaxed font-medium">

            <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
                <p class="text-xs text-slate-500 italic">Terakhir diperbarui: 27-04-2026</p>
                <p class="mt-2 text-xs">Dengan menggunakan aplikasi kursus online ini, pengguna dianggap telah membaca, memahami, dan menyetujui syarat dan ketentuan berikut.</p>
            </div>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">1. Penggunaan Layanan</h4>
                <p class="mb-2 text-xs">Aplikasi ini menyediakan layanan pembelajaran online yang dapat digunakan oleh peserta, mentor, maupun admin. Pengguna setuju untuk:</p>
                <ul class="list-disc ml-4 space-y-1 text-xs">
                    <li>Menggunakan aplikasi sesuai hukum yang berlaku</li>
                    <li>Tidak menyalahgunakan sistem</li>
                    <li>Tidak menggunakan akun milik orang lain tanpa izin</li>
                    <li>Menjaga keamanan akun dan kata sandi</li>
                </ul>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">2. Pendaftaran Akun</h4>
                <p class="mb-2 text-xs">Pengguna bertanggung jawab untuk memberikan data yang benar, menjaga kerahasiaan login, dan memastikan aktivitas akun dilakukan oleh pemiliknya. Kami berhak menangguhkan akun jika ditemukan pelanggaran.</p>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">3. Pembelian Kursus</h4>
                <ul class="list-disc ml-4 space-y-1 text-xs">
                    <li>Harga kursus dapat berubah sewaktu-waktu.</li>
                    <li>Pembayaran yang berhasil tidak dapat dibatalkan kecuali sesuai kebijakan refund.</li>
                    <li>Akses kursus diberikan setelah pembayaran terverifikasi.</li>
                </ul>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">4. Fitur Penghasilan Mentor</h4>
                <p class="text-xs">Mentor memperoleh penghasilan berdasarkan penjualan kursus, jumlah peserta, atau penyelesaian program. Kami berhak menunda pembayaran jika ditemukan aktivitas mencurigakan.</p>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">5. Hak Kekayaan Intelektual</h4>
                <p class="text-xs">Dilarang menyalin materi, menjual ulang konten kursus, atau menyebarkan materi berbayar secara ilegal tanpa izin tertulis.</p>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">6. Larangan Pengguna</h4>
                <p class="text-xs">Dilarang mengunggah konten melanggar hukum, melakukan penipuan, mengganggu server, atau melakukan spam. Pelanggaran berakibat blokir permanen.</p>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">7. Pembatasan Tanggung Jawab</h4>
                <p class="text-xs">Kami tidak bertanggung jawab atas kerugian akibat kesalahan pengguna, gangguan jaringan, atau kehilangan data akibat kelalaian pengguna.</p>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">8. Perubahan & Penutupan Akun</h4>
                <p class="text-xs">Kami dapat mengubah layanan kapan saja. Pengguna dapat menghapus akun kapan saja, dan kami berhak menonaktifkan akun yang melanggar aturan.</p>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">9. Kontak</h4>
                <p class="text-xs">Pertanyaan terkait syarat layanan dapat menghubungi:</p>
                <p class="text-xs font-semibold">Email: email@clearn.com</p>
                <p class="text-xs font-semibold">Telepon: 08123456789</p>
            </section>

        </div>

        <button type="button" onclick="document.getElementById('termsModal').classList.add('hidden')"
            class="mt-6 w-full py-2.5 bg-[#A487F8] text-white rounded-xl font-bold text-xs hover:bg-[#947ADF] transition-all">
            Saya Mengerti
        </button>
    </div>
</div>

{{-- Modal Kebijakan --}}
<div id="conModal" class="fixed inset-0 z-[200] flex items-center justify-center hidden p-4 bg-black/30 backdrop-blur-sm">
    <div class="bg-white dark:bg-[#161525] p-6 rounded-2xl shadow-2xl border border-gray-100 dark:border-gray-800 w-full max-w-lg max-h-[80vh] flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-bold dark:text-white">Kebijakan Privasi</h2>
            <button onclick="document.getElementById('conModal').classList.add('hidden')" class="text-gray-400 hover:text-[#A487F8]">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="overflow-y-auto scrollbar-hide pr-2 space-y-6 text-sm text-slate-600 dark:text-gray-400 leading-relaxed font-medium">

            <div class="border-b border-slate-200 dark:border-slate-800 pb-4">
                <p class="text-xs text-slate-500 italic">Terakhir diperbarui: 27-04-2026</p>
                <p class="mt-2 text-xs">Kami menghargai privasi Anda. Dengan menggunakan aplikasi ini, Anda menyetujui pengumpulan, penggunaan, dan perlindungan data sesuai dengan kebijakan berikut.</p>
            </div>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">1. Informasi yang Kami Kumpulkan</h4>
                <ul class="list-none space-y-2 text-xs">
                    <li><strong>a. Informasi Pribadi:</strong> Nama, email, nomor telepon, foto profil, dan akun login.</li>
                    <li><strong>b. Informasi Pembayaran:</strong> Nama pemilik rekening, detail bank/e-wallet, dan riwayat transaksi.</li>
                    <li><strong>c. Informasi Penggunaan:</strong> Data progres pembelajaran, riwayat login, dan aktivitas interaksi dalam aplikasi.</li>
                </ul>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">2. Penggunaan Informasi</h4>
                <p class="text-xs">Data digunakan untuk mengelola akun, memfasilitasi akses kursus, memproses pembayaran, mengirim notifikasi, serta meningkatkan keamanan dan kualitas layanan kami.</p>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">3. Fitur Penghasilan & Pembayaran</h4>
                <p class="text-xs">Data pembayaran diproses melalui pihak ketiga yang terpercaya untuk validasi transaksi dan transfer penghasilan. Pengguna wajib memastikan data rekening yang diberikan akurat.</p>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">4. Keamanan Data</h4>
                <p class="text-xs">Kami menerapkan sistem enkripsi, autentikasi, dan pembatasan akses data untuk melindungi informasi Anda. Pengguna diharapkan turut menjaga kerahasiaan akun mereka.</p>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">5. Pembagian Informasi</h4>
                <p class="text-xs">Kami tidak menjual data Anda. Informasi hanya dibagikan kepada penyedia layanan pembayaran, mitra teknologi, atau otoritas hukum jika diwajibkan oleh undang-undang.</p>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">6. Hak Pengguna</h4>
                <p class="text-xs">Anda memiliki hak untuk mengakses, memperbarui, atau menghapus data pribadi Anda kapan saja melalui layanan pelanggan kami.</p>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">7. Cookie & Pelacakan</h4>
                <p class="text-xs">Kami menggunakan teknologi pelacakan untuk menyimpan preferensi pengguna, mempermudah akses login, dan menganalisis performa aplikasi.</p>
            </section>

            <section>
                <h4 class="font-bold text-slate-800 dark:text-white mb-2">8. Perubahan & Kontak</h4>
                <p class="text-xs mb-2">Kebijakan ini dapat diperbarui sewaktu-waktu. Jika ada pertanyaan, hubungi kami di:</p>
                <p class="text-xs font-semibold">Email: email@clearn.com</p>
                <p class="text-xs font-semibold">Telepon: 08123456789</p>
            </section>

        </div>
        <button type="button" onclick="document.getElementById('conModal').classList.add('hidden')"
            class="mt-6 w-full py-2.5 bg-[#A487F8] text-white rounded-xl font-bold text-xs hover:bg-[#947ADF] transition-all">
            Saya Mengerti
        </button>
    </div>
</div>
@endsection