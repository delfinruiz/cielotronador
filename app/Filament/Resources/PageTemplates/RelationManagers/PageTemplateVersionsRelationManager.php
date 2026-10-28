<?php

namespace App\Filament\Resources\PageTemplates\RelationManagers;

use App\Models\PageTemplate;
use App\Models\PageTemplateVersion;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class PageTemplateVersionsRelationManager extends RelationManager
{
    protected static string $relationship = 'versions';

    protected static ?string $title = 'Versiones publicadas';

    protected static ?string $modelLabel = 'versión';

    protected static bool $isLazy = false;

    public static function canViewForRecord(Model $ownerRecord, string $pageClass): bool
    {
        return $ownerRecord instanceof PageTemplate && $ownerRecord->isGrapesjs();
    }

    public function isReadOnly(): bool
    {
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->columns([
                TextColumn::make('id')
                    ->label('#')
                    ->sortable(),
                TextColumn::make('published_at')
                    ->label('Publicada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
                TextColumn::make('creator.name')
                    ->label('Por')
                    ->placeholder('Sistema'),
            ])
            ->defaultSort('published_at', 'desc')
            ->recordActions([
                Action::make('preview')
                    ->label('Vista previa')
                    ->icon(Heroicon::OutlinedEye)
                    ->url(fn (PageTemplateVersion $record): string => URL::temporarySignedRoute(
                        'page-templates.preview-version',
                        now()->addMinutes(30),
                        [
                            'template' => $this->getOwnerRecord()->slug,
                            'version' => $record->getKey(),
                        ],
                    ))
                    ->openUrlInNewTab(),
                Action::make('restore')
                    ->label('Restaurar')
                    ->icon(Heroicon::OutlinedArrowUturnLeft)
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Restaurar versión')
                    ->modalDescription('El diseño de esta versión se restaurará y se publicará de inmediato en la web.')
                    ->action(function (PageTemplateVersion $record): void {
                        /** @var PageTemplate $template */
                        $template = $this->getOwnerRecord();

                        $template->restoreVersion($record);

                        Notification::make()
                            ->title('Versión restaurada y publicada')
                            ->success()
                            ->send();
                    }),
            ]);
    }
}
