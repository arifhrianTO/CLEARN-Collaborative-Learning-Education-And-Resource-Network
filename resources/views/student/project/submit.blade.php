@extends('layouts.learning')

@section('title', 'Upload Tugas Akhir')

@section('content')
    <div class="flex w-screen h-screen overflow-hidden bg-slate-50 dark:bg-[#0F0B1A]">
        
        {{-- SIDEBAR --}}
        <aside class="w-80 border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-[#1A1625] flex flex-col">
            {{-- Header Sidebar --}}
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 shrink-0">
                <a href="{{ route('student.course.lesson', $project->session->course->course_slug) }}" class="flex items-center gap-2 text-slate-500 hover:text-primary mb-4 transition">
                    <i class="fas fa-arrow-left"></i> <span class="text-xs font-bold">Kembali ke Materi</span>
                </a>
                <h2 class="font-black text-sm dark:text-white mb-1">{{ $project->session->course->course_title }}</h2>
                <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest">{{ $project->session->sessions_title }}</p>
            </div>

           {{-- DAFTAR SESI --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-8 custom-scrollbar">
                <div>
                    <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Tugas Akhir</h4>
                    <div class="space-y-1">
                        <div class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold transition-all cursor-pointer active-item bg-primary/10 text-primary">
                            <div class="flex items-center gap-3">
                                <i class="fas fa-file-upload"></i>
                                <span>{{ $project->project_title }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

     {{-- MAIN CONTENT --}}
<main class="flex-1 w-full h-full overflow-y-auto bg-slate-50 dark:bg-[#0F0B1A] custom-scrollbar">
    <div class="w-full h-full p-8">

        @if(session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-500 text-xs font-bold flex items-center gap-2">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 text-xs font-bold flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-500/10 border border-red-500/20 text-red-500 text-xs font-bold">
                <ul class="list-disc pl-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- 1. INSTRUKSI TUGAS --}}
        <div class="w-full bg-white dark:bg-[#1c1826] p-8 rounded-2xl border border-gray-200 dark:border-gray-800 mb-6 shadow-sm">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-book text-primary text-sm"></i>
                <h1 class="text-sm font-bold text-slate-800 dark:text-white uppercase tracking-widest">Panduan Tugas: {{ $project->project_title }}</h1>
            </div>
            <div class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed grid grid-cols-1 md:grid-cols-2 gap-8 prose dark:prose-invert">
                <div>
                    {!! nl2br(e($project->project_description)) !!}
                </div>
            </div>

            {{-- Material Pendukung dari Pengajar --}}
            @if($project->materials->isNotEmpty())
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2 mb-4">
                        <i class="fas fa-paperclip text-slate-400 text-sm"></i>
                        <h3 class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Material Pendukung</h3>
                    </div>
                    <div class="space-y-3">
                        @foreach($project->materials as $material)
                            @php
                                $type = strtolower($material->type ?? '');
                                $file = $material->file_path ?? null;
                                $url = $material->url ?? null;
                                $source = $url ?: ($file ? Storage::url($file) : null);
                            @endphp

                            @if($type === 'pdf' && $source)
                                <a href="{{ $source }}" target="_blank"
                                    class="flex items-center gap-4 p-4 rounded-xl bg-cyan-500/5 border border-cyan-500/20 hover:bg-cyan-500/10 transition group">
                                    <div class="w-10 h-10 rounded-lg bg-cyan-500/10 text-cyan-500 flex items-center justify-center shrink-0 group-hover:bg-cyan-500 group-hover:text-white transition">
                                        <i class="fa-regular fa-file-pdf text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-cyan-500 transition">File Panduan (PDF)</p>
                                        <p class="text-[10px] text-slate-500">Klik untuk membuka</p>
                                    </div>
                                </a>
                            @elseif($type === 'link' && $source)
                                <a href="{{ $source }}" target="_blank"
                                    class="flex items-center gap-4 p-4 rounded-xl bg-primary/5 border border-primary/20 hover:bg-primary/10 transition group">
                                    <div class="w-10 h-10 rounded-lg bg-primary/10 text-primary flex items-center justify-center shrink-0 group-hover:bg-primary group-hover:text-white transition">
                                        <i class="fa-solid fa-link text-lg"></i>
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-primary transition">Link Referensi</p>
                                        <p class="text-[10px] text-slate-500 truncate max-w-xs">{{ $source }}</p>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- 2. GRID UPLOAD & STATUS --}}
        <div class="w-full grid grid-cols-1 xl:grid-cols-12 gap-6">
            
            {{-- Area Upload (7/12) --}}
            <div class="xl:col-span-7 bg-white dark:bg-[#1c1826] rounded-2xl border border-gray-200 dark:border-gray-800 p-8 flex flex-col shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-primary/20 text-primary flex items-center justify-center text-sm">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-800 dark:text-white">Unggah Tugas</h2>
                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Max 50MB ({{ $project->allowed_extensions ?? '.zip, .pdf' }})</p>
                    </div>
                </div>

                @if($result && $result->final_project_score !== null && $result->final_project_score >= 70)
                    <div class="border border-emerald-500/30 bg-emerald-500/5 rounded-xl p-10 flex flex-col items-center justify-center text-center h-full min-h-[250px]">
                        <i class="fas fa-check-circle text-emerald-500 text-3xl mb-3"></i>
                        <p class="text-sm font-bold text-emerald-600 dark:text-emerald-400 mb-1">Tugas Selesai Dinilai</p>
                        <p class="text-xs text-slate-500">Anda mendapatkan skor: <span class="font-black text-emerald-500">{{ $result->final_project_score }}</span></p>
                    </div>
                @elseif($result && $result->final_project_score !== null)
                    <div class="border border-red-500/30 bg-red-500/5 rounded-xl p-6 mb-6">
                        <div class="flex flex-col items-center justify-center text-center">
                            <i class="fas fa-times-circle text-red-500 text-3xl mb-3"></i>
                            <p class="text-sm font-bold text-red-600 dark:text-red-400 mb-1">Tugas Tidak Lulus</p>
                            <p class="text-xs text-slate-500">Skor: <span class="font-black text-red-500">{{ $result->final_project_score }}</span></p>
                            @if($result->mentor_notes)
                            <p class="text-[10px] text-slate-400 mt-3 italic">&ldquo;{{ $result->mentor_notes }}&rdquo;</p>
                            @endif
                            <p class="text-[11px] text-slate-500 mt-4 font-bold">Silakan upload ulang tugas Anda.</p>
                        </div>
                    </div>
                    <form action="{{ route('student.project.submit', $project->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col flex-1">
                        @csrf
                        <label for="submission_file" class="flex-1 min-h-[150px] border-2 border-dashed border-red-300 dark:border-red-700 rounded-xl p-6 flex flex-col items-center justify-center text-center cursor-pointer hover:border-primary transition-all relative overflow-hidden group">
                            <i class="fas fa-file-archive text-slate-400 group-hover:text-primary transition-colors text-2xl mb-2"></i>
                            <p class="text-[11px] font-bold text-slate-500 group-hover:text-primary transition-colors" id="file-name">Pilih file baru atau seret ke sini</p>
                            <input type="file" name="submission_file" id="submission_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                        </label>
                        <button type="submit" class="mt-4 w-full py-3 bg-primary hover:bg-primary/90 text-white text-[12px] font-bold rounded-lg uppercase tracking-widest transition-all">
                            Upload Ulang Tugas
                        </button>
                    </form>
                @elseif($result)
                    <div class="border border-amber-500/30 bg-amber-500/5 rounded-xl p-10 flex flex-col items-center justify-center text-center h-full min-h-[250px]">
                        <i class="fas fa-clock text-amber-500 text-3xl mb-3"></i>
                        <p class="text-sm font-bold text-amber-600 dark:text-amber-400 mb-1">Tugas Berhasil Dikirim</p>
                        <p class="text-xs text-slate-500">Menunggu penilaian dari pengajar. Anda tidak dapat mengunggah file baru saat ini.</p>
                    </div>
                @else
                    <form action="{{ route('student.project.submit', $project->id) }}" method="POST" enctype="multipart/form-data" class="flex flex-col h-full">
                        @csrf
                        <label for="submission_file" class="flex-1 min-h-[200px] border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl p-10 flex flex-col items-center justify-center text-center cursor-pointer hover:border-primary transition-all relative overflow-hidden group">
                            <i class="fas fa-file-archive text-slate-400 group-hover:text-primary transition-colors text-3xl mb-3"></i>
                            <p class="text-[12px] font-bold text-slate-500 group-hover:text-primary transition-colors" id="file-name">Pilih file atau seret ke sini</p>
                            <input type="file" name="submission_file" id="submission_file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                        </label>

                        <button type="submit" class="mt-6 w-full py-3 bg-primary hover:bg-primary/90 text-white text-[12px] font-bold rounded-lg uppercase tracking-widest transition-all">
                            Kirim Tugas
                        </button>
                    </form>
                @endif
            </div>

            {{-- Status Tugas (5/12) --}}
            <div class="xl:col-span-5 bg-white dark:bg-[#1c1826] p-8 rounded-2xl border border-gray-200 dark:border-gray-800 shadow-sm">
                <h4 class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-6">Informasi Status</h4>
                
                <div class="space-y-6">
                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-4">
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">Status Saat Ini:</span>
                        @if(!$result)
                            <div class="px-3 py-1 bg-red-500/10 text-red-500 text-[10px] font-bold rounded-full">Belum Mengumpulkan</div>
                        @elseif($result->final_project_score === null)
                            <div class="px-3 py-1 bg-amber-500/10 text-amber-500 text-[10px] font-bold rounded-full">Menunggu Penilaian</div>
                        @elseif($result->final_project_score >= 70)
                            <div class="px-3 py-1 bg-emerald-500/10 text-emerald-500 text-[10px] font-bold rounded-full">Lulus</div>
                        @else
                            <div class="px-3 py-1 bg-red-500/10 text-red-500 text-[10px] font-bold rounded-full">Tidak Lulus</div>
                        @endif
                    </div>
                    
                    @if($result)
                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-4">
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">Terakhir Kirim:</span>
                        <span class="text-[11px] font-bold text-slate-800 dark:text-white">{{ $result->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                    
                    <div class="flex justify-between items-center border-b border-gray-100 dark:border-gray-800 pb-4">
                        <span class="text-[11px] text-slate-500 dark:text-slate-400">File Terkirim:</span>
                        <a href="{{ Storage::url($result->submission_file) }}" target="_blank" class="text-[11px] font-bold text-primary hover:underline flex items-center gap-1">
                            <i class="fas fa-external-link-alt"></i> Lihat File
                        </a>
                    </div>
                    @endif
                </div>
                
                <div class="mt-8 p-4 bg-slate-100 dark:bg-slate-900/50 rounded-xl border border-gray-200 dark:border-gray-800 text-[10px] text-slate-500 italic">
                    Catatan: Penilaian akan diproses oleh pengajar setelah tugas diterima.
                </div>
            </div>
            
        </div>
    </div>
</main>
    </div>

    <script>
        document.getElementById('submission_file').addEventListener('change', function(e) {
            var fileName = e.target.files[0].name;
            document.getElementById('file-name').textContent = fileName;
            document.getElementById('file-name').classList.add('text-primary');
        });
    </script>
@endsection
