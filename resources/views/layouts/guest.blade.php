<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-clearn.png') }}">

    <title>@yield('title', 'CLEARN')</title>

    <!-- Fonts & Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-slate-800 dark:text-slate-200 bg-slate-50 dark:bg-[#0f0a19] min-h-screen selection:bg-[#A487F8] selection:text-white">
    <div class="min-h-screen flex flex-col items-center justify-center p-4">
        {{-- Logo --}}
        <div class="mb-8 animate-fade-down">
            <a href="{{ route('home') }}" class="block transition-transform hover:scale-105">
                <img src="{{ asset('images/logo-light.png') }}" alt="Clearn Logo" class="h-10 dark:hidden">
                <img src="{{ asset('images/logo-dark.png') }}" alt="Clearn Logo" class="h-10 hidden dark:block">
            </a>
        </div>

        {{-- Main Content Box --}}
        <div class="w-full max-w-md animate-fade-up">
            @yield('content')
        </div>
        
        {{-- Footer --}}
        <div class="mt-8 text-center animate-fade-in">
            <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">
                &copy; {{ date('Y') }} Clearn. All rights reserved.
            </p>
        </div>
    </div>

    <style>
        body { font-family: 'Inter', sans-serif; }
        @keyframes fadeDown { from { opacity: 0; transform: translateY(-20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .animate-fade-down { animation: fadeDown 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-fade-up { animation: fadeUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .animate-fade-in { animation: fadeIn 0.8s ease-out forwards; }
    </style>
</body>

</html>