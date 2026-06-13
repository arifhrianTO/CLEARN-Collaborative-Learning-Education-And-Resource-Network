<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Auth – Clearn - Platform Pembelajaran Online')</title>

    <script>
        if (
            localStorage.getItem('theme') === 'dark' ||
            (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)
        ) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="@yield('bodyClass', 'min-h-screen bg-white text-slate-900 dark:bg-[#0f0a19] dark:text-white transition-colors duration-300 font-sans')">

    {{-- Tombol Toggle Theme --}}
    @hasSection('hideThemeToggle')
    @else
    <button type="button" onclick="toggleTheme()"
        class="fixed top-5 right-5 z-50 w-11 h-11 flex items-center justify-center rounded-xl
            bg-white dark:bg-[#1c1826]
            text-primary shadow-lg border border-slate-200 dark:border-slate-800
            hover:scale-110 transition-all">
        <i id="theme-icon" class="fas fa-moon"></i>
    </button>
    @endif

    @yield('content')

    <script>
        const themeIcon = document.getElementById('theme-icon');

        function updateIcon() {
            if (!themeIcon) return;

            themeIcon.classList.toggle(
                'fa-sun',
                document.documentElement.classList.contains('dark')
            );

            themeIcon.classList.toggle(
                'fa-moon',
                !document.documentElement.classList.contains('dark')
            );
        }

        function toggleTheme() {
            const html = document.documentElement;

            html.classList.toggle('dark');

            localStorage.setItem(
                'theme',
                html.classList.contains('dark') ? 'dark' : 'light'
            );

            updateIcon();
        }

        window.toggleTheme = toggleTheme;
    </script>

    @stack('scripts')
</body>

</html>