@extends('layouts.app')
@section('title', 'Serviços '.$filtro.' — Beework')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 flex flex-col sm:flex-row gap-6">
    @include('professional._menu')

    <div class="flex-1 min-w-0">
        <h1 class="font-display font-bold text-2xl capitalize">Serviços {{ $filtro }}</h1>

        <div class="mt-4 space-y-4">
            @forelse ($bookings as $booking)
                <div class="bg-white border border-bee-cream rounded-bee p-5 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <p class="font-semibold">{{ $booking->category->icon }} {{ $booking->category->name }} — {{ $booking->client->name }}</p>
                            <p class="text-sm text-gray-500">
                                {{ $booking->scheduled_date->format('d/m/Y') }} às {{ Str::substr($booking->scheduled_time, 0, 5) }}
                                @if ($booking->price > 0) · R$ {{ number_format($booking->price, 2, ',', '.') }} @endif
                            </p>
                            @if ($booking->address_snapshot)
                                <p class="text-xs text-gray-500 mt-1">
                                    📍 {{ $booking->address_snapshot['street'] ?? '' }}, {{ $booking->address_snapshot['number'] ?? '' }}
                                    — {{ $booking->address_snapshot['district'] ?? '' }}, {{ $booking->address_snapshot['city'] ?? '' }}
                                </p>
                            @endif
                            @if ($booking->notes)
                                <p class="text-sm mt-2 bg-bee-cream rounded-bee px-3 py-2">💬 {{ $booking->notes }}</p>
                            @endif
                            @if ($filtro === 'rejeitados' && $booking->rejection_reason)
                                <p class="text-xs text-red-600 mt-1">Motivo: {{ $booking->rejection_reason }}</p>
                            @endif
                        </div>

                        <div class="flex flex-col gap-2 items-end">
                            @if ($filtro === 'pendentes')
                                <form method="POST" action="{{ route('painel.servico.aceitar', $booking) }}">@csrf
                                    <button class="px-4 py-2 rounded-bee bg-green-500 text-white text-sm font-semibold hover:brightness-105">✅ Aceitar</button>
                                </form>
                                <details class="text-right">
                                    <summary class="cursor-pointer text-sm text-red-600 font-semibold">🚫 Rejeitar</summary>
                                    <form method="POST" action="{{ route('painel.servico.rejeitar', $booking) }}" class="mt-2 flex gap-2">
                                        @csrf
                                        <input type="text" name="rejection_reason" required maxlength="300" placeholder="Motivo"
                                               class="rounded-bee border border-gray-300 px-3 py-1.5 text-sm">
                                        <button class="px-3 py-1.5 rounded-bee bg-red-500 text-white text-sm font-semibold">OK</button>
                                    </form>
                                </details>
                            @elseif ($filtro === 'futuros')
                                <a href="https://wa.me/55{{ $booking->client->phone }}" target="_blank"
                                   class="px-4 py-2 rounded-bee bg-green-500 text-white text-sm font-semibold hover:brightness-105">WhatsApp 💬</a>
                                <form method="POST" action="{{ route('painel.servico.concluir', $booking) }}">@csrf
                                    <button class="px-4 py-2 rounded-bee bg-bee-yellow text-sm font-semibold hover:brightness-105">Marcar como feito ✅</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-bee-cream rounded-bee p-10 text-center text-gray-600">
                    Nenhum serviço {{ $filtro }} por aqui. 🐝
                </div>
            @endforelse
        </div>

        <div class="mt-6">{{ $bookings->links() }}</div>
    </div>
</div>
@endsection
