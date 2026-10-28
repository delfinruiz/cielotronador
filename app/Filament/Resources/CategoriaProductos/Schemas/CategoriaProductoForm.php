<?php

namespace App\Filament\Resources\CategoriaProductos\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CategoriaProductoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Categoría')
                    ->description('Agrupa los artículos de la tienda y alimenta los filtros de la sección de ventas.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('nombre')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(60)
                            ->placeholder('Indumentaria')
                            ->helperText('El identificador para la web se genera automáticamente.')
                            ->columnSpanFull(),
                        Textarea::make('descripcion')
                            ->label('Descripción')
                            ->rows(3)
                            ->maxLength(300)
                            ->helperText('Opcional. Solo se usa en el panel.')
                            ->columnSpanFull(),
                        Toggle::make('publicada')
                            ->label('Publicada')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->helperText('Si se desactiva, sus artículos no aparecen en la sección de ventas.'),
                        TextInput::make('orden')
                            ->label('Orden')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Menor número aparece primero en los filtros.'),
                    ]),
            ]);
    }
}
