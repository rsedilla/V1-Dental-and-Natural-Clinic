<?php

namespace App\Filament\Resources\TreatmentTypes;

use App\Filament\Resources\TreatmentTypes\Pages\CreateTreatmentType;
use App\Filament\Resources\TreatmentTypes\Pages\EditTreatmentType;
use App\Filament\Resources\TreatmentTypes\Pages\ListTreatmentTypes;
use App\Filament\Resources\TreatmentTypes\Schemas\TreatmentTypeForm;
use App\Filament\Resources\TreatmentTypes\Tables\TreatmentTypesTable;
use App\Models\TreatmentType;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TreatmentTypeResource extends Resource
{
    protected static ?string $model = TreatmentType::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TreatmentTypeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TreatmentTypesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTreatmentTypes::route('/'),
            'create' => CreateTreatmentType::route('/create'),
            'edit' => EditTreatmentType::route('/{record}/edit'),
        ];
    }
}
