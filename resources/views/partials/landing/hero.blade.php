<div class="absolute top-20 left-1/2 -translate-x-1/2 w-[90vw] max-w-[800px] h-[250px] md:h-[400px] bg-purple-900/15 blur-[120px] rounded-full -z-10 opacity-0 dark:opacity-100 transition-opacity"></div>

<div class="inline-flex items-center px-4 py-1.5 rounded-full bg-black/5 dark:bg-white/10 backdrop-blur-md border border-black/10 dark:border-white/20 text-slate-900 dark:text-white text-xs mb-6 md:mb-8">
    <i class="fas fa-award mr-2.5"></i> Online Course Application
</div>

<h1 class="text-2xl sm:text-3xl md:text-5xl lg:text-6xl font-extrabold leading-[1.2] md:leading-[1.15] mb-6 md:mb-8 tracking-tight md:tracking-tighter">
    Raih Skill Baru <br>
    Dengan
    <span class="text-[#A487F8] dark:text-[#A487F8]" id="typed-text"></span>
    <br>
    Bersama <span class="text-[#A487F8] dark:text-[#A487F8]">Pengajar Berpengalaman</span>
</h1>

<p class="text-slate-600 dark:text-gray-400 max-w-xs sm:max-w-md md:max-w-2xl mx-auto mb-8 md:mb-12 text-sm md:text-base leading-relaxed">
    Belajar dari pengajar profesional, tingkatkan keterampilan, dan capai tujuan Anda melalui pengalaman belajar yang fleksibel, interaktif, dan berkualitas.
</p>

<div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-5 px-4 sm:px-0">
    <button
        onclick="window.location='{{ route('tutorial') }}'"
        class="w-full sm:w-56 bg-gradient-to-r from-[#A487F8] to-[#A487F8] px-10 py-4 rounded-xl font-bold flex items-center justify-center group text-sm md:text-base shadow-lg text-white">
        Mulai Mengajar
    </button>
    <button onclick="window.location='{{ route('login') }}'" class="w-full sm:w-56 bg-black/5 dark:bg-white/10 backdrop-blur-md border border-black/10 dark:border-white/20 px-10 py-4 rounded-xl font-bold text-sm md:text-base transition-colors text-slate-900 dark:text-white">
        Mulai Belajar
    </button>
</div>

<div class="grid grid-cols-3 gap-6 mt-32 max-w-5xl mx-auto px-6">
    <div class="text-center">
        <h3 class="text-3xl md:text-4xl font-extrabold text-[#A487F8] dark:text-[#A487F8]">{{ $studentCount }}</h3>
        <p class="text-slate-500 dark:text-gray-500 text-xs mt-2 uppercase tracking-widest font-semibold">Pelajar Terdaftar</p>
    </div>
    <div class="text-center">
        <h3 class="text-3xl md:text-4xl font-extrabold text-[#A487F8] dark:text-[#A487F8]">{{ $courseCount }}</h3>
        <p class="text-slate-500 dark:text-gray-500 text-xs mt-2 uppercase tracking-widest font-semibold">Kursus Online</p>
    </div>
    <div class="text-center">
        <h3 class="text-3xl md:text-4xl font-extrabold text-[#A487F8] dark:text-[#A487F8]">{{ $mentorCount }}</h3>
        <p class="text-slate-500 dark:text-gray-500 text-xs mt-2 uppercase tracking-widest font-semibold">Pengajar Aktif</p>
    </div>
</div>