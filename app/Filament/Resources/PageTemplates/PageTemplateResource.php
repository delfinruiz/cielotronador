<?php

namespace App\Filament\Resources\PageTemplates;

use App\Filament\Resources\PageTemplates\Pages\CreatePageTemplate;
use App\Filament\Resources\PageTemplates\Pages\EditPageTemplate;
use App\Filament\Resources\PageTemplates\Pages\ListPageTemplates;
use App\Filament\Resources\PageTemplates\RelationManagers\PageTemplateVersionsRelationManager;
use App\Filament\Resources\PageTemplates\Schemas\PageTemplateForm;
use App\Filament\Resources\PageTemplates\Tables\PageTemplatesTable;
use App\Models\PageTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PageTemplateResource extends Resource
{
    protected static ?string $model = PageTemplate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaintBrush;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?string $navigationLabel = 'Páginas';

    protected static ?string $modelLabel = 'Página';

    protected static ?string $pluralModelLabel = 'Páginas';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return PageTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PageTemplatesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            PageTemplateVersionsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPageTemplates::route('/'),
            'create' => CreatePageTemplate::route('/create'),
            'edit' => EditPageTemplate::route('/{record}/edit'),
        ];
    }
}
