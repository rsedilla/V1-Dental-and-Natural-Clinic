<?php

namespace App\Filament\Resources\Appointments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Schemas\Schema;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                Select::make('patient_id')
                    ->label('Patient')
                    ->relationship('patient', 'first_name', function ($query) {
                        return $query->selectRaw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) as full_name, id")
                            ->orderBy('first_name');
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->first_name . ' ' . ($record->middle_name ? $record->middle_name . ' ' : '') . $record->last_name)
                    ->searchable(['first_name', 'last_name'])
                    ->required()
                    ->preload(),
                    
                Select::make('dentist_id')
                    ->label('Dentist')
                    ->relationship('dentist', 'first_name', function ($query) {
                        return $query->selectRaw("CONCAT(first_name, ' ', COALESCE(middle_name, ''), ' ', last_name) as full_name, id")
                            ->orderBy('first_name');
                    })
                    ->getOptionLabelFromRecordUsing(fn ($record) => 'Dr. ' . $record->first_name . ' ' . ($record->middle_name ? $record->middle_name . ' ' : '') . $record->last_name)
                    ->searchable(['first_name', 'last_name'])
                    ->required()
                    ->preload(),
                    
                Select::make('appointment_type_id')
                    ->label('Appointment Type')
                    ->relationship('appointmentType', 'name')
                    ->required()
                    ->preload(),
                    
                Select::make('status_id')
                    ->label('Status')
                    ->relationship('status', 'name')
                    ->required()
                    ->preload()
                    ->default(function () {
                        return \App\Models\AppointmentStatus::where('name', 'Scheduled')->first()?->id;
                    }),
                    
                DateTimePicker::make('appointment_date')
                    ->label('Appointment Date & Time')
                    ->required()
                    ->native(false)
                    ->displayFormat('M d, Y - h:i A')
                    ->minDate(now())
                    ->columnSpanFull(),
                    
                Textarea::make('notes')
                    ->label('Notes')
                    ->rows(3)
                    ->placeholder('Enter any additional notes for this appointment...')
                    ->columnSpanFull(),
            ]);
    }
}
