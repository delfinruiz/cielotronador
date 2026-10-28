<?php

namespace App\Filament\Resources\CategoriaProductos\Pages;

use App\Filament\Resources\CategoriaProductos\CategoriaProductoResource;
use App\Models\CategoriaProducto;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class ListCategoriaProductos extends ListRecords
{
    protected static string $resource = CategoriaProductoResource::class;

    /**
     * La tabla también se muestra dentro de la sección Tienda. En ese caso
     * se renderiza sin el layout del panel.
     */
    public bool $embedded = false;

    public function mount(bool $embedded = false): void
    {
        $this->embedded = $embedded;

        if ($embedded) {
            Gate::authorize('viewAny', CategoriaProducto::class);
        }

        parent::mount();
    }

    public function getView(): string
    {
        return $this->embedded
            ? 'filament.resources.categoria-productos.pages.list-categoria-productos-embedded'
            : parent::getView();
    }

    public function render(): View
    {
        if ($this->embedded) {
            return view($this->getView(), $this->getViewData());
        }

        return parent::render();
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
