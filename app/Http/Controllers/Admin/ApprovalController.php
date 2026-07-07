<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProfessionalProfile;
use App\Models\Qualification;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ApprovalController extends Controller
{
    // Requisito 3: fila de aprovação
    public function index()
    {
        $profiles = ProfessionalProfile::where('status', 'pending')
            ->with(['user.address', 'categories', 'qualifications.category'])
            ->orderBy('created_at')->paginate(15);

        return view('admin.approvals', ['profiles' => $profiles]);
    }

    public function show(ProfessionalProfile $profile)
    {
        $profile->load(['user.address', 'categories', 'qualifications.category']);
        return view('admin.approval-show', ['profile' => $profile]);
    }

    public function approve(Request $request, ProfessionalProfile $profile)
    {
        $profile->update([
            'status'      => 'approved',
            'approved_by' => $request->user()->id,
            'approved_at' => now(),
        ]);
        $profile->qualifications()->update([
            'status' => 'approved', 'reviewed_by' => $request->user()->id, 'reviewed_at' => now(),
        ]);

        AuditService::log('professional_approved:'.$profile->id);
        $this->notify($profile, 'Parabéns! Seu cadastro na Beework foi aprovado. Já pode acessar seu painel e começar a receber serviços: '.route('login'));

        return back()->with('status', 'Profissional aprovado!');
    }

    public function reject(Request $request, ProfessionalProfile $profile)
    {
        $data = $request->validate(['rejection_reason' => ['required', 'string', 'max:500']]);

        $profile->update(['status' => 'rejected', 'rejection_reason' => $data['rejection_reason']]);
        AuditService::log('professional_rejected:'.$profile->id);
        $this->notify($profile, "Seu cadastro na Beework não foi aprovado. Motivo: {$data['rejection_reason']}. Você pode ajustar seus dados e documentos e tentar novamente.");

        return redirect()->route('admin.aprovacoes')->with('status', 'Profissional rejeitado.');
    }

    // Serve documento privado apenas para admin autenticado
    public function document(Qualification $qualification)
    {
        AuditService::log('qualification_viewed:'.$qualification->id);
        abort_unless(Storage::disk('local')->exists($qualification->file_path), 404);

        return Storage::disk('local')->response($qualification->file_path, $qualification->original_name);
    }

    private function notify(ProfessionalProfile $profile, string $message): void
    {
        try {
            Mail::raw($message, function ($mail) use ($profile) {
                $mail->to($profile->user->email)->subject('Beework — Status do seu cadastro');
            });
        } catch (\Throwable $e) {
            report($e);
        }
    }
}
