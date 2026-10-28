<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex flex-wrap items-center justify-between gap-3">
            <x-filament::button
                type="button"
                color="gray"
                wire:click="restoreDefaults"
                wire:confirm="¿Restaurar la identidad original y borrar los logos personalizados?"
            >
                Restaurar identidad original
            </x-filament::button>

            <x-filament::button type="submit">
                Guardar cambios
            </x-filament::button>
        </div>
    </form>

    <x-filament::section
        heading="¿Dónde se aplica?"
        description="Estos datos se leen desde la base de datos de esta instalación."
    >
        <ul class="list-disc space-y-2 pl-5 text-sm text-zinc-600 dark:text-zinc-300">
            <li>Menú lateral, login y pie del panel de administración.</li>
            <li>Portada principal, páginas legales y pie de página público.</li>
            <li>Plantillas del constructor: los logos se actualizan sin volver a guardar cada página.</li>
            <li>Metadatos, imagen para redes sociales y favicon del sitio.</li>
        </ul>
        <p class="mt-4 text-sm text-zinc-600 dark:text-zinc-300">
            El aviso de cookies se edita aparte, en <strong>Páginas → Aviso</strong>.
        </p>
    </x-filament::section>
</x-filament-panels::page>
