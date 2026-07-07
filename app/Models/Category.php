<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'requires_qualification', 'active'];

    protected $casts = ['requires_qualification' => 'boolean', 'active' => 'boolean'];

    public function professionals()
    {
        return $this->belongsToMany(ProfessionalProfile::class, 'category_professional')
            ->withPivot('price');
    }
}
