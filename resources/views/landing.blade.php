<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Club de Básquetbol Cielo Tronador de Talcahuano, Chile. Más que un club, una familia. Categorías desde sub 9 hasta adultos.">

    <title>Club CieloTronador | Cielo Tronador</title>

    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon-32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('img/favicon-16.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('img/apple-touch-icon.png') }}">

    @fonts

    {{-- Aplica el tema antes del primer render para evitar el flash --}}
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
<body class="bg-white font-sans text-zinc-800 antialiased dark:bg-zinc-950 dark:text-zinc-100">

<div id="scroll-progress" class="fixed top-0 right-0 left-0 z-[60] h-1 bg-brand-500" aria-hidden="true"></div>

{{-- ============================== HEADER ============================== --}}
<header id="site-header" class="sticky top-0 z-50 border-b border-zinc-200/70 bg-white/90 backdrop-blur-md dark:border-white/10 dark:bg-zinc-950/90">
    <div class="header-bar mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">
        <a href="{{ route('landing') }}" class="flex shrink-0 items-center gap-3" aria-label="Club CieloTronador - Inicio">
            <img src="{{ asset('img/logo.png') }}" alt="Club CieloTronador" class="h-12 w-auto">
        </a>

        <nav class="hidden items-center gap-8 lg:flex" aria-label="Navegación principal">
            <a href="#quienes-somos" data-nav-link class="text-sm font-medium text-zinc-600 transition-colors hover:text-brand-600 dark:text-zinc-300 dark:hover:text-brand-400">Quiénes Somos</a>
            <a href="#noticias" data-nav-link class="text-sm font-medium text-zinc-600 transition-colors hover:text-brand-600 dark:text-zinc-300 dark:hover:text-brand-400">Noticias</a>
            <a href="#actividades" data-nav-link class="text-sm font-medium text-zinc-600 transition-colors hover:text-brand-600 dark:text-zinc-300 dark:hover:text-brand-400">Actividades</a>
            <a href="#horarios" data-nav-link class="text-sm font-medium text-zinc-600 transition-colors hover:text-brand-600 dark:text-zinc-300 dark:hover:text-brand-400">Horarios</a>
            <a href="#contacto" data-nav-link class="text-sm font-medium text-zinc-600 transition-colors hover:text-brand-600 dark:text-zinc-300 dark:hover:text-brand-400">Contáctenos</a>
        </nav>

        <div class="flex items-center gap-3">
            <button id="theme-toggle" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-zinc-600 transition-colors hover:bg-zinc-100 hover:text-zinc-900 dark:text-zinc-300 dark:hover:bg-white/10 dark:hover:text-white" aria-label="Cambiar tema">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-moon h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="icon-sun hidden h-5 w-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                </svg>
            </button>

            @auth
                <a href="{{ url('/admin') }}" class="hidden items-center gap-2 rounded-full bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-600 sm:inline-flex">
                    Ir al panel
                </a>
            @else
                <a href="{{ url('/admin/login') }}" class="hidden items-center gap-2 rounded-full bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-brand-600 sm:inline-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="h-4 w-4">
                        <path fill-rule="evenodd" d="M14 7.5a5.5 5.5 0 1 0-11 0 5.5 5.5 0 0 0 11 0ZM8 1a6.5 6.5 0 1 0 3.251 12.132l2.558 2.559a.75.75 0 0 0 1.06-1.06l-2.558-2.559A6.5 6.5 0 0 0 8 1Zm2.6 5.4a2 2 0 1 1-4 0 2 2 0 0 1 4 0Z" clip-rule="evenodd"/>
                    </svg>
                    Ingresar
                </a>
            @endauth

            <button id="mobile-menu-toggle" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-full text-zinc-600 transition-colors hover:bg-zinc-100 dark:text-zinc-300 dark:hover:bg-white/10 lg:hidden" aria-label="Abrir menú" aria-expanded="false">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                </svg>
            </button>
        </div>
    </div>

    <nav id="mobile-menu" class="hidden border-t border-zinc-200/70 px-4 pt-2 pb-4 dark:border-white/10 lg:hidden" aria-label="Navegación móvil">
        <div class="flex flex-col gap-1">
            <a href="#quienes-somos" data-nav-link class="rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-white/10">Quiénes Somos</a>
            <a href="#noticias" data-nav-link class="rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-white/10">Noticias</a>
            <a href="#actividades" data-nav-link class="rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-white/10">Actividades</a>
            <a href="#horarios" data-nav-link class="rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-white/10">Horarios</a>
            <a href="#contacto" data-nav-link class="rounded-lg px-3 py-2.5 text-sm font-medium text-zinc-700 hover:bg-zinc-100 dark:text-zinc-200 dark:hover:bg-white/10">Contáctenos</a>
            @auth
                <a href="{{ url('/admin') }}" class="mt-2 inline-flex items-center justify-center gap-2 rounded-full bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-600">Ir al panel</a>
            @else
                <a href="{{ url('/admin/login') }}" class="mt-2 inline-flex items-center justify-center gap-2 rounded-full bg-brand-500 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-600">Ingresar</a>
            @endauth
        </div>
    </nav>
</header>

<main>
    {{-- ============================== HERO ============================== --}}
    <section class="relative flex min-h-[calc(100vh-5rem)] items-center overflow-hidden bg-zinc-950 text-white">
        <div class="pointer-events-none absolute inset-0" aria-hidden="true">
            <div class="absolute -top-40 left-1/2 h-[36rem] w-[36rem] -translate-x-1/2 rounded-full bg-brand-500/25 blur-3xl"></div>
            <div class="absolute -bottom-32 -left-24 h-80 w-80 rounded-full bg-brand-600/10 blur-3xl"></div>
        </div>

        <div class="relative mx-auto grid max-w-7xl items-center gap-12 px-4 py-20 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-28">
            <div class="order-2 text-center lg:order-1 lg:text-left">
                <p data-reveal class="mb-4 text-sm font-semibold tracking-[0.3em] text-brand-400 uppercase">Bienvenidos</p>
                <h1 data-reveal data-reveal-delay="100" class="font-heading text-5xl leading-none font-semibold tracking-wide uppercase sm:text-6xl lg:text-7xl">
                    Cielo<br>
                    <span class="text-brand-500">Tronador</span>
                </h1>
                <p data-reveal data-reveal-delay="200" class="mt-6 text-xl font-medium text-zinc-300 sm:text-2xl">
                    «Más que un club, una familia»
                </p>
                <p data-reveal data-reveal-delay="300" class="mx-auto mt-4 max-w-md text-sm leading-relaxed text-zinc-400 lg:mx-0">
                    ¡Bienvenidos a nuestro sitio web! Somos el Club de Básquetbol Cielo Tronador de
                    <span class="font-semibold text-zinc-200">Talcahuano, Chile</span>: una familia unida por la pasión por el baloncesto.
                </p>
                <p data-reveal data-reveal-delay="350" class="mx-auto mt-3 max-w-md text-sm leading-relaxed text-zinc-400 lg:mx-0">
                    Desde <span class="font-semibold text-brand-400">2006</span> formamos jugadores y personas en categorías
                    sub 9 hasta adultos, con un cuerpo técnico comprometido y valores como el respeto, la disciplina y el compañerismo.
                </p>

                <div data-reveal data-reveal-delay="400" class="mt-10 flex flex-col items-center justify-center gap-4 sm:flex-row lg:justify-start">
                    <a href="#quienes-somos" class="inline-flex w-full items-center justify-center rounded-full bg-brand-500 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition-colors hover:bg-brand-600 sm:w-auto">
                        Conócenos
                    </a>
                    @guest
                        <a href="{{ url('/admin/login') }}" class="inline-flex w-full items-center justify-center rounded-full border border-white/20 px-8 py-3.5 text-sm font-semibold text-white transition-colors hover:border-brand-400 hover:text-brand-400 sm:w-auto">
                            Ingresar
                        </a>
                    @endguest
                </div>
            </div>

            <div data-reveal="zoom" data-reveal-delay="250" class="order-1 relative mx-auto w-full max-w-xl lg:order-2">
                <div class="absolute inset-0 -rotate-3 rounded-3xl bg-brand-500/20 blur-2xl" aria-hidden="true"></div>
                <img src="{{ asset('img/hero.png') }}" alt="Escudo del Club CieloTronador" class="relative w-full drop-shadow-[0_0_40px_rgba(255,76,0,0.35)]">
            </div>
        </div>
    </section>

    {{-- ============================== QUIÉNES SOMOS ============================== --}}
    <section id="quienes-somos" class="relative scroll-mt-24 overflow-hidden bg-white py-20 dark:bg-zinc-950 lg:py-28">
        @include('landing.partials.parallax', ['color' => 'text-brand-500', 'x' => 0.06])
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-12 lg:grid-cols-2 lg:gap-16">
                <div data-reveal="left" class="relative order-2 mx-auto w-full max-w-sm lg:order-1 lg:max-w-md">
                    <div class="absolute top-4 left-4 h-full w-full rounded-2xl bg-brand-100 dark:bg-brand-500/10" aria-hidden="true"></div>
                    <img src="{{ asset('img/bienvenida.jpg') }}" alt="Jugadores del Club CieloTronador" class="relative aspect-[3/4] w-full rounded-2xl object-cover shadow-xl">
                </div>

                <div data-reveal="right" class="order-1 lg:order-2">
                    <p class="mb-3 text-sm font-semibold tracking-[0.25em] text-brand-600 uppercase dark:text-brand-400">Quiénes somos</p>
                    <h2 class="font-heading text-4xl font-semibold tracking-wide text-zinc-900 uppercase dark:text-white sm:text-5xl">
                        Bienvenidos a<br>nuestro sitio web
                    </h2>

                    <div class="mt-8 space-y-5 text-base leading-relaxed text-zinc-600 dark:text-zinc-300">
                        <p>
                            Somos una verdadera familia unida por la pasión por el baloncesto. Desde
                            <span class="font-semibold text-brand-600 dark:text-brand-400">2006</span>, en
                            <span class="font-semibold text-zinc-900 dark:text-white">Talcahuano, Chile</span>,
                            hemos visto crecer a generaciones de niños y niñas que llegaron con una pelota bajo el brazo
                            y se fueron formando como jugadores y, sobre todo, como personas.
                        </p>
                        <p>
                            Con categorías que van desde <span class="font-semibold text-zinc-900 dark:text-white">sub 9 hasta adultos</span>,
                            trabajamos junto a un cuerpo técnico comprometido no solo el desarrollo técnico y táctico,
                            sino también los valores que nos definen: el respeto, la disciplina, la solidaridad y el compañerismo.
                        </p>
                        <p>
                            Creemos en el deporte como una herramienta de transformación social. Nuestro lema
                            «Más que un club, una familia» te invita a vivir el básquetbol con nosotros
                            y a formar parte de una historia que se escribe con esfuerzo, garra y mucho corazón.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Directiva --}}
            <div class="mt-24">
                <div data-reveal class="mb-12 text-center">
                    <p class="mb-3 text-sm font-semibold tracking-[0.25em] text-brand-600 uppercase dark:text-brand-400">Nuestra directiva</p>
                    <h3 class="font-heading text-3xl font-semibold tracking-wide text-zinc-900 uppercase dark:text-white sm:text-4xl">
                        Quienes llevan el club
                    </h3>
                </div>

                <div class="grid gap-8 sm:grid-cols-3">
                    @php
                        $directiva = [
                            ['nombre' => 'Jacqueline Reyes', 'cargo' => 'Directora'],
                            ['nombre' => 'Angélica Fuente', 'cargo' => 'Secretaria'],
                            ['nombre' => 'Bertita Vergara', 'cargo' => 'Tesorera'],
                        ];
                    @endphp
                    @foreach ($directiva as $miembro)
                        <div data-reveal data-reveal-delay="{{ $loop->index * 150 }}" class="group rounded-2xl border border-zinc-200 bg-zinc-50 p-8 text-center transition-all hover:-translate-y-1 hover:border-brand-300 hover:shadow-lg dark:border-white/10 dark:bg-white/5 dark:hover:border-brand-500/40">
                            <div class="mx-auto mb-5 h-24 w-24 drop-shadow-lg transition-transform duration-300 group-hover:rotate-12 group-hover:scale-110">
                                @include('landing.partials.baloncesto', ['attributes' => 'class="h-full w-full"'])
                            </div>
                            <h4 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $miembro['nombre'] }}</h4>
                            <p class="mt-1 text-sm font-medium tracking-widest text-brand-600 uppercase dark:text-brand-400">{{ $miembro['cargo'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- ============================== NOTICIAS ============================== --}}
    <section id="noticias" class="relative scroll-mt-24 overflow-hidden bg-zinc-100 py-20 dark:bg-zinc-900/50 lg:py-28">
        @include('landing.partials.parallax', ['color' => 'text-brand-600', 'x' => -0.06])
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div data-reveal class="mb-12 flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-end">
                <div>
                    <p class="mb-3 text-sm font-semibold tracking-[0.25em] text-brand-600 uppercase dark:text-brand-400">Noticias</p>
                    <h2 class="font-heading text-4xl font-semibold tracking-wide text-zinc-900 uppercase dark:text-white sm:text-5xl">
                        Últimas noticias
                    </h2>
                </div>
                <a href="https://www.instagram.com/cielotronadorthno/" target="_blank" rel="noopener" class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600 transition-colors hover:text-brand-500 dark:text-brand-400">
                    Ver más noticias
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                    </svg>
                </a>
            </div>

            <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($noticias as $i => $noticia)
                    <article data-reveal data-reveal-delay="{{ $loop->index * 80 }}" class="group flex flex-col overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm transition-all hover:-translate-y-1 hover:shadow-xl dark:border-white/10 dark:bg-zinc-950 {{ $loop->first ? 'lg:col-span-2' : '' }}">
                        <button type="button" data-noticia="{{ $i }}" class="block cursor-zoom-in overflow-hidden text-left focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500" aria-label="Leer noticia: {{ $noticia['titulo'] }}">
                            <img src="{{ asset($noticia['imagen']) }}" alt="{{ $noticia['titulo'] }}" loading="lazy"
                                 class="aspect-video w-full object-cover transition-transform duration-300 group-hover:scale-105">
                        </button>
                        <div class="flex flex-1 flex-col p-6">
                            <time datetime="{{ \Carbon\Carbon::createFromFormat('d/m/Y', $noticia['fecha'])->format('Y-m-d') }}" class="mb-2 text-xs font-semibold tracking-wider text-brand-600 uppercase dark:text-brand-400">
                                {{ $noticia['fecha'] }} · Noticias
                            </time>
                            <h3 class="mb-3 text-lg leading-snug font-semibold text-zinc-900 dark:text-white">
                                {{ $noticia['titulo'] }}
                            </h3>
                            <p class="mb-5 flex-1 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">{{ Str::limit($noticia['extracto'], 160) }}</p>
                            <button type="button" data-noticia="{{ $i }}" class="mt-auto inline-flex cursor-pointer items-center gap-1.5 self-start text-sm font-semibold text-brand-600 transition-colors hover:text-brand-500 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 dark:text-brand-400">
                                Leer más
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                                </svg>
                            </button>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================== GALERÍA ============================== --}}
    <section id="actividades" class="relative scroll-mt-24 overflow-hidden bg-white py-20 dark:bg-zinc-950 lg:py-28">
        @include('landing.partials.parallax', ['color' => 'text-brand-500', 'x' => 0.06])
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div data-reveal class="mx-auto mb-12 max-w-3xl text-center">
                <p class="mb-3 text-sm font-semibold tracking-[0.25em] text-brand-600 uppercase dark:text-brand-400">Actividades</p>
                <h2 class="font-heading text-4xl font-semibold tracking-wide text-zinc-900 uppercase dark:text-white sm:text-5xl">
                    Galería fotográfica
                </h2>
                <p class="mt-6 text-base leading-relaxed text-zinc-600 dark:text-zinc-300">
                    ¡Explora el emocionante mundo del baloncesto con nosotros en el Club de Básquetbol Cielo Tronador!
                    Te invitamos a sumergirte en una colección única de fotografías que capturan la pasión, la energía
                    y la camaradería que se viven en nuestras actividades.
                </p>
                <p class="mt-4 text-base leading-relaxed text-zinc-600 dark:text-zinc-300">
                    Nuestras imágenes te transportarán a través de emocionantes partidos en la cancha, intensos
                    entrenamientos donde nuestros jugadores perfeccionan sus habilidades, y momentos de celebración
                    que reflejan el verdadero espíritu de equipo.
                </p>
            </div>

            @include('landing.partials.galeria')
        </div>
    </section>

    {{-- ============================== HORARIOS ============================== --}}
    <section id="horarios" class="relative scroll-mt-24 overflow-hidden bg-zinc-950 py-20 text-white lg:py-28">
        @include('landing.partials.parallax', ['color' => 'text-white', 'x' => -0.06])
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div data-reveal class="mx-auto mb-12 max-w-2xl text-center">
                <p class="mb-3 text-sm font-semibold tracking-[0.25em] text-brand-400 uppercase">Entrenamientos</p>
                <h2 class="font-heading text-4xl font-semibold tracking-wide uppercase sm:text-5xl">
                    Horarios de entrenamiento
                </h2>
                <p class="mt-6 text-base text-zinc-400">
                    Los entrenamientos se realizan en <span class="font-semibold text-brand-400">la Tortuga de Talcahuano</span>.
                </p>
            </div>

            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ([
                    'img/horarios/formativa.png',
                    'img/horarios/escuelita-nivelacion.png',
                    'img/horarios/version-1.png',
                    'img/horarios/version-1-2.png',
                ] as $imagen)
                    <button
                        type="button"
                        data-reveal
                        data-reveal-delay="{{ $loop->index * 150 }}"
                        data-horario-full="{{ asset($imagen) }}"
                        class="group relative block cursor-zoom-in overflow-hidden rounded-2xl border border-white/10 bg-white/5 transition-shadow duration-300 group-hover:shadow-2xl group-hover:shadow-brand-500/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500"
                        aria-label="Ampliar horario"
                    >
                        <img src="{{ asset($imagen) }}" alt="Horario de entrenamiento del Club CieloTronador" loading="lazy" class="w-full object-contain transition-transform duration-300 group-hover:scale-105">
                        <span class="pointer-events-none absolute inset-0 bg-brand-500/0 transition-colors duration-300 group-hover:bg-brand-500/15" aria-hidden="true"></span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============================== CONTACTO ============================== --}}
    <section id="contacto" class="relative scroll-mt-24 overflow-hidden bg-zinc-100 py-20 dark:bg-zinc-900/50 lg:py-28">
        @include('landing.partials.parallax', ['color' => 'text-brand-600', 'x' => 0.06])
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="grid gap-12 lg:grid-cols-2 lg:gap-16">
                <div data-reveal="left">
                    <p class="mb-3 text-sm font-semibold tracking-[0.25em] text-brand-600 uppercase dark:text-brand-400">Contáctenos</p>
                    <h2 class="font-heading text-4xl font-semibold tracking-wide text-zinc-900 uppercase dark:text-white sm:text-5xl">
                        Escríbenos
                    </h2>
                    <p class="mt-6 max-w-md text-base leading-relaxed text-zinc-600 dark:text-zinc-300">
                        Si tienes alguna consulta, no dudes en escribirnos, te responderemos a la brevedad.
                    </p>

                    <div class="mt-10 space-y-5">
                        <a href="mailto:{{ config('services.contact.email') }}" class="group flex items-center gap-4">
                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-brand-500 text-white shadow-lg shadow-brand-500/30">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                                </svg>
                            </span>
                            <span>
                                <span class="block text-xs font-semibold tracking-wider text-zinc-500 uppercase dark:text-zinc-400">O escríbenos a</span>
                                <span class="block font-semibold text-zinc-900 transition-colors group-hover:text-brand-600 dark:text-white dark:group-hover:text-brand-400">{{ config('services.contact.email') }}</span>
                            </span>
                        </a>

                        <a href="https://www.instagram.com/cielotronadorthno/" target="_blank" rel="noopener" class="group flex items-center gap-4">
                            <span class="flex h-12 w-12 items-center justify-center rounded-full bg-zinc-900 text-white dark:bg-white dark:text-zinc-900">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                                    <rect x="2.5" y="2.5" width="19" height="19" rx="5.5"/>
                                    <circle cx="12" cy="12" r="4.5"/>
                                    <circle cx="17.5" cy="6.5" r="1.2" fill="currentColor" stroke="none"/>
                                </svg>
                            </span>
                            <span>
                                <span class="block text-xs font-semibold tracking-wider text-zinc-500 uppercase dark:text-zinc-400">Síguenos en Instagram</span>
                                <span class="block font-semibold text-zinc-900 transition-colors group-hover:text-brand-600 dark:text-white dark:group-hover:text-brand-400">@cielotronadorthno</span>
                            </span>
                        </a>
                    </div>
                </div>

                <div data-reveal="right" class="rounded-3xl border border-zinc-200 bg-white p-8 shadow-xl dark:border-white/10 dark:bg-zinc-950 sm:p-10">
                    @if (session('contacto-estado') === 'enviado')
                        <div class="mb-6 flex items-start gap-3 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-800 dark:border-green-500/30 dark:bg-green-500/10 dark:text-green-300" role="status">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="mt-0.5 h-5 w-5 shrink-0">
                                <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"/>
                            </svg>
                            <p><strong>¡Mensaje enviado!</strong> Gracias por escribirnos, te responderemos a la brevedad.</p>
                        </div>
                    @elseif (session('contacto-estado') === 'error')
                        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-800 dark:border-red-500/30 dark:bg-red-500/10 dark:text-red-300" role="alert">
                            <p><strong>Lo sentimos.</strong> No pudimos enviar tu mensaje en este momento. Inténtalo más tarde o escríbenos directamente a {{ config('services.contact.email') }}.</p>
                        </div>
                    @endif

                    <form action="{{ route('contacto') }}" method="POST" id="contacto-form" class="space-y-5" novalidate>
                        @csrf

                        <div>
                            <label for="nombre" class="mb-2 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Nombre <span class="text-red-500" aria-hidden="true">*</span></label>
                            <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required
                                   class="w-full rounded-xl border-zinc-300 bg-zinc-50 px-4 py-3 text-sm text-zinc-900 shadow-sm transition-colors placeholder:text-zinc-400 focus:border-brand-500 focus:ring-brand-500 dark:border-white/10 dark:bg-white/5 dark:text-white @error('nombre') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="Tu nombre">
                            @error('nombre')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="mb-2 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Dirección de correo electrónico <span class="text-red-500" aria-hidden="true">*</span></label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                   class="w-full rounded-xl border-zinc-300 bg-zinc-50 px-4 py-3 text-sm text-zinc-900 shadow-sm transition-colors placeholder:text-zinc-400 focus:border-brand-500 focus:ring-brand-500 dark:border-white/10 dark:bg-white/5 dark:text-white @error('email') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                                   placeholder="tu@correo.cl">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="mensaje" class="mb-2 block text-sm font-semibold text-zinc-700 dark:text-zinc-300">Mensaje <span class="text-red-500" aria-hidden="true">*</span></label>
                            <textarea id="mensaje" name="mensaje" rows="5" required
                                      class="w-full rounded-xl border-zinc-300 bg-zinc-50 px-4 py-3 text-sm text-zinc-900 shadow-sm transition-colors placeholder:text-zinc-400 focus:border-brand-500 focus:ring-brand-500 dark:border-white/10 dark:bg-white/5 dark:text-white @error('mensaje') border-red-400 focus:border-red-500 focus:ring-red-500 @enderror"
                                      placeholder="Cuéntanos en qué podemos ayudarte...">{{ old('mensaje') }}</textarea>
                            @error('mensaje')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p id="mensaje-contador" class="mt-2 text-xs text-zinc-500 dark:text-zinc-400" aria-live="polite">Escribe al menos 10 caracteres.</p>
                        </div>

                        <button type="submit" id="contacto-submit"
                                class="inline-flex w-full cursor-pointer items-center justify-center rounded-full bg-brand-500 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition-colors hover:bg-brand-600 focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:bg-brand-500 dark:focus:ring-offset-zinc-950">
                            Enviar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

