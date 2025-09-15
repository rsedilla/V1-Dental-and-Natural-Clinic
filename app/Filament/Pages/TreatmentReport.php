<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\Payment;

class TreatmentReport extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentChartBar;
    
    protected static ?string $navigationLabel = 'Treatment Reports';
    
    protected static ?string $title = 'Treatment Reports';
    
    protected static ?int $navigationSort = 5;
    
    protected string $view = 'filament.pages.treatment-report';
    
    public ?int $selectedPatientId = null;
    
    public function getPatients()
    {
        return Patient::all()->map(function ($patient) {
            return [
                'id' => $patient->id,
                'name' => $patient->first_name . ' ' . ($patient->middle_name ? $patient->middle_name . ' ' : '') . $patient->last_name,
                'email' => $patient->email,
                'phone' => $patient->phone_number,
            ];
        });
    }
    
    public function getPatientData($patientId)
    {
        if (!$patientId) {
            return null;
        }
        
        $patient = Patient::find($patientId);
        
        return [
            'patient' => $patient,
            'appointments' => Appointment::where('patient_id', $patientId)
                ->with(['dentist', 'appointmentType', 'status'])
                ->orderBy('appointment_date', 'desc')
                ->get(),
            'treatments' => Treatment::whereHas('appointment', function ($query) use ($patientId) {
                $query->where('patient_id', $patientId);
            })
                ->with(['appointment', 'treatmentType', 'performedBy'])
                ->orderBy('treatment_date', 'desc')
                ->get(),
            'payments' => Payment::whereHas('appointment', function ($query) use ($patientId) {
                $query->where('patient_id', $patientId);
            })
                ->with(['appointment', 'paymentStatus'])
                ->orderBy('created_at', 'desc')
                ->get(),
        ];
    }
}
