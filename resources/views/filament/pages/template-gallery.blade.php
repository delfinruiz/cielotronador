<x-filament-panels::page>
    <div class="mb-6 max-w-3xl">
        <p class="text-sm text-gray-600 dark:text-gray-300">
            Elige una plantilla para empezar. Se crea una página nueva en modo borrador con los textos,
            las imágenes y el menú ya cargados; después puedes cambiar todo con el constructor y publicarla
            cuando esté lista. La portada actual no se modifica.
        </p>
    </div>

    <div class="grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ($this->presets() as $preset)
            <article
                class="group flex flex-col overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm transition hover:shadow-md dark:border-white/10 dark:bg-gray-900">
                <div class="relative aspect-[16/10] overflow-hidden bg-gray-100 dark:bg-gray-800">
                    @if ($this->thumbnailExists($preset))
                        <img
                            src="{{ $preset->thumbnail() }}"
                            alt="Miniatura de la plantilla {{ $preset->name() }}"
                            class="h-full w-full object-cover object-top transition duration-300 group-hover:scale-[1.02]"
                            loading="lazy"
                        >
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700">
                            <span class="text-3xl font-bold text-gray-400 dark:text-gray-500">
                                {{ $preset->name() }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="flex flex-1 flex-col gap-4 p-5">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-950 dark:text-white">
                            {{ $preset->name() }}
                        </h2>
                        <p class="mt-1 text-sm leading-relaxed text-gray-600 dark:text-gray-300">
                            {{ $preset->tagline() }}
                        </p>
                    </div>

                    <ul class="flex flex-wrap gap-2">
                        @foreach ($preset->tags() as $tag)
                            <li
                                class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-medium text-gray-700 dark:bg-white/10 dark:text-gray-200">
                                {{ $tag }}
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-auto flex flex-wrap items-center gap-3">
                        <button
                            type="button"
                            wire:click="usar('{{ $preset->slug() }}')"
                            wire:loading.attr="disabled"
                            class="inline-flex items-center gap-2 rounded-lg bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-primary-500 disabled:opacity-60"
                        >
                            Usar esta plantilla
                        </button>

                        <a
                            href="{{ $this->previewUrl($preset) }}"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-600 transition hover:text-gray-950 dark:text-gray-300 dark:hover:text-white"
                        >
                            Vista previa
                            <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</x-filament-panels::page>
