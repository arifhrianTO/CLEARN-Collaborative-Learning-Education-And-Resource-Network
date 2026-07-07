@extends('layouts.dashboard')

@section('title', 'CLEARN │ Rancang Kurikulum')

@section('content')

<main class="flex-1 p-5 lg:p-8 transition-colors duration-300 dark:bg-[#0F0B1A] bg-slate-50 min-h-screen">
    <div class="max-w-5xl mx-auto">

        <div class="mb-8">
            <button type="button"
                onclick="window.location='{{ route('mentor.courses.show', $course->id) }}'"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white text-sm font-semibold shadow-md hover:bg-primary/90 transition">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Detail Kursus
            </button>

            <h1 class="text-2xl font-extrabold dark:text-white text-slate-800 tracking-tight mt-4">
                Rancang Kurikulum
            </h1>

            <p class="text-[11px] dark:text-slate-400 text-slate-500 mt-1 uppercase tracking-widest font-black">
                {{ $course->course_title }}
            </p>
        </div>

        @if(session('success'))
        <div class="mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-4 py-3 rounded-xl text-xs font-bold">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl text-xs font-bold">
            {{ session('error') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl text-xs font-semibold">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- FORM UTAMA UPDATE SESSION --}}
        <form action="{{ route('mentor.courses.sessions.update', $course->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-3xl p-6 lg:p-8 shadow-sm">

                <div class="mb-6">
                    <h2 class="text-sm font-black dark:text-white text-slate-800 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-1 h-5 bg-primary rounded-full"></span>
                        Kurikulum & Materi
                    </h2>

                    <p class="text-[11px] dark:text-slate-500 text-slate-400 mt-2">
                        Isi judul dan deskripsi setiap pertemuan. Pertemuan terakhir khusus untuk tugas akhir.
                    </p>
                </div>
                
                {{-- Alert Info Peraturan Pertemuan --}}
                <div class="mt-4 p-3 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 flex gap-2">
                    <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 text-[10px]"></i>
                    <p class="text-[10px] text-blue-600 dark:text-blue-300 font-medium leading-relaxed">
                        <strong>Perhatian:</strong> Kotak pertemuan di bawah ini telah dibuat sesuai dengan jumlah yang Anda tentukan sebelumnya dan <strong>tidak dapat dihapus</strong>. Silakan isi materi dengan cermat. Pertemuan yang paling terakhir otomatis dialokasikan khusus sebagai sesi <strong>Tugas Akhir</strong>.
                    </p>
                </div>
            </div>

                <div class="space-y-5">
                    @foreach($course->sessions as $index => $session)

                    @php
                    $isLastSession = $loop->last;
                    $hasLesson = $session->lessons->count() > 0;
                    $hasExercise = $session->exercises->count() > 0;

                    $courseHasFinalProject = $course->sessions->contains(function ($item) {
                    return $item->finalProjects->count() > 0;
                    });

                    $previousSessionFilled = true;
                    if ($index > 0) {
                    $prevSession = $course->sessions[$index - 1];
                    $previousSessionFilled = $prevSession->lessons->count() > 0;
                    }

                    $canCreateLesson = !$isLastSession && $previousSessionFilled;
                    $canCreateExercise = !$isLastSession && $hasLesson && !$hasExercise;

                    $allPreviousSessionsFilled = true;
                    if ($isLastSession) {
                    for ($i = 0; $i < $index; $i++) {
                        if ($course->sessions[$i]->lessons->count() == 0) {
                        $allPreviousSessionsFilled = false;
                        break;
                        }
                        }
                        }

                        $canCreateFinalProject = $isLastSession && !$courseHasFinalProject && $allPreviousSessionsFilled;
                        @endphp

                        <div class="dark:bg-[#1A1625] bg-slate-50 border dark:border-white/5 border-slate-200 rounded-2xl p-5">

                            <div class="flex items-center justify-between gap-4 mb-4">
                                <span class="text-[10px] font-black {{ $isLastSession ? 'text-emerald-500' : 'text-primary' }} uppercase tracking-widest">
                                    @if($isLastSession)
                                    Sesi Tugas Akhir
                                    @else
                                    Pertemuan {{ $index + 1 }}
                                    @endif
                                </span>

                                <span class="text-[9px] font-black dark:text-slate-500 text-slate-400 uppercase tracking-widest">
                                    @if($isLastSession)
                                    {{ $session->finalProjects->count() }} Tugas Akhir
                                    @else
                                    {{ $session->lessons->count() }} Pelajaran •
                                    {{ $session->exercises->count() }} Latihan
                                    @endif
                                </span>
                            </div>

                            @if(!$isLastSession && !$previousSessionFilled && $index > 0)
                            <div class="mb-5 bg-amber-500/10 border border-amber-500/20 text-amber-500 px-4 py-3 rounded-xl text-[10px] font-bold">
                                Silakan isi pertemuan {{ $index }} terlebih dahulu untuk membuka akses ke pertemuan ini. (Harus memiliki minimal 1 Pelajaran)
                            </div>
                            @endif

                            @if($isLastSession && !$allPreviousSessionsFilled)
                            <div class="mb-5 bg-amber-500/10 border border-amber-500/20 text-amber-500 px-4 py-3 rounded-xl text-[10px] font-bold">
                                Silakan isi semua pertemuan sebelumnya (minimal 1 Pelajaran per pertemuan) untuk membuka akses ke Tugas Akhir.
                            </div>
                            @endif

                            @if($isLastSession)
                            <div class="mb-5 bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 px-4 py-3 rounded-xl text-[10px] font-bold">
                                Pertemuan ini dikhususkan untuk Tugas Akhir.
                            </div>
                            @endif

                            <input type="hidden"
                                name="sessions[{{ $index }}][id]"
                                value="{{ $session->id }}">

                            <div class="mb-4">
                                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                                    Judul {{ $isLastSession ? 'Tugas Akhir' : 'Pertemuan' }}
                                </label>

                                <input type="text"
                                    name="sessions[{{ $index }}][sessions_title]"
                                    value="{{ old("sessions.$index.sessions_title", $session->sessions_title) }}"
                                    {{ (!$isLastSession && !$previousSessionFilled && $index > 0) || ($isLastSession && !$allPreviousSessionsFilled) ? 'disabled' : 'required' }}
                                    placeholder="{{ $isLastSession ? 'Judul Tugas Akhir' : 'Judul Pertemuan ' . ($index + 1) }}"
                                    class="w-full bg-transparent border-b border-slate-300 dark:border-white/10 pb-3 text-sm font-bold dark:text-white text-slate-800 outline-none focus:border-primary transition disabled:opacity-50">
                            </div>

                            <div class="mb-5">
                                <label class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                                    Deskripsi {{ $isLastSession ? 'Tugas Akhir' : 'Pertemuan' }}
                                </label>

                                <textarea name="sessions[{{ $index }}][sessions_description]"
                                    rows="3"
                                    placeholder="{{ $isLastSession ? 'Deskripsi tugas akhir...' : 'Deskripsi materi...' }}"
                                    {{ (!$isLastSession && !$previousSessionFilled && $index > 0) || ($isLastSession && !$allPreviousSessionsFilled) ? 'disabled' : '' }}
                                    class="w-full bg-transparent border-b border-slate-300 dark:border-white/10 pb-3 text-xs dark:text-slate-300 text-slate-600 outline-none focus:border-primary transition disabled:opacity-50">{{ old("sessions.$index.sessions_description", $session->sessions_description) }}</textarea>
                            </div>

                            {{-- Lesson yang sudah ada --}}
                            @if(!$isLastSession && $session->lessons->count() > 0)
                            <div class="mb-5 space-y-4">
                                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400">
                                    Pelajaran di Pertemuan Ini
                                </p>

                                @foreach($session->lessons as $lesson)
                                <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden shadow-sm">

                                    <div class="p-4 flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <h4 class="text-sm font-black dark:text-white text-slate-800">
                                                {{ $lesson->lessons_title }}
                                            </h4>

                                            <p class="text-[11px] dark:text-slate-500 text-slate-400 mt-1">
                                                {{ $lesson->lessons_description ?? 'Tidak ada deskripsi Pelajaran.' }}
                                            </p>
                                        </div>

                                        <div class="flex items-center gap-2 shrink-0">
                                            <a href="{{ route('mentor.lessons.edit', $lesson->id) }}"
                                                class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center hover:bg-amber-500 hover:text-white transition">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </a>

                                            <button type="submit"
                                                form="delete-lesson-{{ $lesson->id }}"
                                                onclick="return confirm('Yakin ingin menghapus Pelajaran ini? Semua materi di dalamnya juga akan dihapus.')"
                                                class="w-9 h-9 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Preview material Lesson --}}
                                    @if($lesson->materials->count() > 0)
                                    <div class="px-4 pb-4 space-y-3">
                                        @foreach($lesson->materials as $material)
                                        @php
                                        $type = strtolower($material->type ?? '');
                                        $file = $material->file_path ?? null;
                                        $url = $material->url ?? null;

                                        $fileSource = $file ? asset('storage/' . $file) : null;
                                        $source = $url ?: $fileSource;
                                        @endphp

                                        @if($type === 'video')
                                        @if($url)
                                        <div class="aspect-video w-full max-w-xl overflow-hidden rounded-xl bg-black border dark:border-white/10 border-slate-200">
                                            <iframe src="{{ $url }}" class="w-full h-full" allowfullscreen allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
                                        </div>
                                        @elseif($fileSource)
                                        <div class="aspect-video w-full max-w-xl overflow-hidden rounded-xl bg-black border dark:border-white/10 border-slate-200">
                                            <video controls class="w-full h-full object-contain bg-black">
                                                <source src="{{ $fileSource }}">
                                                Browser kamu tidak mendukung video.
                                            </video>
                                        </div>
                                        @endif

                                        @elseif($type === 'pdf' || $type === 'link')
                                        @if($source)
                                        <a href="{{ $source }}" target="_blank"
                                            class="flex items-center gap-3 p-3 rounded-xl dark:bg-[#0F0B1A] bg-slate-50 border dark:border-white/5 border-slate-200 hover:border-primary/50 transition">

                                            <div class="w-10 h-10 rounded-lg dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 text-{{ $type === 'pdf' ? 'cyan' : 'primary' }}-500 flex items-center justify-center shrink-0">
                                                <i class="{{ $type === 'pdf' ? 'fa-regular fa-file-pdf' : 'fa-solid fa-link' }} text-lg"></i>
                                            </div>

                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-primary truncate">
                                                    {{ $lesson->lessons_title }}
                                                </p>
                                                <p class="text-[10px] dark:text-slate-500 text-slate-400 truncate">
                                                    {{ $type === 'pdf' ? 'PDF' : $source }}
                                                </p>
                                            </div>
                                        </a>
                                        @endif
                                        @endif
                                        @endforeach
                                    </div>
                                    @endif

                                </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Tugas Akhir yang sudah ada --}}
                            @if($isLastSession && $session->finalProjects->count() > 0)
                            <div class="mb-5 space-y-4">
                                <p class="text-[9px] font-black uppercase tracking-widest text-emerald-500">
                                    Modul Tugas Akhir
                                </p>

                                @foreach($session->finalProjects as $project)
                                <div class="dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 rounded-2xl overflow-hidden shadow-sm">

                                    {{-- Top Section: Details & Buttons --}}
                                    <div class="p-4 flex items-start justify-between gap-4">
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-black dark:text-white text-slate-800">
                                            {{ $project->project_title ?? 'Tugas Akhir' }}
                                        </h4>

                                        <p class="text-[11px] dark:text-slate-500 text-slate-400 mt-1 line-clamp-2">
                                            {{ $project->project_description ?? 'Tidak ada deskripsi.' }}
                                        </p>

                                        {{-- Duration and Format Info --}}
                                        <div class="flex flex-wrap gap-2 mt-3">
                                            @if(!empty($project->duration_days))
                                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg dark:bg-[#0F0B1A] bg-slate-100 text-slate-500 text-[10px] font-bold border dark:border-white/5 border-slate-200">
                                                <i class="fa-regular fa-clock text-emerald-500"></i>
                                                {{ $project->duration_days }} Hari
                                            </div>
                                            @endif

                                            @if(!empty($project->allowed_extensions))
                                            <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-lg dark:bg-[#0F0B1A] bg-slate-100 text-slate-500 text-[10px] font-bold uppercase border dark:border-white/5 border-slate-200">
                                                <i class="fa-solid fa-file-circle-check text-sky-500"></i>
                                                {{ $project->allowed_extensions }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>

                                        {{-- Action Buttons --}}
                                        <div class="flex items-center gap-2 shrink-0">
                                            <a href=""
                                                class="w-9 h-9 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center hover:bg-amber-500 hover:text-white transition">
                                                <i class="fa-solid fa-pen text-xs"></i>
                                            </a>

                                            <button type="submit"
                                                form="delete-project-{{ $project->id }}"
                                                onclick="return confirm('Yakin ingin menghapus Tugas Akhir ini?')"
                                                class="w-9 h-9 rounded-xl bg-red-500/10 text-red-500 flex items-center justify-center hover:bg-red-500 hover:text-white transition">
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Bottom Section: File & Link Upload Preview --}}
                                    @if($project->materials->count() > 0)
                                    <div class="px-4 pb-4 space-y-3">
                                        @foreach($project->materials as $material)
                                        @php
                                        $type = strtolower($material->type ?? '');
                                        $file = $material->file_path ?? null;
                                        $url = $material->url ?? null;

                                        $fileSource = $file ? asset('storage/' . $file) : null;
                                        $source = $url ?: $fileSource;
                                        @endphp

                                        @if($type === 'pdf' || $type === 'link')
                                        @if($source)
                                        <a href="{{ $source }}" target="_blank"
                                            class="flex items-center gap-3 p-3 rounded-xl dark:bg-[#0F0B1A] bg-slate-50 border dark:border-white/5 border-slate-200 hover:border-primary/50 transition">
                                            
                                            <div class="w-10 h-10 rounded-lg dark:bg-[#1A1625] bg-white border dark:border-white/5 border-slate-200 text-{{ $type === 'pdf' ? 'cyan' : 'primary' }}-500 flex items-center justify-center shrink-0">
                                                <i class="{{ $type === 'pdf' ? 'fa-regular fa-file-pdf' : 'fa-solid fa-link' }} text-lg"></i>
                                            </div>

                                            <div class="min-w-0">
                                                <p class="text-xs font-bold text-primary truncate">
                                                    {{ $project->project_title ?? 'Tugas Akhir' }}
                                                </p>
                                                <p class="text-[10px] dark:text-slate-500 text-slate-400 truncate uppercase">
                                                    {{ $type === 'pdf' ? 'FILE DOCUMENT' : $source }}
                                                </p>
                                            </div>
                                        </a>
                                        @endif
                                        @endif
                                        @endforeach
                                    </div>
                                    @endif

                                </div>
                                @endforeach
                            </div>
                            @endif

                            {{-- Exercise yang sudah ada --}}
                            @if(!$isLastSession && $hasExercise)
                            @php
                            $exercise = $session->exercises->first();
                            @endphp

                            <div class="mb-5 dark:bg-[#A487F8]/10 bg-[#A487F8]/20 border dark:border-[#A487F8]/20 border-[#A487F8]/20 rounded-2xl p-4">
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                    <div>
                                        <p class="text-[9px] font-black uppercase tracking-widest text-primary">
                                            Kuis
                                        </p>

                                        <h4 class="text-sm font-black dark:text-white text-slate-800 mt-1">
                                            {{ $exercise->exercise_title }}
                                        </h4>

                                        <p class="text-[10px] dark:text-slate-400 text-slate-500 mt-1">
                                            {{ $exercise->questions->count() }} Soal
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('mentor.sessions.exercises.edit', $exercise->id) }}"
                                            class="px-4 py-2 rounded-xl bg-primary text-white text-[9px] font-black uppercase tracking-widest hover:brightness-110 transition">
                                            Ubah Kuis
                                        </a>

                                        <button type="submit"
                                            form="delete-exercise-{{ $exercise->id }}"
                                            onclick="return confirm('Yakin ingin menghapus kuis ini? Semua soal dan pilihan jawaban akan ikut terhapus.')"
                                            class="px-4 py-2 rounded-xl bg-red-500/10 text-red-500 text-[9px] font-black uppercase tracking-widest hover:bg-red-500 hover:text-white transition">
                                            Hapus Kuis
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endif

                            {{-- Action buttons --}}
                            <div class="pt-4 border-t border-slate-200 dark:border-white/5 flex flex-wrap gap-2">

                                {{-- Lesson --}}
                                @if($isLastSession)
                                <button type="button"
                                    class="px-4 py-2 rounded-xl bg-slate-500/10 text-slate-500 text-[9px] font-black uppercase tracking-widest cursor-not-allowed hidden">
                                </button>
                                @elseif($canCreateLesson)
                                <a href="{{ route('mentor.sessions.lessons.create', $session->id) }}"
                                    class="px-4 py-2 rounded-xl bg-primary/10 text-primary text-[9px] font-black uppercase tracking-widest hover:bg-primary hover:text-white transition">
                                    + Pelajaran
                                </a>
                                @else
                                <button type="button"
                                    class="px-4 py-2 rounded-xl bg-slate-500/10 text-slate-500 text-[9px] font-black uppercase tracking-widest cursor-not-allowed"
                                    title="Isi pertemuan sebelumnya terlebih dahulu">
                                    + Pelajaran (Terkunci)
                                </button>
                                @endif

                                {{-- Exercise / Kuis --}}
                                @if($isLastSession)
                                <button type="button"
                                    class="px-4 py-2 rounded-xl bg-slate-500/10 text-slate-500 text-[9px] font-black uppercase tracking-widest cursor-not-allowed hidden">
                                </button>
                                @elseif($canCreateExercise)
                                <button type="submit"
                                    form="create-exercise-{{ $session->id }}"
                                    class="px-4 py-2 rounded-xl bg-[#A487F8]/10 text-[#A487F8] text-[9px] font-black uppercase tracking-widest hover:bg-[#A487F8] hover:text-white transition">
                                    + Kuis
                                </button>
                                @elseif($hasExercise)
                                <button type="button"
                                    class="px-4 py-2 rounded-xl bg-slate-500/10 text-slate-500 text-[9px] font-black uppercase tracking-widest cursor-not-allowed">
                                    Kuis Sudah Ada
                                </button>
                                @else
                                <button type="button"
                                    class="px-4 py-2 rounded-xl bg-slate-500/10 text-slate-500 text-[9px] font-black uppercase tracking-widest cursor-not-allowed">
                                    + Kuis (Terkunci)
                                </button>
                                @endif

                                {{-- Tugas Akhir --}}
                                @if($isLastSession)
                                @if($canCreateFinalProject)
                                <a href="{{ route('mentor.sessions.projects.create', $session->id) }}"
                                    class="px-4 py-2 rounded-xl bg-emerald-500/10 text-emerald-500 text-[9px] font-black uppercase tracking-widest hover:bg-emerald-500 hover:text-white transition">
                                    + Tugas Akhir
                                </a>
                                @else
                                <button type="button"
                                    class="px-4 py-2 rounded-xl bg-slate-500/10 text-slate-500 text-[9px] font-black uppercase tracking-widest cursor-not-allowed"
                                    title="Isi semua pertemuan sebelumnya terlebih dahulu">
                                    + Tugas Akhir (Terkunci)
                                </button>
                                @endif
                                @endif

                            </div>
                        </div>
                        @endforeach
                </div>

                <div class="flex justify-end gap-3 mt-8">
                    <a href="{{ route('mentor.courses.show', $course->id) }}"
                        class="px-8 py-3 rounded-xl bg-slate-200 dark:bg-white/5 text-slate-600 dark:text-slate-300 text-[10px] font-black uppercase tracking-widest">
                        Kembali
                    </a>

                    <button type="submit"
                        class="px-10 py-3 rounded-xl bg-primary text-white text-[10px] font-black uppercase tracking-widest shadow-lg shadow-primary/20 hover:brightness-110 transition">
                        Simpan Semua
                    </button>
                </div>

            </div>
        </form>

        {{-- FORM DELETE LESSON --}}
        @foreach($course->sessions as $session)
        @foreach($session->lessons as $lesson)
        <form id="delete-lesson-{{ $lesson->id }}"
            action="{{ route('mentor.lessons.destroy', $lesson->id) }}"
            method="POST"
            class="hidden">
            @csrf
            @method('DELETE')
        </form>
        @endforeach
        @endforeach

        {{-- FORM DELETE EXERCISE --}}
        @foreach($course->sessions as $session)
        @foreach($session->exercises as $exercise)
        <form id="delete-exercise-{{ $exercise->id }}"
            action="{{ route('mentor.sessions.exercises.destroy', $exercise->id) }}"
            method="POST"
            class="hidden">
            @csrf
            @method('DELETE')
        </form>
        @endforeach
        @endforeach

        {{-- FORM CREATE EXERCISE --}}
        @foreach($course->sessions as $session)
        <form id="create-exercise-{{ $session->id }}"
            action="{{ route('mentor.sessions.exercises.storeEmpty', $session->id) }}"
            method="POST"
            class="hidden">
            @csrf
        </form>
        @endforeach

        {{-- FORM DELETE TUGAS AKHIR --}}
        @foreach($course->sessions as $session)
        @foreach($session->finalProjects as $project)
        <form id="delete-project-{{ $project->id }}"
            action="{{ route('mentor.projects.destroy', $project->id) }}"
            method="POST"
            class="hidden">
            @csrf
            @method('DELETE')
        </form>
        @endforeach
        @endforeach

    </div>
</main>

@endsection