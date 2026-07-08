@extends('layouts.app')

@php
    $today = \Carbon\Carbon::today();
    $weekEnd = $today->copy()->endOfWeek();

    $totalCount     = $appointments->count();
    $upcomingCount  = $appointments->where('date', '>=', $today)->count();
    $pendingCount   = $appointments->where('statut', 'programme')->count();
    $cancelledCount = $appointments->where('statut', 'annule')->count();
    $thisWeekCount  = $appointments->whereBetween('date', [$today, $weekEnd])->count();

    $monthStart = \Carbon\Carbon::now()->startOfMonth();
    $monthAppointments = $appointments->filter(fn($a) => $a->date->isSameMonth($monthStart));
    $apptsByDay = $monthAppointments->groupBy(fn($a) => (int) $a->date->format('j'));

    // Carbon's locale isn't set to French app-wide (APP_LOCALE=en), so month
    // names are mapped manually here to keep the UI in French.
    $moisFr = [1=>'Janvier',2=>'Février',3=>'Mars',4=>'Avril',5=>'Mai',6=>'Juin',7=>'Juillet',8=>'Août',9=>'Septembre',10=>'Octobre',11=>'Novembre',12=>'Décembre'];
    $moisFrAbbr = [1=>'Jan',2=>'Fév',3=>'Mar',4=>'Avr',5=>'Mai',6=>'Juin',7=>'Juil',8=>'Août',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Déc'];

    $upcomingList = $appointments->filter(fn($a) => $a->date->greaterThanOrEqualTo($today))->take(8);

    $statusMap = [
        'programme' => ['amber', 'Programmé'],
        'confirme'  => ['green', 'Confirmé'],
        'termine'   => ['blue', 'Terminé'],
        'annule'    => ['rose', 'Annulé'],
    ];
@endphp

@section('title', 'Rendez-vous')
@section('page-title', 'Rendez-vous')
@section('page-subtitle', $thisWeekCount . ' rendez-vous planifiés cette semaine')

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $totalCount }}</div>
            <div class="stat-label">Total RDV</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $upcomingCount }}</div>
            <div class="stat-label">À venir</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c-.866 1.5.217 3.374 1.948 3.374H2.749c-1.73 0-2.813-1.874-1.948-3.374L10.51 3.374a2.25 2.25 0 013.98 0l7.742 13.376zM12 15.75h.007v.008H12v-.008z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $pendingCount }}</div>
            <div class="stat-label">Programmés (non confirmés)</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon rose">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $cancelledCount }}</div>
            <div class="stat-label">Annulés</div>
        </div>
    </div>
</div>

<div class="dash-grid-main">

    {{-- Calendar --}}
    <div class="card" style="padding:22px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;">
            <div class="section-title">
                <div class="accent-bar"></div>
                <div>
                    <h3>Calendrier</h3>
                    <span>{{ $moisFr[$monthStart->month] }} {{ $monthStart->year }}</span>
                </div>
            </div>
        </div>

        <div class="calendar-grid">
            @foreach(['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'] as $d)
                <div class="calendar-day-label">{{ $d }}</div>
            @endforeach

            {{-- Leading blanks so day 1 lands on the right weekday (ISO: Monday = 1) --}}
            @for($i = 1; $i < $monthStart->dayOfWeekIso; $i++)
                <div class="calendar-day other-month"><span></span></div>
            @endfor

            @for($d = 1; $d <= $monthStart->daysInMonth; $d++)
                @php
                    $isToday = $today->day === $d && $today->isSameMonth($monthStart);
                    $count = $apptsByDay->get($d)?->count() ?? 0;
                @endphp
                <div class="calendar-day {{ $isToday ? 'today' : '' }} {{ $count > 0 ? 'has-apt' : '' }}">
                    <span>{{ $d }}</span>
                    @if($count > 0)
                        <span class="cal-count">{{ $count }} RDV</span>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    {{-- Upcoming list --}}
    <div class="card">
        <div class="card-header">
            <div class="section-title">
                <div class="accent-bar"></div>
                <div><h3>Prochains RDV</h3><span>{{ $upcomingCount }} planifiés</span></div>
            </div>
            <a href="{{ route('appointments.create') }}" class="btn btn-primary btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Nouveau
            </a>
        </div>

        @forelse($upcomingList as $appt)
        @php [$sc, $sl] = $statusMap[$appt->statut] ?? ['gray', $appt->statut]; @endphp
        <div class="queue-item">
            <div style="display:flex;flex-direction:column;align-items:center;width:44px;padding:4px 6px;background:var(--teal-50);border-radius:10px;flex-shrink:0;">
                <div style="font-size:10px;font-weight:700;color:var(--teal-500);text-transform:uppercase;">{{ $moisFrAbbr[$appt->date->month] }}</div>
                <div style="font-size:17px;font-weight:800;color:var(--teal-800);line-height:1;">{{ $appt->date->format('d') }}</div>
            </div>
            <div class="queue-info">
                <p>{{ $appt->patient->full_name ?? 'Patient supprimé' }}</p>
                <span>{{ $appt->heure->format('H:i') }} · {{ $appt->motif ?: 'Consultation' }} · Dr. {{ $appt->staff->full_name ?? '—' }}</span>
            </div>
            <span class="badge badge-{{ $sc }}">{{ $sl }}</span>
            <div style="display:flex;gap:6px;margin-left:10px;">
                <a href="{{ route('appointments.edit', $appt->id) }}" class="btn btn-outline btn-sm btn-icon-only" title="Modifier">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                </a>
                <form method="POST" action="{{ route('appointments.delete', $appt->id) }}" onsubmit="return confirm('Supprimer ce rendez-vous ?')" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm btn-icon-only" title="Supprimer">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="padding:40px 20px;text-align:center;color:var(--muted);font-size:13px;">
            Aucun rendez-vous à venir. <a href="{{ route('appointments.create') }}" style="color:var(--teal-600);font-weight:600;">En créer un</a>.
        </div>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<style>
.calendar-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:4px; }
.calendar-day-label { font-size:10.5px;font-weight:700;color:var(--teal-600);text-align:center;padding:8px 0;letter-spacing:.06em;text-transform:uppercase; }
.calendar-day { aspect-ratio:1;border-radius:10px;background:var(--teal-50);border:1.5px solid transparent;padding:6px;display:flex;flex-direction:column;font-size:12px;font-weight:600;color:var(--teal-700);cursor:pointer;transition:all .15s;position:relative; }
.calendar-day:hover { border-color:var(--teal-400);background:#fff;transform:translateY(-2px);box-shadow:var(--shadow-sm); }
.calendar-day.other-month { opacity:.35;pointer-events:none; }
.calendar-day.today { background:linear-gradient(135deg,var(--teal-400),var(--teal-600));color:#fff;border-color:var(--teal-500);box-shadow:0 4px 12px rgba(52,168,140,.35); }
.calendar-day.has-apt::after { content:'';position:absolute;bottom:6px;right:6px;width:6px;height:6px;border-radius:50%;background:var(--teal-500); }
.calendar-day.today.has-apt::after { background:#fff; }
.cal-count { margin-top:auto;font-size:10px;padding:1px 5px;background:rgba(52,168,140,.15);color:var(--teal-700);border-radius:5px;align-self:flex-start;font-weight:700; }
.calendar-day.today .cal-count { background:rgba(255,255,255,.2);color:#fff; }
</style>
@endpush
