@extends('layouts.app')
@section('title', 'Alterar valores — Beework')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 flex flex-col sm:flex-row gap-6">
    @include('professional._menu')

    <div class="flex-1 min-w-0">
        <h1 class="font-display font-bold text-2xl">💰 Alterar valores</h1>
        <p class="text-sm text-gray-500 mt-1">Defina o valor de cada serviço que você oferece. Deixe 0 para "a combinar".</p>

        <form method="POST" action="{{ route('painel.valores.update') }}" class="mt-4 bg-bee-cream rounded-bee p-6 space-y-3">
            @csrf
            @foreach ($profile->categories as $cat)
                <div class="flex items-center justify-between bg-white rounded-bee px-4 py-3 gap-4">
                    <span class="font-semibold">{{ $cat->icon }} {{ $cat->name }}</span>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-gray-500">R$</span>
                        <input type="number" name="prices[{{ $cat->id }}]" value="{{ $cat->pivot->price }}"
                               min="0" max="99999" step="0.01"
                               class="w-32 rounded-bee border-gray-300 border px-3 py-2 text-right">
                    </div>
                </div>
            @endforeach

            <button class="w-full py-3 rounded-bee bg-bee-yellow font-display font-bold shadow-md hover:brightness-105 transition">
                Salvar valores
            </button>
        </form>
    </div>
</div>
@endsection
