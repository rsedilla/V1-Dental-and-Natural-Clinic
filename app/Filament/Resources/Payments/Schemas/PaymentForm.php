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
                                ->orWhere('id', 'like', "%{$search}%");
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
                                    $treatment->id => "#{$treatment->id} - {$treatmentType} for {$patientName} ({$date})"
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
                                ->orWhere('id', 'like', "%{$search}%");
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
                                    $treatment->id => "#{$treatment->id} - {$treatmentType} for {$patientName} ({$date})"
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
                    ->required()
                    ->helperText('Search by typing patient name, treatment type, or treatment ID'),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                DatePicker::make('payment_date')
                    ->required(),
                TextInput::make('payment_method')
                    ->required(),
                Select::make('status_id')
                    ->relationship('status', 'name')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
