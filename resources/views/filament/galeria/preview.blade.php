<figure>
    <div class="flex w-full items-center justify-center overflow-hidden rounded-xl bg-zinc-950/90 p-4">
        <img
            src="{{ asset('img/galeria/'.$foto->imagen) }}"
            alt="{{ $foto->titulo }}"
            class="max-h-[70vh] max-w-full rounded-lg object-contain shadow-2xl"
        >
    </div>

    <figcaption class="mt-3 text-left text-sm font-medium text-zinc-500 dark:text-zinc-300">
        {{ $foto->titulo }}
    </figcaption>
</figure>
