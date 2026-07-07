<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::where('role', '!=', 'admin')
            ->when($request->q, fn ($q) => $q->where(fn ($w) =>
                $w->where('name', 'like', "%{$request->q}%")->orWhere('email', 'like', "%{$request->q}%")))
            ->orderBy('name')->paginate(20);

        return view('admin.users', ['users' => $users]);
    }

    public function suspend(User $user)
    {
        abort_if($user->isAdmin(), 403);
        $user->delete(); // softDelete
        AuditService::log('user_suspended:'.$user->id);
        return back()->with('status', 'Usuário suspenso.');
    }
}
