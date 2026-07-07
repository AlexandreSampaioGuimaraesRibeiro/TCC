@extends('layouts.app')
@section('title', 'Usuários — Beework')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <a href="{{ route('admin.dashboard') }}" class="text-sm underline text-gray-500">← Painel</a>
    <h1 class="font-display font-bold text-3xl mt-2">👥 Usuários</h1>

    <form method="GET" class="mt-4 flex gap-2">
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar por nome ou e-mail..."
               class="flex-1 rounded-bee border border-gray-300 px-4 py-2.5">
        <button class="px-5 rounded-bee bg-bee-yellow font-semibold">Buscar</button>
    </form>

    <div class="mt-6 space-y-2">
        @forelse ($users as $user)
            <div class="flex flex-wrap items-center justify-between bg-white border border-bee-cream rounded-bee px-4 py-3 gap-3">
                <div>
                    <p class="font-semibold">{{ $user->name }}
                        <span class="text-xs bg-bee-cream px-2 py-0.5 rounded-full ml-1">{{ $user->role === 'professional' ? 'Profissional' : 'Cliente' }}</span>
                    </p>
                    <p class="text-sm text-gray-500">{{ $user->email }} · {{ $user->phone }}</p>
                </div>
                <form method="POST" action="{{ route('admin.usuarios.suspender', $user) }}"
                      onsubmit="return confirm('Suspender este usuário?')">
                    @csrf
                    <button class="px-4 py-2 rounded-bee bg-red-500 text-white text-sm font-semibold hover:brightness-105">Suspender</button>
                </form>
            </div>
        @empty
            <div class="bg-bee-cream rounded-bee p-10 text-center text-gray-600">Nenhum usuário encontrado.</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $users->links() }}</div>
</div>
@endsection
