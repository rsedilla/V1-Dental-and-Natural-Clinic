<?php

namespace App\Filament\Resources\Dentists\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DentistForm
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
                    
                TextInput::make('license_number')
                    ->label('License Number')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50),
                    
                TextInput::make('phone_number')
                    ->label('Phone Number')
                    ->tel()
                    ->required()
                    ->maxLength(20),
                    
                TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                    
                TextInput::make('specialization')
                    ->label('Specialization')
                    ->placeholder('e.g., Orthodontist, Endodontist, etc.')
                    ->maxLength(255),
                    
                Select::make('gender')
                    ->label('Gender')
                    ->options([
                        'male' => 'Male',
                        'female' => 'Female',
                        'other' => 'Other'
                    ])
                    ->required(),
                    
                Textarea::make('address')
                    ->label('Address')
                    ->required()
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
