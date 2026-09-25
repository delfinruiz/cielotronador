<?php

namespace App\Filament\Resources\Noticias\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NoticiaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Contenido')
                    ->description('Datos que se muestran en la tarjeta y el detalle de la noticia.')
                    ->schema([
                        TextInput::make('titulo')
                            ->label('Título')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        DatePicker::make('fecha')
                            ->label('Fecha')
                            ->required()
                            ->native(false)
                            ->displayFormat('d/m/Y')
                            ->format('Y-m-d'),
                        Toggle::make('published')
                            ->label('Publicada')
                            ->default(true)
                            ->onColor('success')
                            ->offColor('danger')
                            ->helperText('Solo las noticias publicadas aparecen en la página principal.'),
                        FileUpload::make('imagen')
                            ->label('Imagen')
                            ->image()
                            ->disk('noticias')
                            ->directory('')
                            ->required()
                            ->maxSize(5120)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->dehydrateStateUsing(fn ($state): ?string => is_array($state) ? ($state[0] ?? null) : $state)
                            ->columnSpanFull(),
                        Textarea::make('extracto')
                            ->label('Extracto')
                            ->required()
                            ->rows(3)
                            ->maxLength(1000)
                            ->helperText('Resumen breve que se ve en la tarjeta de la noticia.')
                            ->columnSpanFull(),
                        Repeater::make('cuerpo')
                            ->label('Cuerpo')
                            ->schema([
                                Textarea::make('parrafo')
                                    ->label('Párrafo')
                                    ->rows(4)
                                    ->required(),
                            ])
                            ->addActionLabel('Agregar párrafo')
                            ->collapsible()
                            ->collapsed()
                            ->reorderable()
                            ->defaultItems(1)
                            ->minItems(1)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
