<?php

namespace App\Filament\Resources\Galerias\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GaleriaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('titulo')
                    ->label('Título')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Se muestra al ampliar la foto'),
                FileUpload::make('imagen')
                    ->label('Imagen')
                    ->image()
                    ->disk('galeria')
                    ->directory('')
                    ->required()
                    ->maxSize(10240)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->dehydrateStateUsing(fn ($state): ?string => is_array($state) ? ($state[0] ?? null) : $state),
                Toggle::make('published')
                    ->label('Publicada')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->helperText('Solo las fotografías publicadas aparecen en la página principal.'),
            ]);
    }
}
