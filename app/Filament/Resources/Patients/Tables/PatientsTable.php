<?php

namespace App\Filament\Resources\Patients\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PatientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Full Name')
                    ->getStateUsing(fn ($record) => $record->first_name . ' ' . ($record->middle_name ? $record->middle_name . ' ' : '') . $record->last_name)
                    ->searchable(['first_name', 'middle_name', 'last_name'])
                    ->sortable(),
                    
                TextColumn::make('email')
                    ->label('Email Address')
                    ->searchable()
                    ->copyable(),
                    
                TextColumn::make('phone_number')
                    ->label('Phone Number')
                    ->searchable()
                    ->copyable(),
                    
                TextColumn::make('birthday')
                    ->label('Date of Birth')
                    ->date('M d, Y')
                    ->sortable(),
                    
                TextColumn::make('gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'male' => 'info',
                        'female' => 'success',
                        'other' => 'warning',
                    }),
                    
                TextColumn::make('age')
                    ->getStateUsing(fn ($record) => $record->birthday ? now()->diffInYears($record->birthday) . ' years' : 'N/A')
                    ->sortable(),
                    
                TextColumn::make('created_at')
                    ->label('Registered')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
