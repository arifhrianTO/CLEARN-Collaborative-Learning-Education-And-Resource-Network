<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-clearn.png') }}">
    <title>CLEARN | @yield('title', 'Collaborative Learning Education And Resource Network')</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dark mode awal supaya tidak kedip -->
    <script>
        if (
            localStorage.theme === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>

<body class="antialiased text-sm">
    <main class="transition-colors duration-300 bg-slate-50 dark:bg-[#08070d] min-h-screen text-slate-900 dark:text-white">
        @yield('content')
    </main>
    
    @stack('scripts')
</body>

</html>