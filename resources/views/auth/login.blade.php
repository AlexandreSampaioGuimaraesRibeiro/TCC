@extends('layouts.app')
@section('title', 'Entrar — Beework')

@section('content')
<div class="max-w-md mx-auto px-4 py-16">
    <h1 class="font-display font-bold text-3xl text-center">Bem-vindo de volta! 🐝</h1>

    <form method="POST" action="{{ route('login.store') }}" class="mt-8 bg-bee-cream rounded-bee p-8 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-semibold mb-1">E-mail</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus
                   class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Senha</label>
            <input type="password" name="password" required
                   class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
        </div>
        <div class="flex items-center justify-between text-sm">
            <label class="flex items-center gap-2">
                <input type="checkbox" name="remember" class="accent-[#E8B33C]"> Lembrar de mim
            </label>
            <a href="{{ route('password.request') }}" class="underline font-semibold">Esqueci a senha</a>
        </div>
        <button class="w-full py-3 rounded-bee bg-bee-yellow font-display font-bold text-lg shadow-md hover:brightness-105 transition">Entrar</button>
        <p class="text-center text-sm text-gray-600">
            Não tem conta?
            <a href="{{ route('cadastro.cliente') }}" class="font-semibold underline">Procure serviços</a> ·
            <a href="{{ route('cadastro.profissional') }}" class="font-semibold underline">Trabalhe conosco</a>
        </p>
    </form>
</div>
@endsection
