<?php

namespace App\Http\Controllers\Professional;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Services\AuditService;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Submenu: pendentes | feitos | futuros | rejeitados
    public function index(Request $request, string $filtro)
    {
        $profile = $request->user()->professionalProfile;
        $query   = $profile->bookings()->with(['client', 'category']);

        $query = match ($filtro) {
            'pendentes'  => $query->where('status', 'pending'),
            'feitos'     => $query->where('status', 'completed'),
            'futuros'    => $query->where('status', 'accepted')->whereDate('scheduled_date', '>=', today()),
            'rejeitados' => $query->where('status', 'rejected'),
            default      => abort(404),
        };

        return view('professional.services', [
            'bookings' => $query->orderBy('scheduled_date')->paginate(15),
            'filtro'   => $filtro,
        ]);
    }

    public function accept(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);
        $booking->update(['status' => 'accepted']);
        AuditService::log('booking_accepted:'.$booking->id);
        return back()->with('status', 'Serviço confirmado!');
    }

    public function reject(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);
        $data = $request->validate(['rejection_reason' => ['required', 'string', 'max:300']]);
        $booking->update(['status' => 'rejected', 'rejection_reason' => $data['rejection_reason']]);
        AuditService::log('booking_rejected:'.$booking->id);
        return back()->with('status', 'Serviço rejeitado.');
    }

    public function complete(Request $request, Booking $booking)
    {
        $this->authorizeBooking($request, $booking);
        $booking->update(['status' => 'completed']);
        AuditService::log('booking_completed:'.$booking->id);
        return back()->with('status', 'Serviço concluído!');
    }

    private function authorizeBooking(Request $request, Booking $booking): void
    {
        abort_unless(
            $booking->professional_profile_id === $request->user()->professionalProfile->id, 403
        );
    }
}
