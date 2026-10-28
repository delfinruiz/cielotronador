<?php

namespace App\Filament\Resources\PageTemplates\Tables;

use App\Actions\ActivatePageTemplate;
use App\Models\PageTemplate;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\URL;

class PageTemplatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Página')
                    ->searchable()
                    ->sortable()
                    ->description(fn (PageTemplate $record): ?string => $record->description),
                TextColumn::make('slug')
                    ->label('URL')
                    ->formatStateUsing(fn (string $state, PageTemplate $record): string => $record->is_active ? 'Portada (/)' : '/'.$state)
                    ->searchable()
                    ->toggleable(),
                IconColumn::make('is_active')
                    ->label('Activa')
                    ->boolean()
                    ->trueIcon(Heroicon::OutlinedCheckCircle)
                    ->falseIcon(Heroicon::OutlinedMinusCircle),
                TextColumn::make('menu_items_count')
                    ->label('Enlaces')
                    ->counts('menuItems')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label('Actualizada')
                    ->since()
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Publicado')
                    ->since()
                    ->placeholder('Sin publicar')
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->label('Creada por')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('is_active', 'desc')
            ->recordActions([
                ActionGroup::make([
                    EditAction::make(),
                    Action::make('design')
                        ->label('Diseñar')
                        ->icon(Heroicon::OutlinedPaintBrush)
                        ->url(fn (PageTemplate $record): string => $record->grapesjsEditorUrl())
                        ->visible(fn (PageTemplate $record): bool => $record->isGrapesjs()),
                    Action::make('preview')
                        ->label('Vista previa (borrador)')
                        ->icon(Heroicon::OutlinedEye)
                        ->tooltip('Muestra el borrador actual aunque no esté publicado. No es la web pública.')
                        ->url(fn (PageTemplate $record): string => URL::temporarySignedRoute(
                            'page-templates.preview',
                            now()->addMinutes(30),
                            ['template' => $record->slug],
                        ))
                        ->openUrlInNewTab(),
                    Action::make('visit')
                        ->label('Ver página publicada')
                        ->icon(Heroicon::OutlinedArrowTopRightOnSquare)
                        ->tooltip('Abre la versión publicada, tal como la ven los visitantes.')
                        ->url(fn (PageTemplate $record): string => $record->publicUrl())
                        ->openUrlInNewTab()
                        ->visible(fn (PageTemplate $record): bool => $record->isGrapesjs() && $record->isPublished()),
                    Action::make('publish')
                        ->label('Publicar cambios')
                        ->icon(Heroicon::OutlinedArrowUpTray)
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Publicar cambios')
                        ->modalDescription('El borrador actual reemplazará el diseño visible en la web.')
                        ->visible(fn (PageTemplate $record): bool => $record->isGrapesjs() && $record->hasDesign())
                        ->action(function (PageTemplate $record): void {
                            $record->publish();

                            Notification::make()
                                ->title('Cambios publicados')
                                ->success()
                                ->send();
                        }),
                    Action::make('unpublish')
                        ->label('Ocultar página')
                        ->icon(Heroicon::OutlinedEyeSlash)
                        ->color('gray')
                        ->requiresConfirmation()
                        ->modalHeading('Ocultar página')
                        ->modalDescription('La página dejará de estar disponible en su URL hasta que vuelvas a publicarla.')
                        ->visible(fn (PageTemplate $record): bool => $record->isGrapesjs() && $record->isPublished() && ! $record->is_active)
                        ->action(function (PageTemplate $record): void {
                            $record->unpublish();

                            Notification::make()
                                ->title('Página ocultada')
                                ->success()
                                ->send();
                        }),
                    Action::make('activate')
                        ->label('Activar')
                        ->icon(Heroicon::OutlinedCheckCircle)
                        ->color('success')
                        ->requiresConfirmation()
                        ->modalHeading('Activar como portada')
                        ->modalDescription('La portada pública cambiará a esta página inmediatamente.')
                        ->visible(fn (PageTemplate $record): bool => ! $record->is_active)
                        ->action(function (PageTemplate $record): void {
                            app(ActivatePageTemplate::class)->execute($record);

                            Notification::make()
                                ->title('Página activada')
                                ->body('La portada ya muestra '.$record->name.'.')
                                ->success()
                                ->send();
                        }),
                    Action::make('duplicate')
                        ->label('Duplicar')
                        ->icon(Heroicon::OutlinedDocumentDuplicate)
                        ->requiresConfirmation()
                        ->action(function (PageTemplate $record): void {
                            $copy = $record->replicate(['is_active', 'published_at']);
                            $copy->name = $record->name.' (copia)';
                            $copy->slug = $record->slug.'-copia-'.strtolower(str()->random(4));
                            $copy->is_active = false;
                            $copy->published_at = null;
                            $copy->created_by = auth()->id();
                            $copy->updated_by = auth()->id();
                            $copy->save();

                            foreach ($record->menuItems()->get() as $item) {
                                $copy->menuItems()->create($item->only([
                                    'location',
                                    'label',
                                    'link_type',
                                    'link_value',
                                    'target',
                                    'is_visible',
                                    'sort_order',
                                ]));
                            }

                            Notification::make()->title('Página duplicada')->success()->send();
                        }),
                    DeleteAction::make()
                        ->visible(fn (PageTemplate $record): bool => ! $record->is_active && $record->slug !== 'clasica'),
                ])
                    ->button()
                    ->label('Acciones')
                    ->icon(Heroicon::OutlinedEllipsisVertical)
                    ->tooltip('Diseñar, publicar, duplicar o borrar')
                    ->color('gray'),
            ]);
    }
}
