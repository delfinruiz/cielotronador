<div class="space-y-4">
    <x-filament::tabs label="Tienda">
        @if (\App\Filament\Resources\Productos\ProductoResource::canViewAny())
            <x-filament::tabs.item
                data-tienda="productos"
                :active="$tiendaTab === 'productos'"
                wire:click="$set('tiendaTab', 'productos')"
            >
                Productos
            </x-filament::tabs.item>
        @endif

        @if (\App\Filament\Resources\CategoriaProductos\CategoriaProductoResource::canViewAny())
            <x-filament::tabs.item
                data-tienda="categorias"
                :active="$tiendaTab === 'categorias'"
                wire:click="$set('tiendaTab', 'categorias')"
            >
                Categorías
            </x-filament::tabs.item>
        @endif
    </x-filament::tabs>

    @if ($tiendaTab === 'productos' && \App\Filament\Resources\Productos\ProductoResource::canViewAny())
        @livewire(\App\Filament\Resources\Productos\Pages\ListProductos::class, ['embedded' => true], key('productos-embedded'))
    @elseif ($tiendaTab === 'categorias' && \App\Filament\Resources\CategoriaProductos\CategoriaProductoResource::canViewAny())
        @livewire(\App\Filament\Resources\CategoriaProductos\Pages\ListCategoriaProductos::class, ['embedded' => true], key('categorias-embedded'))
    @endif
</div>
