<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Qualification extends Model
{
    protected $fillable = [
        'professional_profile_id', 'category_id', 'file_path', 'original_name',
        'status', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = ['reviewed_at' => 'datetime'];

    public function professionalProfile()
    {
        return $this->belongsTo(ProfessionalProfile::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
