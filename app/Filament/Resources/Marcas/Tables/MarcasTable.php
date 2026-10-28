<?php

namespace App\Filament\Resources\Marcas\Tables;

use App\Models\Marca;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class MarcasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->getStateUsing(fn (Marca $record): string => $record->logoUrl())
                    ->height(36),
                TextColumn::make('name')
                    ->label('Marca')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('url')
                    ->label('Enlace')
                    ->placeholder('—')
                    ->limit(40)
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Visible')
                    ->boolean(),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
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
