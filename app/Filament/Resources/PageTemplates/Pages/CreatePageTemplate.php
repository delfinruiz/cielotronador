<?php

namespace App\Filament\Resources\PageTemplates\Pages;

use App\Filament\Resources\PageTemplates\PageTemplateResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePageTemplate extends CreateRecord
{
    protected static string $resource = PageTemplateResource::class;

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['render_mode'] = 'grapesjs';
        $data['created_by'] = auth()->id();
        $data['updated_by'] = auth()->id();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        $record = $this->getRecord();

        if ($record?->isGrapesjs()) {
            return $record->grapesjsEditorUrl();
        }

        return parent::getRedirectUrl();
    }
}
