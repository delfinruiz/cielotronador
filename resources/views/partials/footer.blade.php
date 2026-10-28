<footer data-global-footer class="w-full border-t border-zinc-200 bg-white py-12 text-zinc-800 dark:border-white/10 dark:bg-zinc-950 dark:text-white">
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-between gap-8 md:flex-row">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <img src="{{ \App\Support\Branding::logoLightUrl() }}" alt="{{ \App\Support\Branding::name() }}" class="h-14 w-auto dark:hidden">
                <img src="{{ \App\Support\Branding::logoDarkUrl() }}" alt="{{ \App\Support\Branding::name() }}" class="hidden h-14 w-auto dark:block">
            </a>

            <p class="font-heading text-center text-sm tracking-[0.25em] text-zinc-500 uppercase dark:text-zinc-400">
                {{ \App\Support\Branding::tagline() }}
            </p>

            <div class="flex items-center gap-4">
                <a href="{{ \App\Support\Branding::instagramUrl() }}" target="_blank" rel="noopener"
                   class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-zinc-300 text-zinc-600 transition-colors hover:border-brand-500 hover:text-brand-600 dark:border-white/15 dark:text-zinc-300 dark:hover:border-brand-500 dark:hover:text-brand-400" aria-label="Instagram">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <rect x="2.5" y="2.5" width="19" height="19" rx="5.5"/>
                        <circle cx="12" cy="12" r="4.5"/>
                        <circle cx="17.5" cy="6.5" r="1.2" fill="currentColor" stroke="none"/>
                    </svg>
                </a>
                <a href="mailto:{{ \App\Support\Branding::contactEmail() }}"
                   class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-zinc-300 text-zinc-600 transition-colors hover:border-brand-500 hover:text-brand-600 dark:border-white/15 dark:text-zinc-300 dark:hover:border-brand-500 dark:hover:text-brand-400" aria-label="Correo de contacto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="mt-10 border-t border-zinc-200 pt-6 text-center dark:border-white/10">
            @if (isset($footerMenuItems) && $footerMenuItems->isNotEmpty())
                <nav class="mb-3 flex flex-wrap items-center justify-center gap-x-6 gap-y-2" aria-label="Enlaces del pie de página">
                    @foreach ($footerMenuItems as $item)
                        @if ($item->is_visible)
                            <a href="{{ $item->resolvedUrl() }}"
                               @if ($item->isExternal()) target="{{ $item->target }}" rel="noopener noreferrer" @endif
                               class="text-sm text-zinc-500 transition-colors hover:text-brand-600 dark:hover:text-brand-400">{{ $item->label }}</a>
                        @endif
                    @endforeach
                </nav>
            @endif
            <nav class="mb-3 flex flex-wrap items-center justify-center gap-x-6 gap-y-2" aria-label="Enlaces legales">
                <a href="{{ route('politica.privacidad') }}" class="text-sm text-zinc-500 transition-colors hover:text-brand-600 dark:hover:text-brand-400">Política de Privacidad</a>
                <a href="{{ route('politica.cookies') }}" class="text-sm text-zinc-500 transition-colors hover:text-brand-600 dark:hover:text-brand-400">Política de Cookies</a>
                <a href="{{ route('politica.datos') }}" class="text-sm text-zinc-500 transition-colors hover:text-brand-600 dark:hover:text-brand-400">Seguridad y Manejo de Datos</a>
            </nav>
            <p class="text-sm text-zinc-500">
                Copyright {{ now()->year }} por {{ \App\Support\Branding::name() }} · {{ \App\Support\Branding::city() }}
            </p>
        </div>
    </div>
</footer>
