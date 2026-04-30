@extends('layouts.app')

@section('title', 'Rendez-vous')
@section('page-title', 'Rendez-vous')
@section('page-subtitle', '12 rendez-vous planifiés cette semaine')

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">8</div>
            <div class="stat-label">Total RDV</div>
            <div class="stat-trend up">↑ +3 cette semaine</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">6</div>
            <div class="stat-label">À venir</div>
            <div class="stat-trend up">↑ Prochaines 10j</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9.303 3.376c-.866 1.5.217 3.374 1.948 3.374H2.749c-1.73 0-2.813-1.874-1.948-3.374L10.51 3.374a2.25 2.25 0 013.98 0l7.742 13.376zM12 15.75h.007v.008H12v-.008z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">2</div>
            <div class="stat-label">En attente confirmation</div>
            <div class="stat-trend warn">● À confirmer</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon rose">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">1</div>
            <div class="stat-label">Annulés</div>
            <div class="stat-trend down">↓ Ce mois</div>
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
                    <span>Mai 2026</span>
                </div>
            </div>
            <div style="display:flex;gap:6px;">
                <button class="btn btn-outline btn-sm btn-icon-only">‹</button>
                <button class="btn btn-outline btn-sm">Aujourd'hui</button>
                <button class="btn btn-outline btn-sm btn-icon-only">›</button>
            </div>
        </div>

        <div class="calendar-grid">
            @foreach(['Lun','Mar','Mer','Jeu','Ven','Sam','Dim'] as $d)
                <div class="calendar-day-label">{{ $d }}</div>
            @endforeach

            {{-- April fillers: April 1 2026 = Wednesday (index 2), so 2 fillers --}}
            <div class="calendar-day other-month"><span>30</span></div>
            <div class="calendar-day other-month"><span>31</span></div>

            {{-- April days --}}
            @for($d = 1; $d <= 30; $d++)
                @php
                    $isToday = ($d === 29);
                    $hasApt  = in_array($d, [1, 3, 6, 10, 14, 18, 22, 26, 29]);
                    $aptCount = match(true) {
                        in_array($d, [1, 3]) => 2,
                        in_array($d, [6, 10, 14, 18, 22]) => 1,
                        default => 0
                    };
                @endphp
                <div class="calendar-day {{ $isToday ? 'today' : '' }} {{ $hasApt ? 'has-apt' : '' }}">
                    <span>{{ $d }}</span>
                    @if($aptCount > 0)
                        <span class="cal-count">{{ $aptCount }} RDV</span>
                    @endif
                </div>
            @endfor

            {{-- May fillers --}}
            @for($d = 1; $d <= 12; $d++)
                <div class="calendar-day other-month"><span>{{ $d }}</span></div>
            @endfor
        </div>
    </div>

    {{-- Upcoming list --}}
    <div class="card">
        <div class="card-header">
            <div class="section-title">
                <div class="accent-bar"></div>
                <div><h3>Prochains RDV</h3><span>8 planifiés</span></div>
            </div>
            <a href="{{ url('/appointments/create') }}" class="btn btn-primary btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Nouveau
            </a>
        </div>

        @php
        $appointments = [
            ['month'=>'Avr','day'=>30,'patient'=>'Fatima El Idrissi','time'=>'09:00','reason'=>'Contrôle cardiaque','status'=>'confirmé'],
            ['month'=>'Avr','day'=>30,'patient'=>'Youssef Benali','time'=>'10:30','reason'=>'Consultation générale','status'=>'confirmé'],
            ['month'=>'Mai','day'=>1, 'patient'=>'Aicha Moussaoui','time'=>'14:00','reason'=>'Suivi traitement','status'=>'en_attente'],
            ['month'=>'Mai','day'=>2, 'patient'=>'Zineb Chraibi','time'=>'11:15','reason'=>'Vaccination','status'=>'confirmé'],
            ['month'=>'Mai','day'=>4, 'patient'=>'Meriem Tahiri','time'=>'09:45','reason'=>'Résultats analyses','status'=>'confirmé'],
            ['month'=>'Mai','day'=>6, 'patient'=>'Hassan Ouazzani','time'=>'16:00','reason'=>'Échographie','status'=>'en_attente'],
            ['month'=>'Mai','day'=>7, 'patient'=>'Nadia Filali','time'=>'10:00','reason'=>'Consultation dermato','status'=>'confirmé'],
            ['month'=>'Mai','day'=>9, 'patient'=>'Omar Benhaddou','time'=>'15:30','reason'=>'Visite mensuelle','status'=>'confirmé'],
        ];
        $statusMap = ['confirmé'=>['green','Confirmé'],'en_attente'=>['amber','En attente'],'annulé'=>['rose','Annulé']];
        @endphp

        @foreach($appointments as $appt)
        @php [$sc, $sl] = $statusMap[$appt['status']] ?? ['gray',$appt['status']]; @endphp
        <div class="queue-item">
            <div style="display:flex;flex-direction:column;align-items:center;width:44px;padding:4px 6px;background:var(--teal-50);border-radius:10px;flex-shrink:0;">
                <div style="font-size:10px;font-weight:700;color:var(--teal-500);text-transform:uppercase;">{{ $appt['month'] }}</div>
                <div style="font-size:17px;font-weight:800;color:var(--teal-800);line-height:1;">{{ $appt['day'] }}</div>
            </div>
            <div class="queue-info">
                <p>{{ $appt['patient'] }}</p>
                <span>{{ $appt['time'] }} · {{ $appt['reason'] }}</span>
            </div>
            <span class="badge badge-{{ $sc }}">{{ $sl }}</span>
        </div>
        @endforeach
    </div>
</div>

@endsection

@push('scripts')
<style>
.calendar-grid { display:grid; grid-template-columns:repeat(7,1fr); gap:4px; }
.calendar-day-label { font-size:10.5px;font-weight:700;color:var(--teal-600);text-align:center;padding:8px 0;letter-spacing:.06em;text-transform:uppercase; }
.calendar-day { aspect-ratio:1;border-radius:10px;background:var(--teal-50);border:1.5px solid transparent;padding:6px;display:flex;flex-direction:column;font-size:12px;font-weight:600;color:var(--teal-700);cursor:pointer;transition:all .15s;position:relative; }
.calendar-day:hover { border-color:var(--teal-400);background:#fff;transform:translateY(-2px);box-shadow:var(--shadow-sm); }
.calendar-day.other-month { opacity:.35; }
.calendar-day.today { background:linear-gradient(135deg,var(--teal-400),var(--teal-600));color:#fff;border-color:var(--teal-500);box-shadow:0 4px 12px rgba(52,168,140,.35); }
.calendar-day.has-apt::after { content:'';position:absolute;bottom:6px;right:6px;width:6px;height:6px;border-radius:50%;background:var(--teal-500); }
.calendar-day.today.has-apt::after { background:#fff; }
.cal-count { margin-top:auto;font-size:10px;padding:1px 5px;background:rgba(52,168,140,.15);color:var(--teal-700);border-radius:5px;align-self:flex-start;font-weight:700; }
.calendar-day.today .cal-count { background:rgba(255,255,255,.2);color:#fff; }
</style>
@endpush
