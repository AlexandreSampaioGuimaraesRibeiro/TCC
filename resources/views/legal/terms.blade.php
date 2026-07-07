@extends('layouts.app')
@section('title', 'Termos de Uso — Beework')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-12">
    <h1 class="font-display font-bold text-3xl">Termos de Uso</h1>
    <p class="text-sm text-gray-500 mt-1">Última atualização: {{ date('d/m/Y') }}</p>

    <div class="mt-6 space-y-6 text-gray-700 leading-relaxed">
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">1. O serviço</h2>
            <p>A Beework é uma plataforma de intermediação que conecta clientes a profissionais autônomos de serviços. A Beework não é empregadora dos profissionais nem parte na relação contratual entre cliente e profissional.</p>
        </section>
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">2. Cadastro</h2>
            <p>É necessário ter no mínimo 18 anos e fornecer dados verdadeiros e atualizados. Profissionais de categorias regulamentadas devem comprovar qualificação, sujeita à análise e aprovação da nossa equipe. Cadastros com informações falsas serão suspensos.</p>
        </section>
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">3. Responsabilidades</h2>
            <p>O profissional é responsável pela qualidade e execução do serviço prestado. O cliente é responsável pelas informações fornecidas na solicitação. A Beework atua na verificação inicial dos cadastros, mas recomenda que as partes confirmem detalhes antes do serviço.</p>
        </section>
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">4. Conduta</h2>
            <p>É proibido usar a plataforma para fins ilícitos, publicar conteúdo ofensivo, ou tentar burlar mecanismos de segurança. Violações podem resultar em suspensão imediata da conta.</p>
        </section>
        <section>
            <h2 class="font-display font-semibold text-xl text-bee-black">5. Alterações</h2>
            <p>Estes termos podem ser atualizados. Alterações relevantes serão comunicadas por e-mail. O uso contínuo da plataforma após a atualização representa concordância.</p>
        </section>
    </div>
</div>
@endsection
