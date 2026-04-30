@extends('layouts.app')

@section('title', "File d'Attente")
@section('page-title', "File d'Attente")
@section('page-subtitle', '5 patients en attente')

@section('content')

@php
$queue = [
    ['num'=>'01','initials'=>'FE','color'=>'teal',  'patient'=>'Fatima El Idrissi','doctor'=>'Dr. Mehdi Alaoui','specialty'=>'Cardiologie',    'arrived'=>'08:45','wait'=>'44 min', 'status'=>'en_attente'],
    ['num'=>'02','initials'=>'YB','color'=>'blue',  'patient'=>'Youssef Benali',   'doctor'=>'Dr. Sara Tazi',   'specialty'=>'Méd. Générale',  'arrived'=>'09:00','wait'=>'29 min', 'status'=>'en_cours'],
    ['num'=>'03','initials'=>'AM','color'=>'amber', 'patient'=>'Aicha Moussaoui',  'doctor'=>'Dr. Mehdi Alaoui','specialty'=>'Cardiologie',    'arrived'=>'09:15','wait'=>'14 min', 'status'=>'en_attente'],
    ['num'=>'04','initials'=>'HO','color'=>'rose',  'patient'=>'Hassan Ouazzani',  'doctor'=>'Dr. Sara Tazi',   'specialty'=>'Méd. Générale',  'arrived'=>'09:30','wait'=>'Dans 1 min','status'=>'en_attente'],
    ['num'=>'05','initials'=>'ZC','color'=>'violet','patient'=>'Zineb Chraibi',    'doctor'=>'Dr. Karim Fassi', 'specialty'=>'Pédiatrie',      'arrived'=>'09:45','wait'=>'Dans 15 min','status'=>'en_attente'],
];
$statusMap = [
    'en_attente'=>['amber','En attente'],
    'en_cours'  =>['green','En cours'],
    'terminé'   =>['teal', 'Terminé'],
];
@endphp

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">5</div>
            <div class="stat-label">En attente</div>
            <div class="stat-trend warn">● Temps réel</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green" style="background:linear-gradient(135deg,rgba(16,185,129,.18),rgba(16,185,129,.08));color:#059669;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">1</div>
            <div class="stat-label">En cours de consultation</div>
            <div class="stat-trend up">↑ Youssef Benali</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">~22 min</div>
            <div class="stat-label">Attente moyenne</div>
            <div class="stat-trend up">↑ Correct aujourd'hui</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">12</div>
            <div class="stat-label">Consultations terminées</div>
            <div class="stat-trend up">↑ Aujourd'hui</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div><h3>File d'Attente</h3><span>{{ count($queue) }} patients</span></div>
        </div>
        <a href="{{ url('/queue/create') }}" class="btn btn-primary btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Ajouter à la file
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>Médecin assigné</th>
                    <th>Heure d'arrivée</th>
                    <th>Attente</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($queue as $q)
                @php [$sc,$sl] = $statusMap[$q['status']] ?? ['gray',$q['status']]; @endphp
                <tr>
                    <td>
                        <div class="queue-number {{ $q['status']==='en_cours' ? 'active' : '' }}" style="margin:0;">
                            {{ $q['num'] }}
                        </div>
                    </td>
                    <td>
                        <div class="avatar-chip">
                            <div class="avatar {{ $q['color'] }}">{{ $q['initials'] }}</div>
                            <div class="avatar-info"><p>{{ $q['patient'] }}</p></div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--teal-800);">{{ $q['doctor'] }}</div>
                            <div style="font-size:11.5px;color:var(--muted);">{{ $q['specialty'] }}</div>
                        </div>
                    </td>
                    <td>{{ $q['arrived'] }}</td>
                    <td style="color:var(--muted);">{{ $q['wait'] }}</td>
                    <td><span class="badge badge-{{ $sc }}">{{ $sl }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;">
                            @if($q['status'] === 'en_attente')
                                <button class="btn btn-outline btn-sm">Appeler</button>
                                <button class="btn btn-primary btn-sm">En cours</button>
                            @elseif($q['status'] === 'en_cours')
                                <button class="btn btn-success btn-sm">✓ Terminer</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
