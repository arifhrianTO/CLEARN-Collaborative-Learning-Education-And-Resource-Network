<x-guest-layout>
    <x-slot:title>Lupa Password</x-slot:title>

    <div class="bg-[#161525] border border-white/5 p-8 rounded-2xl shadow-2xl w-full max-w-md mx-auto">
        
        <div class="mb-8 text-left">
            <h2 class="text-white text-lg font-black uppercase tracking-widest mb-2">Lupa Password?</h2>
            <p class="text-[10px] font-medium leading-relaxed text-slate-400">
                {{ __('Masukkan alamat email Anda, dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.') }}
            </p>
        </div>

        @if (session('status'))
            <div class="mb-6 text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 p-3 rounded-xl italic text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="#" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1.5 ml-1">Alamat Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-500">
                        <i class="fa-solid fa-envelope text-[10px]"></i>
                    </span>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                        placeholder="nama@email.com"
                        class="block w-full pl-10 pr-4 py-3 text-xs bg-[#0f0a19] text-white border border-white/10 rounded-xl focus:outline-none focus:ring-1 focus:ring-[#7C3AED] focus:border-[#7C3AED] transition-all font-medium placeholder:text-slate-700">
                </div>
                @error('email')
                    <p class="text-[10px] text-rose-500 font-bold mt-1.5 italic ml-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex flex-col gap-3 pt-2">
                <button type="submit" 
                    class="w-full inline-flex items-center justify-center px-4 py-3 bg-[#7C3AED] text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-[#7C3AED]/20 hover:bg-[#6d34d1] active:scale-[0.98] transition-all gap-2">
                    <i class="fa-solid fa-paper-plane text-[9px]"></i>
                    Kirim Tautan Reset
                </button>
                
                <a href="{{ route('login') }}" class="text-center text-[10px] font-black text-slate-500 uppercase tracking-widest hover:text-[#7C3AED] transition-colors py-2">
                    <i class="fa-solid fa-arrow-left text-[9px] mr-1"></i> Kembali ke Login
                </a>
            </div>
        </form>
    </div>
</x-guest-layout>