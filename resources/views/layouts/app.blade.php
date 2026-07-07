<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Beework — Serviços com quem entende')</title>
    <link rel="icon" href="{{ asset('img/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Baloo+2:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        bee: { yellow: '#E8B33C', soft: '#F5D78A', black: '#1E1E1E', cream: '#FDF9F0' },
                    },
                    fontFamily: {
                        display: ['"Baloo 2"', 'cursive'],
                        body: ['Inter', 'sans-serif'],
                    },
                    borderRadius: { bee: '16px' },
                },
            },
        };
    </script>
    <link rel="stylesheet" href="{{ asset('css/beework.css') }}">
    @stack('head')
</head>
<body class="font-body bg-white text-bee-black min-h-screen flex flex-col">

    <nav class="bg-white border-b border-bee-cream sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ auth()->check() ? auth()->user()->homeRoute() : route('landing') }}" class="flex items-center gap-2">
                <img src="{{ asset('img/logo.png') }}" alt="Beework" class="h-10 w-10 rounded-full object-cover"
                     onerror="this.style.display='none'">
                <span class="font-display font-bold text-2xl">Bee<span class="text-bee-yellow">work</span></span>
            </a>

            <div class="flex items-center gap-3 text-sm font-medium">
                @guest
                    <a href="{{ route('login') }}" class="px-4 py-2 rounded-bee hover:bg-bee-cream transition">Entrar</a>
                    <a href="{{ route('cadastro.cliente') }}" class="px-4 py-2 rounded-bee bg-bee-yellow font-semibold hover:brightness-105 transition shadow-sm">Procure por serviços</a>
                    <a href="{{ route('cadastro.profissional') }}" class="hidden sm:inline px-4 py-2 rounded-bee border-2 border-bee-yellow font-semibold hover:bg-bee-cream transition">Trabalhe conosco</a>
                @else
                    @if(auth()->user()->isClient())
                        <a href="{{ route('meus-pedidos') }}" class="px-3 py-2 rounded-bee hover:bg-bee-cream transition">Meus pedidos</a>
                    @endif
                    <span class="hidden sm:inline text-gray-500">Olá, {{ Str::before(auth()->user()->name, ' ') }} 🐝</span>
                    <form method="POST" action="{{ route('logout') }}">@csrf
                        <button class="px-3 py-2 rounded-bee hover:bg-bee-cream transition">Sair</button>
                    </form>
                @endguest
            </div>
        </div>
    </nav>

    @if (session('status'))
        <div class="max-w-6xl mx-auto px-4 mt-4 w-full">
            <div class="bg-bee-soft/40 border border-bee-yellow text-bee-black px-4 py-3 rounded-bee text-sm">
                🐝 {{ session('status') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="max-w-6xl mx-auto px-4 mt-4 w-full">
            <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-bee text-sm">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                </ul>
            </div>
        </div>
    @endif

    <main class="flex-1">
        @yield('content')
    </main>

    <footer class="bg-bee-black text-white mt-16">
        <div class="max-w-6xl mx-auto px-4 py-10 grid gap-8 sm:grid-cols-3 text-sm">
            <div>
                <p class="font-display font-bold text-xl mb-2">Bee<span class="text-bee-yellow">work</span></p>
                <p class="text-gray-400">Conectando você aos melhores profissionais da sua região, com leveza e segurança.</p>
            </div>
            <div>
                <p class="font-semibold mb-2 text-bee-yellow">Legal</p>
                <a href="{{ route('privacidade') }}" class="block text-gray-400 hover:text-white transition">Política de Privacidade (LGPD)</a>
                <a href="{{ route('termos') }}" class="block text-gray-400 hover:text-white transition">Termos de Uso</a>
            </div>
            <div>
                <p class="font-semibold mb-2 text-bee-yellow">Contato</p>
                <p class="text-gray-400">contato@beework.com.br</p>
            </div>
        </div>
        <div class="border-t border-white/10 py-4 text-center text-xs text-gray-500">
            © {{ date('Y') }} Beework. Todos os direitos reservados.
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