{{-- ============================== FOOTER ============================== --}}
@include('partials.footer')

{{-- ============================== BOTÓN VOLVER ARRIBA ============================== --}}
<button id="top-button" type="button" aria-label="Volver arriba"
        class="fixed bottom-6 left-1/2 z-50 -translate-x-1/2 cursor-pointer transition-opacity duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400"
        data-top-button inert>
    <span class="top-button-bounce block" aria-hidden="true">
        <span class="top-button-rotator block drop-shadow-lg transition-transform duration-200 ease-linear"
              style="--rotacion: 0deg">
            @include('landing.partials.baloncesto', ['attributes' => 'class="h-14 w-14"'])
        </span>
    </span>
</button>

{{-- ============================== MODAL GALERÍA ============================== --}}
<div id="gallery-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-zinc-950/90 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="Vista ampliada de fotografía">
    <button type="button" id="gallery-modal-close" class="absolute top-4 right-4 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400" aria-label="Cerrar">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
        </svg>
    </button>

    <button type="button" id="gallery-modal-prev" class="absolute left-2 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 sm:left-6" aria-label="Fotografía anterior">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
        </svg>
    </button>

    <button type="button" id="gallery-modal-next" class="absolute right-2 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 sm:right-6" aria-label="Fotografía siguiente">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
    </button>

    <figure class="flex max-h-full flex-col items-center">
        <img id="gallery-modal-img" src="" alt="" class="max-h-[80vh] max-w-full rounded-xl object-contain shadow-2xl">
        <figcaption id="gallery-modal-caption" class="mt-3 text-sm font-medium text-zinc-300"></figcaption>
    </figure>
