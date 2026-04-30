@extends('layouts.app')

@section('title', 'Staff Médical')
@section('page-title', 'Staff Médical')
@section('page-subtitle', '6 membres du personnel')

@section('content')

@php
$staff = [
    ['initials'=>'MA','color'=>'teal',  'name'=>'Dr. Mehdi Alaoui', 'specialty'=>'Cardiologie',       'role'=>'Médecin',      'phone'=>'0661 000 111','email'=>'m.alaoui@clinicpro.ma', 'hired'=>'Jan 2020','salary'=>15000],
    ['initials'=>'ST','color'=>'rose',  'name'=>'Dr. Sara Tazi',    'specialty'=>'Méd. Générale',      'role'=>'Médecin',      'phone'=>'0662 111 222','email'=>'s.tazi@clinicpro.ma',   'hired'=>'Mar 2021','salary'=>12000],
    ['initials'=>'KF','color'=>'violet','name'=>'Dr. Karim Fassi',  'specialty'=>'Pédiatrie',          'role'=>'Médecin',      'phone'=>'0663 222 333','email'=>'k.fassi@clinicpro.ma',  'hired'=>'Jun 2022','salary'=>13500],
    ['initials'=>'LB','color'=>'amber', 'name'=>'Lina Bouazza',     'specialty'=>'–',                  'role'=>'Infirmier(e)', 'phone'=>'0664 333 444','email'=>'l.bouazza@clinicpro.ma','hired'=>'Sep 2023','salary'=>5500],
    ['initials'=>'AZ','color'=>'blue',  'name'=>'Ahmed Zeroual',    'specialty'=>'–',                  'role'=>'Infirmier(e)', 'phone'=>'0665 444 555','email'=>'a.zeroual@clinicpro.ma','hired'=>'Nov 2023','salary'=>5200],
    ['initials'=>'NB','color'=>'teal',  'name'=>'Dr. Nawal Berrada','specialty'=>'Dermatologie',       'role'=>'Médecin',      'phone'=>'0666 555 666','email'=>'n.berrada@clinicpro.ma','hired'=>'Fév 2024','salary'=>14000],
];
@endphp

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">6</div>
            <div class="stat-label">Total Staff</div>
            <div class="stat-trend up">↑ Tous actifs</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">4</div>
            <div class="stat-label">Médecins</div>
            <div class="stat-trend up">↑ 4 spécialités</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">2</div>
            <div class="stat-label">Infirmiers</div>
            <div class="stat-trend up">↑ En service</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon violet">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value" style="font-size:20px;">65 200 <span style="font-size:12px;color:var(--muted);">MAD</span></div>
            <div class="stat-label">Masse salariale / mois</div>
            <div class="stat-trend up">↑ Total mensuel</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div><h3>Staff Médical</h3><span>{{ count($staff) }} membres actifs</span></div>
        </div>
        <a href="{{ url('/staff/create') }}" class="btn btn-primary btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Nouveau Membre
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Membre</th>
                    <th>Spécialité</th>
                    <th>Rôle</th>
                    <th>Téléphone</th>
                    <th>Embauché</th>
                    <th>Salaire / mois</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff as $s)
                <tr>
                    <td>
                        <div class="avatar-chip">
                            <div class="avatar {{ $s['color'] }}">{{ $s['initials'] }}</div>
                            <div class="avatar-info">
                                <p>{{ $s['name'] }}</p>
                                <span>{{ $s['email'] }}</span>
                            </div>
                        </div>
                    </td>
                    <td>{{ $s['specialty'] }}</td>
                    <td>
                        <span class="badge badge-{{ $s['role']==='Médecin' ? 'teal' : 'blue' }}">{{ $s['role'] }}</span>
                    </td>
                    <td>{{ $s['phone'] }}</td>
                    <td style="color:var(--muted);">{{ $s['hired'] }}</td>
                    <td><strong style="color:var(--teal-700);">{{ number_format($s['salary'],0,',',' ') }} MAD</strong></td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <button class="btn btn-outline btn-sm btn-icon-only" title="Modifier">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                            </button>
                            <button class="btn btn-danger btn-sm btn-icon-only" title="Supprimer">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
