<?php

namespace App\Filament\Resources\Galerias\Tables;

use App\Models\Galeria;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GaleriasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('imagen')
                    ->label('Imagen')
                    ->getStateUsing(fn (Galeria $record): string => asset('img/galeria/'.$record->imagen))
                    ->square()
                    ->size(56),
                TextColumn::make('titulo')
                    ->label('Título')
                    ->searchable()
                    ->placeholder('—')
                    ->wrap()
                    ->limit(50),
                IconColumn::make('published')
                    ->label('Publicada')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
                ViewAction::make()
                    ->modalHeading('')
                    ->modalWidth('4xl')
                    ->schema([])
                    ->modalContent(fn (Galeria $record) => view('filament.galeria.preview', ['foto' => $record])),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
