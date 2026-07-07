@extends('layouts.app')
@section('title', 'Aprovações — Beework')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-10">
    <a href="{{ route('admin.dashboard') }}" class="text-sm underline text-gray-500">← Painel</a>
    <h1 class="font-display font-bold text-3xl mt-2">⏳ Fila de aprovação</h1>

    <div class="mt-6 space-y-4">
        @forelse ($profiles as $profile)
            <a href="{{ route('admin.aprovacoes.show', $profile) }}"
               class="block bg-white border border-bee-cream rounded-bee p-5 shadow-sm hover:shadow-md transition">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="font-semibold">{{ $profile->user->name }}</p>
                        <p class="text-sm text-gray-500">
                            {{ $profile->user->email }} · {{ $profile->user->address?->city }}/{{ $profile->user->address?->state }}
                            · cadastrado {{ $profile->created_at->diffForHumans() }}
                        </p>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach ($profile->categories as $cat)
                                <span class="text-xs bg-bee-cream px-2.5 py-1 rounded-full">{{ $cat->icon }} {{ $cat->name }}
                                    @if ($cat->requires_qualification) 📜 @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                    <span class="text-sm font-semibold text-bee-yellow">Analisar →</span>
                </div>
            </a>
        @empty
            <div class="bg-bee-cream rounded-bee p-10 text-center text-gray-600">Nenhum cadastro aguardando aprovação. 🎉</div>
        @endforelse
    </div>

    <div class="mt-6">{{ $profiles->links() }}</div>
</div>
@endsection
