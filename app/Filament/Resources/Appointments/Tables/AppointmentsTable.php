<?php

namespace App\Filament\Resources\Appointments\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient_name')
                    ->label('Patient')
                    ->getStateUsing(fn ($record) => $record->patient ? $record->patient->first_name . ' ' . ($record->patient->middle_name ? $record->patient->middle_name . ' ' : '') . $record->patient->last_name : 'N/A')
                    ->searchable(['patient.first_name', 'patient.last_name'])
                    ->sortable(),
                    
                TextColumn::make('dentist_name')
                    ->label('Dentist')
                    ->getStateUsing(fn ($record) => $record->dentist ? 'Dr. ' . $record->dentist->first_name . ' ' . ($record->dentist->middle_name ? $record->dentist->middle_name . ' ' : '') . $record->dentist->last_name : 'N/A')
                    ->searchable(['dentist.first_name', 'dentist.last_name'])
                    ->sortable(),
                    
                TextColumn::make('appointment_type')
                    ->label('Type')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                    
                TextColumn::make('appointment_date')
                    ->label('Date & Time')
                    ->dateTime('M d, Y - h:i A')
                    ->sortable(),
                    
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'scheduled' => 'info',
                        'confirmed' => 'success',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'cancelled' => 'danger',
                        'no_show' => 'gray',
                        default => 'gray',
                    })
                    ->searchable(),
                    
                TextColumn::make('notes')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('appointment_date', 'desc');
    }
}
