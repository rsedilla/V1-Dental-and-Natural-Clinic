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
                TextColumn::make('date')
                    ->label('Date')
                    ->getStateUsing(fn ($record) => $record->date ? $record->date->format('M d, Y') : 'N/A')
                    ->sortable()
                    ->badge()
                    ->color(function ($record) {
                        if (!$record->date) return 'gray';
                        $appointmentDate = $record->date->format('Y-m-d');
                        $today = now()->format('Y-m-d');
                        if ($appointmentDate === $today) {
                            return 'warning'; // Today's appointments
                        } elseif ($appointmentDate < $today) {
                            return 'danger'; // Overdue appointments
                        } else {
                            return 'success'; // Future appointments
                        }
                    }),

                TextColumn::make('time')
                    ->label('Time')
                    ->getStateUsing(fn ($record) => $record->time ? $record->time->format('h:i A') : 'N/A')
                    ->sortable(),
                    
                TextColumn::make('patient_name')
                    ->label('Patient')
                    ->getStateUsing(fn ($record) => $record->patient ? $record->patient->first_name . ' ' . ($record->patient->middle_name ? $record->patient->middle_name . ' ' : '') . $record->patient->last_name : 'N/A')
                    ->sortable(),
                    
                TextColumn::make('dentist_name')
                    ->label('Dentist')
                    ->getStateUsing(fn ($record) => $record->dentist ? 'Dr. ' . $record->dentist->first_name . ' ' . ($record->dentist->middle_name ? $record->dentist->middle_name . ' ' : '') . $record->dentist->last_name : 'N/A')
                    ->sortable(),
                    
                TextColumn::make('appointmentType.name')
                    ->label('Type')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                TextColumn::make('status.name')
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
            ->defaultSort('date', 'asc');
    }
}
