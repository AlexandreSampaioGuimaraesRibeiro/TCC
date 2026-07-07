@extends('layouts.app')
@section('title', $profile->user->name.' — Beework')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <a href="{{ url()->previous() }}" class="text-sm underline text-gray-500">← Voltar</a>

    <div class="mt-4 bg-bee-cream rounded-bee p-6 sm:p-8">
        <div class="flex items-center gap-4">
            @if ($profile->photo)
                <img src="{{ asset('storage/'.$profile->photo) }}" class="h-20 w-20 rounded-full object-cover" alt="">
            @else
                <div class="h-20 w-20 rounded-full bg-bee-soft flex items-center justify-center text-3xl font-display font-bold">
                    {{ Str::upper(Str::substr($profile->user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h1 class="font-display font-bold text-2xl">{{ $profile->user->name }}</h1>
                <p class="text-sm text-gray-600">📍 {{ $profile->user->address?->city }} — {{ $profile->user->address?->state }}</p>
                <span class="inline-block mt-1 text-xs bg-green-100 text-green-800 px-2.5 py-1 rounded-full">✅ Verificado pela Beework</span>
            </div>
        </div>

        @if ($profile->bio)
            <p class="mt-4 text-gray-700">{{ $profile->bio }}</p>
        @endif

        <div class="mt-4">
            <h2 class="font-semibold text-sm text-gray-500 uppercase tracking-wide">Serviços e valores</h2>
            <div class="mt-2 space-y-2">
                @foreach ($profile->categories as $cat)
                    <div class="flex justify-between bg-white rounded-bee px-4 py-3">
                        <span>{{ $cat->icon }} {{ $cat->name }}</span>
                        <strong class="font-display">
                            {{ $cat->pivot->price > 0 ? 'R$ '.number_format($cat->pivot->price, 2, ',', '.') : 'A combinar' }}
                        </strong>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('booking.store', $profile) }}" class="mt-6 bg-white border border-bee-cream rounded-bee p-6 sm:p-8 space-y-4 shadow-sm">
        @csrf
        <h2 class="font-display font-bold text-xl">📅 Solicitar serviço</h2>

        <div>
            <label class="block text-sm font-semibold mb-1">Serviço *</label>
            <select name="category_id" required class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
                @foreach ($profile->categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Data *</label>
                <input type="date" name="scheduled_date" required min="{{ today()->toDateString() }}"
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Horário *</label>
                <input type="time" name="scheduled_time" required
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
            </div>
        </div>

        <div>
            <label class="block text-sm font-semibold mb-1">Detalhes do serviço (opcional)</label>
            <textarea name="notes" rows="3" maxlength="500" placeholder="Descreva o que precisa..."
                      class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none"></textarea>
        </div>

        <button class="w-full py-3.5 rounded-bee bg-bee-yellow font-display font-bold text-lg shadow-md hover:brightness-105 transition">
            Enviar solicitação 🐝
        </button>
    </form>
</div>
@endsection
