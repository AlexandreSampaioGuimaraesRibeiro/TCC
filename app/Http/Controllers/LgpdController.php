<?php

namespace App\Http\Controllers;

use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LgpdController extends Controller
{
    // Direito de portabilidade: baixar os próprios dados (JSON)
    public function export(Request $request)
    {
        $user = $request->user()->load(['address', 'professionalProfile.categories', 'bookingsAsClient']);
        AuditService::log('lgpd_data_export');

        return response()->json($user->toArray(), 200, [
            'Content-Disposition' => 'attachment; filename="meus-dados-beework.json"',
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    // Direito de exclusão: softDelete + logout (anonimização definitiva via job após 30 dias)
    public function destroy(Request $request)
    {
        $request->validate(['confirm' => ['accepted']], ['confirm.accepted' => 'Confirme a exclusão marcando a caixa.']);

        $user = $request->user();
        AuditService::log('lgpd_account_deletion', $user->id);

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('landing')->with('status', 'Sua conta foi excluída. Seus dados serão removidos definitivamente em até 30 dias, conforme nossa Política de Privacidade.');
    }
}
