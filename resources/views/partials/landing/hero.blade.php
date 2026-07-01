 <div class="absolute top-20 left-1/2 -translate-x-1/2 w-[800px] h-[400px] bg-purple-900/15 blur-[120px] rounded-full -z-10 opacity-0 dark:opacity-100 transition-opacity"></div>

 <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-purple-100 dark:bg-purple-900/40 border border-purple-200 dark:border-purple-700/50 text-purple-600 dark:text-purple-300 text-xs mb-8">
     <i class="fas fa-award mr-2.5"></i> Platform Belajar Online Baru
 </div>

 <h1 class="text-3xl md:text-6xl font-extrabold leading-[1.15] mb-8 tracking-tighter">
     Raih Skill Baru </br>
     dengan
     <span class="text-purple-600 dark:text-purple-400" id="typed-text"></span>
     <br>
     Bersama <span class="text-purple-600 dark:text-purple-400">Pengajar Berpengalaman</span>
 </h1>

 <p class="text-slate-600 dark:text-gray-400 max-w-2xl mx-auto mb-12 text-base leading-relaxed">
     Belajar dari para ahli industri dan kuasai keterampilan baru sesuai kecepatan Anda sendiri. Bergabunglah dengan para pelajar berprestasi.
 </p>

 <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-5">
     <button class="bg-gradient-to-r from-purple-700 to-purple-500 px-10 py-4 rounded-xl font-bold flex items-center group text-base shadow-lg text-white">
         Mulai Belajar <i class="fas fa-caret-right ml-2.5 group-hover:translate-x-1 transition-transform"></i>
     </button>
     <button class="bg-white dark:bg-gray-900/70 border border-slate-200 dark:border-gray-800 px-10 py-4 rounded-xl font-bold text-base transition-colors text-slate-700 dark:text-white">
         Lihat Semua Kursus
     </button>
 </div>

 <div class="grid grid-cols-3 gap-6 mt-32 max-w-5xl mx-auto px-6">
     <div class="text-center">
         <h3 class="text-3xl md:text-4xl font-extrabold text-purple-600 dark:text-purple-400">{{ $studentCount }}</h3>
         <p class="text-slate-500 dark:text-gray-500 text-xs mt-2 uppercase tracking-widest font-semibold">Pelajar Terdaftar</p>
     </div>
     <div class="text-center">
         <h3 class="text-3xl md:text-4xl font-extrabold text-purple-600 dark:text-purple-400">{{ $courseCount }}+</h3>
         <p class="text-slate-500 dark:text-gray-500 text-xs mt-2 uppercase tracking-widest font-semibold">Kursus Online</p>
     </div>
     <div class="text-center">
         <h3 class="text-3xl md:text-4xl font-extrabold text-purple-600 dark:text-purple-400">{{ $mentorCount }}+</h3>
         <p class="text-slate-500 dark:text-gray-500 text-xs mt-2 uppercase tracking-widest font-semibold">Pengajar Aktif</p>
     </div>
 </div>