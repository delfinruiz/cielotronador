<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;

class ConstructorHelp extends Page
{
    protected string $view = 'filament.pages.constructor-help';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedQuestionMarkCircle;

    protected static string|\UnitEnum|null $navigationGroup = 'Contenido';

    protected static ?string $navigationLabel = 'Guía del constructor';

    protected static ?string $title = 'Guía del constructor';

    protected static ?int $navigationSort = 6;

    /**
     * La guía sigue disponible desde la pestaña Contenido de cada plantilla;
     * no necesita una entrada propia en el menú lateral.
     */
    protected static bool $shouldRegisterNavigation = false;

    public static function canAccess(): bool
    {
        return auth()->user()?->can('ViewAny:PageTemplate') ?? false;
    }
}
