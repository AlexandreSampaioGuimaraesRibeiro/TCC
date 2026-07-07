@extends('layouts.app')
@section('title', 'Categorias — Beework')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">
    <a href="{{ route('admin.dashboard') }}" class="text-sm underline text-gray-500">← Painel</a>
    <h1 class="font-display font-bold text-3xl mt-2">🗂️ Categorias</h1>

    <form method="POST" action="{{ route('admin.categorias.store') }}" class="mt-6 bg-bee-cream rounded-bee p-5 flex flex-wrap gap-3 items-end">
        @csrf
        <div class="flex-1 min-w-40">
            <label class="block text-xs font-semibold mb-1">Nome</label>
            <input type="text" name="name" required class="w-full rounded-bee border border-gray-300 px-3 py-2">
        </div>
        <div class="w-20">
            <label class="block text-xs font-semibold mb-1">Ícone</label>
            <input type="text" name="icon" maxlength="10" placeholder="🐝" class="w-full rounded-bee border border-gray-300 px-3 py-2">
        </div>
        <label class="flex items-center gap-2 text-sm pb-2.5">
            <input type="checkbox" name="requires_qualification" value="1" class="accent-[#E8B33C]"> Exige certificado
        </label>
        <button class="px-5 py-2.5 rounded-bee bg-bee-yellow font-semibold">Adicionar</button>
    </form>

    <div class="mt-6 space-y-2">
        @foreach ($categories as $category)
            <div class="flex flex-wrap items-center justify-between bg-white border border-bee-cream rounded-bee px-4 py-3 gap-3">
                <span class="font-semibold {{ $category->active ? '' : 'opacity-40 line-through' }}">
                    {{ $category->icon }} {{ $category->name }}
                </span>
                <div class="flex gap-2 text-xs">
                    <form method="POST" action="{{ route('admin.categorias.toggle', [$category, 'requires_qualification']) }}">@csrf
                        <button class="px-3 py-1.5 rounded-full {{ $category->requires_qualification ? 'bg-bee-yellow' : 'bg-gray-200' }} font-semibold">
                            📜 {{ $category->requires_qualification ? 'Exige certificado' : 'Sem certificado' }}
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.categorias.toggle', [$category, 'active']) }}">@csrf
                        <button class="px-3 py-1.5 rounded-full {{ $category->active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }} font-semibold">
                            {{ $category->active ? 'Ativa' : 'Inativa' }}
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
