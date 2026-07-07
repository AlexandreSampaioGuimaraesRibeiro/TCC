<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ProfessionalProfile;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'pendingCount'  => ProfessionalProfile::where('status', 'pending')->count(),
            'approvedCount' => ProfessionalProfile::where('status', 'approved')->count(),
            'clientCount'   => User::where('role', 'client')->count(),
            'bookingsMonth' => Booking::whereMonth('created_at', now()->month)->count(),
        ]);
    }
}
