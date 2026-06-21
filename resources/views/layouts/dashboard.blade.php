<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Clearn – Dashboard')</title>

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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</head>

<body class="antialiased">
    <div class="flex min-h-screen">
        @yield('content')
    </div>
</body>

@stack('library')
@stack('scripts')

</html>