<?php

namespace App\Filament\Resources\Galerias;

use App\Filament\Resources\Galerias\Pages\ManageGalerias;
use App\Filament\Resources\Galerias\Schemas\GaleriaForm;
use App\Filament\Resources\Galerias\Tables\GaleriasTable;
use App\Models\Galeria;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GaleriaResource extends Resource
{
    protected static ?string $model = Galeria::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPhoto;

    protected static ?string $navigationLabel = 'Galería';

    protected static ?string $modelLabel = 'Fotografía';

    protected static ?string $pluralModelLabel = 'Galería';

    protected static ?string $recordTitleAttribute = 'titulo';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return GaleriaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GaleriasTable::configure($table);
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
            'index' => ManageGalerias::route('/'),
        ];
    }
}
