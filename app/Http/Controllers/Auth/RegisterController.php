<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\User;
use App\Rules\ValidCpf;
use App\Services\AuditService;
use App\Services\GeocodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    public function showClient()
    {
        return view('auth.register', ['tipo' => 'cliente', 'categories' => collect()]);
    }

    public function showProfessional()
    {
        return view('auth.register', [
            'tipo'       => 'profissional',
            'categories' => Category::where('active', true)->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request, string $tipo)
    {
        abort_unless(in_array($tipo, ['cliente', 'profissional']), 404);
        $isProfessional = $tipo === 'profissional';

        $rules = [
            'name'       => ['required', 'string', 'min:5', 'max:120'],
            'cpf'        => ['required', new ValidCpf, 'unique:users,cpf'],
            'phone'      => ['required', 'string', 'min:10', 'max:20'],
            'email'      => ['required', 'email', 'max:150', 'unique:users,email'],
            'password'   => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            // Requisito 2: mínimo 18 anos para os dois perfis
            'birth_date' => ['required', 'date', 'before_or_equal:'.now()->subYears(18)->toDateString()],
            'cep'        => ['required', 'string', 'size:9'],
            'street'     => ['required', 'string', 'max:150'],
            'number'     => ['required', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:100'],
            'district'   => ['required', 'string', 'max:100'],
            'city'       => ['required', 'string', 'max:100'],
            'state'      => ['required', 'string', 'size:2'],
            'lgpd'       => ['accepted'],
        ];

        if ($isProfessional) {
            $rules['categories']   = ['required', 'array', 'min:1'];
            $rules['categories.*'] = ['exists:categories,id'];
            $rules['bio']          = ['nullable', 'string', 'max:1000'];
        }

        $messages = [
            'birth_date.before_or_equal' => 'É necessário ter no mínimo 18 anos para se cadastrar.',
            'lgpd.accepted'              => 'Você precisa aceitar a Política de Privacidade (LGPD).',
        ];

        $data = $request->validate($rules, $messages);

        // Requisito 2: qualificação obrigatória para categorias regulamentadas
        if ($isProfessional) {
            $regulated = Category::whereIn('id', $data['categories'])
                ->where('requires_qualification', true)->get();

            foreach ($regulated as $cat) {
                $request->validate(
                    ["qualification_{$cat->id}" => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120']],
                    ["qualification_{$cat->id}.required" => "A categoria {$cat->name} exige comprovação de qualificação (PDF ou imagem)."]
                );
            }
        }

        $user = DB::transaction(function () use ($request, $data, $isProfessional) {
            $user = User::create([
                'name'             => $data['name'],
                'email'            => $data['email'],
                'password'         => $data['password'],
                'cpf'              => preg_replace('/\D/', '', $data['cpf']),
                'phone'            => preg_replace('/\D/', '', $data['phone']),
                'birth_date'       => $data['birth_date'],
                'role'             => $isProfessional ? 'professional' : 'client',
                'lgpd_accepted_at' => now(),
            ]);

            $geo = GeocodingService::geocode($data['street'], $data['city'], $data['state'], $data['cep']);

            $user->address()->create([
                'cep'        => $data['cep'],
                'street'     => $data['street'],
                'number'     => $data['number'],
                'complement' => $data['complement'] ?? null,
                'district'   => $data['district'],
                'city'       => $data['city'],
                'state'      => strtoupper($data['state']),
                'latitude'   => $geo['lat'] ?? null,
                'longitude'  => $geo['lng'] ?? null,
            ]);

            if ($isProfessional) {
                $profile = $user->professionalProfile()->create([
                    'bio'    => $data['bio'] ?? null,
                    'status' => 'pending', // Requisito 3: só entra no ar após aprovação do ADM
                ]);

                $prices = [];
                foreach ($data['categories'] as $catId) {
                    $prices[$catId] = ['price' => 0];
                }
                $profile->categories()->attach($prices);

                foreach ($data['categories'] as $catId) {
                    if ($request->hasFile("qualification_{$catId}")) {
                        $file = $request->file("qualification_{$catId}");
                        $path = $file->storeAs(
                            'qualifications',
                            Str::uuid().'.'.$file->getClientOriginalExtension(),
                            'local' // storage privado, fora do public
                        );
                        $profile->qualifications()->create([
                            'category_id'   => $catId,
                            'file_path'     => $path,
                            'original_name' => $file->getClientOriginalName(),
                        ]);
                    }
                }
            }

            return $user;
        });

        $user->sendEmailVerificationNotification();
        AuditService::log('register:'.$user->role, $user->id);
        Auth::login($user);

        return redirect()->route('verification.notice');
    }
}
