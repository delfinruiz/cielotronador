<?php

namespace App\Filament\Resources\Faqs\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('question')
                    ->label('Pregunta')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('¿Cómo puedo pedir una cotización?'),
                Textarea::make('answer')
                    ->label('Respuesta')
                    ->required()
                    ->rows(4)
                    ->maxLength(2000)
                    ->helperText('Se muestra al abrir la pregunta.'),
                Toggle::make('is_active')
                    ->label('Visible')
                    ->default(true)
                    ->onColor('success')
                    ->offColor('danger')
                    ->helperText('Solo las preguntas visibles aparecen en las páginas.'),
            ]);
    }
}
