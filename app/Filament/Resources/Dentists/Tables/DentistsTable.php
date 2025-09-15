<?php

namespace App\Filament\Resources\Dentists\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DentistsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->getStateUsing(fn ($record) => 'Dr. ' . $record->first_name . ' ' . ($record->middle_name ? $record->middle_name . ' ' : '') . $record->last_name)
                    ->searchable(['first_name', 'middle_name', 'last_name'])
                    ->sortable(),
                    
                TextColumn::make('license_number')
                    ->label('License Number')
                    ->searchable()
                    ->copyable(),
                    
                TextColumn::make('specialization')
                    ->badge()
                    ->color('info')
                    ->placeholder('General Dentist')
                    ->searchable(),
                    
                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->copyable(),
                    
                TextColumn::make('phone_number')
                    ->label('Phone Number')
                    ->searchable()
                    ->copyable(),
                    
                TextColumn::make('gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'male' => 'info',
                        'female' => 'success',
                        'other' => 'warning',
                    }),
                    
                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
