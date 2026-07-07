@extends('layouts.app')
@section('title', 'Política de Privacidade — Beework')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12 prose-sm">
    <h1 class="font-display font-bold text-3xl">Política de Privacidade</h1>
    <p class="text-sm text-gray-500 mt-1">Última atualização: {{ date('d/m/Y') }} · Em conformidade com a Lei nº 13.709/2018 (LGPD)</p>

    <div class="mt-6 space-y-6 text-gray-700 leading-relaxed">
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">1. Quem somos</h2>
            <p>A Beework ("nós") é uma plataforma que conecta clientes a profissionais de serviços. Somos os controladores dos dados pessoais tratados nesta plataforma. Contato do encarregado (DPO): contato@beework.com.br.</p>
        </section>
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">2. Dados que coletamos</h2>
            <p>Coletamos apenas o necessário para o funcionamento do serviço: nome completo, CPF, e-mail, telefone, data de nascimento, endereço e, para profissionais, documentos de comprovação de qualificação. Também registramos dados técnicos (IP e navegador) em logs de auditoria de segurança.</p>
        </section>
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">3. Para que usamos</h2>
            <p>Utilizamos seus dados para: criar e manter sua conta; conectar clientes e profissionais próximos (base legal: execução de contrato); verificar qualificações profissionais (obrigação legal e legítimo interesse); enviar comunicações essenciais sobre seus serviços; e cumprir obrigações legais. Não vendemos seus dados a terceiros.</p>
        </section>
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">4. Localização</h2>
            <p>Sua localização aproximada (via navegador, com sua permissão, ou via endereço cadastrado) é usada exclusivamente para mostrar profissionais próximos. Você pode negar a permissão de localização no navegador sem perder acesso à plataforma.</p>
        </section>
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">5. Compartilhamento</h2>
            <p>Ao confirmar um serviço, compartilhamos com o profissional apenas os dados necessários ao atendimento (nome, telefone e endereço do serviço). Documentos de qualificação são acessíveis somente à equipe administrativa da Beework.</p>
        </section>
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">6. Seus direitos (art. 18, LGPD)</h2>
            <p>Você pode, a qualquer momento: confirmar a existência de tratamento; acessar e corrigir seus dados; solicitar portabilidade (botão "Baixar meus dados" no seu perfil); revogar consentimento; e solicitar a exclusão da sua conta (botão "Excluir minha conta"). Após a solicitação de exclusão, os dados são removidos definitivamente em até 30 dias, salvo obrigação legal de retenção.</p>
        </section>
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">7. Segurança</h2>
            <p>Adotamos medidas técnicas como criptografia de senhas (bcrypt), conexões seguras, controle de acesso por perfil, limitação de tentativas de login e registro de auditoria de ações sensíveis.</p>
        </section>
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">8. Retenção</h2>
            <p>Mantemos seus dados enquanto sua conta estiver ativa. Logs de auditoria são mantidos por até 12 meses para fins de segurança.</p>
        </section>
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">9. Contato</h2>
            <p>Dúvidas sobre esta política ou sobre seus dados: contato@beework.com.br. Você também pode contatar a Autoridade Nacional de Proteção de Dados (ANPD).</p>
        </section>
    </div>
</div>
@endsection
