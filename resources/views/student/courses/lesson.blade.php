<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-clearn.png') }}">
    <title>CLEARN │ {{ $id ?? 'Materi' }}</title>

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
            <a href="{{ route('student.course.index', $course->course_slug) }}" class="p-2 hover:bg-gray-500/10 rounded-lg transition text-slate-500 hover:text-primary">
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
                        <a href="{{ route('student.course.lesson', ['slug' => $course->course_slug, 'lesson_id' => $lesson->id]) }}" class="flex flex-col gap-1 px-4 py-3 rounded-xl hover:bg-black/5 dark:hover:bg-dark-card-lighter text-slate-500 dark:text-zinc-400 hover:text-primary dark:hover:text-primary transition-all cursor-pointer group {{ request('lesson_id') == $lesson->id ? 'bg-primary/10 text-primary' : '' }}">
                            <div class="flex items-center gap-3">
                                <i class="far fa-play-circle text-xs group-hover:text-primary"></i>
                                <span class="text-[13px] font-semibold leading-tight group-hover:text-primary transition-colors">{{ $lesson->lessons_title }}</span>
                            </div>

                        </a>
                        @endforeach

                        @if($session->exercise)
                        <a href="{{ route('student.exercise.show', $session->exercise->id) }}" class="flex flex-col gap-1 px-4 py-3 rounded-xl hover:bg-black/5 dark:hover:bg-dark-card-lighter text-slate-500 dark:text-zinc-400 hover:text-primary dark:hover:text-primary transition-all cursor-pointer group {{ request()->routeIs('student.exercise.*') && request()->route('exerciseId') == $session->exercise->id ? 'bg-primary/10 text-primary' : '' }}">
                            <div class="flex items-center gap-3">
                                <i class="far fa-file-alt text-xs group-hover:text-primary"></i>
                                <span class="text-[13px] font-semibold leading-tight group-hover:text-primary transition-colors text-zinc-300">Latihan: {{ $session->exercise->exercise_title }}</span>
                            </div>
                        </a>
                        @endif

                        @if(count($session->finalProjects) > 0)
                        @foreach($session->finalProjects as $project)
                        <a href="{{ route('student.project.show', $project->id) }}" class="flex flex-col gap-1 px-4 py-3 rounded-xl hover:bg-black/5 dark:hover:bg-dark-card-lighter text-slate-500 dark:text-zinc-400 hover:text-primary dark:hover:text-primary transition-all cursor-pointer group">
                            <div class="flex items-center gap-3">
                                <i class="far fa-file-archive text-xs group-hover:text-primary"></i>
                                <span class="text-[13px] font-semibold leading-tight group-hover:text-primary transition-colors text-zinc-300">Tugas Akhir: {{ $project->project_title }}</span>
                            </div>
                        </a>
                        @endforeach
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </aside>

        <main class="flex-1 overflow-y-auto bg-slate-50 dark:bg-dark-bg p-6 lg:p-10 custom-scrollbar">
            <div class="max-w-5xl mx-auto">

                @php
                $videoMaterial = $activeLesson ? $activeLesson->materials->where('type', 'video')->first() : null;
                $documentMaterial = $activeLesson ? $activeLesson->materials->whereIn('type', ['document', 'pdf'])->first() : null;
                $linkMaterial = $activeLesson ? $activeLesson->materials->where('type', 'link')->first() : null;
                @endphp
                @if($videoMaterial)
                <div class="relative bg-black aspect-video rounded-2xl overflow-hidden shadow-2xl border border-white/5 mb-8">
                    @if($videoMaterial->url)
                    <iframe src="{{ str_replace('watch?v=', 'embed/', $videoMaterial->url) }}" class="absolute inset-0 w-full h-full" frameborder="0" allowfullscreen></iframe>
                    @elseif($videoMaterial->file_path)
                    <video controls class="absolute inset-0 w-full h-full">
                        <source src="{{ Storage::url($videoMaterial->file_path) }}" type="video/mp4">
                    </video>
                    @endif
                </div>
                @endif

                @if($documentMaterial)
                <div class="bg-white dark:bg-dark-sidebar border border-gray-200 dark:border-border-custom rounded-2xl p-6 mb-8">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4"><i class="fas fa-file-pdf text-red-500 mr-2"></i>Materi Dokumen</h2>
                    @if($documentMaterial->url)
                    <a href="{{ $documentMaterial->url }}" target="_blank" class="bg-primary/10 text-primary px-4 py-2 rounded-lg font-medium hover:bg-primary/20 transition inline-block"><i class="fas fa-external-link-alt mr-2"></i> Buka Dokumen</a>
                    @elseif($documentMaterial->file_path)
                    <a href="{{ Storage::url($documentMaterial->file_path) }}" target="_blank" class="bg-primary text-white px-4 py-2 rounded-lg font-medium hover:bg-primary/90 transition inline-block"><i class="fas fa-download mr-2"></i> Unduh File Materi</a>
                    @endif
                </div>
                @endif

                @if($linkMaterial)
                <div class="bg-white dark:bg-dark-sidebar border border-gray-200 dark:border-border-custom rounded-2xl p-6 mb-8">
                    <h2 class="text-lg font-bold text-slate-800 dark:text-white mb-4"><i class="fas fa-link text-blue-500 mr-2"></i>Tautan Eksternal</h2>
                    <a href="{{ $linkMaterial->url }}" target="_blank" class="bg-blue-500/10 text-blue-500 px-4 py-2 rounded-lg font-medium hover:bg-blue-500/20 transition inline-block"><i class="fas fa-external-link-alt mr-2"></i> Kunjungi Tautan</a>
                </div>
                @endif

                @if($activeLesson && $activeLesson->lessons_description)
                <div class="bg-white dark:bg-dark-sidebar border border-gray-200 dark:border-border-custom rounded-2xl p-6 prose dark:prose-invert max-w-none">
                    {!! $activeLesson->lessons_description !!}
                </div>
                @endif
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