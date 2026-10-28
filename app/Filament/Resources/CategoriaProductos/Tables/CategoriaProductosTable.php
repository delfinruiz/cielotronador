<?php

namespace App\Filament\Resources\CategoriaProductos\Tables;

use App\Models\CategoriaProducto;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class CategoriaProductosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nombre')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->description(fn (CategoriaProducto $record): ?string => $record->descripcion),
                TextColumn::make('slug')
                    ->label('Identificador')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('productos_count')
                    ->label('Artículos')
                    ->counts('productos')
                    ->sortable()
                    ->alignEnd(),
                ToggleColumn::make('publicada')
                    ->label('Publicada')
                    ->disabled(fn (CategoriaProducto $record): bool => ! auth()->user()?->can('update', $record)),
                TextColumn::make('orden')
                    ->label('Orden')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('orden')
            ->reorderable('orden')
            ->filters([
                TernaryFilter::make('publicada')
                    ->label('Publicada')
                    ->trueLabel('Solo publicadas')
                    ->falseLabel('Solo ocultas')
                    ->placeholder('Todas'),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ])->label('Acciones'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
