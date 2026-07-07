<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ProfessionalProfile extends Model
{
    protected $fillable = [
        'user_id', 'bio', 'photo', 'status', 'rejection_reason', 'approved_by', 'approved_at',
    ];

    protected $casts = ['approved_at' => 'datetime'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_professional')
            ->withPivot('price');
    }

    public function qualifications()
    {
        return $this->hasMany(Qualification::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function blocks()
    {
        return $this->hasMany(AvailabilityBlock::class);
    }

    public function scopeApproved(Builder $q): Builder
    {
        return $q->where('status', 'approved');
    }

    /**
     * Ordena profissionais aprovados por distância (Haversine, km).
     */
    public function scopeNearby(Builder $q, float $lat, float $lng, float $radiusKm = 25): Builder
    {
        return $q->approved()
            ->join('users', 'users.id', '=', 'professional_profiles.user_id')
            ->join('addresses', 'addresses.user_id', '=', 'users.id')
            ->whereNotNull('addresses.latitude')
            ->select('professional_profiles.*')
            ->selectRaw(
                '(6371 * acos(cos(radians(?)) * cos(radians(addresses.latitude)) *
                cos(radians(addresses.longitude) - radians(?)) +
                sin(radians(?)) * sin(radians(addresses.latitude)))) AS distance_km',
                [$lat, $lng, $lat]
            )
            ->having('distance_km', '<', $radiusKm)
            ->orderBy('distance_km');
    }

    /**
     * Verifica conflito com bloqueios de agenda.
     */
    public function isBlockedAt(string $date, string $time): bool
    {
        return $this->blocks()
            ->where('date', $date)
            ->where(function ($q) use ($time) {
                $q->whereNull('start_time') // dia inteiro
                  ->orWhere(function ($q) use ($time) {
                      $q->where('start_time', '<=', $time)->where('end_time', '>', $time);
                  });
            })->exists();
    }
}
