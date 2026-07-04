@props([
'variant' => 'preview',
'icon' => 'fas fa-circle',
'iconBg' => 'bg-[#A487F8]/10',
'iconColor' => 'text-[#A487F8]',
'hoverBg' => 'group-hover:bg-[#A487F8]',
'hoverBorder' => 'hover:border-[#A487F8]',
'title' => '',
'count' => '',
'desc' => '',
'students' => '',
'href' => '#',
'color' => null, // ← tambahan: dari category_color di DB
])

@php
// Jika color dari DB tersedia, generate class dinamis
// Karena Tailwind tidak bisa pakai string dinamis, kita pakai style inline
$useInlineColor = !empty($color);
@endphp

@if ($variant === 'preview')

<a href="{{ $href }}"
    class="group block bg-white dark:bg-[#13111a] border border-slate-200
          dark:border-gray-800 p-5 rounded-2xl text-center
          transition-all duration-300 cursor-pointer
          shadow-sm hover:-translate-y-1 no-underline
          text-slate-900 dark:text-white"
    @if($useInlineColor)
    style="--cat-color: {{ $color }};"
    onmouseenter="
            this.style.borderColor='{{ $color }}';
            this.querySelector('.icon-box').style.backgroundColor='{{ $color }}';
            this.querySelector('.icon-box').style.color='white';
        "
    onmouseleave="
            this.style.borderColor='';
            this.querySelector('.icon-box').style.backgroundColor='{{ $color }}1a';
            this.querySelector('.icon-box').style.color='{{ $color }}';
        "
    @endif>

    <div class="icon-box w-12 h-12 rounded-xl mx-auto mb-4
                flex items-center justify-center text-lg
                transition-all duration-300"
        style="background-color: {{ $color }}1a; color: {{ $color }};">
        <i class="{{ $icon }}"></i>
    </div>

    <h5 class="text-sm font-bold">{{ $title }}</h5>
    <p class="text-[11px] text-slate-500 mt-1">{{ $count }}</p>
</a>

@else

<a href="{{ $href }}"
    class="group block p-6 rounded-2xl bg-white dark:bg-[#17122b]
          border border-slate-200 dark:border-white/5
          transition-all duration-300 hover:-translate-y-1 no-underline
          text-slate-900 dark:text-white"
    @if($useInlineColor)
    onmouseenter="
            this.style.borderColor='{{ $color }}';
            this.querySelector('.icon-box').style.backgroundColor='{{ $color }}';
            this.querySelector('.icon-box').style.color='white';
        "
    onmouseleave="
            this.style.borderColor='';
            this.querySelector('.icon-box').style.backgroundColor='{{ $color }}1a';
            this.querySelector('.icon-box').style.color='{{ $color }}';
        "
    @endif>

    <div class="icon-box w-12 h-12 flex items-center justify-center rounded-xl mb-4 text-xl transition-all duration-300"
        style="background-color: {{ $color }}1a; color: {{ $color }};">
        <i class="{{ $icon }}"></i>
    </div>

    <h3 class="font-semibold text-lg mb-1">{{ $title }}</h3>
    <p class="text-sm text-slate-500 dark:text-gray-400 mb-4">{{ $desc }}</p>

    <div class="flex justify-between text-xs text-slate-500 dark:text-gray-400">
        <span>{{ $count }} Kursus</span>
        <span>{{ $students }} Pelajar</span>
    </div>
</a>

@endif