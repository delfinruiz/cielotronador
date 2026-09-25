<footer class="border-t border-white/10 bg-zinc-950 py-12 text-white">
    <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col items-center justify-between gap-8 md:flex-row">
            <a href="{{ route('landing') }}" class="flex items-center gap-3">
                <img src="{{ asset('img/logo.png') }}" alt="Club CieloTronador" class="h-14 w-auto">
            </a>

            <p class="font-heading text-sm tracking-[0.25em] text-zinc-400 uppercase">
                «Más que un club, una familia»
            </p>

            <div class="flex items-center gap-4">
                <a href="https://www.instagram.com/cielotronadorthno/" target="_blank" rel="noopener"
                   class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/15 text-zinc-300 transition-colors hover:border-brand-500 hover:text-brand-400" aria-label="Instagram del club">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <rect x="2.5" y="2.5" width="19" height="19" rx="5.5"/>
                        <circle cx="12" cy="12" r="4.5"/>
                        <circle cx="17.5" cy="6.5" r="1.2" fill="currentColor" stroke="none"/>
                    </svg>
                </a>
                <a href="mailto:{{ config('services.contact.email') }}"
                   class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/15 text-zinc-300 transition-colors hover:border-brand-500 hover:text-brand-400" aria-label="Correo de contacto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/>
                    </svg>
                </a>
            </div>
        </div>

        <div class="mt-10 border-t border-white/10 pt-6 text-center">
            <nav class="mb-3 flex flex-wrap items-center justify-center gap-x-6 gap-y-2" aria-label="Enlaces legales">
                <a href="{{ route('politica.privacidad') }}" class="text-sm text-zinc-500 transition-colors hover:text-brand-400">Política de Privacidad</a>
                <a href="{{ route('politica.cookies') }}" class="text-sm text-zinc-500 transition-colors hover:text-brand-400">Política de Cookies</a>
                <a href="{{ route('politica.datos') }}" class="text-sm text-zinc-500 transition-colors hover:text-brand-400">Seguridad y Manejo de Datos</a>
            </nav>
            <p class="text-sm text-zinc-500">
                Copyright {{ now()->year }} por CieloTronador · Talcahuano, Chile
            </p>
        </div>
    </div>
</footer>
