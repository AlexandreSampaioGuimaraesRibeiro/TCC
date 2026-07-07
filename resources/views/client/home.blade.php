@extends('layouts.app')
@section('title', 'Início — Beework')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">

    {{-- Requisito 8: submenu com cada tipo de profissional --}}
    <div class="flex gap-3 overflow-x-auto pb-3 -mx-1 px-1">
        <a href="{{ route('home') }}"
           class="shrink-0 px-5 py-2.5 rounded-full font-semibold text-sm transition
                  {{ !$currentSlug ? 'bg-bee-yellow shadow' : 'bg-bee-cream hover:bg-bee-soft' }}">
            🐝 Todos
        </a>
        @foreach ($categories as $category)
            <a href="{{ route('servicos.categoria', $category->slug) }}"
               class="shrink-0 px-5 py-2.5 rounded-full font-semibold text-sm transition
                      {{ $currentSlug === $category->slug ? 'bg-bee-yellow shadow' : 'bg-bee-cream hover:bg-bee-soft' }}">
                {{ $category->icon }} {{ $category->name }}
            </a>
        @endforeach
    </div>

    <div class="mt-6 flex items-center justify-between flex-wrap gap-2">
        <h1 class="font-display font-bold text-2xl">
            {{ isset($currentCategory) ? $currentCategory->icon.' '.$currentCategory->name.' perto de você' : 'Profissionais perto de você' }}
        </h1>
        <span id="geo-status" class="text-sm text-gray-500">📍 Usando o endereço do seu cadastro</span>
    </div>

    <div id="professionals-grid" class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @forelse ($professionals as $p)
            <a href="{{ route('profissional.show', $p) }}"
               class="bg-white border border-bee-cream rounded-bee p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition block">
                <div class="flex items-center gap-3">
                    @if ($p->photo)
                        <img src="{{ asset('storage/'.$p->photo) }}" class="h-14 w-14 rounded-full object-cover" alt="">
                    @else
                        <div class="h-14 w-14 rounded-full bg-bee-soft flex items-center justify-center text-xl font-display font-bold">
                            {{ Str::upper(Str::substr($p->user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="font-semibold">{{ $p->user->name }}</p>
                        <p class="text-xs text-gray-500">
                            {{ $p->user->address?->city }}
                            @if (isset($p->distance_km)) · {{ number_format($p->distance_km, 1, ',') }} km @endif
                        </p>
                    </div>
                </div>
                <div class="mt-3 flex flex-wrap gap-1.5">
                    @foreach ($p->categories as $cat)
                        <span class="text-xs bg-bee-cream px-2.5 py-1 rounded-full">{{ $cat->icon }} {{ $cat->name }}</span>
                    @endforeach
                </div>
                @php $min = $p->categories->min('pivot.price'); @endphp
                @if ($min > 0)
                    <p class="mt-3 text-sm">A partir de <strong class="font-display">R$ {{ number_format($min, 2, ',', '.') }}</strong></p>
                @endif
            </a>
        @empty
            <div class="col-span-full bg-bee-cream rounded-bee p-10 text-center text-gray-600">
                🐝 Ainda não encontramos profissionais {{ isset($currentCategory) ? 'de '.$currentCategory->name : '' }} na sua região.
                <br>Volte em breve — nossa colmeia cresce todo dia!
            </div>
        @endforelse
    </div>
</div>

@push('scripts')
<script>
// Requisito 8: Geolocation API — refina a lista com a posição real do usuário
(function () {
    if (!navigator.geolocation) return;
    const status = document.getElementById('geo-status');

    navigator.geolocation.getCurrentPosition(async pos => {
        status.textContent = '📍 Buscando profissionais na sua localização...';
        try {
            const params = new URLSearchParams({
                lat: pos.coords.latitude,
                lng: pos.coords.longitude,
                @if ($currentSlug) slug: '{{ $currentSlug }}', @endif
            });
            const res = await fetch(`{{ route('api.proximos') }}?${params}`, {
                headers: { 'Accept': 'application/json' },
            });
            if (!res.ok) throw new Error();
            const pros = await res.json();
            if (!pros.length) { status.textContent = '📍 Usando sua localização atual'; return; }

            const grid = document.getElementById('professionals-grid');
            grid.innerHTML = pros.map(p => `
                <a href="${p.url}" class="bg-white border border-bee-cream rounded-bee p-5 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition block">
                    <div class="flex items-center gap-3">
                        ${p.photo
                            ? `<img src="${p.photo}" class="h-14 w-14 rounded-full object-cover" alt="">`
                            : `<div class="h-14 w-14 rounded-full bg-bee-soft flex items-center justify-center text-xl font-bold">${p.name.charAt(0).toUpperCase()}</div>`}
                        <div>
                            <p class="font-semibold">${p.name}</p>
                            <p class="text-xs text-gray-500">${p.city ?? ''} · ${p.distance} km</p>
                        </div>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-1.5">
                        ${p.categories.map(c => `<span class="text-xs bg-bee-cream px-2.5 py-1 rounded-full">${c}</span>`).join('')}
                    </div>
                    ${p.min_price > 0 ? `<p class="mt-3 text-sm">A partir de <strong>R$ ${Number(p.min_price).toFixed(2).replace('.', ',')}</strong></p>` : ''}
                </a>`).join('');
            status.textContent = '📍 Usando sua localização atual';
        } catch { status.textContent = '📍 Usando o endereço do seu cadastro'; }
    }, () => { /* usuário negou: mantém a lista pelo endereço do cadastro */ });
})();
</script>
@endpush
@endsection
