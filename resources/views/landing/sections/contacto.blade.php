<section id="contacto" class="relative scroll-mt-24 overflow-hidden bg-zinc-100 py-20 dark:bg-zinc-900/50 lg:py-28">
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

                <form action="{{ route('contacto') }}" method="POST" id="contacto-form" data-form-form="yes" class="space-y-5" novalidate>
                    @csrf
                    <div class="hidden" aria-hidden="true">
                        <label for="website">No completar</label>
                        <input type="text" id="website" name="website" value="" tabindex="-1" autocomplete="off">
                    </div>

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
                        <p id="mensaje-contador" data-form-counter="yes" class="mt-2 text-xs text-zinc-500 dark:text-zinc-400" aria-live="polite">Escribe al menos 10 caracteres.</p>
                    </div>

                    <button type="submit" id="contacto-submit" data-form-button-text="yes"
                            class="inline-flex w-full cursor-pointer items-center justify-center rounded-full bg-brand-500 px-8 py-3.5 text-sm font-semibold text-white shadow-lg shadow-brand-500/25 transition-colors hover:bg-brand-600 focus:ring-2 focus:ring-brand-500 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-60 disabled:hover:bg-brand-500 dark:focus:ring-offset-zinc-950">
                        Enviar
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
