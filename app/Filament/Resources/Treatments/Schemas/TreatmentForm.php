<?php

namespace App\Filament\Resources\Treatments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class TreatmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('appointment_id')
                    ->label('Appointment')
                    ->relationship('appointment', 'id')
                    ->getOptionLabelFromRecordUsing(function ($record) {
                        $patient = $record->patient;
                        $dentist = $record->dentist;
                        return $patient->first_name . ' ' . $patient->last_name . 
                               ' - Dr. ' . $dentist->first_name . ' ' . $dentist->last_name . 
                               ' (' . $record->appointment_date->format('M d, Y') . ')';
                    })
                    ->searchable()
                    ->preload()
                    ->required(),
                    
                Select::make('treatment_type_id')
                    ->label('Treatment Type')
                    ->relationship('treatmentType', 'name')
                    ->preload()
                    ->required(),
                    
                TextInput::make('tooth_number')
                    ->label('Tooth Number')
                    ->placeholder('e.g., 1, 2, 3...')
                    ->maxLength(10),
                    
                DatePicker::make('treatment_date')
                    ->label('Treatment Date')
                    ->native(false)
                    ->displayFormat('M d, Y')
                    ->default(now())
                    ->required(),
                    
                TextInput::make('cost')
                    ->label('Cost (PHP)')
                    ->required()
                    ->numeric()
                    ->prefix('₱')
                    ->step(0.01)
                    ->minValue(0),
                    
                Select::make('performed_by')
                    ->label('Performed By')
                    ->relationship('performedBy', 'first_name')
                    ->getOptionLabelFromRecordUsing(fn ($record) => 'Dr. ' . $record->first_name . ' ' . ($record->middle_name ? $record->middle_name . ' ' : '') . $record->last_name)
                    ->searchable(['first_name', 'last_name'])
                    ->preload()
                    ->required(),
                    
                Textarea::make('description')
                    ->label('Description')
                    ->required()
                    ->rows(3)
                    ->placeholder('Detailed description of the treatment performed...')
                    ->columnSpanFull(),
                    
                Textarea::make('notes')
                    ->label('Additional Notes')
                    ->rows(2)
                    ->placeholder('Any additional notes or observations...')
                    ->columnSpanFull(),
            ]);
    }
}
