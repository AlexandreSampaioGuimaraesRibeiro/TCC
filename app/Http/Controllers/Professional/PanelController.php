<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Models\AvailabilityBlock;
use App\Models\Category;
use App\Services\GeocodingService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PanelController extends Controller
{
    // Requisito 9: agenda semanal/diária
    public function agenda(Request $request)
    {
        return view('professional.agenda', [
            'profile' => $request->user()->professionalProfile,
        ]);
    }

    public function waiting(Request $request)
    {
        $profile = $request->user()->professionalProfile;
        if ($profile && $profile->status === 'approved') {
            return redirect()->route('painel.agenda');
        }
        return view('professional.waiting', ['profile' => $profile]);
    }

    // Eventos JSON para o FullCalendar (compromissos + bloqueios)
    public function events(Request $request)
    {
        $profile = $request->user()->professionalProfile;

        $bookings = $profile->bookings()
            ->whereIn('status', ['accepted', 'pending'])
            ->with(['client', 'category'])->get()
            ->map(fn ($b) => [
                'id'        => 'b'.$b->id,
                'title'     => $b->category->name,
                'start'     => $b->scheduled_date->format('Y-m-d').'T'.$b->scheduled_time,
                'color'     => $b->status === 'pending' ? '#F5D78A' : '#E8B33C',
                'textColor' => '#1E1E1E',
                'extendedProps' => [
                    'cliente'   => $b->client->name,
                    'pendente'  => $b->status === 'pending',
                ],
            ]);

        $blocks = $profile->blocks()->get()->map(fn ($bl) => [
            'id'     => 'x'.$bl->id,
            'title'  => $bl->reason ?: 'Bloqueado',
            'start'  => $bl->start_time
                ? $bl->date->format('Y-m-d').'T'.$bl->start_time
                : $bl->date->format('Y-m-d'),
            'end'    => $bl->end_time ? $bl->date->format('Y-m-d').'T'.$bl->end_time : null,
            'allDay' => is_null($bl->start_time),
            'color'  => '#9CA3AF',
            'textColor' => '#1E1E1E',
            'extendedProps' => ['blockId' => $bl->id],
        ]);

        return response()->json($bookings->concat($blocks)->values());
    }

    // Bloquear dia inteiro ou faixa de horário
    public function storeBlock(Request $request)
    {
        $data = $request->validate([
            'date'       => ['required', 'date', 'after_or_equal:today'],
            'start_time' => ['nullable', 'date_format:H:i', 'required_with:end_time'],
            'end_time'   => ['nullable', 'date_format:H:i', 'after:start_time'],
            'reason'     => ['nullable', 'string', 'max:100'],
        ]);

        $request->user()->professionalProfile->blocks()->create($data);

        return back()->with('status', 'Bloqueio criado.');
    }

    public function destroyBlock(Request $request, AvailabilityBlock $block)
    {
        abort_unless($block->professional_profile_id === $request->user()->professionalProfile->id, 403);
        $block->delete();
        return back()->with('status', 'Bloqueio removido.');
    }

    // Submenu: editar perfil
    public function editProfile(Request $request)
    {
        $profile = $request->user()->professionalProfile->load('categories');

        return view('professional.profile-edit', [
            'profile'    => $profile,
            'categories' => Category::where('active', true)->orderBy('name')->get(),
            'address'    => $request->user()->address,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'bio'        => ['nullable', 'string', 'max:1000'],
            'photo'      => ['nullable', 'image', 'max:2048'],
            'phone'      => ['required', 'string', 'min:10', 'max:20'],
            'cep'        => ['required', 'string', 'size:9'],
            'street'     => ['required', 'string', 'max:150'],
            'number'     => ['required', 'string', 'max:20'],
            'complement' => ['nullable', 'string', 'max:100'],
            'district'   => ['required', 'string', 'max:100'],
            'city'       => ['required', 'string', 'max:100'],
            'state'      => ['required', 'string', 'size:2'],
        ]);

        $user    = $request->user();
        $profile = $user->professionalProfile;

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->storeAs(
                'profiles', Str::uuid().'.'.$request->file('photo')->extension(), 'public'
            );
            $profile->update(['photo' => $data['photo']]);
        }

        $profile->update(['bio' => $data['bio'] ?? null]);
        $user->update(['phone' => preg_replace('/\D/', '', $data['phone'])]);

        $geo = GeocodingService::geocode($data['street'], $data['city'], $data['state'], $data['cep']);
        $user->address()->updateOrCreate([], [
            'cep' => $data['cep'], 'street' => $data['street'], 'number' => $data['number'],
            'complement' => $data['complement'] ?? null, 'district' => $data['district'],
            'city' => $data['city'], 'state' => strtoupper($data['state']),
            'latitude' => $geo['lat'] ?? null, 'longitude' => $geo['lng'] ?? null,
        ]);

        return back()->with('status', 'Perfil atualizado!');
    }

    // Submenu: alterar valores (preço por categoria)
    public function editPrices(Request $request)
    {
        return view('professional.prices', [
            'profile' => $request->user()->professionalProfile->load('categories'),
        ]);
    }

    public function updatePrices(Request $request)
    {
        $data = $request->validate([
            'prices'   => ['required', 'array'],
            'prices.*' => ['numeric', 'min:0', 'max:99999'],
        ]);

        $profile = $request->user()->professionalProfile;

        foreach ($data['prices'] as $categoryId => $price) {
            $profile->categories()->updateExistingPivot($categoryId, ['price' => $price]);
        }

        return back()->with('status', 'Valores atualizados!');
    }
}
