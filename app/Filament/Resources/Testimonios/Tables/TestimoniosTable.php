<?php

namespace App\Filament\Resources\Testimonios\Tables;

use App\Models\Testimonio;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TestimoniosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Autor')
                    ->description(fn (Testimonio $record): ?string => $record->role)
                    ->searchable(),
                TextColumn::make('quote')
                    ->label('Testimonio')
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('rating')
                    ->label('Estrellas')
                    ->placeholder('—')
                    ->sortable(),
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
