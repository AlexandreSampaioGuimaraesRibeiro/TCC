@extends('layouts.app')
@section('title', 'Cadastro em análise — Beework')

@section('content')
<div class="max-w-md mx-auto px-4 py-20 text-center">
    @if ($profile?->status === 'rejected')
        <div class="text-5xl">😔</div>
        <h1 class="font-display font-bold text-3xl mt-4">Cadastro não aprovado</h1>
        <p class="text-gray-600 mt-2">Motivo: {{ $profile->rejection_reason }}</p>
        <p class="text-sm text-gray-500 mt-4">Entre em contato conosco para ajustar seus dados: contato@beework.com.br</p>
    @else
        <div class="text-5xl">🐝⏳</div>
        <h1 class="font-display font-bold text-3xl mt-4">Cadastro em análise</h1>
        <p class="text-gray-600 mt-2">
            Nossa equipe está revisando seus dados e qualificações.
            Você receberá um e-mail assim que seu perfil for aprovado!
        </p>
    @endif
</div>
@endsection
