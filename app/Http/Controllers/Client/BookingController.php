<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ProfessionalProfile;
use App\Services\AuditService;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function showProfessional(ProfessionalProfile $profile)
    {
        abort_unless($profile->status === 'approved', 404);
        $profile->load(['user.address', 'categories']);

        return view('client.professional-show', ['profile' => $profile]);
    }

    public function store(Request $request, ProfessionalProfile $profile)
    {
        abort_unless($profile->status === 'approved', 404);

        $data = $request->validate([
            'category_id'    => ['required', 'exists:categories,id'],
            'scheduled_date' => ['required', 'date', 'after_or_equal:today'],
            'scheduled_time' => ['required', 'date_format:H:i'],
            'notes'          => ['nullable', 'string', 'max:500'],
        ]);

        $category = $profile->categories()->where('categories.id', $data['category_id'])->first();
        abort_unless($category, 422, 'O profissional não atende esta categoria.');

        if ($profile->isBlockedAt($data['scheduled_date'], $data['scheduled_time'])) {
            return back()->withErrors(['scheduled_time' => 'O profissional não está disponível neste dia/horário. Escolha outro.'])->withInput();
        }

        $address = $request->user()->address;

        Booking::create([
            'client_id'               => $request->user()->id,
            'professional_profile_id' => $profile->id,
            'category_id'             => $category->id,
            'scheduled_date'          => $data['scheduled_date'],
            'scheduled_time'          => $data['scheduled_time'],
            'price'                   => $category->pivot->price,
            'notes'                   => $data['notes'] ?? null,
            'address_snapshot'        => $address?->only(['cep','street','number','complement','district','city','state']),
            'status'                  => 'pending',
        ]);

        AuditService::log('booking_created');

        return redirect()->route('meus-pedidos')->with('status', 'Solicitação enviada! O profissional irá confirmar em breve.');
    }

    public function myBookings(Request $request)
    {
        $bookings = $request->user()->bookingsAsClient()
            ->with(['professionalProfile.user', 'category'])
            ->orderByDesc('created_at')->paginate(15);

        return view('client.my-bookings', ['bookings' => $bookings]);
    }
}
