<section id="directiva" class="relative scroll-mt-24 overflow-hidden bg-zinc-100 py-20 dark:bg-zinc-900/50 lg:py-28">
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="mb-12 text-center">
            <p class="mb-3 text-sm font-semibold tracking-[0.25em] text-brand-600 uppercase dark:text-brand-400">Nuestra directiva</p>
            <h2 class="font-heading text-3xl font-semibold tracking-wide text-zinc-900 uppercase dark:text-white sm:text-4xl">
                Quienes llevan el club
            </h2>
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
                <div class="group rounded-2xl border border-zinc-200 bg-white p-8 text-center transition-all hover:-translate-y-1 hover:border-brand-300 hover:shadow-lg dark:border-white/10 dark:bg-white/5 dark:hover:border-brand-500/40">
                    <div class="mx-auto mb-5 h-24 w-24 drop-shadow-lg transition-transform duration-300 group-hover:rotate-12 group-hover:scale-110">
                        @include('landing.partials.baloncesto', ['attributes' => 'class="h-full w-full"'])
                    </div>
                    <h3 class="text-lg font-semibold text-zinc-900 dark:text-white">{{ $miembro['nombre'] }}</h3>
                    <p class="mt-1 text-sm font-medium tracking-widest text-brand-600 uppercase dark:text-brand-400">{{ $miembro['cargo'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
