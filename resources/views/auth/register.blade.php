@extends('layouts.app')
@section('title', $tipo === 'profissional' ? 'Trabalhe conosco — Beework' : 'Crie sua conta — Beework')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-12">
    <h1 class="font-display font-bold text-3xl text-center">
        {{ $tipo === 'profissional' ? '💼 Trabalhe conosco' : '🔎 Procure por serviços' }}
    </h1>
    <p class="text-center text-gray-600 mt-2">
        {{ $tipo === 'profissional'
            ? 'Cadastre-se, comprove suas qualificações e comece a receber clientes.'
            : 'Crie sua conta e encontre os melhores profissionais perto de você.' }}
    </p>

    <form method="POST" action="{{ route('cadastro.store', $tipo) }}" enctype="multipart/form-data"
          class="mt-8 bg-bee-cream rounded-bee p-6 sm:p-8 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-semibold mb-1">Nome completo *</label>
            <input type="text" name="name" value="{{ old('name') }}" required
                   class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">CPF *</label>
                <input type="text" name="cpf" id="cpf" value="{{ old('cpf') }}" required maxlength="14" placeholder="000.000.000-00"
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Telefone / WhatsApp *</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required placeholder="(31) 99999-9999"
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">E-mail *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Data de nascimento * <span class="text-xs text-gray-500">(18+)</span></label>
                <input type="date" name="birth_date" value="{{ old('birth_date') }}" required
                       max="{{ now()->subYears(18)->toDateString() }}"
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
            </div>
        </div>

        <div class="grid sm:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Senha *</label>
                <input type="password" name="password" required
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
                <p class="text-xs text-gray-500 mt-1">Mín. 8 caracteres, com maiúscula, número e símbolo.</p>
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Confirmar senha *</label>
                <input type="password" name="password_confirmation" required
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
            </div>
        </div>

        <hr class="border-bee-soft">
        <h2 class="font-display font-semibold text-lg">📍 Endereço</h2>

        <div class="grid sm:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">CEP *</label>
                <input type="text" name="cep" id="cep" value="{{ old('cep') }}" required maxlength="9" placeholder="00000-000"
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
                <p class="text-xs text-gray-500 mt-1" id="cep-status">Preenche o endereço automaticamente</p>
            </div>
            <div class="sm:col-span-2">
                <label class="block text-sm font-semibold mb-1">Rua *</label>
                <input type="text" name="street" id="street" value="{{ old('street') }}" required
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
            </div>
        </div>

        <div class="grid sm:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Número *</label>
                <input type="text" name="number" value="{{ old('number') }}" required
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Complemento</label>
                <input type="text" name="complement" value="{{ old('complement') }}"
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Bairro *</label>
                <input type="text" name="district" id="district" value="{{ old('district') }}" required
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div class="col-span-2">
                    <label class="block text-sm font-semibold mb-1">Cidade *</label>
                    <input type="text" name="city" id="city" value="{{ old('city') }}" required
                           class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">UF *</label>
                    <input type="text" name="state" id="state" value="{{ old('state') }}" required maxlength="2"
                           class="w-full rounded-bee border-gray-300 border px-4 py-2.5 uppercase focus:ring-2 focus:ring-bee-yellow focus:outline-none">
                </div>
            </div>
        </div>

        @if ($tipo === 'profissional')
            <hr class="border-bee-soft">
            <h2 class="font-display font-semibold text-lg">🛠️ Sua atuação</h2>

            <div>
                <label class="block text-sm font-semibold mb-2">Categorias que você atende *</label>
                <div class="grid sm:grid-cols-2 gap-2">
                    @foreach ($categories as $category)
                        <label class="flex items-center gap-2 bg-white rounded-bee px-4 py-3 cursor-pointer border border-transparent has-[:checked]:border-bee-yellow transition">
                            <input type="checkbox" name="categories[]" value="{{ $category->id }}"
                                   class="cat-check accent-[#E8B33C]"
                                   data-requires="{{ $category->requires_qualification ? 1 : 0 }}"
                                   data-name="{{ $category->name }}"
                                   @checked(in_array($category->id, old('categories', [])))>
                            <span>{{ $category->icon }} {{ $category->name }}</span>
                            @if ($category->requires_qualification)
                                <span class="ml-auto text-xs bg-bee-soft px-2 py-0.5 rounded-full">exige certificado</span>
                            @endif
                        </label>
                    @endforeach
                </div>
            </div>

            <div id="qualification-uploads" class="space-y-3"></div>

            <div>
                <label class="block text-sm font-semibold mb-1">Fale um pouco sobre você (opcional)</label>
                <textarea name="bio" rows="3" maxlength="1000"
                          class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:ring-2 focus:ring-bee-yellow focus:outline-none">{{ old('bio') }}</textarea>
            </div>

            <p class="text-xs text-gray-600 bg-white rounded-bee p-3">
                ℹ️ Seu cadastro passará pela análise da nossa equipe antes de ficar visível no site.
                Você receberá um e-mail assim que for aprovado.
            </p>
        @endif

        <label class="flex items-start gap-2 text-sm">
            <input type="checkbox" name="lgpd" value="1" required class="mt-1 accent-[#E8B33C]">
            <span>Li e aceito a <a href="{{ route('privacidade') }}" target="_blank" class="underline font-semibold">Política de Privacidade</a>
            e os <a href="{{ route('termos') }}" target="_blank" class="underline font-semibold">Termos de Uso</a>,
            conforme a Lei Geral de Proteção de Dados (LGPD). *</span>
        </label>

        <button type="submit"
                class="w-full py-3.5 rounded-bee bg-bee-yellow font-display font-bold text-lg shadow-md hover:brightness-105 transition">
            Criar minha conta 🐝
        </button>

        <p class="text-center text-sm text-gray-600">
            Já tem conta? <a href="{{ route('login') }}" class="font-semibold underline">Entrar</a>
        </p>
    </form>
