<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Booking;
use App\Models\ProfessionalProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProfissionalSeeder extends Seeder
{
    /**
     * 10 profissionais (2 por categoria) + 8 clientes.
     * Cada profissional: perfil aprovado, endereço, 1 categoria com preço no pivot,
     * e bookings nos 4 status (pending, accepted futuro, completed, rejected)
     * — os accepted/pending aparecem também na agenda (events()).
     */
    public function run(): void
    {
        // ---------------------------------------------------------------
        // 1) CLIENTES (8)
        // ---------------------------------------------------------------
        $clientesData = [
            ['Maria Oliveira',   'cliente1@beework.com.br', '22200000001', '31970000001', '1995-08-20'],
            ['João Pereira',     'cliente2@beework.com.br', '22200000002', '31970000002', '1990-02-11'],
            ['Ana Souza',        'cliente3@beework.com.br', '22200000003', '31970000003', '1988-12-03'],
            ['Carlos Lima',      'cliente4@beework.com.br', '22200000004', '31970000004', '1992-05-30'],
            ['Fernanda Costa',   'cliente5@beework.com.br', '22200000005', '31970000005', '2000-07-14'],
            ['Rafael Almeida',   'cliente6@beework.com.br', '22200000006', '31970000006', '1985-01-25'],
            ['Juliana Ribeiro',  'cliente7@beework.com.br', '22200000007', '31970000007', '1997-09-09'],
            ['Bruno Fernandes',  'cliente8@beework.com.br', '22200000008', '31970000008', '1993-11-18'],
        ];

        $clientes = collect($clientesData)->map(function ($c) {
            return User::firstOrCreate(
                ['email' => $c[1]],
                [
                    'name'              => $c[0],
                    'password'          => Hash::make('Beework@2026'), // TROCAR EM PRODUÇÃO
                    'cpf'               => $c[2],
                    'phone'             => $c[3],
                    'birth_date'        => $c[4],
                    'role'              => 'client',
                    'email_verified_at' => now(),
                    'lgpd_accepted_at'  => now(),
                ]
            );
        });

        // ---------------------------------------------------------------
        // 2) PROFISSIONAIS — 2 por categoria (slug => [ [nome, email, cpf, tel, nasc, bio, preço, endereço], ... ])
        // ---------------------------------------------------------------
        $enderecos = [
            ['30140-071', 'Av. Afonso Pena',       '1500', 'Centro',        'Belo Horizonte', 'MG', -19.9245, -43.9352],
            ['30130-005', 'Rua da Bahia',          '1200', 'Centro',        'Belo Horizonte', 'MG', -19.9208, -43.9378],
            ['30310-000', 'Av. do Contorno',       '4000', 'Funcionários',  'Belo Horizonte', 'MG', -19.9350, -43.9260],
            ['31270-901', 'Av. Antônio Carlos',    '6627', 'Pampulha',      'Belo Horizonte', 'MG', -19.8700, -43.9660],
            ['30170-000', 'Rua dos Guajajaras',    '800',  'Lourdes',       'Belo Horizonte', 'MG', -19.9290, -43.9420],
        ];

        $profissionaisPorCategoria = [
            'diarista' => [
                ['Vera Diarista',    'vera.diarista@beework.com.br',    '33300000001', '31960000001', '1980-04-12', 'Diarista com experiência em limpeza residencial e comercial.', 150.00],
                ['Sônia Faxina',     'sonia.faxina@beework.com.br',     '33300000002', '31960000002', '1983-06-22', 'Faxina pesada, passar roupa e organização.', 140.00],
            ],
            'pedreiro' => [
                ['José Pedreiro',    'jose.pedreiro@beework.com.br',    '33300000003', '31960000003', '1978-01-05', 'Alvenaria, reboco e pequenas reformas.', 200.00],
                ['Antônio Construtor','antonio.obra@beework.com.br',    '33300000004', '31960000004', '1975-09-19', 'Assentamento de piso e revestimentos.', 220.00],
            ],
            'eletricista' => [
                ['Carlos Eletricista','carlos.eletric@beework.com.br',  '33300000005', '31960000005', '1985-03-10', 'Instalações residenciais e comerciais, quadros de energia.', 120.00],
                ['Marcelo Energia',  'marcelo.energia@beework.com.br',  '33300000006', '31960000006', '1990-11-02', 'Manutenção elétrica e automação residencial.', 130.00],
            ],
            'bombeiro-hidraulico' => [
                ['Pedro Hidráulico', 'pedro.hidraulico@beework.com.br', '33300000007', '31960000007', '1982-07-28', 'Reparo de vazamentos, instalação de tubulações.', 160.00],
                ['Luiz Encanador',   'luiz.encanador@beework.com.br',   '33300000008', '31960000008', '1987-02-14', 'Desentupimento e instalação de louças sanitárias.', 150.00],
            ],
            'jardineiro' => [
                ['Roberto Jardim',   'roberto.jardim@beework.com.br',   '33300000009', '31960000009', '1979-10-08', 'Poda, corte de grama e paisagismo.', 110.00],
                ['Sérgio Verde',     'sergio.verde@beework.com.br',     '33300000010', '31960000010', '1991-05-16', 'Manutenção de jardins e hortas urbanas.', 100.00],
            ],
        ];

        $idxEndereco = 0;

        foreach ($profissionaisPorCategoria as $slug => $lista) {
            $categoria = Category::where('slug', $slug)->first();
            if (! $categoria) {
                continue; // categoria ainda não semeada
            }

            foreach ($lista as $p) {
                [$nome, $email, $cpf, $tel, $nasc, $bio, $preco] = $p;

                // 2.1) Usuário profissional
                $user = User::firstOrCreate(
                    ['email' => $email],
                    [
                        'name'              => $nome,
                        'password'          => Hash::make('Beework@2026'), // TROCAR EM PRODUÇÃO
                        'cpf'               => $cpf,
                        'phone'             => $tel,
                        'birth_date'        => $nasc,
                        'role'              => 'professional',
                        'email_verified_at' => now(),
                        'lgpd_accepted_at'  => now(),
                    ]
                );

                // 2.2) Endereço (rotaciona pela lista)
                $end = $enderecos[$idxEndereco % count($enderecos)];
                $idxEndereco++;
                $user->address()->updateOrCreate([], [
                    'cep'       => $end[0], 'street' => $end[1], 'number' => $end[2],
                    'district'  => $end[3], 'city'   => $end[4], 'state'  => $end[5],
                    'latitude'  => $end[6], 'longitude' => $end[7],
                ]);

                // 2.3) Perfil profissional aprovado
                $profile = ProfessionalProfile::firstOrCreate(
                    ['user_id' => $user->id],
                    ['bio' => $bio, 'status' => 'approved', 'photo' => null]
                );

                // 2.4) Categoria única no pivot com preço
                $profile->categories()->syncWithoutDetaching([
                    $categoria->id => ['price' => $preco],
                ]);

                // 2.5) Bookings nos 4 status (clientes variados)
                $c1 = $clientes[($idxEndereco) % $clientes->count()];
                $c2 = $clientes[($idxEndereco + 1) % $clientes->count()];
                $c3 = $clientes[($idxEndereco + 2) % $clientes->count()];
                $c4 = $clientes[($idxEndereco + 3) % $clientes->count()];

                $bookings = [
                    ['status' => 'pending',   'client' => $c1, 'date' => today()->addDays(2), 'time' => '09:00'],
                    ['status' => 'accepted',  'client' => $c2, 'date' => today()->addDays(4), 'time' => '14:00'],
                    ['status' => 'completed', 'client' => $c3, 'date' => today()->subDays(3), 'time' => '10:30'],
                    ['status' => 'rejected',  'client' => $c4, 'date' => today()->subDays(1), 'time' => '16:00', 'reason' => 'Fora da área de atendimento.'],
                ];

                foreach ($bookings as $b) {
                    Booking::firstOrCreate(
                        [
                            'professional_profile_id' => $profile->id,
                            'client_id'               => $b['client']->id,
                            'category_id'             => $categoria->id,
                            'scheduled_date'          => $b['date']->toDateString(),
                            'scheduled_time'          => $b['time'],
                        ],
                        [
                            'professional_profile_id' => $profile->id,
                            'client_id'               => $b['client']->id,
                            'category_id'             => $categoria->id,
                            'scheduled_date'          => $b['date']->toDateString(),
                            'scheduled_time'          => $b['time'],
                            'status'                  => $b['status'],
                            'rejection_reason'        => $b['reason'] ?? null,
                        ]
                    );
                }

                // 2.6) Um bloqueio de agenda por profissional
                $profile->blocks()->firstOrCreate(
                    ['date' => today()->addDays(3)->toDateString()],
                    ['reason' => 'Compromisso pessoal', 'start_time' => null, 'end_time' => null]
                );
            }
        }
    }
}