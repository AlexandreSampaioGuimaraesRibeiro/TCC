<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@beework.com.br'],
            [
                'name'              => 'Administrador Beework',
                'password'          => Hash::make('Beework@2026'), // TROCAR EM PRODUÇÃO
                'cpf'               => '00000000000',
                'phone'             => '31999999999',
                'birth_date'        => '1990-01-01',
                'role'              => 'admin',
                'email_verified_at' => now(),
                'lgpd_accepted_at'  => now(),
            ]
        );
    }
}
