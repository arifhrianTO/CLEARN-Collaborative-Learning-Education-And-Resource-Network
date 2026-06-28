<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Belajar: {{ $id ?? 'Materi' }} - Clearn</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="h-screen flex flex-col bg-slate-50 dark:bg-dark-bg text-slate-900 dark:text-white transition-colors duration-300 antialiased overflow-hidden font-sans">

    <header class="h-16 border-b border-gray-200 dark:border-border-custom bg-white/80 dark:bg-dark-sidebar backdrop-blur-md px-6 flex items-center justify-between shrink-0 z-50">
        <div class="flex items-center gap-4">
            <a href="{{ route('student.course.show', $course->course_slug) }}" class="p-2 hover:bg-gray-500/10 rounded-lg transition text-slate-500 hover:text-primary">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h2 class="font-bold text-sm tracking-tight hidden md:block">
                {{ $course->course_title }}
            </h2>
        </div>

        <div class="flex items-center gap-6">
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Progress Kursus</p>
                    <p class="text-xs font-black">14% <span class="text-slate-400 font-medium">(2/12 Materi)</span></p>
                </div>
                <div class="w-32 h-2 bg-gray-200 dark:bg-border-custom rounded-full overflow-hidden">
                    <div class="h-full bg-primary" style="width:14%"></div>
                </div>
            </div>

            <button onclick="toggleTheme()" class="p-2 rounded-xl hover:bg-gray-500/10 transition">
                <i id="theme-icon" class="fa-solid fa-moon text-lg text-primary"></i>
            </button>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">

        <aside class="w-80 lg:w-96 border-r border-gray-200 dark:border-border-custom bg-white dark:bg-dark-sidebar overflow-y-auto hidden md:block custom-scrollbar">
            <div class="p-6">
                <h3 class="font-black text-lg tracking-tighter mb-6 dark:text-white">Kurikulum Kursus</h3>

                    @foreach($course->sessions as $session)
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">{{ $session->sessions_title }}</h4>
                            <span class="text-[10px] font-bold bg-slate-100 dark:bg-border-custom px-2 py-0.5 rounded dark:text-slate-400">{{ count($session->lessons) }} Materi</span>
                        </div>

                        <div class="space-y-2">
                            @foreach($session->lessons as $lesson)
                            <div class="flex flex-col gap-1 px-4 py-3 rounded-xl hover:bg-black/5 dark:hover:bg-dark-card-lighter text-slate-500 dark:text-zinc-400 hover:text-primary dark:hover:text-primary transition-all cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <i class="far fa-play-circle text-xs group-hover:text-primary"></i>
                                    <span class="text-[13px] font-semibold leading-tight group-hover:text-primary transition-colors">{{ $lesson->lessons_title }}</span>
                                </div>
                                <div class="flex items-center gap-1 ml-6 text-[10px] opacity-60 group-hover:text-primary">
                                    <i class="far fa-clock"></i> --:--
                                </div>
                            </div>
                            @endforeach
                            
                            @if($session->exercise)
                            <div class="flex flex-col gap-1 px-4 py-3 rounded-xl hover:bg-black/5 dark:hover:bg-dark-card-lighter text-slate-500 dark:text-zinc-400 hover:text-primary dark:hover:text-primary transition-all cursor-pointer group">
                                <div class="flex items-center gap-3">
                                    <i class="far fa-file-alt text-xs group-hover:text-primary"></i>
                                    <span class="text-[13px] font-semibold leading-tight group-hover:text-primary transition-colors text-zinc-300">Latihan: {{ $session->exercise->exercise_title }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-dark-bg p-6 lg:p-10 custom-scrollbar">
            <div class="max-w-5xl mx-auto">

                <div class="relative bg-black aspect-video rounded-2xl overflow-hidden shadow-2xl border border-white/5 mb-8">
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-dark-card/60">
                        <button class="w-20 h-20 bg-primary rounded-full flex items-center justify-center text-white text-3xl shadow-2xl hover:scale-110 transition-transform">
                            <i class="fas fa-play ml-1"></i>
                        </button>
                        <p class="mt-4 font-bold text-white/40 tracking-widest uppercase text-[10px]">
                            Video Materi Pengembangan Web
                        </p>
                    </div>
                </div>

                <div class="mb-10">
                    <h1 class="text-2xl font-black tracking-tighter mb-6 dark:text-white">
                        {{ $id ?? '1' }}. Selamat Datang di Program Pelatihan Lengkap Pengembangan Web
                    </h1>

                    <div class="flex gap-8 border-b border-gray-200 dark:border-border-custom mb-8">
                        <button class="pb-3 px-2 text-sm font-bold border-b-2 border-primary text-primary">Deskripsi</button>
                        <button class="pb-3 px-2 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-primary transition-all">Catatan</button>
                        <button class="pb-3 px-2 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-primary transition-all">Diskusi</button>
                        <button class="pb-3 px-2 text-sm font-bold border-b-2 border-transparent text-slate-500 hover:text-primary transition-all">Sumber Daya (2)</button>
                    </div>

                    <div class="prose dark:prose-invert max-w-none mb-10">
                        <p class="text-slate-600 dark:text-zinc-400 leading-relaxed text-sm">
                            Pada materi pertama ini, Anda akan memahami gambaran umum dunia web development. 
                            Kursus ini membahas HTML, CSS, JavaScript, hingga framework Laravel dan Tailwind CSS agar siap menjadi web developer profesional.
                        </p>
                    </div>

                    <div class="space-y-3">
                        <h3 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4">File Pendukung</h3>
                        
                        <div class="bg-white dark:bg-dark-sidebar border border-gray-200 dark:border-border-custom p-4 rounded-xl flex items-center justify-between group hover:border-primary/50 transition-all">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center text-primary">
                                    <i class="fas fa-file-archive"></i>
                                </div>
                                <div>
                                    <p class="text-[14px] font-bold dark:text-zinc-200">Source-Code-Latihan.zip</p>
                                    <p class="text-[11px] text-slate-500">2.4 MB • Updated 2 days ago</p>
                                </div>
                            </div>
                            <button class="w-8 h-8 flex items-center justify-center rounded-full text-slate-400 hover:bg-primary hover:text-white transition-all">
                                <i class="fas fa-download text-xs"></i>
                            </button>
                        </div>
                    </div>

                    <div class="mt-12 pt-10 border-t border-gray-200 dark:border-border-custom">
                        <h3 class="text-lg font-black tracking-tighter mb-6 dark:text-white">Diskusi Materi</h3>
                        <div class="flex gap-4 mb-8">
                            <div class="w-10 h-10 rounded-full bg-primary/20 shrink-0 flex items-center justify-center font-bold text-primary text-xs">
                                JD
                            </div>
                            <div class="flex-1">
                                <textarea class="w-full bg-white dark:bg-dark-sidebar border border-gray-200 dark:border-border-custom rounded-xl p-4 text-sm focus:ring-1 focus:ring-primary focus:border-primary outline-none transition-all dark:text-zinc-300" placeholder="Tanyakan sesuatu tentang materi ini..."></textarea>
                                <div class="flex justify-end mt-2">
                                    <button class="px-6 py-2 bg-primary text-white text-xs font-bold rounded-lg hover:bg-primary/90 transition-all">
                                        Kirim Pertanyaan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');
            
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.theme = isDark ? 'dark' : 'light';
            
            themeIcon.classList.replace(isDark ? 'fa-moon' : 'fa-sun', isDark ? 'fa-sun' : 'fa-moon');
        }

        document.addEventListener('DOMContentLoaded', () => {
            const themeIcon = document.getElementById('theme-icon');
            if (document.documentElement.classList.contains('dark')) {
                themeIcon.classList.replace('fa-moon', 'fa-sun');
            }
        });
    </script>
</body>
</html>