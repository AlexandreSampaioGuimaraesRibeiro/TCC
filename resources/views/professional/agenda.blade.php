@extends('layouts.app')
@section('title', 'Minha agenda — Beework')

@push('head')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.15/locales/pt-br.global.min.js"></script>
<style>
    :root {
        --bee-yellow: #E8B33C;
        --bee-soft:   #F5D78A;
        --bee-black:  #1E1E1E;
        --bee-cream:  #F3E8CE;
    }

    /* ---------- Estrutura geral do calendário ---------- */
    #calendar {
        --fc-border-color: #EFE7D4;
        --fc-today-bg-color: #FBF4E1;
        --fc-now-indicator-color: var(--bee-yellow);
        --fc-page-bg-color: transparent;
        font-family: inherit;
    }

    /* Cabeçalho dos dias (dom/seg/ter...) */
    .fc .fc-col-header-cell {
        background: #FBF7EC;
        padding: .35rem 0;
    }
    .fc .fc-col-header-cell-cushion {
        color: var(--bee-black);
        font-weight: 700;
        text-transform: capitalize;
        font-size: .8rem;
        padding: 6px 4px;
    }
    /* Coluna "Dia todo" e horas */
    .fc .fc-timegrid-axis-cushion,
    .fc .fc-timegrid-slot-label-cushion {
        color: #8A8578;
        font-size: .72rem;
        font-weight: 600;
    }

    /* Linha do "agora" mais visível */
    .fc .fc-timegrid-now-indicator-line { border-width: 2px; }

    /* ---------- Toolbar / botões ---------- */
    .fc .fc-toolbar-title {
        font-weight: 800;
        color: var(--bee-black);
        letter-spacing: -.01em;
    }
    .fc .fc-button {
        background: #fff;
        border: 1px solid #E7DEC9;
        color: var(--bee-black);
        font-weight: 600;
        text-transform: capitalize;
        box-shadow: 0 1px 2px rgba(30,30,30,.04);
        transition: all .15s ease;
    }
    .fc .fc-button:hover { background: #FBF4E1; border-color: var(--bee-yellow); }
    .fc .fc-button-primary:not(:disabled).fc-button-active,
    .fc .fc-button-primary:not(:disabled):active {
        background: var(--bee-black);
        border-color: var(--bee-black);
        color: #fff;
    }
    .fc .fc-button:focus { box-shadow: 0 0 0 3px rgba(232,179,60,.35); }
    .fc .fc-today-button:disabled {
        background: #F5F1E6; border-color: #E7DEC9; color: #B4AE9C; opacity: 1;
    }

    /* ---------- Eventos ---------- */
    .fc .fc-timegrid-event,
    .fc .fc-daygrid-event {
        border: none;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(30,30,30,.12);
        transition: transform .12s ease, box-shadow .12s ease;
        overflow: hidden;
    }
    .fc .fc-timegrid-event:hover,
    .fc .fc-daygrid-event:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 10px rgba(30,30,30,.18);
        cursor: pointer;
        z-index: 5;
    }
    .fc .fc-event-main { padding: 0; overflow: hidden; }

    .fc-ev {
        position: relative;
        height: 100%;
        padding: 3px 6px 3px 10px;
        line-height: 1.2;
        overflow: hidden;
    }
    /* Barra lateral colorida por status */
    .fc-ev::before {
        content: '';
        position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px; border-radius: 8px 0 0 8px;
        background: rgba(0,0,0,.28);
    }
    .fc-ev.is-pending::before { background: var(--bee-yellow); }
    .fc-ev.is-block::before   { background: #6B7280; }

    .fc-ev-head {
        display: flex; align-items: center; gap: 4px;
        margin-bottom: 1px;
    }
    .fc-ev-time {
        font-weight: 800; font-size: .72rem;
        letter-spacing: -.01em;
    }
    .fc-ev-badge {
        font-size: .58rem; font-weight: 700;
        text-transform: uppercase; letter-spacing: .03em;
        padding: 1px 5px; border-radius: 999px;
        background: rgba(30,30,30,.14); color: var(--bee-black);
        white-space: nowrap;
    }
    .fc-ev-title {
        font-size: .74rem; font-weight: 700;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }
    .fc-ev-sub {
        display: flex; align-items: center; gap: 3px;
        font-size: .68rem; font-weight: 500; opacity: .8;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    /* Bloqueio (all-day) mais discreto, listrado */
    .fc-ev.is-block {
        background: repeating-linear-gradient(45deg,#EDEEF0,#EDEEF0 8px,#E4E6E9 8px,#E4E6E9 16px);
        color: #4B5563;
    }
    .fc-ev.is-block .fc-ev-title { font-weight: 700; }

    /* Slots com altura confortável */
    .fc .fc-timegrid-slot { height: 2.7rem; }

    /* ---------- Mobile ---------- */
    @media (max-width: 640px) {
        .fc .fc-toolbar { flex-direction: column; gap: .5rem; }
        .fc .fc-toolbar-title { font-size: 1.1rem; }
        .fc .fc-button { padding: .3rem .6rem; font-size: .8rem; }
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8 flex flex-col sm:flex-row gap-6">
    @include('professional._menu')

    <div class="flex-1 min-w-0">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h1 class="font-display font-bold text-2xl flex items-center gap-2">
                    <span class="inline-flex items-center justify-center h-9 w-9 rounded-bee bg-bee-yellow/20 text-lg">📅</span>
                    Minha agenda
                </h1>
            </div>
            <button onclick="document.getElementById('block-modal').classList.remove('hidden')"
                    class="px-4 py-2 rounded-bee bg-bee-black text-white text-sm font-semibold hover:opacity-90 transition inline-flex items-center gap-2 shadow-sm">
                🚫 Bloquear dia/horário
            </button>
        </div>

        {{-- Legenda em "chips" --}}
        <div class="flex flex-wrap items-center gap-2 mt-3">
            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 bg-white border border-bee-cream rounded-full px-3 py-1">
                <span class="h-2.5 w-2.5 rounded-full" style="background:#E8B33C"></span> Confirmado
            </span>
            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 bg-white border border-bee-cream rounded-full px-3 py-1">
                <span class="h-2.5 w-2.5 rounded-full" style="background:#F5D78A"></span> Pendente
            </span>
            <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 bg-white border border-bee-cream rounded-full px-3 py-1">
                <span class="h-2.5 w-2.5 rounded-full" style="background:#9CA3AF"></span> Bloqueado
            </span>
        </div>

        <div class="mt-4 bg-white border border-bee-cream rounded-bee p-2 sm:p-5 shadow-sm overflow-x-auto">
            <div id="calendar" class="min-w-[640px] sm:min-w-0"></div>
        </div>
    </div>
</div>

{{-- Modal de bloqueio --}}
<div id="block-modal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <form method="POST" action="{{ route('painel.bloqueio.store') }}"
          class="bg-white rounded-bee p-6 w-full max-w-md space-y-4 shadow-xl">
        @csrf
        <h2 class="font-display font-bold text-xl flex items-center gap-2">🚫 Bloquear agenda</h2>
        <div>
            <label class="block text-sm font-semibold mb-1">Data *</label>
            <input type="date" name="date" id="block-date" required min="{{ today()->toDateString() }}"
                   class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:border-bee-yellow focus:ring-2 focus:ring-bee-yellow/30 outline-none">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold mb-1">Início <span class="text-xs text-gray-500">(vazio = dia todo)</span></label>
                <input type="time" name="start_time"
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:border-bee-yellow focus:ring-2 focus:ring-bee-yellow/30 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Fim</label>
                <input type="time" name="end_time"
                       class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:border-bee-yellow focus:ring-2 focus:ring-bee-yellow/30 outline-none">
            </div>
        </div>
        <div>
            <label class="block text-sm font-semibold mb-1">Motivo (opcional)</label>
            <input type="text" name="reason" maxlength="100" placeholder="Ex.: consulta médica"
                   class="w-full rounded-bee border-gray-300 border px-4 py-2.5 focus:border-bee-yellow focus:ring-2 focus:ring-bee-yellow/30 outline-none">
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="document.getElementById('block-modal').classList.add('hidden')"
                    class="flex-1 py-2.5 rounded-bee border border-gray-300 font-semibold hover:bg-gray-50 transition">Cancelar</button>
            <button class="flex-1 py-2.5 rounded-bee bg-bee-yellow font-display font-bold hover:brightness-105 transition">Bloquear</button>
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
        dayHeaderFormat: { weekday: 'short', day: '2-digit', month: '2-digit' },
        events: '{{ route('painel.agenda.eventos') }}',

        eventContent(arg) {
            const p = arg.event.extendedProps;
            const wrap = document.createElement('div');
            wrap.className = 'fc-ev';

            // Bloqueio (all-day)
            if (p.blockId) {
                wrap.classList.add('is-block');
                wrap.innerHTML = `<div class="fc-ev-title">🚫 ${arg.event.title}</div>`;
                return { domNodes: [wrap] };
            }

            if (p.pendente) wrap.classList.add('is-pending');

            const hora  = arg.timeText || '';
            const badge = p.pendente
                ? '<span class="fc-ev-badge">Pendente</span>'
                : '';

            wrap.innerHTML = `
                <div class="fc-ev-head">
                    <span class="fc-ev-time">${hora}</span>
                    ${badge}
                </div>
                <div class="fc-ev-title">${arg.event.title}</div>
                ${p.cliente ? `<div class="fc-ev-sub">👤 ${p.cliente}</div>` : ''}
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