<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'client_id', 'professional_profile_id', 'category_id', 'scheduled_date',
        'scheduled_time', 'address_snapshot', 'price', 'notes', 'status', 'rejection_reason',
    ];

    protected $casts = [
        'scheduled_date'   => 'date',
        'address_snapshot' => 'array',
    ];

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function professionalProfile()
    {
        return $this->belongsTo(ProfessionalProfile::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'pending'   => 'Pendente',
            'accepted'  => 'Confirmado',
            'rejected'  => 'Rejeitado',
            'completed' => 'Concluído',
            'cancelled' => 'Cancelado',
            default     => $this->status,
        };
    }
}
