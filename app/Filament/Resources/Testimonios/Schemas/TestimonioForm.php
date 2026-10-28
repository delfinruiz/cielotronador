<?php

namespace App\Filament\Resources\Testimonios\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TestimonioForm
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
                TextInput::make('role')
                    ->label('Cargo o empresa')
                    ->maxLength(255)
                    ->placeholder('Fundadora de Nube Café'),
                Textarea::make('quote')
                    ->label('Testimonio')
                    ->required()
                    ->rows(4)
                    ->maxLength(1000),
                FileUpload::make('avatar')
                    ->label('Fotografía (opcional)')
                    ->image()
                    ->disk('testimonios')
                    ->directory('')
                    ->maxSize(4096)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                    ->helperText('Si no se sube, se muestran las iniciales del nombre.')
                    ->dehydrateStateUsing(fn ($state): ?string => is_array($state) ? ($state[0] ?? null) : $state),
                Select::make('rating')
                    ->label('Valoración')
                    ->options([
                        5 => '5 estrellas',
                        4 => '4 estrellas',
                        3 => '3 estrellas',
                        2 => '2 estrellas',
                        1 => '1 estrella',
                    ])
                    ->placeholder('Sin valoración'),
                Toggle::make('is_active')
                    ->label('Visible')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger'),
            ]);
    }
}
