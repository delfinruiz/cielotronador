<div id="galeria" class="scroll-mt-28" data-gallery-root>
    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
@foreach ($galeria as $imagen)
            <button type="button" data-reveal data-reveal-delay="{{ ($loop->index % 4) * 60 }}" data-gallery-full="{{ asset($imagen['imagen']) }}" data-gallery-caption="{{ $imagen['titulo'] }}" class="group relative block cursor-zoom-in overflow-hidden rounded-xl bg-zinc-100 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500 dark:bg-white/5" aria-label="Ampliar fotografía">
                <img src="{{ asset($imagen['imagen']) }}" alt="{{ $imagen['titulo'] ?? 'Actividad del Club CieloTronador' }}" loading="lazy"
                     class="aspect-square w-full object-cover transition-transform duration-300 group-hover:scale-110">
                <span class="pointer-events-none absolute inset-0 bg-brand-500/0 transition-colors group-hover:bg-brand-500/20" aria-hidden="true"></span>
            </button>
        @endforeach
    </div>

    @if ($galeria->lastPage() > 1)
        @php
            $paginaActual = $galeria->currentPage();
            $ultimaPagina = $galeria->lastPage();
            $enlaces = collect(range(1, $ultimaPagina))
                ->filter(fn ($p) => $p === 1 || $p === $ultimaPagina || abs($p - $paginaActual) <= 1)
                ->values();
        @endphp
        <nav class="mt-12 flex flex-wrap items-center justify-center gap-2" aria-label="Paginación de la galería">
            @if ($galeria->onFirstPage())
                <span class="inline-flex h-10 items-center rounded-full px-4 text-sm font-medium text-zinc-400 dark:text-zinc-600" aria-disabled="true">Anterior</span>
            @else
                <a href="{{ $galeria->previousPageUrl() }}#galeria" data-gallery-page class="inline-flex h-10 items-center rounded-full border border-zinc-300 px-4 text-sm font-medium text-zinc-700 transition-colors hover:border-brand-500 hover:text-brand-600 dark:border-white/15 dark:text-zinc-200 dark:hover:border-brand-400 dark:hover:text-brand-400">Anterior</a>
            @endif

            @foreach ($enlaces as $i => $pagina)
                @if ($i > 0 && $pagina - $enlaces[$i - 1] > 1)
                    <span class="inline-flex h-10 min-w-10 items-center justify-center text-sm text-zinc-400 dark:text-zinc-500">…</span>
                @endif

                @if ($pagina === $paginaActual)
                    <span class="inline-flex h-10 min-w-10 items-center justify-center rounded-full bg-brand-500 px-3 text-sm font-semibold text-white shadow-sm" aria-current="page">{{ $pagina }}</span>
                @else
                    <a href="{{ $galeria->url($pagina) }}#galeria" data-gallery-page class="inline-flex h-10 min-w-10 items-center justify-center rounded-full border border-zinc-300 px-3 text-sm font-medium text-zinc-700 transition-colors hover:border-brand-500 hover:text-brand-600 dark:border-white/15 dark:text-zinc-200 dark:hover:border-brand-400 dark:hover:text-brand-400">{{ $pagina }}</a>
                @endif
            @endforeach

            @if ($galeria->hasMorePages())
                <a href="{{ $galeria->nextPageUrl() }}#galeria" data-gallery-page class="inline-flex h-10 items-center rounded-full border border-zinc-300 px-4 text-sm font-medium text-zinc-700 transition-colors hover:border-brand-500 hover:text-brand-600 dark:border-white/15 dark:text-zinc-200 dark:hover:border-brand-400 dark:hover:text-brand-400">Siguiente</a>
            @else
                <span class="inline-flex h-10 items-center rounded-full px-4 text-sm font-medium text-zinc-400 dark:text-zinc-600" aria-disabled="true">Siguiente</span>
            @endif
        </nav>

        <p class="mt-4 text-center text-xs text-zinc-500 dark:text-zinc-400">
            Página {{ $paginaActual }} de {{ $ultimaPagina }} · {{ $galeria->total() }} fotografías
        </p>
    @endif
</div>
