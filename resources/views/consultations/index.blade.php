@extends('layouts.app')

@section('title', 'Consultations')
@section('page-title', 'Consultations')
@section('page-subtitle', '6 consultations enregistrées')

@section('content')

@php
$consultations = [
    ['initials'=>'OB','color'=>'teal',  'patient'=>'Omar Benhaddou',  'doctor'=>'Dr. Mehdi Alaoui','specialty'=>'Cardiologie',   'date'=>'Aujourd\'hui 09:00','diag'=>true, 'trait'=>true, 'ord'=>true, 'scan'=>false,'anal'=>true, 'note'=>'Hypertension légère. RDV de contrôle dans 1 mois.'],
    ['initials'=>'MT','color'=>'blue',  'patient'=>'Meriem Tahiri',   'doctor'=>'Dr. Sara Tazi',   'specialty'=>'Méd. Générale', 'date'=>'Aujourd\'hui 06:30','diag'=>false,'trait'=>true, 'ord'=>false,'scan'=>true, 'anal'=>false,'note'=>'Scanner abdominal demandé. Résultats en attente.'],
    ['initials'=>'RA','color'=>'amber', 'patient'=>'Rachid Amrani',   'doctor'=>'Dr. Karim Fassi', 'specialty'=>'Pédiatrie',     'date'=>'Hier 10:00',        'diag'=>true, 'trait'=>false,'ord'=>false,'scan'=>false,'anal'=>false,'note'=>'Consultation pédiatrique de routine. Enfant en bonne santé.'],
    ['initials'=>'NF','color'=>'rose',  'patient'=>'Nadia Filali',    'doctor'=>'Dr. Mehdi Alaoui','specialty'=>'Cardiologie',   'date'=>'Hier 06:00',        'diag'=>false,'trait'=>false,'ord'=>true, 'scan'=>false,'anal'=>true, 'note'=>'Suivi post-opératoire. Prescription renouvelée.'],
    ['initials'=>'FE','color'=>'teal',  'patient'=>'Fatima El Idrissi','doctor'=>'Dr. Sara Tazi',  'specialty'=>'Méd. Générale', 'date'=>'il y a 2j',         'diag'=>true, 'trait'=>true, 'ord'=>true, 'scan'=>false,'anal'=>false,'note'=>'Grippe saisonnière. Repos 5 jours recommandé.'],
    ['initials'=>'AM','color'=>'amber', 'patient'=>'Aicha Moussaoui', 'doctor'=>'Dr. Mehdi Alaoui','specialty'=>'Cardiologie',   'date'=>'il y a 3j',         'diag'=>true, 'trait'=>true, 'ord'=>true, 'scan'=>true, 'anal'=>true, 'note'=>'Examens complets prescrits. Patient à risque cardiovasculaire.'],
];
@endphp

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">6</div>
            <div class="stat-label">Total consultations</div>
            <div class="stat-trend up">↑ +2 aujourd'hui</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">4</div>
            <div class="stat-label">Ordonnances émises</div>
            <div class="stat-trend up">↑ Cette semaine</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">2</div>
            <div class="stat-label">Scanners demandés</div>
            <div class="stat-trend warn">● En attente résultats</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon violet">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">3</div>
            <div class="stat-label">Analyses prescrites</div>
            <div class="stat-trend up">↑ Laboratoire</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div><h3>Consultations récentes</h3><span>{{ count($consultations) }} enregistrées</span></div>
        </div>
        <a href="{{ url('/consultations/create') }}" class="btn btn-primary btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Nouvelle Consultation
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Médecin</th>
                    <th>Date</th>
                    <th>Diag.</th>
                    <th>Trait.</th>
                    <th>Ordon.</th>
                    <th>Scanner</th>
                    <th>Analyse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($consultations as $c)
                <tr>
                    <td>
                        <div class="avatar-chip">
                            <div class="avatar {{ $c['color'] }}">{{ $c['initials'] }}</div>
                            <div class="avatar-info"><p>{{ $c['patient'] }}</p></div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:600;color:var(--teal-800);">{{ $c['doctor'] }}</div>
                        <div style="font-size:11px;color:var(--muted);">{{ $c['specialty'] }}</div>
                    </td>
                    <td style="color:var(--muted);white-space:nowrap;">{{ $c['date'] }}</td>
                    @foreach(['diag','trait','ord','scan','anal'] as $field)
                    <td>
                        @if($c[$field])
                            <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:8px;background:rgba(16,185,129,.12);color:#059669;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:8px;background:#f0f7f5;color:#94a3b8;">–</span>
                        @endif
                    </td>
                    @endforeach
                    <td>
                        <div style="display:flex;gap:4px;">
                            <button class="btn btn-outline btn-sm btn-icon-only" title="Voir">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                            <button class="btn btn-outline btn-sm btn-icon-only" title="Modifier">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span>Affichage 1–6 sur 6</span>
        <div class="pagination-btns">
            <button class="page-btn" disabled>‹</button>
            <button class="page-btn active">1</button>
            <button class="page-btn" disabled>›</button>
        </div>
    </div>
</div>

@endsection
