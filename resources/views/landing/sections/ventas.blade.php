@php
    $productos = $productos ?? collect();
    $categorias = $categoriasProductos ?? collect();
    $mostrarFiltros = $categorias->count() > 1;
@endphp

<section id="ventas" data-cf-sales="yes" class="relative scroll-mt-24 overflow-hidden bg-white py-20 dark:bg-zinc-950 lg:py-28">
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mx-auto mb-12 max-w-3xl text-center">
            <p class="mb-3 text-sm font-semibold tracking-[0.25em] text-brand-600 uppercase dark:text-brand-400">Tienda</p>
            <h2 class="font-heading text-4xl font-semibold tracking-wide text-zinc-900 uppercase dark:text-white sm:text-5xl">
                Artículos en venta
            </h2>
            <p class="mt-6 text-base leading-relaxed text-zinc-600 dark:text-zinc-300">
                Indumentaria, accesorios y entradas del club. Cada artículo se publica desde el panel y aparece aquí automáticamente.
            </p>
        </div>

        @if ($productos->isEmpty())
            <div class="mx-auto max-w-xl rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-10 text-center dark:border-white/15 dark:bg-white/5">
                <p class="text-lg font-semibold text-zinc-900 dark:text-white">Estamos preparando nuevos artículos</p>
                <p class="mt-2 text-sm leading-relaxed text-zinc-600 dark:text-zinc-400">Vuelve pronto para conocer las novedades de la tienda del club.</p>
            </div>
        @else
            @if ($mostrarFiltros)
                <div data-sales-filters role="group" aria-label="Filtrar artículos por categoría">
                    <button type="button" data-sales-filter="todos" aria-pressed="true">Todos</button>
                    @foreach ($categorias as $categoria)
                        <button type="button" data-sales-filter="{{ $categoria->slug }}" aria-pressed="false">{{ $categoria->nombre }}</button>
                    @endforeach
                </div>
            @endif

            <div data-product-grid="yes" style="display:grid;gap:24px;">
                @foreach ($productos as $producto)
                    {!! \App\Support\SalesWidget::cardFor($producto) !!}
                @endforeach
            </div>
        @endif
    </div>
</section>
