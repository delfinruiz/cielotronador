<?php

namespace App\Filament\Resources\Noticias;

use App\Filament\Resources\Noticias\Pages\CreateNoticia;
use App\Filament\Resources\Noticias\Pages\EditNoticia;
use App\Filament\Resources\Noticias\Pages\ListNoticias;
use App\Filament\Resources\Noticias\Pages\ViewNoticia;
use App\Filament\Resources\Noticias\Schemas\NoticiaForm;
use App\Filament\Resources\Noticias\Tables\NoticiasTable;
use App\Models\Noticia;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NoticiaResource extends Resource
{
    protected static ?string $model = Noticia::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedNewspaper;

    protected static ?string $navigationLabel = 'Noticias';

    protected static ?string $modelLabel = 'Noticia';

    protected static ?string $pluralModelLabel = 'Noticias';

    protected static ?string $recordTitleAttribute = 'titulo';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return NoticiaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NoticiasTable::configure($table);
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
            'index' => ListNoticias::route('/'),
            'create' => CreateNoticia::route('/create'),
            'edit' => EditNoticia::route('/{record}/edit'),
            'view' => ViewNoticia::route('/{record}'),
        ];
    }
}
