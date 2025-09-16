<?php

namespace App\Filament\Resources\Payments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\Treatment;
use Illuminate\Database\Eloquent\Builder;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('treatment_id')
                    ->label('Treatment')
                    ->placeholder('Search by patient name or treatment type...')
                    ->default(function () {
                        // Pre-fill from URL parameter if available
                        return request()->get('treatment_id');
                    })
                    ->options(function (string $search = ''): array {
                        if (empty($search)) {
                            // Show recent treatments if no search
                            return Treatment::with(['treatmentType', 'appointment.patient'])
                                ->latest()
                                ->limit(20)
                                ->get()
                                ->mapWithKeys(function ($treatment) {
                                    $patient = $treatment->appointment->patient ?? null;
                                    $patientName = $patient ? "{$patient->first_name} {$patient->last_name}" : 'Unknown Patient';
                                    $treatmentType = $treatment->treatmentType->name ?? 'Unknown Treatment';
                                    $date = $treatment->treatment_date ? $treatment->treatment_date->format('M d, Y') : 'No date';
                                    
                                    return [
                                        $treatment->id => "#{$treatment->id} - {$treatmentType} for {$patientName} ({$date})"
                                    ];
                                })
                                ->toArray();
                        }

                        // Search by patient name, treatment type, or treatment ID
                        return Treatment::with(['treatmentType', 'appointment.patient'])
                            ->where(function (Builder $query) use ($search) {
                                $query->whereHas('appointment.patient', function (Builder $q) use ($search) {
                                    $q->where('first_name', 'like', "%{$search}%")
                                      ->orWhere('last_name', 'like', "%{$search}%")
                                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"]);
                                })
                                ->orWhereHas('treatmentType', function (Builder $q) use ($search) {
                                    $q->where('name', 'like', "%{$search}%");
                                })
                                ->orWhere('treatment_id', 'like', "%{$search}%");
                            })
                            ->latest()
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($treatment) {
                                $patient = $treatment->appointment->patient ?? null;
                                $patientName = $patient ? "{$patient->first_name} {$patient->last_name}" : 'Unknown Patient';
                                $treatmentType = $treatment->treatmentType->name ?? 'Unknown Treatment';
                                $date = $treatment->treatment_date ? $treatment->treatment_date->format('M d, Y') : 'No date';
                                
                                return [
                                    $treatment->treatment_id => "#{$treatment->treatment_id} - {$treatmentType} for {$patientName} ({$date})"
                                ];
                            })
                            ->toArray();
                    })
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search): array {
                        return Treatment::with(['treatmentType', 'appointment.patient'])
                            ->where(function (Builder $query) use ($search) {
                                $query->whereHas('appointment.patient', function (Builder $q) use ($search) {
                                    $q->where('first_name', 'like', "%{$search}%")
                                      ->orWhere('last_name', 'like', "%{$search}%")
                                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"]);
                                })
                                ->orWhereHas('treatmentType', function (Builder $q) use ($search) {
                                    $q->where('name', 'like', "%{$search}%");
                                })
                                ->orWhere('treatment_id', 'like', "%{$search}%");
                            })
                            ->latest()
                            ->limit(50)
                            ->get()
                            ->mapWithKeys(function ($treatment) {
                                $patient = $treatment->appointment->patient ?? null;
                                $patientName = $patient ? "{$patient->first_name} {$patient->last_name}" : 'Unknown Patient';
                                $treatmentType = $treatment->treatmentType->name ?? 'Unknown Treatment';
                                $date = $treatment->treatment_date ? $treatment->treatment_date->format('M d, Y') : 'No date';
                                
                                return [
                                    $treatment->treatment_id => "#{$treatment->treatment_id} - {$treatmentType} for {$patientName} ({$date})"
                                ];
                            })
                            ->toArray();
                    })
                    ->getOptionLabelUsing(function ($value): ?string {
                        $treatment = Treatment::with(['treatmentType', 'appointment.patient'])->find($value);
                        if (!$treatment) return null;
                        
                        $patient = $treatment->appointment->patient ?? null;
                        $patientName = $patient ? "{$patient->first_name} {$patient->last_name}" : 'Unknown Patient';
                        $treatmentType = $treatment->treatmentType->name ?? 'Unknown Treatment';
                        $date = $treatment->treatment_date ? $treatment->treatment_date->format('M d, Y') : 'No date';
                        
                        return "#{$treatment->id} - {$treatmentType} for {$patientName} ({$date})";
                    })
                    ->live()
                    ->afterStateUpdated(function ($state, $set) {
                        // Reset amount when treatment changes to a fully paid treatment
                        if ($state) {
                            $treatment = Treatment::find($state);
                            if ($treatment && $treatment->remaining_balance <= 0) {
                                $set('amount', null);
                            }
                        }
                    })
                    ->required()
                    ->helperText('Search by typing patient name, treatment type, or treatment ID'),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('₱')
                    ->live(debounce: 500)
                    ->disabled(function ($get) {
                        $treatmentId = $get('treatment_id');
                        if (!$treatmentId) return false;
                        
                        $treatment = Treatment::find($treatmentId);
                        return $treatment && $treatment->remaining_balance <= 0;
                    })
                    ->rules([
                        function ($get) {
                            return function (string $attribute, $value, $fail) use ($get) {
                                $treatmentId = $get('treatment_id');
                                if ($treatmentId) {
                                    $treatment = Treatment::find($treatmentId);
                                    if ($treatment && $treatment->remaining_balance <= 0) {
                                        $fail('Cannot add payment to a fully paid treatment.');
                                    }
                                }
                            };
                        },
                    ])
                    ->default(function () {
                        // Pre-fill remaining balance if specified in URL
                        return request()->get('amount');
                    })
                    ->helperText(function ($get) {
                        $treatmentId = $get('treatment_id');
                        if (!$treatmentId) return 'Select a treatment to see balance information';
                        
                        $treatment = Treatment::find($treatmentId);
                        if (!$treatment) return 'Treatment not found';
                        
                        // Check if treatment is fully paid
                        if ($treatment->remaining_balance <= 0) {
                            return '✅ Treatment is fully paid. No further payments allowed.';
                        }
                        
                        $cost = $treatment->cost;
                        $totalPaid = $treatment->total_paid;
                        $balance = $treatment->remaining_balance;
                        
                        return "Treatment Cost: ₱" . number_format($cost, 2) . 
                               " | Total Paid: ₱" . number_format($totalPaid, 2) . 
                               " | Remaining Balance: ₱" . number_format($balance, 2);
                    }),
                DatePicker::make('payment_date')
                    ->required(),
                Select::make('payment_method')
                    ->label('Payment Method')
                    ->options([
                        'cash' => 'Cash',
                        'credit_card' => 'Credit Card',
                        'debit_card' => 'Debit Card',
                        'bank_transfer' => 'Bank Transfer',
                        'installment' => 'Installment',
                        'hmo card' => 'HMO Card',
                    ])
                    ->searchable()
                    ->required(),
                Select::make('status_id')
                    ->relationship('status', 'name')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
