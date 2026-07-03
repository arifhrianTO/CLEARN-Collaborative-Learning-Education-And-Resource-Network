<x-dashboard.app-layout>
    @php
        $totalSoal = 10;
        
        $currentSoal = (int)request('q', 1);
        
        $currentSoal = max(1, min($totalSoal, $currentSoal));
        
        $isLast = ($currentSoal == $totalSoal);
    @endphp

    <main class="flex-1 p-6 lg:p-8 transition-colors duration-300 dark:bg-[#0b0a1a] bg-slate-50 min-h-screen">
        <div class="max-w-5xl mx-auto">
            
            <div class="mb-4">
                <a href="#" class="inline-flex items-center gap-2 text-[9px] font-black text-slate-400 hover:text-[#A487F8] uppercase tracking-widest transition-colors">
                    <i class="fas fa-arrow-left"></i> Kembali ke Materi
                </a>
            </div>

            <div class="mb-4">
                {{-- JUDUL KUIS --}}
                <h1 class="text-2xl font-black text-slate-800 dark:text-white mb-2">Kuis: Dasar HTML & CSS</h1>
                <p class="text-xs text-slate-500 font-medium">Selesaikan kuis ini untuk mengukur pemahaman Anda.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                
                {{-- AREA SOAL --}}
                <div class="lg:col-span-8">
                    <div class="bg-white dark:bg-[#1c1826] border border-gray-100 dark:border-gray-800 rounded-2xl p-6 shadow-sm">
                        <div class="mb-6">
                            <span class="text-[9px] font-black text-[#A487F8] bg-[#7C3AED]/10 px-2 py-0.5 rounded-md uppercase tracking-widest">Pertanyaan {{ $currentSoal }}</span>
                            <h2 class="text-sm font-bold mt-3 text-slate-800 dark:text-white">"Manakah selector CSS yang digunakan untuk menargetkan ID sebuah elemen?" (Ini adalah soal {{ $currentSoal }})</h2>
                        </div>

                        <div class="space-y-2">
                            @foreach(['Selector Pagar (#)', 'Selector Titik (.)', 'Selector Bintang (*)', 'Selector Elemen'] as $index => $opsi)
                            <label class="group block cursor-pointer">
                               
                                <input type="radio" name="jawaban" value="opsi_{{ $index }}" class="peer sr-only" 
                                    onchange="simpanJawaban({{ $currentSoal }}, 'opsi_{{ $index }}')">
                                <div class="flex items-center gap-3 p-3 rounded-xl border-2 border-slate-100 dark:border-slate-800 transition-all peer-checked:border-[#7C3AED] peer-checked:bg-[#7C3AED]/5 hover:border-[#7C3AED]/30">
                                    <div class="w-7 h-7 rounded-lg flex items-center justify-center font-black text-[9px] bg-slate-100 dark:bg-slate-800 text-slate-500 peer-checked:bg-[#7C3AED] peer-checked:text-white group-hover:bg-[#7C3AED]/20 group-hover:text-[#A487F8] transition-colors">
                                        {{ chr(64 + $loop->iteration) }}
                                    </div>
                                    <span class="text-xs font-bold text-slate-700 dark:text-slate-300">{{ $opsi }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>

                        {{-- Navigasi Bawah --}}
                        <div class="mt-8 flex justify-between items-center pt-4 border-t border-slate-100 dark:border-slate-800">
                            <a href="?q={{ max(1, $currentSoal - 1) }}" class="text-[9px] font-black uppercase tracking-widest text-slate-400 hover:text-[#A487F8] transition-colors">Sebelumnya</a>
                            @if(!$isLast)
                                <a href="?q={{ $currentSoal + 1 }}" class="px-5 py-2 bg-[#A487F8] hover:bg-[#947ADF] text-white text-[9px] font-black rounded-lg uppercase tracking-widest shadow-lg shadow-[#A487F8]/20 transition-all">
                                    Selanjutnya <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- AREA NAVIGASI KANAN --}}
                <div class="lg:col-span-4">
                    <div class="bg-white dark:bg-[#1c1826] p-5 rounded-2xl border border-gray-100 dark:border-gray-800 shadow-sm">
                        <div class="grid grid-cols-5 gap-2 mb-5">
                            @for($i = 1; $i <= $totalSoal; $i++)
                                <a id="nav-{{ $i }}" href="?q={{ $i }}" 
                                class="aspect-square flex items-center justify-center text-[11px] font-black rounded-lg border transition-all
                                {{ $i == $currentSoal ? 'border-2 border-[#A487F8] text-[#A487F8] ring-2 ring-[#A487F8]/20' : 'bg-slate-50 dark:bg-slate-800 text-slate-400 border-slate-100 dark:border-slate-700' }}">
                                    {{ $i }}
                                </a>
                            @endfor
                        </div>

                        @if($isLast)
                            <button class="w-full py-3 bg-[#A487F8] hover:bg-[#947ADF] text-white text-[9px] font-black rounded-lg uppercase tracking-widest transition-all shadow-lg shadow-[#A487F8]/20">
                                Submit Seluruh Jawaban
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
    function simpanJawaban(nomorSoal, nilaiJawaban) {
        localStorage.setItem('jawaban_soal_' + nomorSoal, nilaiJawaban);

        const btn = document.getElementById('nav-' + nomorSoal);
        if (btn) {
            btn.classList.remove('border-2', 'border-[#A487F8]', 'text-[#A487F8]', 'ring-2', 'ring-[#A487F8]/20');
            btn.classList.add('bg-[#A487F8]', 'text-white', 'border-[#A487F8]');
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        const jawabanTersimpan = localStorage.getItem('jawaban_soal_' + {{ $currentSoal }});
        if (jawabanTersimpan) {
            const radio = document.querySelector(`input[value="${jawabanTersimpan}"]`);
            if (radio) radio.checked = true;
        }

        for (let i = 1; i <= {{ $totalSoal }}; i++) {
            if (localStorage.getItem('jawaban_soal_' + i)) {
                const btn = document.getElementById('nav-' + i);
                if (btn && i != {{ $currentSoal }}) {
                    btn.classList.remove('bg-slate-50', 'dark:bg-slate-800', 'text-slate-400', 'border-slate-100');
                    btn.classList.add('bg-[#A487F8]', 'text-white', 'border-[#A487F8]');
                }
            }
        }
    });
</script>
</x-dashboard.app-layout>