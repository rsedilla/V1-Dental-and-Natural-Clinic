<?php

namespace App\Filament\Resources\TreatmentTypes\Pages;

use App\Filament\Resources\TreatmentTypes\TreatmentTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTreatmentType extends EditRecord
{
    protected static string $resource = TreatmentTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
