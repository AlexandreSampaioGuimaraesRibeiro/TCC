<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Booking;
use App\Models\ProfessionalProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ProfissionalSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Usuário profissional
        $pro = User::firstOrCreate(
            ['email' => 'eletricista@beework.com.br'],
            [
                'name'              => 'Carlos Eletricista',
                'password'          => Hash::make('Beework@2026'), // TROCAR EM PRODUÇÃO
                'cpf'               => '33333333333',
                'phone'             => '31966666666',
                'birth_date'        => '1985-03-10',
                'role'              => 'professional',
                'email_verified_at' => now(),
                'lgpd_accepted_at'  => now(),
            ]
        );

        // Endereço do profissional (usado no editProfile / geocoding)
        $pro->address()->updateOrCreate([], [
            'cep'       => '30140-071',
            'street'    => 'Av. Afonso Pena',
            'number'    => '1500',
            'district'  => 'Centro',
            'city'      => 'Belo Horizonte',
            'state'     => 'MG',
            'latitude'  => -19.9245,
            'longitude' => -43.9352,
        ]);

        // 2) Perfil profissional aprovado (waiting() -> painel.agenda)
        $profile = ProfessionalProfile::firstOrCreate(
            ['user_id' => $pro->id],
            [
                'bio'    => 'Eletricista com 10 anos de experiência em instalações residenciais e comerciais.',
                'status' => 'approved',
                'photo'  => null,
            ]
        );

        // 3) Categoria + preço no pivot (updatePrices usa pivot 'price')
        $categoria = Category::where('slug', 'eletricista')->first();
        if ($categoria) {
            $profile->categories()->syncWithoutDetaching([
                $categoria->id => ['price' => 120.00],
            ]);
        }

        // 4) Cliente para os bookings
        $cliente = User::firstOrCreate(
            ['email' => 'cliente@beework.com.br'],
            [
                'name'              => 'Maria Cliente',
                'password'          => Hash::make('Beework@2026'),
                'cpf'               => '22222222222',
                'phone'             => '31977777777',
                'birth_date'        => '1995-08-20',
                'role'              => 'client',
                'email_verified_at' => now(),
                'lgpd_accepted_at'  => now(),
            ]
        );

        if (! $categoria) {
            return;
        }

        // 5) Bookings — cobrem os 4 submenus e aparecem na agenda (events)
        $bookings = [
            // pending  -> submenu "pendentes" + agenda (amarelo)
            [
                'status'         => 'pending',
                'scheduled_date' => today()->addDays(2)->toDateString(),
                'scheduled_time' => '09:00',
            ],
            // accepted futuro -> submenu "futuros" + agenda (âmbar)
            [
                'status'         => 'accepted',
                'scheduled_date' => today()->addDays(4)->toDateString(),
                'scheduled_time' => '14:00',
            ],
            // completed -> submenu "feitos"
            [
                'status'         => 'completed',
                'scheduled_date' => today()->subDays(3)->toDateString(),
                'scheduled_time' => '10:30',
            ],
            // rejected -> submenu "rejeitados"
            [
                'status'            => 'rejected',
                'scheduled_date'    => today()->subDays(1)->toDateString(),
                'scheduled_time'    => '16:00',
                'rejection_reason'  => 'Fora da área de atendimento.',
            ],
        ];

        foreach ($bookings as $b) {
            Booking::firstOrCreate(
                [
                    'professional_profile_id' => $profile->id,
                    'client_id'               => $cliente->id,
                    'category_id'             => $categoria->id,
                    'scheduled_date'          => $b['scheduled_date'],
                    'scheduled_time'          => $b['scheduled_time'],
                ],
                array_merge($b, [
                    'professional_profile_id' => $profile->id,
                    'client_id'               => $cliente->id,
                    'category_id'             => $categoria->id,
                ])
            );
        }

        // 6) Bloqueio de agenda (aparece em events como allDay cinza)
        $profile->blocks()->firstOrCreate(
            ['date' => today()->addDays(3)->toDateString()],
            [
                'reason'     => 'Compromisso pessoal',
                'start_time' => null,
                'end_time'   => null,
            ]
        );
    }
}