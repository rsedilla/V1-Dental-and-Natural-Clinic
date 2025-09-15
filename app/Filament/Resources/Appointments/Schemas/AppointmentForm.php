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
                    ->relationship(
                        name: 'patient',
                        titleAttribute: 'first_name',
                        modifyQueryUsing: fn ($query) => $query->orderBy('first_name')->orderBy('last_name')
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->full_name)
                    ->searchable(['first_name', 'last_name', 'middle_name', 'email', 'phone_number'])
                    ->preload(false)
                    ->required()
                    ->placeholder('Search and select a patient...')
                    ->helperText('Type patient name, email, or phone to search')
                    ->native(false),
                    
                Select::make('dentist_id')
                    ->label('Dentist')
                    ->relationship(
                        name: 'dentist',
                        titleAttribute: 'first_name',
                        modifyQueryUsing: fn ($query) => $query->orderBy('first_name')->orderBy('last_name')
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => "Dr. {$record->full_name}")
                    ->preload()
                    ->required()
                    ->placeholder('Select a dentist...')
                    ->native(false),
                    
                Select::make('appointment_type_id')
                    ->label('Appointment Type')
                    ->relationship(
                        name: 'appointmentType',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->orderBy('name')
                    )
                    ->preload()
                    ->required()
                    ->placeholder('Select appointment type...')
                    ->native(false),
                    
                Select::make('status_id')
                    ->label('Status')
                    ->relationship(
                        name: 'status',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->orderBy('name')
                    )
                    ->getOptionLabelFromRecordUsing(fn ($record) => ucfirst($record->name))
                    ->preload()
                    ->required()
                    ->default(function () {
                        return \App\Models\AppointmentStatus::where('name', 'scheduled')->first()?->id;
                    })
                    ->placeholder('Select status...')
                    ->native(false),
                    
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
