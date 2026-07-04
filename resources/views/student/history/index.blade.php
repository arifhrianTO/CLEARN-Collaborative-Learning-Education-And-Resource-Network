<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-clearn.png') }}">
    <title>CLEARN │ Riwayat</title>
    
    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    {{-- Anti-Flash Script --}}
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style type="text/tailwindcss">
        @layer base {
            body { 
                @apply bg-slate-50 text-slate-900 transition-colors duration-300 antialiased font-sans;
                letter-spacing: -0.025em;
            }
            .dark body { @apply bg-[#090613] text-white; }
        }

        @layer components {
            .card-bg { @apply bg-white border border-gray-200 rounded-2xl transition-all duration-300 shadow-sm; }
            .dark .card-bg { @apply bg-[#110D1F] border-[#2d2644] shadow-none; }
            
            .text-muted-custom { @apply text-slate-500 font-medium; }
            .dark .text-muted-custom { @apply text-slate-400; }
        }
    </style>
</head>
<body class="min-h-screen flex">

    {{-- Sidebar Pelajar --}}
    <x-dashboard.sidebar
    role="Student"
    name="{{ auth()->user()->name ?? 'User Pelajar' }}"
    initials="{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}"
    photo="{{ auth()->user()->profile_picture }}"
    active="order-history" />

    <main class="flex-1 p-6 lg:p-10">
        <div class="max-w-5xl mx-auto">
            
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-2xl font-black tracking-tighter mb-1">Riwayat Pesanan</h1>
                <p class="text-muted-custom text-[12px]">Pantau semua transaksi dan akses kursus yang telah Anda beli.</p>
            </div>

            {{-- Stats Grid (Diselaraskan dengan halaman utama) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="card-bg p-5 flex items-center gap-4 group">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                        <i class="fa-solid fa-box"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black leading-none">{{ $totalOrders }}</h2>
                        <span class="text-[9px] font-bold text-muted-custom uppercase tracking-widest">Total Pesanan</span>
                    </div>
                </div>

                <div class="card-bg p-5 flex items-center gap-4 group">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                        <i class="fa-solid fa-wallet"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black leading-none text-nowrap">Rp{{ number_format($totalSpent, 0, ',', '.') }}</h2>
                        <span class="text-[9px] font-bold text-muted-custom uppercase tracking-widest">Pengeluaran</span>
                    </div>
                </div>

                <div class="card-bg p-5 flex items-center gap-4 group">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary text-sm">
                        <i class="fa-solid fa-graduation-cap"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-black leading-none">{{ $activeCourses }}</h2>
                        <span class="text-[9px] font-bold text-muted-custom uppercase tracking-widest">Kursus Aktif</span>
                    </div>
                </div>
            </div>

            {{-- List Transaksi --}}
            <div class="space-y-4">
                @forelse($payments as $payment)
                {{-- Card Transaksi --}}
                <div class="card-bg overflow-hidden group">
                    {{-- Header Transaksi --}}
                    <div class="px-5 py-3 border-b border-gray-100 dark:border-[#2d2644] flex flex-wrap justify-between items-center gap-4 bg-slate-50/50 dark:bg-[#161226]">
                        <div class="flex gap-8">
                            <div>
                                <p class="text-[9px] font-bold text-muted-custom uppercase tracking-wider mb-0.5">ID Pesanan</p>
                                <p class="text-[12px] font-black tracking-tight">{{ $payment->midtrans_order_id }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-bold text-muted-custom uppercase tracking-wider mb-0.5">Tanggal</p>
                                <p class="text-[12px] font-bold">{{ $payment->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            @if($payment->connection_status === 'success' || $payment->connection_status === 'settlement')
                                <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-green-500/10 text-green-500 border border-green-500/20">Berhasil</span>
                            @elseif($payment->connection_status === 'pending')
                                <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-yellow-500/10 text-yellow-500 border border-yellow-500/20">Menunggu</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-wider bg-red-500/10 text-red-500 border border-red-500/20">{{ ucfirst($payment->connection_status) }}</span>
                            @endif
                            <button class="text-muted-custom hover:text-primary transition-colors"><i class="fas fa-file-invoice text-sm"></i></button>
                        </div>
                    </div>
                    
                    {{-- Detail Transaksi --}}
                    <div class="p-5 flex flex-col md:flex-row gap-6">
                        <div class="w-full md:w-36 shrink-0">
                            <div class="aspect-video rounded-xl overflow-hidden border border-gray-100 dark:border-[#2d2644]">
                                @if($payment->enrollment->course->course_image)
                                    <img src="{{ Storage::url($payment->enrollment->course->course_image) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=400" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                @endif
                            </div>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h3 class="font-extrabold text-[15px] leading-tight tracking-tight group-hover:text-primary transition-colors">{{ $payment->enrollment->course->course_title }}</h3>
                                <p class="text-[15px] font-black tracking-tight text-primary">Rp{{ number_format($payment->gross_amount, 0, ',', '.') }}</p>
                            </div>
                            <p class="text-[10px] text-muted-custom mb-5 font-bold tracking-widest uppercase">{{ $payment->enrollment->course->mentor->name ?? 'Mentor Clearn' }}</p>
                            
                            <div class="flex gap-2">
                                @if($payment->connection_status === 'success' || $payment->connection_status === 'settlement')
                                    <a href="{{ route('student.course.lesson', $payment->enrollment->course->course_slug) }}" class="inline-block bg-primary text-white text-[10px] font-extrabold px-5 py-2.5 rounded-xl shadow-lg shadow-primary/20 hover:brightness-110 active:scale-95 transition-all uppercase tracking-widest">Mulai Belajar</a>
                                @endif
                                <a href="{{ route('student.course.show', $payment->enrollment->course->course_slug) }}" class="inline-block border border-gray-200 dark:border-[#2d2644] text-muted-custom text-[10px] font-extrabold px-5 py-2.5 rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 active:scale-95 transition-all uppercase tracking-widest">Detail</a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="card-bg p-10 text-center">
                    <div class="w-16 h-16 bg-gray-100 dark:bg-[#1a1429] rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                        <i class="fas fa-receipt text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold mb-2">Belum ada transaksi</h3>
                    <p class="text-muted-custom text-sm mb-6">Anda belum pernah melakukan pembelian kursus apapun.</p>
                    <a href="{{ route('courses') }}" class="inline-block bg-primary text-white px-6 py-2 rounded-xl text-sm font-bold shadow-lg shadow-primary/20 hover:-translate-y-1 transition-all">Cari Kursus</a>
                </div>
                @endforelse

                <div class="mt-6">
                    {{ $payments->links() }}
                </div>
            </div>
        </div>
    </main>

</body>
</html>