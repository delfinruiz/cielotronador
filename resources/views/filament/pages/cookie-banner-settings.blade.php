<x-filament-panels::page>
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex w-full" style="justify-content: flex-end;">
            <x-filament::button type="submit">
                Guardar aviso
            </x-filament::button>
        </div>
    </form>

    <x-filament::section heading="¿Dónde se muestra?" description="El aviso aparece en la parte inferior de la página hasta que la persona elige una opción. Su elección se guarda en el navegador.">
        <p class="text-sm text-zinc-600 dark:text-zinc-300">
            Se incluye en todas las plantillas, en la portada clásica y en las páginas legales. No es un bloque
            del constructor y no se puede borrar ni ocultar desde el editor.
        </p>
        <p class="mt-3 text-sm">
            <a href="{{ route('politica.cookies') }}" target="_blank" rel="noopener" class="font-semibold text-primary-600 hover:underline dark:text-primary-400">
                Ver la Política de Cookies
            </a>
        </p>
    </x-filament::section>
</x-filament-panels::page>
