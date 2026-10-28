<?php

namespace App\Filament\Resources\PageTemplates\Pages;

use App\Actions\ActivatePageTemplate;
use App\Actions\DuplicatePageTemplateAsGrapesjs;
use App\Filament\Resources\PageTemplates\PageTemplateResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditPageTemplate extends EditRecord
{
    protected static string $resource = PageTemplateResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_by'] = auth()->id();

        /*
         * El formulario solo edita los campos SEO. Conservamos el resto de
         * ajustes de la página (tema y tipografías de la plantilla) para que
         * guardar el SEO no borre el diseño.
         */
        $data['settings'] = array_merge(
            $this->getRecord()->settings ?? [],
            $data['settings'] ?? [],
        );

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->visible(fn (): bool => ! $this->getRecord()->is_active && $this->getRecord()->slug !== 'clasica'),
        ];
    }

    /**
     * Guarda los ajustes del formulario y publica el borrador del constructor.
     */
    public function publish(): void
    {
        $this->save(shouldRedirect: false, shouldSendSavedNotification: false);

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        $this->getRecord()->publish();

        Notification::make()
            ->title('Cambios publicados')
            ->success()
            ->send();
    }

    public function unpublish(): void
    {
        $this->getRecord()->unpublish();

        Notification::make()
            ->title('Página ocultada')
            ->success()
            ->send();
    }

    public function activate(): void
    {
        $this->save(shouldRedirect: false, shouldSendSavedNotification: false);

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        app(ActivatePageTemplate::class)->execute($this->getRecord());

        Notification::make()
            ->title('Página activada')
            ->success()
            ->send();

        $this->redirect(PageTemplateResource::getUrl('index'));
    }

    public function duplicateAsGrapesjs(): void
    {
        $this->save(shouldRedirect: false, shouldSendSavedNotification: false);

        if ($this->getErrorBag()->isNotEmpty()) {
            return;
        }

        $copy = app(DuplicatePageTemplateAsGrapesjs::class)->execute($this->getRecord());

        Notification::make()
            ->title('Página creada')
            ->body('Abriendo el constructor visual...')
            ->success()
            ->send();

        $this->redirect($copy->grapesjsEditorUrl());
    }
}
