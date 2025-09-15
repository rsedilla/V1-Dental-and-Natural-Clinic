<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TreatmentType extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function treatments(): HasMany
    {
        return $this->hasMany(Treatment::class);
    }
}
