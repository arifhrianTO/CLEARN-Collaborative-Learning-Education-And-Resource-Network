@extends('layouts.sections')

@section('title', 'Mentor | Clearn - Platform Pembelajaran Online')

@section('content')

<!-- Beranda Mentor -->
<section id="beranda" class="relative py-28 px-4 text-center overflow-hidden transition-colors duration-300 dark:bg-[#120d22]">
    <button
        onclick="window.location='{{ route('home') }}'"
        class="absolute left-6 top-6 z-50 text-white hover:opacity-80 transition">
        <i class="fas fa-reply text-2xl -translate-x-1"></i>
    </button>

    <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-purple-900/15 blur-[120px] rounded-full -z-10 opacity-0 dark:opacity-100 transition-opacity"></div>
    <h1 class="text-4xl md:text-6xl font-inter font-normal leading-[1.15] mb-8 tracking-tighter">
        Belajarlah dari yang Terbaik
    </h1>

    <p class="text-slate-600 dark:text-gray-400 max-w-xl mx-auto mb-12 text-base leading-relaxed">
        Pengajar kami adalah pakar industri dari perusahaan-perusahaan ternama di indonesia
    </p>

    <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-5">
        <button
            onclick="window.location='{{ route('register.mentor') }}'"
            class="bg-gradient-to-r from-purple-700 to-purple-500 px-10 py-4 rounded-xl font-bold flex items-center group text-base shadow-lg text-white">
            Menjadi Pengajar
        </button>
    </div>
</section>

<!-- Mentor -->
<section id="pengajar" class="py-24 px-6 bg-slate-100 dark:bg-[#0d0c13] transition-colors duration-300">
    <div class="grid grid-cols-3 gap-6 max-w-5xl mx-auto text-center mb-16">
        <div>
            <h3 class="text-3xl md:text-4xl font-extrabold text-purple-600 dark:text-purple-400">400+</h3>
            <p class="text-slate-500 text-xs mt-2 uppercase tracking-widest font-semibold">Pengajar Ahli</p>
        </div>
        <div>
            <h3 class="text-3xl md:text-4xl font-extrabold text-purple-600 dark:text-purple-400">4.8</h3>
            <p class="text-slate-500 text-xs mt-2 uppercase tracking-widest font-semibold">Rating</p>
        </div>
        <div>
            <h3 class="text-3xl md:text-4xl font-extrabold text-purple-600 dark:text-purple-400">500+</h3>
            <p class="text-slate-500 text-xs mt-2 uppercase tracking-widest font-semibold">Pelajar Aktif</p>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 max-w-[1200px] mx-auto">

        <x-landing.mentor-card
            photo="https://i.pravatar.cc/150?img=1"
            name="Dr. Angela Yu"
            title="Instruktur Utama, Pengembangan Web"
            description="Mantan pengembang utama di Google. Berpengalaman membangun generasi developer."
            :tags="['JavaScript', 'React']"
            rating="4.9"
            students="250"
            courses="8" />

        <x-landing.mentor-card
            photo="https://i.pravatar.cc/150?img=2"
            name="Sarah Johnson"
            title="Desainer UX Senior"
            description="Desainer UX dengan pengalaman lebih dari 15 tahun di perusahaan teknologi."
            :tags="['UI/UX', 'Figma', 'Sistem Desain']"
            rating="4.9"
            students="251"
            courses="12" />

        <x-landing.mentor-card
            photo="https://i.pravatar.cc/150?img=3"
            name="Sarah Johnson"
            title="Desainer UX Senior"
            description="Desainer UX dengan pengalaman lebih dari 15 tahun di perusahaan teknologi."
            :tags="['UI/UX', 'Figma', 'Sistem Desain']"
            rating="4.9"
            students="251"
            courses="12" />
        <x-landing.mentor-card
            photo="https://i.pravatar.cc/150?img=1"
            name="Dr. Angela Yu"
            title="Instruktur Utama, Pengembangan Web"
            description="Mantan pengembang utama di Google. Berpengalaman membangun generasi developer."
            :tags="['JavaScript', 'React']"
            rating="4.9"
            students="250"
            courses="8" />

        <x-landing.mentor-card
            photo="https://i.pravatar.cc/150?img=2"
            name="Sarah Johnson"
            title="Desainer UX Senior"
            description="Desainer UX dengan pengalaman lebih dari 15 tahun di perusahaan teknologi."
            :tags="['UI/UX', 'Figma', 'Sistem Desain']"
            rating="4.9"
            students="251"
            courses="12" />

        <x-landing.mentor-card
            photo="https://i.pravatar.cc/150?img=3"
            name="Sarah Johnson"
            title="Desainer UX Senior"
            description="Desainer UX dengan pengalaman lebih dari 15 tahun di perusahaan teknologi."
            :tags="['UI/UX', 'Figma', 'Sistem Desain']"
            rating="4.9"
            students="251"
            courses="12" />
    </div>
</section>

<section class="px-6 pb-24">
    <div class="max-w-[1100px] mx-auto text-center p-12 rounded-[30px] bg-[#A184F5] text-white">

        <h2 class="text-2xl md:text-3xl font-bold mb-4">
            Bagikan Pengetahuan Anda
        </h2>

        <p class="text-sm opacity-90 mb-8 max-w-xl mx-auto">
            Bergabunglah dengan komunitas instruktur ahli kami dan inspirasi jutaan pelajar di seluruh dunia.
        </p>

        <div class="flex justify-center gap-4">
            <button class="px-6 py-3 rounded-xl bg-white text-purple-600 font-semibold hover:scale-105 transition">
                Mulai Mengajar
            </button>
        </div>
    </div>
</section>

@endsection