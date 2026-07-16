@extends('layouts.dashboard')

@section('title', 'CLEARN │ Tambah Kursus')

@section('content')


<main class="flex-1 p-5 lg:p-8 transition-colors duration-300 dark:bg-[#0F0B1A] bg-slate-50 min-h-screen">
    <div class="max-w-4xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('mentor.courses.index') }}"
                class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary text-white text-sm font-semibold hover:bg-primary/90 transition">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali ke Dashboard
            </a>

            <h1 class="text-2xl font-bold dark:text-white text-slate-800 tracking-tight mt-4">
                Tambah Kursus Baru
            </h1>

            <p class="text-[11px] dark:text-slate-400 text-slate-500 mt-1 italic font-medium uppercase tracking-widest">
                Langkah 1: Informasi Dasar
            </p>
        </div>

        {{-- Error Validation --}}
        @if ($errors->any())
        <div class="mb-6 bg-red-500/10 border border-red-500/20 text-red-500 px-4 py-3 rounded-xl text-xs font-semibold">
            <ul class="list-disc pl-5 space-y-1">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Form --}}
        <form action="{{ route('mentor.courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="card-bg rounded-3xl p-6 lg:p-8">

                {{-- Title Card --}}
                <div class="mb-7">
                    <div class="flex items-center gap-3">
                        <span class="w-1.5 h-5 rounded-full bg-primary"></span>

                        <h2 class="text-sm font-black dark:text-white text-slate-800 uppercase tracking-widest">
                            Informasi Dasar
                        </h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

                    {{-- Judul Kursus --}}
                    <div class="md:col-span-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Judul Kursus
                        </label>

                        <input type="text"
                            name="course_title"
                            value="{{ old('course_title') }}"
                            required
                            placeholder="Contoh: UI/UX"
                            class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1A1625] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">
                    </div>

                    {{-- Deskripsi Kursus --}}
                    <div class="md:col-span-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Deskripsi
                        </label>

                        <textarea name="course_description"
                            rows="5"
                            required
                            placeholder="Jelaskan isi dan tujuan kursus ini..."
                            class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1A1625] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition overflow-hidden resize-none"
                            oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'">{{ old('course_description') }}</textarea>
                    </div>

                    {{-- Kategori --}}
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Kategori
                        </label>

                        <select name="category_id"
                            required
                            class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1A1625] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">
                            <option value="">Pilih Kategori</option>

                            @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Jumlah Session --}}
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Jumlah Pertemuan
                        </label>

                        <input type="number"
                            name="session_count"
                            value="{{ old('session_count') }}"
                            min="1"
                            max="50"
                            required
                            placeholder="Contoh: 12"
                            class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1A1625] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">

                        <div class="mt-3 p-3 rounded-xl bg-blue-50 dark:bg-blue-500/10 border border-blue-200 dark:border-blue-500/20 flex gap-2">
                            <i class="fa-solid fa-circle-info text-blue-500 mt-0.5 text-[10px]"></i>
                            <p class="text-[10px] text-blue-600 dark:text-blue-300 font-medium leading-relaxed">
                                <strong>Penting:</strong> Jumlah pertemuan yang dibuat tidak dapat dihapus nantinya. Harap pastikan jumlah ini sudah benar. Pertemuan paling akhir (terakhir) otomatis akan dialokasikan sebagai <strong>Tugas Akhir</strong>.
                            </p>
                        </div>
                    </div>

                    {{-- Tipe Harga Kursus --}}
                    <div>
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Tipe Kursus
                        </label>

                        <div class="flex gap-3">
                            <button type="button" id="btn_paid"
                                onclick="setCourseType('paid')"
                                class="flex-1 py-3 rounded-xl text-xs font-bold border-2 transition-all duration-200 bg-primary text-white border-primary shadow-md shadow-primary/20">
                                <i class="fa-solid fa-tag mr-1.5"></i> Berbayar
                            </button>
                            <button type="button" id="btn_free"
                                onclick="setCourseType('free')"
                                class="flex-1 py-3 rounded-xl text-xs font-bold border-2 transition-all duration-200 bg-white dark:bg-[#1A1625] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-white/10 hover:border-primary/50">
                                <i class="fa-solid fa-gift mr-1.5"></i> Gratis
                            </button>
                        </div>
                        <input type="hidden" id="course_type" name="course_type" value="{{ old('course_type', 'paid') }}">
                        <input type="hidden" id="actual_price" name="course_price" value="{{ old('course_price') }}">

                        {{-- Input Harga (muncul jika Berbayar) --}}
                        <div id="price_section" class="mt-4">
                            <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                                Harga (RP)
                            </label>

                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold text-sm">Rp</span>
                                <input type="text"
                                    id="display_price"
                                    value="{{ old('course_price') }}"
                                    placeholder="Contoh: 50.000"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl bg-white dark:bg-[#1A1625] border border-slate-200 dark:border-white/10 dark:text-white text-slate-800 text-sm outline-none focus:border-primary transition">
                            </div>

                            {{-- Keterangan Pembagian Profit --}}
                            <div class="mt-3 p-4 rounded-xl bg-primary/5 border border-primary/20 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-primary/10 flex items-center justify-center flex-shrink-0">
                                        <i class="fa-solid fa-wallet text-primary text-[10px]"></i>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-slate-700 dark:text-slate-300">Estimasi Pendapatan Anda (80%)</p>
                                        <p class="text-xs font-black text-primary" id="mentor_profit_display">Rp 0</p>
                                    </div>
                                </div>
                                <div class="hidden sm:block w-px h-8 bg-primary/20"></div>
                                <div>
                                    <p class="text-[9px] font-semibold text-slate-500 dark:text-slate-400">Biaya Platform (20%)</p>
                                    <p class="text-[10px] font-bold text-slate-600 dark:text-slate-400" id="admin_fee_display">Rp 0</p>
                                </div>
                            </div>
                        </div>

                        {{-- Pesan Gratis --}}
                        <div id="free_section" class="mt-4 hidden">
                            <div class="p-4 rounded-xl bg-green-50 dark:bg-green-500/10 border border-green-200 dark:border-green-500/20 flex gap-3">
                                <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-500/20 flex items-center justify-center flex-shrink-0">
                                    <i class="fa-solid fa-check text-green-500 text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-green-700 dark:text-green-300">Kursus Gratis</p>
                                    <p class="text-[10px] text-green-600 dark:text-green-400 mt-0.5">Kursus ini akan tersedia secara gratis untuk semua pelajar. Tidak ada pembagian profit.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Thumbnail Kursus --}}
                    <div class="md:col-span-3">
                        <label class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2 block">
                            Thumbnail Kursus
                        </label>

                        <input type="file"
                            name="course_thumbnail"
                            accept="image/*"
                            required
                            class="w-full px-4 py-3 rounded-xl bg-white dark:bg-[#1A1625] border border-slate-200 dark:border-white/10 dark:text-white text-slate-500 text-sm outline-none focus:border-primary transition">

                        <p class="text-[10px] text-slate-400 mt-2">
                            Format: JPG, JPEG, PNG, WEBP. Maksimal 2MB.
                        </p>
                    </div>

                </div>

                {{-- Button --}}
                <div class="flex items-center justify-end gap-3 mt-8">
                    <a href="{{ route('mentor.courses.index') }}"
                        class="px-5 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest bg-slate-200 dark:bg-white/5 dark:text-slate-300 text-slate-600 hover:opacity-80 transition">
                        Batal
                    </a>

                    <button type="submit"
                        class="px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest bg-primary text-white shadow-md shadow-primary/20 hover:scale-105 transition">
                        Lanjut ke Kurikulum
                    </button>
                </div>

            </div>
        </form>

    </div>
