<?php

namespace App\Filament\Resources\Marcas;

use App\Filament\Resources\Marcas\Pages\ManageMarcas;
use App\Filament\Resources\Marcas\Schemas\MarcaForm;
use App\Filament\Resources\Marcas\Tables\MarcasTable;
use App\Models\Marca;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MarcaResource extends Resource
{
    protected static ?string $model = Marca::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingStorefront;

    protected static string|UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?string $navigationLabel = 'Marcas';

    protected static ?string $modelLabel = 'Marca';

    protected static ?string $pluralModelLabel = 'Marcas';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 6;

    public static function form(Schema $schema): Schema
    {
        return MarcaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MarcasTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageMarcas::route('/'),
        ];
    }
}