</div>

@push('scripts')
<script>
// Máscaras simples
document.getElementById('cpf')?.addEventListener('input', e => {
    e.target.value = e.target.value.replace(/\D/g, '').slice(0, 11)
        .replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d)/, '$1.$2').replace(/(\d{3})(\d{1,2})$/, '$1-$2');
});
document.getElementById('phone')?.addEventListener('input', e => {
    e.target.value = e.target.value.replace(/\D/g, '').slice(0, 11)
        .replace(/(\d{2})(\d)/, '($1) $2').replace(/(\d{5})(\d)/, '$1-$2');
});

// Requisito 6: ViaCEP — preenche endereço pelo CEP
const cepInput = document.getElementById('cep');
cepInput?.addEventListener('input', e => {
    e.target.value = e.target.value.replace(/\D/g, '').slice(0, 8).replace(/(\d{5})(\d)/, '$1-$2');
});
cepInput?.addEventListener('blur', async e => {
    const cep = e.target.value.replace(/\D/g, '');
    const status = document.getElementById('cep-status');
    if (cep.length !== 8) return;
    status.textContent = 'Buscando...';
    try {
        const res = await fetch(`https://viacep.com.br/ws/${cep}/json/`);
        const data = await res.json();
        if (data.erro) { status.textContent = 'CEP não encontrado.'; return; }
        document.getElementById('street').value   = data.logradouro || '';
        document.getElementById('district').value = data.bairro || '';
        document.getElementById('city').value     = data.localidade || '';
        document.getElementById('state').value    = data.uf || '';
        status.textContent = 'Endereço preenchido! ✅';
    } catch { status.textContent = 'Erro ao buscar o CEP.'; }
});

// Requisito 2: upload obrigatório para categorias regulamentadas
const uploadsBox = document.getElementById('qualification-uploads');
function refreshUploads() {
    if (!uploadsBox) return;
    uploadsBox.innerHTML = '';
    document.querySelectorAll('.cat-check:checked').forEach(check => {
        if (check.dataset.requires !== '1') return;
        const div = document.createElement('div');
        div.className = 'bg-white rounded-bee p-4 border border-bee-yellow';
        div.innerHTML = `
            <label class="block text-sm font-semibold mb-1">
                📜 Comprovação de qualificação — ${check.dataset.name} *
            </label>
            <input type="file" name="qualification_${check.value}" required
                   accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm">
            <p class="text-xs text-gray-500 mt-1">PDF, JPG ou PNG. Máx. 5 MB. (ex.: certificado NR-10, curso técnico)</p>`;
        uploadsBox.appendChild(div);
    });
}
document.querySelectorAll('.cat-check').forEach(c => c.addEventListener('change', refreshUploads));
refreshUploads();
</script>
@endpush
@endsection
