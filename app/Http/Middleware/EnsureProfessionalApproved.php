<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureProfessionalApproved
{
    public function handle(Request $request, Closure $next)
    {
        $profile = $request->user()?->professionalProfile;

        if (!$profile || $profile->status !== 'approved') {
            return redirect()->route('painel.aguardando');
        }
        return $next($request);
    }
}
