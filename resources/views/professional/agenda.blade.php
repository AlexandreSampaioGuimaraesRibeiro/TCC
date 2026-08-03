@extends('layouts.app')
@section('title', 'Minha agenda — Beework')

@push('head')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales/pt-br.global.min.js"></script>
<style>
    /* Evento com conteúdo customizado — sem texto cortado feio */
    .fc .fc-event-main { padding: 2px 4px; overflow: hidden; }
    .fc-ev { line-height: 1.2; overflow: hidden; }
    .fc-ev .fc-ev-time { font-weight: 700; font-size: .72rem; }
    .fc-ev .fc-ev-title { font-size: .72rem; font-weight: 600;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .fc-ev .fc-ev-sub { font-size: .68rem; opacity: .85;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    /* Slots mais altos = evento de 1h cabe as linhas */
    .fc .fc-timegrid-slot { height: 2.6rem; }
    /* Toolbar quebra melhor no mobile */
    @media (max-width: 640px) {
        .fc .fc-toolbar { flex-direction: column; gap: .5rem; }
        .fc .fc-toolbar-title { font-size: 1.05rem; }
    }
    /* All-day (bloqueio) também com ellipsis */
    .fc-daygrid-event .fc-event-title { overflow: hidden; text-overflow: ellipsis; }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 flex flex-col sm:flex-row gap-6">
    @include('professional._menu')

    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <h1 class="font-display font-bold text-2xl">📅 Minha agenda</h1>
            <button onclick="document.getElementById('block-modal').classList.remove('hidden')"
                    class="px-4 py-2 rounded-bee bg-bee-black text-white text-sm font-semibold hover:opacity-90 transition">
                🚫 Bloquear dia/horário
            </button>
        </div>
        <p class="text-sm text-gray-500 mt-1">
            <span class="inline-block h-3 w-3 rounded-full bg-bee-yellow align-middle"></span> confirmado ·
            <span class="inline-block h-3 w-3 rounded-full bg-bee-soft align-middle"></span> pendente ·
            <span class="inline-block h-3 w-3 rounded-full bg-gray-400 align-middle"></span> bloqueado
        </p>

        <div class="mt-4 bg-white border border-bee-cream rounded-bee p-2 sm:p-4 shadow-sm overflow-x-auto">
            <div id="calendar" class="min-w-[640px] sm:min-w-0"></div>
        </div>
    </div>
</div>

{{-- Modal de bloqueio --}}
<div id="block-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <form method="POST" action="{{ route('painel.bloqueio.store') }}"
          class="bg-white rounded-bee p-6 w-full max-w-md space-y-4">
        @csrf
        <h2 class="font-display font-bold text-xl">Bloquear agenda</h2>
        <div>
            <label class="block text-sm font-semibold mb-1">Data *</label>
            <input type="date" name="date" id="block-date" required min="{{ today()->toDateString() }}"
                   class="w-full rounded-bee border-gray-300 border px-4 py-2.5">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Início <span class="text-xs text-gray-500">(vazio = dia todo)</span></label>
                <input type="time" name="start_time" class="w-full rounded-bee border-gray-300 border px-4 py-2.5">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Fim</label>
                <input type="time" name="end_time" class="w-full rounded-bee border-gray-300 border px-4 py-2.5">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Motivo (opcional)</label>
            <input type="text" name="reason" maxlength="100" placeholder="Ex.: consulta médica"
                   class="w-full rounded-bee border-gray-300 border px-4 py-2.5">
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="document.getElementById('block-modal').classList.add('hidden')"
                    class="flex-1 py-2.5 rounded-bee border border-gray-300 font-semibold">Cancelar</button>
            <button class="flex-1 py-2.5 rounded-bee bg-bee-yellow font-display font-bold">Bloquear</button>
        </div>
    </form>
</div>

{{-- Form escondido pra remover bloqueio --}}
<form id="unblock-form" method="POST" class="hidden">@csrf @method('DELETE')</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        locale: 'pt-br',
        initialView: window.innerWidth < 640 ? 'timeGridDay' : 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,timeGridDay', // Requisito 9: semanal e diária
        },
        buttonText: { today: 'Hoje', week: 'Semana', day: 'Dia' },
        allDaySlot: true,
        allDayText: 'Dia todo',
        slotMinTime: '06:00:00',
        slotMaxTime: '22:00:00',
        height: 'auto',
        expandRows: true,
        nowIndicator: true,
        events: '{{ route('painel.agenda.eventos') }}',

        eventContent(arg) {
            const p = arg.event.extendedProps;
            const wrap = document.createElement('div');
            wrap.className = 'fc-ev';

            // Bloqueio (all-day): só o motivo
            if (p.blockId) {
                wrap.innerHTML = `<div class="fc-ev-title">🚫 ${arg.event.title}</div>`;
                return { domNodes: [wrap] };
            }

            const hora = arg.timeText || '';
            const tag  = p.pendente ? '⏳ ' : '';
            wrap.innerHTML = `
                <div class="fc-ev-time">${hora}</div>
                <div class="fc-ev-title">${tag}${arg.event.title}</div>
                ${p.cliente ? `<div class="fc-ev-sub">${p.cliente}</div>` : ''}
            `;
            return { domNodes: [wrap] };
        },

        dateClick(info) {
            // clique em dia/horário vazio abre o modal de bloqueio já preenchido
            document.getElementById('block-date').value = info.dateStr.substring(0, 10);
            document.getElementById('block-modal').classList.remove('hidden');
        },
        eventClick(info) {
            const blockId = info.event.extendedProps.blockId;
            if (blockId && confirm('Remover este bloqueio?')) {
                const form = document.getElementById('unblock-form');
                form.action = '{{ url('painel/bloqueio') }}/' + blockId;
                form.submit();
            }
        },
    });
    calendar.render();
});
</script>
@endpush
@endsection