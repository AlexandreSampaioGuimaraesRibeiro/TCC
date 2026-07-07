<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'cpf', 'phone', 'birth_date', 'role', 'lgpd_accepted_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'lgpd_accepted_at'  => 'datetime',
            'birth_date'        => 'date',
            'password'          => 'hashed',
        ];
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function professionalProfile()
    {
        return $this->hasOne(ProfessionalProfile::class);
    }

    public function bookingsAsClient()
    {
        return $this->hasMany(Booking::class, 'client_id');
    }

    public function isAdmin(): bool        { return $this->role === 'admin'; }
    public function isProfessional(): bool { return $this->role === 'professional'; }
    public function isClient(): bool       { return $this->role === 'client'; }

    public function homeRoute(): string
    {
        return match ($this->role) {
            'admin'        => route('admin.dashboard'),
            'professional' => route('painel.agenda'),
            default        => route('home'),
        };
    }
}
