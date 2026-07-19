@props(['course'])

<a href="{{ route('public.course.show', $course->course_slug) }}"
    class="block bg-white dark:bg-[#0F0B1A] rounded-2xl overflow-hidden border border-slate-200 dark:border-gray-800/60 hover:border-[#A487F8]/50 transition-all duration-300 hover:-translate-y-1.5 group cursor-pointer shadow-sm hover:shadow-xl">
    <div class="relative h-44 md:h-56 overflow-hidden">
        <img src="{{ $course->course_thumbnail ? asset('storage/' . $course->course_thumbnail) : asset('images/default-course.png') }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            alt="{{ $course->course_title }}">

    </div>
    <div class="p-7">
        <h4 class="font-bold text-lg leading-snug mb-2.5 h-12 overflow-hidden">{{ $course->course_title }}</h4>
        <p class="text-xs text-slate-500 dark:text-gray-400 mb-4 flex items-center">
            <i class="fas fa-user-circle mr-1.5"></i> {{ $course->mentor->name ?? 'Tim Pengajar' }}
        </p>
        <div class="flex items-center justify-between text-xs mb-5">
            <div class="flex items-center text-yellow-500">
                <i class="fas fa-star mr-1"></i> {{ number_format($course->rates_avg_course_rate ?? 0, 1) }} <span class="text-slate-400 ml-1.5">({{ $course->rates_count ?? 0 }})</span>
            </div>
            <div class="text-slate-400">
                <i class="fas fa-users mr-1.5"></i> {{ $course->enrollments->count() }} pelajar
            </div>
        </div>
        <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-gray-800">
            <div class="text-2xl font-extrabold {{ $course->course_price == 0 ? 'text-green-500' : 'text-[#A487F8] dark:text-[#A487F8]' }}">
                @if($course->course_price == 0)
                    Gratis
                @else
                    Rp{{ number_format($course->course_price, 0, ',', '.') }}
                @endif
            </div>
            <span class="text-xs font-semibold text-[#A487F8] dark:text-[#A487F8]">
                Detail <i class="fas fa-chevron-right ml-1"></i>
            </span>
        </div>
    </div>
</a>