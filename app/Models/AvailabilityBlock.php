<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailabilityBlock extends Model
{
    protected $fillable = ['professional_profile_id', 'date', 'start_time', 'end_time', 'reason'];

    protected $casts = ['date' => 'date'];

    public function professionalProfile()
    {
        return $this->belongsTo(ProfessionalProfile::class);
    }
}
