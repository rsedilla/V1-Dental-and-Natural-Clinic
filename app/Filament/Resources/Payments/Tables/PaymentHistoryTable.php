<?php

namespace App\Filament\Resources\Payments\Tables;

use App\Models\Payment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

class PaymentHistoryTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Payment::query()
                    ->with(['treatment.appointment.patient', 'treatment.treatmentType', 'status'])
                    ->join('treatments', 'payments.treatment_id', '=', 'treatments.id')
                    ->join('appointments', 'treatments.appointment_id', '=', 'appointments.id')
                    ->join('patients', 'appointments.patient_id', '=', 'patients.id')
                    ->select('payments.*')
            )
            ->columns([
                TextColumn::make('treatment.appointment.patient.first_name')
                    ->label('Patient Name')
                    ->formatStateUsing(fn ($record) => 
                        $record->treatment->appointment->patient->first_name . ' ' . 
                        $record->treatment->appointment->patient->last_name
                    )
                    ->searchable(false)
                    ->sortable(['patients.first_name']),
                    
                TextColumn::make('treatment.treatmentType.name')
                    ->label('Treatment')
                    ->searchable(false)
                    ->sortable(),
                    
                TextColumn::make('treatment.cost')
                    ->label('Treatment Cost')
                    ->money('PHP')
                    ->sortable(),
                    
                TextColumn::make('cumulative_paid')
                    ->label('Total Paid')
                    ->money('PHP')
                    ->getStateUsing(function ($record) {
                        // Calculate cumulative payment up to this payment date
                        return $record->treatment->payments()
                            ->where('payment_date', '<=', $record->payment_date)
                            ->where(function ($query) use ($record) {
                                $query->where('payment_date', '<', $record->payment_date)
                                      ->orWhere('id', '<=', $record->id);
                            })
                            ->sum('amount');
                    })
                    ->color('success'),
                    
                TextColumn::make('remaining_balance_at_time')
                    ->label('Remaining Balance')
                    ->money('PHP')
                    ->getStateUsing(function ($record) {
                        // Calculate remaining balance after this payment
                        $cumulativePaid = $record->treatment->payments()
                            ->where('payment_date', '<=', $record->payment_date)
                            ->where(function ($query) use ($record) {
                                $query->where('payment_date', '<', $record->payment_date)
                                      ->orWhere('id', '<=', $record->id);
                            })
                            ->sum('amount');
                        return $record->treatment->cost - $cumulativePaid;
                    })
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),
                    
                TextColumn::make('payment_status_at_time')
                    ->label('Payment Status')
                    ->badge()
                    ->getStateUsing(function ($record) {
                        // Calculate payment status at the time of this payment
                        $cumulativePaid = $record->treatment->payments()
                            ->where('payment_date', '<=', $record->payment_date)
                            ->where(function ($query) use ($record) {
                                $query->where('payment_date', '<', $record->payment_date)
                                      ->orWhere('id', '<=', $record->id);
                            })
                            ->sum('amount');
                        
                        $cost = $record->treatment->cost;
                        
                        if ($cumulativePaid == 0) {
                            return 'unpaid';
                        } elseif ($cumulativePaid >= $cost) {
                            return 'paid';
                        } else {
                            return 'partial';
                        }
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'partial' => 'warning',
                        'unpaid' => 'danger',
                        default => 'gray',
                    }),
                    
                TextColumn::make('amount')
                    ->label('This Payment')
                    ->money('PHP')
                    ->sortable(),
                    
                TextColumn::make('payment_date')
                    ->label('Payment Date')
                    ->date()
                    ->sortable(),
                    
                TextColumn::make('payment_method')
                    ->label('Method')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'credit_card', 'debit_card' => 'info',
                        'bank_transfer' => 'warning',
                        'installment' => 'gray',
                        'hmo_card' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                Filter::make('search')
                    ->form([
                        TextInput::make('search')
                            ->label('Search')
                            ->placeholder('Search by patient name or treatment type...')
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!$data['search']) {
                            return $query;
                        }
                        
                        $search = $data['search'];
                        
                        return $query->where(function (Builder $query) use ($search) {
                            $query->whereHas('treatment.appointment.patient', function (Builder $q) use ($search) {
                                $q->where('first_name', 'like', "%{$search}%")
                                  ->orWhere('last_name', 'like', "%{$search}%")
                                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"]);
                            })
                            ->orWhereHas('treatment.treatmentType', function (Builder $q) use ($search) {
                                $q->where('name', 'like', "%{$search}%");
                            });
                        });
                    }),
                    
                SelectFilter::make('payment_status')
                    ->label('Payment Status')
                    ->options([
                        'paid' => 'Paid',
                        'partial' => 'Partial',
                        'unpaid' => 'Unpaid',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (!$data['value']) {
                            return $query;
                        }
                        
                        return $query->whereHas('treatment', function (Builder $query) use ($data) {
                            switch ($data['value']) {
                                case 'paid':
                                    $query->whereRaw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE treatment_id = treatments.treatment_id) >= treatments.cost');
                                    break;
                                case 'partial':
                                    $query->whereRaw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE treatment_id = treatments.treatment_id) > 0')
                                          ->whereRaw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE treatment_id = treatments.treatment_id) < treatments.cost');
                                    break;
                                case 'unpaid':
                                    $query->whereRaw('(SELECT COALESCE(SUM(amount), 0) FROM payments WHERE treatment_id = treatments.treatment_id) = 0');
                                    break;
                            }
                        });
                    }),
                    
                SelectFilter::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'cash' => 'Cash',
                        'credit_card' => 'Credit Card',
                        'debit_card' => 'Debit Card',
                        'bank_transfer' => 'Bank Transfer',
                        'installment' => 'Installment',
                        'hmo_card' => 'HMO Card',
                    ]),
                    
                Filter::make('cost_range')
                    ->form([
                        TextInput::make('cost_from')
                            ->label('Cost From')
                            ->numeric()
                            ->prefix('₱'),
                        TextInput::make('cost_to')
                            ->label('Cost To')
                            ->numeric()
                            ->prefix('₱'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['cost_from'],
                                fn (Builder $query, $cost): Builder => $query->whereHas('treatment', 
                                    fn (Builder $query) => $query->where('cost', '>=', $cost)
                                ),
                            )
                            ->when(
                                $data['cost_to'],
                                fn (Builder $query, $cost): Builder => $query->whereHas('treatment', 
                                    fn (Builder $query) => $query->where('cost', '<=', $cost)
                                ),
                            );
                    }),
            ])
            ->defaultSort('payment_date', 'desc')
            ->striped()
            ->paginated([10, 25, 50, 100]);
    }
}