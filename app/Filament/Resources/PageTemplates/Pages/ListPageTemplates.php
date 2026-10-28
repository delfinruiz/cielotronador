<?php

namespace App\Filament\Resources\PageTemplates\Pages;

use App\Filament\Pages\CookieBannerSettings;
use App\Filament\Pages\TemplateGallery;
use App\Filament\Resources\PageTemplates\PageTemplateResource;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Livewire\Attributes\Url;

class ListPageTemplates extends ListRecords
{
    protected static string $resource = PageTemplateResource::class;

    protected string $view = 'filament.resources.page-templates.pages.list-page-templates';

    /**
     * Pestañas de la sección Páginas. El estado vive en la URL para que
     * los enlaces del editor puedan abrir directamente Aviso.
     */
    #[Url(as: 'pestana')]
    public string $pestana = 'paginas';

    public function mount(): void
    {
        parent::mount();

        if (! in_array($this->pestana, ['paginas', 'aviso'], true)) {
            $this->pestana = 'paginas';
        }

        if ($this->pestana === 'aviso' && ! $this->puedeVerAviso()) {
            $this->pestana = 'paginas';
        }
    }

    public function getTitle(): string|Htmlable
    {
        return match ($this->pestana) {
            'aviso' => 'Aviso de cookies',
            default => parent::getTitle(),
        };
    }

    public function puedeVerAviso(): bool
    {
        return CookieBannerSettings::canAccess();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('plantillas')
                ->label('Plantillas')
                ->icon(Heroicon::OutlinedRectangleStack)
                ->color('gray')
                ->url(TemplateGallery::getUrl())
                ->visible(fn (): bool => $this->pestana === 'paginas' && TemplateGallery::canAccess()),
            CreateAction::make()
                ->label('Nueva plantilla')
                ->visible(fn (): bool => $this->pestana === 'paginas'),
        ];
    }
}