</div>

{{-- ============================== MODAL HORARIO ============================== --}}
<div id="horario-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-zinc-950/90 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="Horario ampliado">
    <button type="button" id="horario-modal-close" class="absolute top-4 right-4 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400" aria-label="Cerrar">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
        </svg>
    </button>

    <button type="button" id="horario-modal-prev" class="absolute left-2 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 sm:left-6" aria-label="Horario anterior">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
        </svg>
    </button>

    <button type="button" id="horario-modal-next" class="absolute right-2 inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/10 text-white transition-colors hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400 sm:right-6" aria-label="Horario siguiente">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-7 w-7">
            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
        </svg>
    </button>

    <img id="horario-modal-img" src="" alt="Horario de entrenamiento del Club CieloTronador" class="max-h-[85vh] max-w-full rounded-xl object-contain shadow-2xl">
</div>

{{-- ============================== MODAL NOTICIA ============================== --}}
<script type="application/json" id="noticias-data">@json(collect($noticias)->map(fn ($n) => [...$n, 'imagen' => asset($n['imagen'])]), JSON_UNESCAPED_UNICODE)</script>

<div id="noticia-modal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-zinc-950/90 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-label="Noticia">
    <div class="flex max-h-full w-full max-w-3xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl dark:bg-zinc-900">
        <div class="relative shrink-0">
            <img id="noticia-modal-img" src="" alt="" class="max-h-64 w-full object-cover">
            <button type="button" id="noticia-modal-close" class="absolute top-4 right-4 inline-flex h-11 w-11 items-center justify-center rounded-full bg-zinc-950/60 text-white transition-colors hover:bg-zinc-950/80 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400" aria-label="Cerrar noticia">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="overflow-y-auto p-6 sm:p-8">
            <time id="noticia-modal-fecha" datetime="" class="mb-2 block text-xs font-semibold tracking-wider text-brand-600 uppercase dark:text-brand-400"></time>
            <h3 id="noticia-modal-titulo" class="font-heading mb-4 text-2xl leading-snug font-semibold text-zinc-900 dark:text-white"></h3>
            <div id="noticia-modal-cuerpo" class="space-y-4 text-sm leading-relaxed text-zinc-600 dark:text-zinc-300"></div>
        </div>
    </div>
</div>

@include('landing.partials.whatsapp')

@include('landing.partials.cookie-banner')

</body>
</html>
