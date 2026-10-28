<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::tabs label="Contenido de páginas">
            <x-filament::tabs.item
                data-pestana="paginas"
                :active="$pestana === 'paginas'"
                wire:click="$set('pestana', 'paginas')"
            >
                Páginas
            </x-filament::tabs.item>

            @if ($this->puedeVerAviso())
                <x-filament::tabs.item
                    data-pestana="aviso"
                    :active="$pestana === 'aviso'"
                    wire:click="$set('pestana', 'aviso')"
                >
                    Aviso
                </x-filament::tabs.item>
            @endif
        </x-filament::tabs>

        @if ($pestana === 'paginas')
            {{ $this->table }}
        @elseif ($pestana === 'aviso' && $this->puedeVerAviso())
            @livewire(\App\Filament\Pages\CookieBannerSettings::class, ['embedded' => true], key('cookie-banner-embedded'))
        @endif
    </div>
</x-filament-panels::page>
