<?php

namespace App\Filament\Resources\Treatments\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TreatmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient_name')
                    ->label('Patient')
                    ->getStateUsing(function ($record) {
                        $patient = $record->appointment ? $record->appointment->patient : null;
                        if (!$patient) return 'N/A';
                        return $patient->first_name . ' ' . ($patient->middle_name ? $patient->middle_name . ' ' : '') . $patient->last_name;
                    })
                    ->searchable(['appointment.patient.first_name', 'appointment.patient.last_name'])
                    ->sortable(),
                    
                TextColumn::make('treatment_name')
                    ->label('Treatment Type')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                    
                TextColumn::make('tooth_number')
                    ->label('Tooth #')
                    ->placeholder('N/A')
                    ->searchable(),
                    
                TextColumn::make('cost')
                    ->label('Cost')
                    ->money('PHP')
                    ->sortable(),
                    
                TextColumn::make('dentist_name')
                    ->label('Performed By')
                    ->getStateUsing(function ($record) {
                        $dentist = $record->performedBy ?: ($record->appointment ? $record->appointment->dentist : null);
                        if (!$dentist) return 'N/A';
                        return 'Dr. ' . $dentist->first_name . ' ' . ($dentist->middle_name ? $dentist->middle_name . ' ' : '') . $dentist->last_name;
                    })
                    ->searchable(['performedBy.first_name', 'performedBy.last_name'])
                    ->sortable(),
                    
                TextColumn::make('treatment_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),
                    
                TextColumn::make('dentist_revenue')
                    ->label('Dentist Share')
                    ->getStateUsing(fn ($record) => '₱' . number_format($record->cost * 0.4, 2))
                    ->color('success')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('clinic_revenue')
                    ->label('Clinic Share')
                    ->getStateUsing(fn ($record) => '₱' . number_format($record->cost * 0.6, 2))
                    ->color('warning')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('description')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('treatment_date', 'desc');
    }
}
