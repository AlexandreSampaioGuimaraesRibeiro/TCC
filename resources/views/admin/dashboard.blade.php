@extends('layouts.app')
@section('title', 'Admin — Beework')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="font-display font-bold text-3xl">Painel administrativo 🐝</h1>

    <div class="mt-6 grid gap-4 sm:grid-cols-4">
        @foreach ([
            ['⏳', $pendingCount, 'Aguardando aprovação', route('admin.aprovacoes')],
            ['✅', $approvedCount, 'Profissionais ativos', null],
            ['👤', $clientCount, 'Clientes', route('admin.usuarios')],
            ['📅', $bookingsMonth, 'Serviços no mês', null],
        ] as [$icon, $count, $label, $link])
            <a href="{{ $link ?? '#' }}" class="bg-bee-cream rounded-bee p-5 {{ $link ? 'hover:shadow-md hover:-translate-y-0.5' : 'cursor-default' }} transition block">
                <div class="text-2xl">{{ $icon }}</div>
                <p class="font-display font-bold text-3xl mt-1">{{ $count }}</p>
                <p class="text-sm text-gray-600">{{ $label }}</p>
            </a>
        @endforeach
    </div>

    <div class="mt-8 flex flex-wrap gap-3">
        <a href="{{ route('admin.aprovacoes') }}" class="px-5 py-3 rounded-bee bg-bee-yellow font-semibold shadow-sm hover:brightness-105 transition">⏳ Fila de aprovação</a>
        <a href="{{ route('admin.categorias') }}" class="px-5 py-3 rounded-bee border-2 border-bee-yellow font-semibold hover:bg-bee-cream transition">🗂️ Categorias</a>
        <a href="{{ route('admin.usuarios') }}" class="px-5 py-3 rounded-bee border-2 border-bee-yellow font-semibold hover:bg-bee-cream transition">👥 Usuários</a>
    </div>
</div>
@endsection