</main>

@push('scripts')
<script>
    function setCourseType(type) {
        const courseTypeInput = document.getElementById('course_type');
        const priceSection = document.getElementById('price_section');
        const freeSection = document.getElementById('free_section');
        const btnPaid = document.getElementById('btn_paid');
        const btnFree = document.getElementById('btn_free');
        const actualInput = document.getElementById('actual_price');

        courseTypeInput.value = type;

        if (type === 'free') {
            priceSection.classList.add('hidden');
            freeSection.classList.remove('hidden');
            actualInput.value = '0';

            btnFree.className = 'flex-1 py-3 rounded-xl text-xs font-bold border-2 transition-all duration-200 bg-primary text-white border-primary shadow-md shadow-primary/20';
            btnPaid.className = 'flex-1 py-3 rounded-xl text-xs font-bold border-2 transition-all duration-200 bg-white dark:bg-[#1A1625] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-white/10 hover:border-primary/50';
        } else {
            priceSection.classList.remove('hidden');
            freeSection.classList.add('hidden');
            actualInput.value = '';

            btnPaid.className = 'flex-1 py-3 rounded-xl text-xs font-bold border-2 transition-all duration-200 bg-primary text-white border-primary shadow-md shadow-primary/20';
            btnFree.className = 'flex-1 py-3 rounded-xl text-xs font-bold border-2 transition-all duration-200 bg-white dark:bg-[#1A1625] text-slate-500 dark:text-slate-400 border-slate-200 dark:border-white/10 hover:border-primary/50';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const displayInput = document.getElementById('display_price');
        const actualInput = document.getElementById('actual_price');
        const mentorProfitDisplay = document.getElementById('mentor_profit_display');
        const adminFeeDisplay = document.getElementById('admin_fee_display');
        const courseType = document.getElementById('course_type').value;

        const formatRupiah = (angka) => {
            if (!angka) return '';
            return new Intl.NumberFormat('id-ID').format(angka);
        };

        const calculateProfit = (price) => {
            if(!price || isNaN(price)) {
                mentorProfitDisplay.textContent = 'Rp 0';
                adminFeeDisplay.textContent = 'Rp 0';
                return;
            }
            
            const numPrice = parseFloat(price);
            const mentorProfit = numPrice * 0.8;
            const adminFee = numPrice * 0.2;
            
            mentorProfitDisplay.textContent = 'Rp ' + formatRupiah(mentorProfit);
            adminFeeDisplay.textContent = 'Rp ' + formatRupiah(adminFee);
        };

        // Set toggle berdasarkan tipe yang sudah dipilih (saat validasi gagal)
        if (courseType === 'free') {
            setCourseType('free');
        }

        // Format nilai awal jika ada
        if (actualInput.value && actualInput.value !== '0') {
            const initialStrValue = actualInput.value.toString().split('.')[0];
            const cleanInitialValue = initialStrValue.replace(/[^0-9]/g, '');
            displayInput.value = formatRupiah(cleanInitialValue);
            actualInput.value = cleanInitialValue;
            calculateProfit(cleanInitialValue);
        }

        displayInput.addEventListener('input', function(e) {
            let value = this.value.replace(/[^0-9]/g, '');

            if (value) {
                this.value = formatRupiah(value);
                actualInput.value = value;
                calculateProfit(value);
            } else {
                this.value = '';
                actualInput.value = '';
                calculateProfit(0);
            }
        });
    });
</script>
@endpush

@endsection