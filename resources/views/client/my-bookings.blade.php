@extends('layouts.app')
@section('title', 'Meus pedidos — Beework')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <h1 class="font-display font-bold text-3xl">Meus pedidos</h1>

    <div class="mt-6 space-y-4">
        @forelse ($bookings as $booking)
            <div class="bg-white border border-bee-cream rounded-bee p-5 shadow-sm flex flex-wrap items-center justify-between gap-3">
                <div>
                    <p class="font-semibold">{{ $booking->category->icon }} {{ $booking->category->name }}
                        com {{ $booking->professionalProfile->user->name }}</p>
                    <p class="text-sm text-gray-500">
                        {{ $booking->scheduled_date->format('d/m/Y') }} às {{ Str::substr($booking->scheduled_time, 0, 5) }}
                        @if ($booking->price > 0) · R$ {{ number_format($booking->price, 2, ',', '.') }} @endif
                    </p>
                    @if ($booking->status === 'rejected' && $booking->rejection_reason)
                        <p class="text-xs text-red-600 mt-1">Motivo: {{ $booking->rejection_reason }}</p>
                    @endif
                </div>
                <div class="flex items-center gap-3">
                    <span class="text-xs px-3 py-1.5 rounded-full font-semibold
                        @switch($booking->status)
                            @case('pending') bg-bee-soft @break
                            @case('accepted') bg-green-100 text-green-800 @break
                            @case('completed') bg-blue-100 text-blue-800 @break
                            @default bg-red-100 text-red-800
                        @endswitch">
                        {{ $booking->statusLabel() }}
                    </span>
                    @if ($booking->status === 'accepted')
                        <a href="https://wa.me/55{{ $booking->professionalProfile->user->phone }}" target="_blank"
                           class="text-xs bg-green-500 text-white px-3 py-1.5 rounded-full font-semibold hover:brightness-105">
                            WhatsApp 💬
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="bg-bee-cream rounded-bee p-10 text-center text-gray-600">
                Você ainda não solicitou nenhum serviço. <a href="{{ route('home') }}" class="underline font-semibold">Encontre um profissional</a> 🐝
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $bookings->links() }}</div>
</div>
@endsection
