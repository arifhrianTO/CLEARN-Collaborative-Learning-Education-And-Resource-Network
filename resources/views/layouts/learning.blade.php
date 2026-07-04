<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/logo-clearn.png') }}">
    <title>CLEARN - @yield('title', 'Learning')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="h-screen flex flex-col bg-slate-50 dark:bg-[#0b0a1a] text-slate-900 dark:text-white transition-colors duration-300 antialiased overflow-hidden font-sans">
    
    @yield('content')

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const themeIcon = document.getElementById('theme-icon');
            
            html.classList.toggle('dark');
            const isDark = html.classList.contains('dark');
            localStorage.theme = isDark ? 'dark' : 'light';
            
            if(themeIcon) {
                themeIcon.classList.replace(isDark ? 'fa-moon' : 'fa-sun', isDark ? 'fa-sun' : 'fa-moon');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const themeIcon = document.getElementById('theme-icon');
            if (themeIcon && document.documentElement.classList.contains('dark')) {
                themeIcon.classList.replace('fa-moon', 'fa-sun');
            }
        });
    </script>
</body>
</html>