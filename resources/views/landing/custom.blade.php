<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('landing.partials.seo', ['seo' => $seo])

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ \App\Support\Branding::faviconUrl() }}" sizes="any">
    <link rel="apple-touch-icon" href="{{ \App\Support\Branding::faviconUrl() }}">

    @fonts($pageFonts ?? \App\Support\TemplateFonts::DEFAULTS)

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

    <style>{!! \App\Support\BlockTheme::css() !!}</style>
    <style>{!! \App\Support\AnimationLibrary::css() !!}</style>
    <style>{!! \App\Support\SocialLinkLibrary::css() !!}</style>
    <style>{!! \App\Support\WhatsAppWidget::css() !!}</style>
    <style>{!! \App\Support\SalesWidget::css() !!}</style>
    <style>{!! \App\Support\StatsWidget::css() !!}</style>
    <style>{!! \App\Support\GalleryCardsWidget::css() !!}</style>
    <style>{!! \App\Support\DirectivaWidget::css() !!}</style>
    <style>{!! \App\Support\ClassicHomeWidget::css() !!}</style>
    <style>{!! \App\Support\ParallaxWidget::css() !!}</style>
    <style>{!! \App\Support\CarouselWidget::css() !!}</style>
    <style>{!! \App\Support\FormWidget::css() !!}</style>
    <style>{!! \App\Support\DynamicSectionsWidget::css() !!}</style>
    <style>{!! \App\Support\VideoWidget::css() !!}</style>

    @if (filled($cssPlantilla))
        <style>{!! $cssPlantilla !!}</style>
    @endif
</head>
<body class="bg-white font-sans text-zinc-800 antialiased dark:bg-zinc-950 dark:text-zinc-100"
      data-contacto-estado="{{ session('contacto-estado') }}"
      data-contacto-errores="{{ $errors->any() ? $errors->first() : '' }}">

<div id="scroll-progress" class="fixed top-0 right-0 left-0 z-[60] h-1 bg-brand-500" aria-hidden="true"></div>

@include('landing.partials.header', ['menuItems' => $menuItems])

<main id="cf-template">
    @if (filled($contenido))
        {!! $contenido !!}
    @else
        <section class="mx-auto max-w-3xl px-4 py-24 text-center">
            <p class="mb-3 text-sm font-semibold tracking-[0.25em] text-brand-600 uppercase dark:text-brand-400">Plantilla</p>
            <h1 class="font-heading text-4xl font-semibold tracking-wide text-zinc-900 uppercase dark:text-white">
                {{ $template->name }}
            </h1>
            <p class="mt-4 text-base text-zinc-600 dark:text-zinc-300">
                Esta plantilla todavía no tiene contenido. Edítala desde el panel de administración.
            </p>
        </section>
    @endif
</main>

{{--
    La pestaña de enlaces del pie se retiró del formulario. El pie ahora se construye
    con los bloques "Pies de página"; si la plantilla no trae uno, se mantiene el pie
    clásico para no romper las plantillas antiguas.
--}}
@unless (($hasCustomFooter ?? false) || str_contains($contenido ?? '', 'data-cf-footer'))
    {!! \App\Support\PageBlockLibrary::defaultFooter() !!}
@endunless

{{--
    Si la plantilla trae su propio bloque "Botón volver arriba", ese bloque manda
    (permite cambiar el icono y desactivarlo). Si no, se mantiene el botón clásico
    para que las plantillas antiguas no lo pierdan.
--}}
@unless (str_contains($contenido ?? '', 'data-top-button') || str_contains($contenido ?? '', 'id="top-button'))
    <button id="top-button" type="button" aria-label="Volver arriba"
            class="fixed bottom-6 left-1/2 z-50 -translate-x-1/2 cursor-pointer transition-opacity duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-400"
            data-top-button inert>
        <span class="top-button-bounce block" aria-hidden="true">
            <span class="top-button-rotator block drop-shadow-lg transition-transform duration-200 ease-linear"
                  style="--rotacion: 0deg">
                <span style="display:flex;align-items:center;justify-content:center;width:3.5rem;height:3.5rem;border-radius:9999px;background-color:var(--cf-brand, #ff4c00);color:var(--cf-on-dark, #ffffff);font-size:1.75rem;line-height:1;">↑</span>
            </span>
        </span>
    </button>
@endunless

@include('landing.partials.modals')

{{--
    El widget de WhatsApp de las plantillas GrapesJS es un bloque opcional.
    No se incluye el widget global: si la plantilla quiere el widget, debe
    arrastrar el bloque "Widget de WhatsApp". La portada clásica y las páginas
    legales conservan el widget original.
--}}

@include('landing.partials.cookie-banner')

</body>
</html>
