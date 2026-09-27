<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, follow">

    <title>@yield('titulo', 'Políticas') | Club CieloTronador</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/apple-touch-icon.png') }}">

    @fonts

    <script>
        document.documentElement.classList.add('js');
        (function () {
            var theme = localStorage.getItem('theme');
            if (theme === null) {
                theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-white font-sans text-zinc-800 antialiased dark:bg-zinc-950 dark:text-zinc-100">

<header id="site-header" class="sticky top-0 z-50 border-b border-zinc-200/70 bg-white/90 backdrop-blur-md dark:border-white/10 dark:bg-zinc-950/90">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('landing') }}" class="flex shrink-0 items-center gap-3" aria-label="Club CieloTronador - Inicio">
            <img src="{{ asset('img/logo.png') }}" alt="Club CieloTronador" class="h-12 w-auto">
        </a>

        <a href="{{ route('landing') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-brand-600 transition-colors hover:text-brand-500 dark:text-brand-400 dark:hover:text-brand-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
            </svg>
            Volver al inicio
        </a>
    </div>
</header>

<main class="flex-1">
    <div class="mx-auto max-w-3xl px-4 py-12 sm:px-6 sm:py-16 lg:px-8">
        @yield('contenido')
    </div>
</main>

@include('partials.footer')

@include('landing.partials.whatsapp')

@include('landing.partials.cookie-banner')

</body>
</html>
