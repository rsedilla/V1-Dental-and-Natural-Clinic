<?php

namespace App\Filament\Resources\Patients\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PatientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(2)
            ->components([
                TextInput::make('first_name')
                    ->label('First Name')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('middle_name')
                    ->label('Middle Name')
                    ->maxLength(255),
                    
                TextInput::make('last_name')
                    ->label('Last Name')
                    ->required()
                    ->maxLength(255),
                    
                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                    
                TextInput::make('phone_number')
                    ->label('Phone Number')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                    
                DatePicker::make('birthday')
                    ->label('Date of Birth')
                    ->required()
                    ->native(false)
                    ->displayFormat('M d, Y')
                    ->maxDate(now()),
                    
                Select::make('gender')
                    ->label('Gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other'
                    ])
                    ->required(),
                    
                Textarea::make('present_address')
                    ->label('Present Address')
                    ->required()
                    ->rows(2)
                    ->columnSpanFull(),
                    
                Textarea::make('medical_history')
                    ->label('Medical History')
                    ->rows(3)
                    ->placeholder('Enter any relevant medical history, allergies, current medications, etc.')
                    ->columnSpanFull(),
                    
                TextInput::make('emergency_contact_name')
                    ->label('Emergency Contact Name')
                    ->maxLength(255),
                    
                TextInput::make('emergency_contact_phone')
                    ->label('Emergency Contact Phone')
                    ->tel()
                    ->maxLength(20),
            ]);
    }
}
