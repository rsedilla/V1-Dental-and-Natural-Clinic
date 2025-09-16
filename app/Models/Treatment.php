<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Treatment extends Model
{
    protected $fillable = [
        'appointment_id',
        'treatment_type_id',
        'description',
        'tooth_number',
        'cost',
        'performed_by',
        'dentist_share',
        'clinic_share',
        'treatment_date',
        'follow_up_date',
        'follow_up_notes',
        'notes',
    ];

    protected $casts = [
        'treatment_date' => 'date',
        'follow_up_date' => 'date',
        'cost' => 'decimal:2',
        'dentist_share' => 'decimal:2',
        'clinic_share' => 'decimal:2',
    ];

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function treatmentType(): BelongsTo
    {
        return $this->belongsTo(TreatmentType::class);
    }

    public function dentist(): BelongsTo
    {
        return $this->belongsTo(Dentist::class, 'performed_by');
    }

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(Dentist::class, 'performed_by');
    }

    public function getDentistAmountAttribute(): float
    {
        return $this->cost * $this->dentist_share;
    }

    public function getClinicAmountAttribute(): float
    {
        return $this->cost * $this->clinic_share;
    }
}
