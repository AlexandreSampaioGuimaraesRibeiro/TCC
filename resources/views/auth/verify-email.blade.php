@extends('layouts.app')
@section('title', 'Verifique seu e-mail — Beework')

@section('content')
<div class="max-w-md mx-auto px-4 py-16 text-center">
    <div class="text-5xl">📧</div>
    <h1 class="font-display font-bold text-3xl mt-4">Verifique seu e-mail</h1>
    <p class="text-gray-600 mt-2">
        Enviamos um link de confirmação para <strong>{{ auth()->user()->email }}</strong>.
        Clique nele para ativar sua conta.
    </p>
    <form method="POST" action="{{ route('verification.send') }}" class="mt-6">
        @csrf
        <button class="px-6 py-3 rounded-bee bg-bee-yellow font-display font-bold shadow-md hover:brightness-105 transition">
            Reenviar e-mail
        </button>
    </form>
</div>
@endsection
