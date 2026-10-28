<div class="space-y-6">
    <form wire:submit="save" class="space-y-6">
        {{ $this->form }}

        <div class="flex flex-wrap items-center justify-between gap-3">
            <x-filament::button
                type="button"
                color="gray"
                wire:click="restoreDefaults"
                wire:confirm="¿Restaurar los textos originales del aviso?"
            >
                Restaurar texto por defecto
            </x-filament::button>

            <x-filament::button type="submit">
                Guardar aviso
            </x-filament::button>
        </div>
    </form>
</div>
