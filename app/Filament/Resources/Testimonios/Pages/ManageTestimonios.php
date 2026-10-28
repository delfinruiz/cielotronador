<?php

namespace App\Filament\Resources\Testimonios\Pages;

use App\Filament\Resources\Testimonios\TestimonioResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageTestimonios extends ManageRecords
{
    protected static string $resource = TestimonioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Agregar testimonio')
                ->modalHeading('Nuevo testimonio'),
        ];
    }
}
