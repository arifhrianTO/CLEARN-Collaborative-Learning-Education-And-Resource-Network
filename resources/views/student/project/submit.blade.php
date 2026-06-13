<x-dashboard.app-layout>
    @php
        $kurikulum = [
            'Sesi 1: Dasar' => [
                ['id' => 1, 'type' => 'video', 'title' => 'Pengenalan Web', 'active' => false, 'completed' => true],
                ['id' => 2, 'type' => 'pdf', 'title' => 'Modul Panduan', 'active' => false, 'completed' => false],
                ['id' => 3, 'type' => 'quiz', 'title' => 'Kuis Sesi 1', 'active' => false, 'completed' => false],
            ],
            'Sesi 2: Layouting' => [
                ['id' => 4, 'type' => 'video', 'title' => 'Dasar Tailwind', 'active' => false, 'completed' => false],
                ['id' => 99, 'type' => 'project', 'title' => 'Tugas Akhir (Praktikum)', 'active' => true, 'completed' => false],
            ]
        ];
    @endphp

    <div class="flex w-screen h-screen overflow-hidden bg-slate-50 dark:bg-[#0b0a1a]">
        
        {{-- SIDEBAR --}}
        <aside class="w-80 border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-[#1c1826] flex flex-col">
            {{-- Header Sidebar --}}
            <div class="p-6 border-b border-gray-100 dark:border-gray-800 shrink-0">
                <h2 class="font-black text-sm dark:text-white mb-1">Program Pelatihan Web</h2>
                <p class="text-[10px] text-slate-500 dark:text-slate-400 font-bold uppercase tracking-widest">Mentor: Budi Santoso</p>
                <div class="mt-6">
                    <div class="flex justify-between text-[10px] font-bold text-slate-400 mb-2">
                        <span>Progress Kursus</span> <span>54%</span>
                    </div>
                    <div class="h-2 bg-slate-100 dark:bg-gray-800 rounded-full">
                        <div class="h-full bg-primary rounded-full" style="width: 54%"></div>
                    </div>
                </div>
            </div>

           {{-- DAFTAR SESI --}}
            <div class="flex-1 overflow-y-auto p-6 space-y-8 custom-scrollbar">
                @foreach($kurikulum as $namaSesi => $items)
                    <div>
                        <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">{{ $namaSesi }}</h4>
                        <div class="space-y-1">
                            @foreach($items as $item)
                                @php
                                    $targetUrl = match($item['type']) {
                                        'quiz'    => route('student.quiz'),
                                        'project' => route('student.project'),
                                        default   => route('student.lesson'),
                                    };
                                @endphp

                                <a href="{{ $targetUrl }}" 
                                class="flex items-center justify-between px-4 py-3 rounded-xl text-sm font-bold transition-all cursor-pointer 
                                {{ ($item['active'] ?? false) ? 'active-item bg-primary/10 text-primary' : 'text-slate-600 dark:text-slate-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                    
                                    <div class="flex items-center gap-3">
                                        @if($item['type'] == 'video') <i class="fas fa-play-circle"></i>
                                        @elseif($item['type'] == 'pdf') <i class="fas fa-file-pdf"></i>
                                        @elseif($item['type'] == 'quiz') <i class="fas fa-question-circle"></i>
                                        @elseif($item['type'] == 'project') <i class="fas fa-file-upload"></i> 
                                        @endif
                                        <span>{{ $item['title'] }}</span>
                                    </div>
                                    @if($item['type'] == 'project')
                                        <i class="fas fa-lock text-slate-400 text-xs"></i>
                                    @endif

                                    @if($item['completed'] ?? false)
                                        <i class="fas fa-check-circle text-emerald-500 text-xs"></i>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                        
                    </div>
                @endforeach
            </div>
        </aside>

     {{-- MAIN CONTENT --}}
<main class="flex-1 w-full h-full overflow-y-auto bg-slate-50 dark:bg-[#0b0a1a] custom-scrollbar">
    <div class="w-full h-full p-8">

        {{-- 1. INSTRUKSI TUGAS (Dibuat lebih panjang) --}}
        <div class="w-full bg-[#1c1826] p-8 rounded-2xl border border-gray-800 mb-6">
            <div class="flex items-center gap-3 mb-6">
                <i class="fas fa-book text-[#7C3AED] text-sm"></i>
                <h1 class="text-sm font-bold text-white uppercase tracking-widest">Panduan Tugas</h1>
            </div>
            <div class="text-[11px] text-slate-400 leading-relaxed grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="text-[11px] text-slate-400 leading-relaxed">

                <p class="mb-2">Silakan buat aplikasi sederhana menggunakan Tailwind CSS. Pastikan folder mencakup:</p>

                <ul class="list-disc pl-4 space-y-1 font-bold">

                    <li>Struktur folder yang rapi.</li>

                    <li>File index.html utama.</li>

                    <li>Responsive design untuk mobile.</li>

                </ul>

            </div>
            </div>
        </div>

        {{-- 2. GRID UPLOAD & STATUS (Proporsi 7:5) --}}
        <div class="w-full grid grid-cols-1 lg:grid-cols-12 gap-6">
            
            {{-- Area Upload (7/12) --}}
            <div class="lg:col-span-7 bg-[#1c1826] rounded-2xl border border-gray-800 p-8 flex flex-col">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-8 h-8 rounded-lg bg-[#7C3AED]/20 text-[#7C3AED] flex items-center justify-center text-sm">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-white">Unggah Tugas</h2>
                        <p class="text-[9px] font-bold text-slate-500 uppercase tracking-widest">Max 50MB (.zip, .rar)</p>
                    </div>
                </div>

                <div class="border-2 border-dashed border-gray-700 rounded-xl p-10 flex flex-col items-center justify-center text-center cursor-pointer hover:border-[#7C3AED] transition-all fle">
                    <i class="fas fa-file-archive text-slate-600 text-2xl mb-3"></i>
                    <p class="text-[10px] font-bold text-slate-400">Seret file zip tugas Anda ke sini</p>
                </div>
                
                <button class="mt-6 w-full py-3 bg-[#7C3AED] hover:bg-[#6d31d9] text-white text-[10px] font-bold rounded-lg uppercase tracking-widest transition-all">
                    Kirim
                </button>
            </div>

            {{-- Status Tugas (5/12 - Dibuat lebih lebar & informatif) --}}
            <div class="lg:col-span-5 bg-[#1c1826] p-8 rounded-2xl border border-gray-800">
                <h4 class="text-[9px] font-bold text-slate-500 uppercase tracking-widest mb-6">Informasi Status</h4>
                
                <div class="space-y-6">
                    <div class="flex justify-between items-center border-b border-gray-800 pb-4">
                        <span class="text-[11px] text-slate-400">Status Saat Ini:</span>
                        <div class="px-3 py-1 bg-amber-500/10 text-amber-500 text-[10px] font-bold rounded-full">Menunggu Penilaian</div>
                    </div>
                    <div class="flex justify-between items-center border-b border-gray-800 pb-4">
                        <span class="text-[11px] text-slate-400">Tanggal Kirim:</span>
                        <span class="text-[11px] font-bold text-white">25 Mei 2026, 14:30</span>
                    </div>
                </div>
                
                <div class="mt-8 p-4 bg-slate-900/50 rounded-xl border border-gray-800 text-[10px] text-slate-500 italic">
                    Catatan: Penilaian akan segera diproses setelah tugas diterima. Pastikan Anda tidak mengubah file setelah status berubah menjadi "Dinilai".
                </div>
            </div>
            
        </div>
    </div>
</main>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const activeItem = document.querySelector('.active-item');
            if (activeItem) {
                activeItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        });
    </script>
</x-dashboard.app-layout>