<?php

namespace App\Filament\Resources\Galerias\Pages;

use App\Filament\Resources\Galerias\GaleriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageGalerias extends ManageRecords
{
    protected static string $resource = GaleriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Publicar fotografía')
                ->modalHeading('Publicar fotografía'),
        ];
    }
}
