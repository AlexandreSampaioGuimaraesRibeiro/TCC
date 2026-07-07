@extends('layouts.app')
@section('title', 'Nova senha — Beework')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <h1 class="font-display font-bold text-3xl text-center">Definir nova senha</h1>

    <form method="POST" action="{{ route('password.update') }}" class="mt-8 bg-bee-cream rounded-bee p-8 space-y-4">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div>
            <label class="block text-sm font-semibold mb-1">E-mail</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" required
                   class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Nova senha</label>
            <input type="password" name="password" required
                   class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
            <p class="text-xs text-gray-500 mt-1">Mín. 8 caracteres, com maiúscula, número e símbolo.</p>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Confirmar nova senha</label>
            <input type="password" name="password_confirmation" required
                   class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
        </div>
        <button class="w-full py-3 rounded-bee bg-bee-yellow font-display font-bold shadow-md hover:brightness-105 transition">Redefinir senha</button>
    </form>
</div>
@endsection
