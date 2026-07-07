@extends('layouts.app')
@section('title', 'Recuperar senha — Beework')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <h1 class="font-display font-bold text-3xl text-center">Recuperar senha 🔑</h1>
    <p class="text-center text-gray-600 mt-2 text-sm">Informe seu e-mail e enviaremos um link para redefinir sua senha.</p>

    <form method="POST" action="{{ route('password.email') }}" class="mt-8 bg-bee-cream rounded-bee p-8 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-semibold mb-1">E-mail</label>
            <input type="email" name="email" required autofocus
                   class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
        </div>
        <button class="w-full py-3 rounded-bee bg-bee-yellow font-display font-bold shadow-md hover:brightness-105 transition">Enviar link de recuperação</button>
        <p class="text-center text-sm"><a href="{{ route('login') }}" class="underline">Voltar ao login</a></p>
    </form>
</div>
@endsection
