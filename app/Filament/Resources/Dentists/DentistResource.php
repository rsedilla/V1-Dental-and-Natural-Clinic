<?php

namespace App\Filament\Resources\Dentists;

use App\Filament\Resources\Dentists\Pages\CreateDentist;
use App\Filament\Resources\Dentists\Pages\EditDentist;
use App\Filament\Resources\Dentists\Pages\ListDentists;
use App\Filament\Resources\Dentists\Schemas\DentistForm;
use App\Filament\Resources\Dentists\Tables\DentistsTable;
use App\Models\Dentist;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DentistResource extends Resource
{
    protected static ?string $model = Dentist::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;
    
    protected static ?string $navigationLabel = 'Dentists';
    
    protected static ?string $modelLabel = 'Dentist';
    
    protected static ?string $pluralModelLabel = 'Dentists';
    
    protected static ?int $navigationSort = 7;

    public static function form(Schema $schema): Schema
    {
        return DentistForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DentistsTable::configure($table);
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
            'index' => ListDentists::route('/'),
            'create' => CreateDentist::route('/create'),
            'edit' => EditDentist::route('/{record}/edit'),
        ];
    }
}
