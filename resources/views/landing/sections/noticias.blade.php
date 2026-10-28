<section id="noticias" class="relative scroll-mt-24 overflow-hidden bg-zinc-100 py-20 dark:bg-zinc-900/50 lg:py-28">
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
