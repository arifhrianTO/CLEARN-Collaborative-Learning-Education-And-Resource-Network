<x-dashboard.app-layout>
    <x-slot:title>CLEARN │ Kuis</x-slot:title>

    <main class="flex-1 p-6 lg:p-10 bg-slate-50 dark:bg-[#0b0a1a] min-h-screen">
        <div class="max-w-4xl mx-auto">
            
            {{-- Tombol Kembali yang aman --}}
            <div class="mb-8">
                <a href="{{ url()->previous() }}" class="inline-flex items-center gap-2 text-[10px] font-black text-slate-400 hover:text-primary transition-all uppercase tracking-widest">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>

            <header class="mb-8">
                <h1 class="text-xl font-bold dark:text-white">Review Konten Kuis</h1>
                <p class="text-xs text-slate-400 mt-1">Halaman audit untuk memeriksa soal dan kunci jawaban.</p>
            </header>

            <div class="space-y-6">
                {{-- Loop Soal (Dummy) --}}
                @foreach (range(1, 3) as $i)
                <div class="bg-white dark:bg-[#1c1826] border border-gray-100 dark:border-gray-800 rounded-3xl p-6 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <span class="text-[9px] font-black uppercase text-[#A487F8] tracking-widest bg-[#A487F8]/10 px-2 py-1 rounded">Soal #{{ $i }}</span>
                    </div>
                    
                    <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4">
                        Pertanyaan contoh nomor {{ $i }} yang diajukan oleh mentor.
                    </h3>

                    <div class="grid grid-cols-2 gap-3">
                        @foreach(['A', 'B', 'C', 'D'] as $index => $opsi)
                            <div class="p-3 rounded-xl border {{ $index === 0 ? 'border-emerald-500 bg-emerald-500/10' : 'border-gray-100 dark:border-gray-800' }}">
                                <p class="text-[10px] font-bold {{ $index === 0 ? 'text-emerald-600' : 'text-slate-500' }}">
                                    {{ $opsi }}. Pilihan Jawaban {{ $index === 0 ? '(Kunci)' : '' }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
            {{-- Tombol Aksi di Bawah --}}
            <div class="mt-10 pt-8 border-t border-gray-200 dark:border-gray-800 flex justify-center">
                <button type="button" 
                        class="px-10 py-4 bg-[#A487F8] hover:bg-[#8B6FE8] text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-[#A487F8]/20 transition-all">
                    Selesai Tinjau
                </button>
            </div>
        </div>
    </main>
</x-dashboard.app-layout>