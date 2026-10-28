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
