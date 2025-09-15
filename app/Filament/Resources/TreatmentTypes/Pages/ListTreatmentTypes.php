<?php

namespace App\Filament\Resources\TreatmentTypes\Pages;

use App\Filament\Resources\TreatmentTypes\TreatmentTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTreatmentTypes extends ListRecords
{
    protected static string $resource = TreatmentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
