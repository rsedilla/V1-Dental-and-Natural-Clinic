<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient_name')
                    ->label('Patient')
                    ->getStateUsing(function ($record) {
                        $patient = $record->treatment && $record->treatment->appointment ? $record->treatment->appointment->patient : null;
                        if (!$patient) return 'N/A';
                        return $patient->first_name . ' ' . $patient->last_name;
                    })
                    ->searchable(['treatment.appointment.patient.first_name', 'treatment.appointment.patient.last_name'])
                    ->sortable(),
                    
                TextColumn::make('treatment_info')
                    ->label('Treatment')
                    ->getStateUsing(function ($record) {
                        if (!$record->treatment) return 'N/A';
                        return $record->treatment->treatmentType->name ?? 'Unknown Treatment';
                    })
                    ->searchable(['treatment.treatmentType.name'])
                    ->sortable(),
                    
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('PHP')
                    ->sortable(),
                    
                TextColumn::make('payment_method')
                    ->label('Payment Method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'credit_card' => 'info',
                        'debit_card' => 'info',
                        'bank_transfer' => 'warning',
                        'installment' => 'gray',
                        default => 'gray',
                    }),
                    
                TextColumn::make('payment_date')
                    ->label('Date')
                    ->date('M d, Y')
                    ->sortable(),
                    
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'completed' => 'success',
                        'failed' => 'danger',
                        'refunded' => 'info',
                        default => 'gray',
                    }),
                    
                TextColumn::make('reference_number')
                    ->label('Reference #')
                    ->searchable()
                    ->copyable()
                    ->placeholder('N/A'),
                    
                TextColumn::make('notes')
                    ->limit(40)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return strlen($state) > 40 ? $state : null;
                    }),
                    
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime('M d, Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('payment_date', 'desc');
    }
}
