<?php

namespace App\Filament\Resources\CategoriaProductos;

use App\Filament\Resources\CategoriaProductos\Pages\CreateCategoriaProducto;
use App\Filament\Resources\CategoriaProductos\Pages\EditCategoriaProducto;
use App\Filament\Resources\CategoriaProductos\Pages\ListCategoriaProductos;
use App\Filament\Resources\CategoriaProductos\Schemas\CategoriaProductoForm;
use App\Filament\Resources\CategoriaProductos\Tables\CategoriaProductosTable;
use App\Models\CategoriaProducto;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CategoriaProductoResource extends Resource
{
    protected static ?string $model = CategoriaProducto::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    /**
     * La tienda se administra desde la sección Tienda del menú lateral.
     */
    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationLabel = 'Categorías';

    protected static ?string $modelLabel = 'Categoría';

    protected static ?string $pluralModelLabel = 'Categorías';

    protected static ?string $recordTitleAttribute = 'nombre';

    protected static ?int $navigationSort = 10;

    public static function form(Schema $schema): Schema
    {
        return CategoriaProductoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriaProductosTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategoriaProductos::route('/'),
            'create' => CreateCategoriaProducto::route('/create'),
            'edit' => EditCategoriaProducto::route('/{record}/edit'),
        ];
    }
}
