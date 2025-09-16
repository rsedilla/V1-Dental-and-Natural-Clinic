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
                // 1. Patient Name
                TextColumn::make('appointment.patient.first_name')
                    ->label('Patient Name')
                    ->formatStateUsing(function ($record) {
                        $patient = $record->appointment ? $record->appointment->patient : null;
                        if (!$patient) return 'N/A';
                        return $patient->first_name . ' ' . ($patient->middle_name ? $patient->middle_name . ' ' : '') . $patient->last_name;
                    })
                    ->searchable()
                    ->sortable(),
                    
                // 2. Dentist or performed by
                TextColumn::make('performedBy.first_name')
                    ->label('Performed By')
                    ->formatStateUsing(function ($record) {
                        $dentist = $record->performedBy ?: ($record->appointment ? $record->appointment->dentist : null);
                        if (!$dentist) return 'N/A';
                        return 'Dr. ' . $dentist->first_name . ' ' . ($dentist->middle_name ? $dentist->middle_name . ' ' : '') . $dentist->last_name;
                    })
                    ->searchable()
                    ->sortable(),
                    
                // 3. Type - from treatment type table
                TextColumn::make('treatmentType.name')
                    ->label('Treatment Type')
                    ->badge()
                    ->color('info')
                    ->searchable()
                    ->sortable(),
                    
                // 4. Date
                TextColumn::make('treatment_date')
                    ->label('Treatment Date')
                    ->date('M d, Y')
                    ->sortable(),
                    
                // 5. Cost
                TextColumn::make('cost')
                    ->label('Cost')
                    ->money('PHP')
                    ->sortable(),
                    
                // 6. Follow up
                TextColumn::make('follow_up_date')
                    ->label('Follow Up')
                    ->date('M d, Y')
                    ->placeholder('No follow-up scheduled')
                    ->badge()
                    ->color(function ($state) {
                        if (!$state) return 'gray';
                        return $state->isPast() ? 'danger' : 'success';
                    })
                    ->sortable(),
                    
                // Additional columns (hidden by default)
                TextColumn::make('tooth_number')
                    ->label('Tooth #')
                    ->placeholder('N/A')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('dentist_revenue')
                    ->label('Dentist Share')
                    ->getStateUsing(fn ($record) => '₱' . number_format($record->cost * ($record->dentist_share ?? 0.4), 2))
                    ->color('success')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('clinic_revenue')
                    ->label('Clinic Share')
                    ->getStateUsing(fn ($record) => '₱' . number_format($record->cost * ($record->clinic_share ?? 0.6), 2))
                    ->color('warning')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('description')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('follow_up_notes')
                    ->label('Follow-up Notes')
                    ->limit(50)
                    ->placeholder('No notes')
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('treatment_date', 'desc')
            ->searchOnBlur()
            ->filters([
                // Add filters here if needed
            ]);
    }
}
