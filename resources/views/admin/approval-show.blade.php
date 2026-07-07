@extends('layouts.app')
@section('title', 'Analisar cadastro — Beework')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">
    <a href="{{ route('admin.aprovacoes') }}" class="text-sm underline text-gray-500">← Fila de aprovação</a>

    <div class="mt-4 bg-bee-cream rounded-bee p-6 sm:p-8">
        <h1 class="font-display font-bold text-2xl">{{ $profile->user->name }}</h1>

        <dl class="mt-4 grid sm:grid-cols-2 gap-x-6 gap-y-3 text-sm">
            <div><dt class="text-gray-500">E-mail</dt><dd class="font-semibold">{{ $profile->user->email }}</dd></div>
            <div><dt class="text-gray-500">Telefone</dt><dd class="font-semibold">{{ $profile->user->phone }}</dd></div>
            <div><dt class="text-gray-500">CPF</dt><dd class="font-semibold">{{ $profile->user->cpf }}</dd></div>
            <div><dt class="text-gray-500">Nascimento</dt><dd class="font-semibold">{{ $profile->user->birth_date->format('d/m/Y') }} ({{ $profile->user->birth_date->age }} anos)</dd></div>
            <div class="sm:col-span-2"><dt class="text-gray-500">Endereço</dt>
                <dd class="font-semibold">
                    {{ $profile->user->address?->street }}, {{ $profile->user->address?->number }}
                    — {{ $profile->user->address?->district }}, {{ $profile->user->address?->city }}/{{ $profile->user->address?->state }}
                    · CEP {{ $profile->user->address?->cep }}
                </dd>
            </div>
            @if ($profile->bio)
                <div class="sm:col-span-2"><dt class="text-gray-500">Bio</dt><dd>{{ $profile->bio }}</dd></div>
            @endif
        </dl>

        <h2 class="font-display font-semibold text-lg mt-6">Categorias solicitadas</h2>
        <div class="mt-2 flex flex-wrap gap-2">
            @foreach ($profile->categories as $cat)
                <span class="bg-white px-3 py-1.5 rounded-full text-sm">{{ $cat->icon }} {{ $cat->name }}
                    @if ($cat->requires_qualification)<strong class="text-bee-yellow"> · exige certificado</strong>@endif
                </span>
            @endforeach
        </div>

        <h2 class="font-display font-semibold text-lg mt-6">📜 Documentos enviados</h2>
        @forelse ($profile->qualifications as $q)
            <div class="mt-2 flex items-center justify-between bg-white rounded-bee px-4 py-3">
                <span class="text-sm">{{ $q->category->name }} — {{ $q->original_name }}</span>
                <a href="{{ route('admin.documento', $q) }}" target="_blank"
                   class="text-sm font-semibold underline">Ver documento →</a>
            </div>
        @empty
            <p class="text-sm text-gray-600 mt-2">Nenhum documento enviado (nenhuma categoria selecionada exige).</p>
        @endforelse
    </div>

    <div class="mt-6 grid sm:grid-cols-2 gap-4">
        <form method="POST" action="{{ route('admin.aprovacoes.aprovar', $profile) }}"
              onsubmit="return confirm('Aprovar este profissional?')">
            @csrf
            <button class="w-full py-3.5 rounded-bee bg-green-500 text-white font-display font-bold text-lg shadow-md hover:brightness-105 transition">
                ✅ Aprovar profissional
            </button>
        </form>

        <details class="bg-white border border-red-200 rounded-bee">
            <summary class="cursor-pointer py-3.5 text-center font-display font-bold text-lg text-red-600">🚫 Rejeitar</summary>
            <form method="POST" action="{{ route('admin.aprovacoes.rejeitar', $profile) }}" class="p-4 space-y-3">
                @csrf
                <textarea name="rejection_reason" required maxlength="500" rows="2" placeholder="Motivo da rejeição (será enviado por e-mail)"
                          class="w-full rounded-bee border border-gray-300 px-3 py-2 text-sm"></textarea>
                <button class="w-full py-2.5 rounded-bee bg-red-500 text-white font-semibold">Confirmar rejeição</button>
            </form>
        </details>
    </div>
</div>
@endsection
