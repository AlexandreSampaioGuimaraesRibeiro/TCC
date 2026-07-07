@extends('layouts.app')
@section('title', 'Editar perfil — Beework')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 flex flex-col sm:flex-row gap-6">
    @include('professional._menu')

    <div class="flex-1 min-w-0">
        <h1 class="font-display font-bold text-2xl">✏️ Editar perfil</h1>

        <form method="POST" action="{{ route('painel.perfil.update') }}" enctype="multipart/form-data"
              class="mt-4 bg-bee-cream rounded-bee p-6 space-y-4">
            @csrf

            <div class="flex items-center gap-4">
                @if ($profile->photo)
                    <img src="{{ asset('storage/'.$profile->photo) }}" class="h-16 w-16 rounded-full object-cover" alt="">
                @endif
                <div class="flex-1">
                    <label class="block text-sm font-semibold mb-1">Foto de perfil</label>
                    <input type="file" name="photo" accept="image/*" class="text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Sobre você</label>
                <textarea name="bio" rows="3" maxlength="1000"
                          class="w-full rounded-bee border-gray-300 border px-4 py-2.5">{{ old('bio', $profile->bio) }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Telefone / WhatsApp *</label>
                <input type="text" name="phone" value="{{ old('phone', $profile->user->phone) }}" required
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5">
            </div>

            <h2 class="font-display font-semibold text-lg pt-2">📍 Endereço</h2>
            <div class="grid sm:grid-cols-3 gap-4">
                <input type="text" name="cep" id="cep" value="{{ old('cep', $address?->cep) }}" required maxlength="9" placeholder="CEP"
                       class="rounded-bee border-gray-300 border px-4 py-2.5">
                <input type="text" name="street" id="street" value="{{ old('street', $address?->street) }}" required placeholder="Rua"
                       class="sm:col-span-2 rounded-bee border-gray-300 border px-4 py-2.5">
            </div>
            <div class="grid sm:grid-cols-4 gap-4">
                <input type="text" name="number" value="{{ old('number', $address?->number) }}" required placeholder="Número"
                       class="rounded-bee border-gray-300 border px-4 py-2.5">
                <input type="text" name="complement" value="{{ old('complement', $address?->complement) }}" placeholder="Complemento"
                       class="rounded-bee border-gray-300 border px-4 py-2.5">
                <input type="text" name="district" id="district" value="{{ old('district', $address?->district) }}" required placeholder="Bairro"
                       class="rounded-bee border-gray-300 border px-4 py-2.5">
                <div class="grid grid-cols-3 gap-2">
                    <input type="text" name="city" id="city" value="{{ old('city', $address?->city) }}" required placeholder="Cidade"
                           class="col-span-2 rounded-bee border-gray-300 border px-4 py-2.5">
                    <input type="text" name="state" id="state" value="{{ old('state', $address?->state) }}" required maxlength="2" placeholder="UF"
                           class="rounded-bee border-gray-300 border px-4 py-2.5 uppercase">
                </div>
            </div>

            <button class="w-full py-3 rounded-bee bg-bee-yellow font-display font-bold shadow-md hover:brightness-105 transition">
                Salvar alterações
            </button>
        </form>

        {{-- LGPD --}}
        <div class="mt-8 bg-white border border-bee-cream rounded-bee p-6">
            <h2 class="font-display font-semibold text-lg">🔒 Seus dados (LGPD)</h2>
            <div class="mt-3 flex flex-wrap gap-3 items-center">
                <a href="{{ route('lgpd.export') }}" class="px-4 py-2 rounded-bee border border-gray-300 text-sm font-semibold hover:bg-bee-cream transition">
                    ⬇️ Baixar meus dados
                </a>
                <form method="POST" action="{{ route('lgpd.destroy') }}"
                      onsubmit="return confirm('Tem certeza? Sua conta será excluída.')" class="flex items-center gap-2">
                    @csrf
                    <label class="text-xs flex items-center gap-1">
                        <input type="checkbox" name="confirm" value="1" required class="accent-[#E8B33C]"> Confirmo a exclusão
                    </label>
                    <button class="px-4 py-2 rounded-bee bg-red-500 text-white text-sm font-semibold hover:brightness-105">Excluir minha conta</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
const cepInput = document.getElementById('cep');
cepInput?.addEventListener('blur', async e => {
    const cep = e.target.value.replace(/\D/g, '');
    if (cep.length !== 8) return;
    try {
        const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const data = await res.json();
        if (data.erro) return;
        document.getElementById('street').value   = data.logradouro || '';
        document.getElementById('district').value = data.bairro || '';
        document.getElementById('city').value     = data.localidade || '';
        document.getElementById('state').value    = data.uf || '';
    } catch {}
});
</script>
@endpush
@endsection
