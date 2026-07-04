@props(['href', 'icon', 'label', 'active' => false])

<a href="{{ $href }}" 
   class="flex items-center gap-3 p-3 px-4 rounded-xl text-[13px] transition-all duration-200 relative
   {{ $active 
      ? 'bg-primary text-white font-bold shadow-lg shadow-primary/20 before:content-[""] before:absolute before:left-0 before:top-1/2 before:-translate-y-1/2 before:h-5 before:w-1 before:bg-white before:rounded-r-full' 
      : 'font-medium text-slate-500 dark:text-slate-400 hover:bg-primary/10 hover:text-primary' 
   }}">
    <i class="fa-solid {{ $icon }} w-4 text-center"></i>
    <span>{{ $label }}</span>
</a>