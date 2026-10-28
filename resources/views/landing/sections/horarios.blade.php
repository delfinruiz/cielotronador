<section id="horarios" class="relative scroll-mt-24 overflow-hidden bg-zinc-950 py-20 text-white lg:py-28">
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
                    class="group relative block cursor-zoom-in overflow-hidden rounded-2xl border border-white/10 bg-white/5 transition-shadow duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand-500"
                    aria-label="Ampliar horario"
                >
                    <img src="{{ asset($imagen) }}" alt="Horario de entrenamiento del Club CieloTronador" loading="lazy" class="w-full object-contain transition-transform duration-300 group-hover:scale-105">
                    <span class="pointer-events-none absolute inset-0 bg-brand-500/0 transition-colors duration-300 group-hover:bg-brand-500/15" aria-hidden="true"></span>
                </button>
            @endforeach
        </div>
    </div>
</section>
