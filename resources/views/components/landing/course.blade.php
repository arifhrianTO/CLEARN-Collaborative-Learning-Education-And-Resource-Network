@props(['course'])

<a href="{{ route('course.show', $course->course_slug) }}"
    class="block bg-white dark:bg-[#13111a] rounded-2xl overflow-hidden border border-slate-200 dark:border-gray-800/60 hover:border-purple-500/50 transition-all duration-300 hover:-translate-y-1.5 group cursor-pointer shadow-sm hover:shadow-xl">
    <div class="relative h-56 overflow-hidden">
        <img src="{{ asset('storage/' . $course->course_thumbnail) }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
            alt="{{ $course->course_title }}">
        <span class="absolute top-4 right-4 bg-purple-600 text-white text-[10px] px-3 py-1.5 rounded-md font-bold uppercase tracking-wider">Populer</span>
    </div>
    <div class="p-7">
        <h4 class="font-bold text-lg leading-snug mb-2.5 h-12 overflow-hidden">{{ $course->course_title }}</h4>
        <p class="text-xs text-slate-500 dark:text-gray-400 mb-4 flex items-center">
            <i class="fas fa-user-circle mr-1.5"></i> {{ $course->mentor->name ?? 'Tim Mentor' }}
        </p>
        <div class="flex items-center justify-between text-xs mb-5">
            <div class="flex items-center text-yellow-500">
                <i class="fas fa-star mr-1"></i> 4.8 <span class="text-slate-400 ml-1.5">(10)</span>
            </div>
            <div class="text-slate-400">
                <i class="fas fa-users mr-1.5"></i> {{ $course->enrollments->count() }} pelajar
            </div>
        </div>
        <div class="flex items-center justify-between pt-4 border-t border-slate-100 dark:border-gray-800">
            <div class="text-2xl font-extrabold text-purple-600 dark:text-purple-400">
                Rp{{ number_format($course->course_price, 0, ',', '.') }}
            </div>
            <span class="text-xs font-semibold text-purple-600 dark:text-purple-300">
                Detail <i class="fas fa-chevron-right ml-1"></i>
            </span>
        </div>
    </div>
</a>