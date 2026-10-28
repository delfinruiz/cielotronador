<?php

namespace App\Filament\Resources\Productos\Tables;

use App\Models\Producto;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ProductosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('imagen')
                    ->label('Imagen')
                    ->getStateUsing(fn (Producto $record): string => $record->imagenUrl())
                    ->square()
                    ->size(56),
                TextColumn::make('nombre')
                    ->label('Artículo')
                    ->searchable()
                    ->sortable()
                    ->wrap()
                    ->limit(60)
                    ->description(fn (Producto $record): ?string => $record->categoria?->nombre),
                TextColumn::make('precio')
                    ->label('Precio')
                    ->getStateUsing(fn (Producto $record): string => $record->precioFormateado())
                    ->sortable()
                    ->alignEnd(),
                TextColumn::make('moneda')
                    ->label('Moneda')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('etiqueta')
                    ->label('Distintivo')
                    ->badge()
                    ->color('warning')
                    ->placeholder('—')
                    ->toggleable(),
                ToggleColumn::make('publicado')
                    ->label('Publicado')
                    ->disabled(fn (Producto $record): bool => ! auth()->user()?->can('update', $record)),
                TextColumn::make('orden')
                    ->label('Orden')
                    ->numeric()
                    ->sortable(),
            ])
            ->defaultSort('orden')
            ->reorderable('orden')
            ->filters([
                SelectFilter::make('categoria_id')
                    ->label('Categoría')
                    ->relationship('categoria', 'nombre')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('publicado')
                    ->label('Publicado')
                    ->trueLabel('Solo publicados')
                    ->falseLabel('Solo borradores')
                    ->placeholder('Todos'),
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('preview')
                        ->label('Vista previa')
                        ->icon(Heroicon::OutlinedEye)
                        ->modalHeading(fn (Producto $record): string => $record->nombre)
                        ->modalContent(fn (Producto $record) => view('filament.producto.preview', ['producto' => $record]))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Cerrar'),
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
