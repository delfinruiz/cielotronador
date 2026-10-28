<?php

namespace App\Filament\Resources\Marcas\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class MarcaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255),
                FileUpload::make('logo')
                    ->label('Logo')
                    ->image()
                    ->disk('marcas')
                    ->directory('')
                    ->required()
                    ->maxSize(4096)
                    ->acceptedFileTypes(['image/svg+xml', 'image/png', 'image/jpeg', 'image/webp'])
                    ->helperText('Se muestra en la franja de marcas. Mejor un logo horizontal con fondo transparente.')
                    ->dehydrateStateUsing(fn ($state): ?string => is_array($state) ? ($state[0] ?? null) : $state),
                TextInput::make('url')
                    ->label('Enlace')
                    ->url()
                    ->maxLength(255)
                    ->placeholder('https://sitio-de-la-marca.cl')
                    ->helperText('Opcional. Si se completa, el logo enlaza a ese sitio.'),
                Toggle::make('is_active')
                    ->label('Visible')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger'),
            ]);
    }
}
