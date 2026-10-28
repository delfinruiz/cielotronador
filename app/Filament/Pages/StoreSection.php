<?php

namespace App\Filament\Pages;

use App\Filament\Resources\CategoriaProductos\CategoriaProductoResource;
use App\Filament\Resources\Productos\ProductoResource;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use Livewire\Attributes\Url;
use UnitEnum;

/**
 * Tienda como sección propia dentro del grupo Contenido. Reutiliza las
 * tablas de Productos y Categorías y puede abrirse sin permiso sobre las
 * páginas.
 */
class StoreSection extends Page
{
    protected string $view = 'filament.pages.store-section';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?string $navigationLabel = 'Tienda';

    protected static ?string $title = 'Tienda';

    protected static ?string $slug = 'tienda';

    protected static ?int $navigationSort = 8;

    #[Url(as: 'tienda')]
    public string $tiendaTab = 'productos';

    public function mount(): void
    {
        if (! in_array($this->tiendaTab, ['productos', 'categorias'], true)) {
            $this->tiendaTab = 'productos';
        }

        if ($this->tiendaTab === 'productos' && ! ProductoResource::canViewAny()) {
            $this->tiendaTab = 'categorias';
        }

        if ($this->tiendaTab === 'categorias' && ! CategoriaProductoResource::canViewAny()) {
            $this->tiendaTab = 'productos';
        }
    }

    public static function canAccess(): bool
    {
        return ProductoResource::canViewAny() || CategoriaProductoResource::canViewAny();
    }
}
