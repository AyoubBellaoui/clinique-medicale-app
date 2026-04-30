@extends('layouts.app')

@section('title', 'Patients')
@section('page-title', 'Patients')
@section('page-subtitle', '9 patients enregistrés')

@section('content')

@php
$patients = [
    ['initials'=>'FE','color'=>'teal',  'name'=>'Fatima El Idrissi', 'cin'=>'BK123456','age'=>41,'gender'=>'F','blood'=>'A+', 'phone'=>'0612 345 678','email'=>'fatima@email.ma',   'last'=>'il y a 11j', 'allergies'=>'Pénicilline'],
    ['initials'=>'YB','color'=>'blue',  'name'=>'Youssef Benali',    'cin'=>'CD789012','age'=>36,'gender'=>'M','blood'=>'O+', 'phone'=>'0698 765 432','email'=>'y.benali@email.ma',  'last'=>'il y a 9j',  'allergies'=>'Aucune'],
    ['initials'=>'AM','color'=>'amber', 'name'=>'Aicha Moussaoui',   'cin'=>'EF345678','age'=>48,'gender'=>'F','blood'=>'B+', 'phone'=>'0655 234 567','email'=>'a.moussaoui@email.ma','last'=>'il y a 14j', 'allergies'=>'Arachides'],
    ['initials'=>'HO','color'=>'rose',  'name'=>'Hassan Ouazzani',   'cin'=>'GH901234','age'=>61,'gender'=>'M','blood'=>'AB+','phone'=>'0611 222 333','email'=>'h.ouazzani@email.ma', 'last'=>'il y a 7j',  'allergies'=>'Latex'],
    ['initials'=>'ZC','color'=>'violet','name'=>'Zineb Chraibi',     'cin'=>'IJ567890','age'=>31,'gender'=>'F','blood'=>'A-', 'phone'=>'0677 888 999','email'=>'z.chraibi@email.ma',  'last'=>'il y a 10j', 'allergies'=>'Aucune'],
    ['initials'=>'OB','color'=>'teal',  'name'=>'Omar Benhaddou',    'cin'=>'KL111222','age'=>54,'gender'=>'M','blood'=>'O-', 'phone'=>'0644 555 666','email'=>'o.benhaddou@email.ma','last'=>'il y a 8j',  'allergies'=>'Sulfamides'],
    ['initials'=>'MT','color'=>'blue',  'name'=>'Meriem Tahiri',     'cin'=>'MN333444','age'=>38,'gender'=>'F','blood'=>'A+', 'phone'=>'0699 111 222','email'=>'m.tahiri@email.ma',   'last'=>'il y a 12j', 'allergies'=>'Aucune'],
    ['initials'=>'RA','color'=>'amber', 'name'=>'Rachid Amrani',     'cin'=>'OP555666','age'=>44,'gender'=>'M','blood'=>'B-', 'phone'=>'0633 777 888','email'=>'r.amrani@email.ma',   'last'=>'il y a 13j', 'allergies'=>'Aspirine'],
    ['initials'=>'NF','color'=>'rose',  'name'=>'Nadia Filali',      'cin'=>'QR777888','age'=>35,'gender'=>'F','blood'=>'O+', 'phone'=>'0655 444 333','email'=>'n.filali@email.ma',   'last'=>'il y a 15j', 'allergies'=>'Aucune'],
];
@endphp

<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div><h3>Patients</h3><span>{{ count($patients) }} patients enregistrés</span></div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;">
            <div style="display:flex;align-items:center;gap:8px;background:var(--teal-50);border:1.5px solid rgba(52,168,140,.2);border-radius:10px;padding:8px 14px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                <input type="text" placeholder="Rechercher un patient..." style="border:none;outline:none;background:none;font-size:13px;font-family:inherit;color:var(--teal-800);width:200px;">
            </div>
            <button class="btn btn-outline btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Exporter
            </button>
            <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Nouveau Patient
            </a>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>CIN</th>
                    <th>Âge</th>
                    <th>Genre</th>
                    <th>Groupe sanguin</th>
                    <th>Dernière visite</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $p)
                <tr>
                    <td>
                        <div class="avatar-chip">
                            <div class="avatar {{ $p['color'] }}">{{ $p['initials'] }}</div>
                            <div class="avatar-info">
                                <p>{{ $p['name'] }}</p>
                                <span>{{ $p['email'] }}</span>
                            </div>
                        </div>
                    </td>
                    <td><span class="text-mono">{{ $p['cin'] }}</span></td>
                    <td>{{ $p['age'] }} ans</td>
                    <td>
                        <span class="badge badge-{{ $p['gender']==='F' ? 'rose' : 'blue' }}">
                            {{ $p['gender']==='F' ? 'Féminin' : 'Masculin' }}
                        </span>
                    </td>
                    <td>
                        <span style="font-size:12.5px;font-weight:700;color:var(--teal-700);background:rgba(52,168,140,.08);padding:3px 10px;border-radius:6px;">{{ $p['blood'] }}</span>
                    </td>
                    <td style="color:var(--muted);">{{ $p['last'] }}</td>
                    <td>{{ $p['phone'] }}</td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <button class="btn btn-outline btn-sm btn-icon-only" title="Voir">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
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

    <div class="pagination">
        <span>Affichage 1–9 sur 9</span>
        <div class="pagination-btns">
            <button class="page-btn" disabled>‹</button>
            <button class="page-btn active">1</button>
            <button class="page-btn" disabled>›</button>
        </div>
    </div>
</div>

@endsection
