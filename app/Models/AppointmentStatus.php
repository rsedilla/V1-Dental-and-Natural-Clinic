<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AppointmentStatus extends Model
{
    protected $fillable = [
        'name',
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class, 'status_id');
    }
}
