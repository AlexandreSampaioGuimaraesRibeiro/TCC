{{-- Requisito 9: submenu do profissional --}}
<aside class="sm:w-56 shrink-0">
    <nav class="bg-bee-cream rounded-bee p-3 space-y-1 text-sm font-medium sticky top-20">
        @foreach ([
            ['painel.agenda', '📅 Minha agenda', request()->routeIs('painel.agenda')],
            ['painel.perfil', '✏️ Editar perfil', request()->routeIs('painel.perfil')],
            ['painel.valores', '💰 Alterar valores', request()->routeIs('painel.valores')],
        ] as [$route, $label, $active])
            <a href="{{ route($route) }}"
               class="block px-4 py-2.5 rounded-bee transition {{ $active ? 'bg-bee-yellow font-semibold' : 'hover:bg-bee-soft' }}">
                {{ $label }}
            </a>
        @endforeach

        <hr class="border-bee-soft my-2">

        @foreach ([
            ['pendentes', '⏳ Serviços pendentes'],
            ['futuros', '📌 Serviços futuros'],
            ['feitos', '✅ Serviços feitos'],
            ['rejeitados', '🚫 Serviços rejeitados'],
        ] as [$filtro, $label])
            <a href="{{ route('painel.servicos', $filtro) }}"
               class="block px-4 py-2.5 rounded-bee transition
                      {{ request()->route('filtro') === $filtro ? 'bg-bee-yellow font-semibold' : 'hover:bg-bee-soft' }}">
                {{ $label }}
            </a>
        @endforeach
    </nav>
</aside>
