<div class="relative bg-[#151322] rounded-2xl p-6 text-center
            border border-white/5
            shadow-[0_0_40px_rgba(164,135,248,0.08)]
            hover:shadow-[0_0_60px_rgba(164,135,248,0.18)]
            transition-all duration-300">

    {{-- Badge --}}
    <span class="absolute top-4 left-1/2 -translate-x-1/2
                 bg-[#A487F8]/20 text-[#A487F8] text-xs font-semibold
                 px-3 py-1 rounded-full">
        ★ Rating Tertinggi
    </span>

    {{-- Avatar --}}
    <div class="mt-6 mb-4 flex justify-center">
        <img
            src="{{ $photo }}"
            alt="{{ $name }}"
            class="w-24 h-24 rounded-full object-cover
                   ring-4 ring-[#A487F8]/30"
        >
    </div>

    {{-- Nama --}}
    <h3 class="text-lg font-bold text-white">
        {{ $name }}
    </h3>

    {{-- Jabatan --}}
    <p class="text-sm text-[#A487F8] mb-3">
        {{ $title }}
    </p>

    {{-- Deskripsi --}}
    <p class="text-sm text-slate-400 mb-4 leading-relaxed">
        {{ $description }}
    </p>

    {{-- Tags --}}
    <div class="flex flex-wrap justify-center gap-2 mb-5">
        @foreach ($tags as $tag)
            <span class="bg-[#A487F8]/15 text-[#A487F8] text-xs px-3 py-1 rounded-full">
                {{ $tag }}
            </span>
        @endforeach
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 text-sm text-slate-300">
        <div>
            <p class="text-yellow-400 font-semibold">★ {{ $rating }}</p>
            <span class="text-xs text-slate-500">Rating</span>
        </div>
        <div>
            <p class="font-semibold">{{ $students }}</p>
            <span class="text-xs text-slate-500">Pelajar</span>
        </div>
        <div>
            <p class="font-semibold">{{ $courses }}</p>
            <span class="text-xs text-slate-500">Kursus</span>
        </div>
    </div>
</div>