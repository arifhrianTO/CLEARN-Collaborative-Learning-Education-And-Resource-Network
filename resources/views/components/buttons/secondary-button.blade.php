<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white/40 dark:bg-white/10 backdrop-blur-md border border-slate-200/60 dark:border-white/20 rounded-md font-semibold text-xs text-slate-700 dark:text-white uppercase tracking-widest shadow-sm hover:bg-white/60 dark:hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-[#A487F8]/50 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
