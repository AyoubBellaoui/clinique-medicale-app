@extends('layouts.app')

@section('title', 'Ordonnances')
@section('page-title', 'Ordonnances')
@section('page-subtitle', '5 ordonnances émises')

@section('content')

@php
$prescriptions = [
    [
        'initials'=>'OB','color'=>'teal','patient'=>'Omar Benhaddou',
        'doctor'=>'Dr. Mehdi Alaoui','date'=>'Aujourd\'hui 09:00','duration'=>'3 mois',
        'meds'=>['Amlodipine 5mg — 1/jour, matin','Aspirine 100mg — 1/jour','Oméga-3 — 2 gélules/jour'],
    ],
    [
        'initials'=>'NF','color'=>'rose','patient'=>'Nadia Filali',
        'doctor'=>'Dr. Mehdi Alaoui','date'=>'Hier 06:00','duration'=>'5 jours',
        'meds'=>['Paracétamol 1g — si douleur','Ibuprofène 400mg — 3/jour, 5 jours'],
    ],
    [
        'initials'=>'FE','color'=>'teal','patient'=>'Fatima El Idrissi',
        'doctor'=>'Dr. Sara Tazi','date'=>'il y a 2j','duration'=>'7 jours',
        'meds'=>['Doliprane 1g — 4/jour','Tussidane sirop — 3 c.à.s/jour','Vitamine C 1000mg'],
    ],
    [
        'initials'=>'AM','color'=>'amber','patient'=>'Aicha Moussaoui',
        'doctor'=>'Dr. Mehdi Alaoui','date'=>'il y a 3j','duration'=>'6 mois',
        'meds'=>['Atorvastatine 20mg — 1/jour, soir','Metformine 500mg — 2/jour','Régime hyposodé strict'],
    ],
    [
        'initials'=>'RA','color'=>'amber','patient'=>'Rachid Amrani',
        'doctor'=>'Dr. Karim Fassi','date'=>'il y a 1j','duration'=>'1 mois',
        'meds'=>['Multivitamines pédiatriques — 1/jour'],
    ],
];
@endphp

{{-- Stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ count($prescriptions) }}</div>
            <div class="stat-label">Total Ordonnances</div>
            <div class="stat-trend up">↑ +2 cette semaine</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">2</div>
            <div class="stat-label">Aujourd'hui</div>
            <div class="stat-trend up">↑ Émises</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">14</div>
            <div class="stat-label">Médicaments prescrits</div>
            <div class="stat-trend up">↑ Au total</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div><h3>Ordonnances récentes</h3><span>{{ count($prescriptions) }} ordonnances</span></div>
        </div>
        <a href="{{ url('/prescriptions/create') }}" class="btn btn-primary btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Nouvelle Ordonnance
        </a>
    </div>

    <div style="padding:18px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            @foreach($prescriptions as $rx)
            <div class="rx-card">
                <div class="rx-head">
                    <div style="display:flex;gap:10px;align-items:center;">
                        <div class="avatar {{ $rx['color'] }}">{{ $rx['initials'] }}</div>
                        <div>
                            <h4 style="font-size:14px;font-weight:700;color:var(--teal-800);">{{ $rx['patient'] }}</h4>
                            <span style="font-size:11.5px;color:var(--muted);">{{ $rx['doctor'] }} · {{ $rx['date'] }} · {{ $rx['duration'] }}</span>
                        </div>
                    </div>
                    <button class="btn btn-ghost btn-sm btn-icon-only">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                    </button>
                </div>
                <div class="rx-meds">
                    @foreach($rx['meds'] as $med)
                        <div class="rx-med">{{ $med }}</div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.rx-card { padding:16px;border-radius:12px;background:linear-gradient(135deg,#fff,var(--teal-50));border:1px solid rgba(52,168,140,.15);margin-bottom:0;cursor:pointer;transition:all .2s; }
.rx-card:hover { transform:translateY(-2px);box-shadow:var(--shadow);border-color:var(--teal-400); }
.rx-head { display:flex;justify-content:space-between;margin-bottom:10px;align-items:start; }
.rx-meds { display:flex;flex-direction:column;gap:6px;margin-top:10px;padding-top:10px;border-top:1px dashed rgba(52,168,140,.2); }
.rx-med { display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--teal-700); }
.rx-med::before { content:'✦';font-weight:800;color:var(--teal-500);font-size:11px; }
</style>
@endpush
