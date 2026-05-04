@extends('layouts.app')

@section('title', 'Patients')
@section('page-title', 'Patients')
@section('page-subtitle', 'Gestion des patients')

@section('content')

{{-- STATS --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $patients->count() ?? 0 }}</div>
            <div class="stat-label">Total Patients</div> 
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $patients->where('gender', 'M')->count() ?? 0 }}</div>
            <div class="stat-label">Hommes</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon rose">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5zM12 14.25v5.25M9.75 16.5h4.5M12 3v3"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $patients->where('gender', 'F')->count() ?? 0 }}</div>
            <div class="stat-label">Femmes</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $patients->where('status', 'actif')->count() ?? 0 }}</div>
            <div class="stat-label">Actifs</div>
        </div>
    </div>

</div>

{{-- TABLE --}}
<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div>
                <h3>Patients</h3>
                <span>{{ $patients->count() ?? 0 }} patients enregistrés</span>
            </div>
        </div>

        <div style="display:flex;gap:8px;align-items:center;">
            <div style="display:flex;align-items:center;gap:8px;background:var(--teal-50);border:1.5px solid rgba(52,168,140,.2);border-radius:10px;padding:8px 14px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text"
                    placeholder="Rechercher un patient..."
                    style="border:none;outline:none;background:none;font-size:13px;font-family:inherit;color:var(--teal-800);width:200px;">
            </div>

            <button class="btn btn-outline btn-sm">Exporter</button>

            <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm">
                ➕ Nouveau Patient
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
                @forelse($patients as $p)
                    <tr>
                        <td>
                            <div class="avatar-chip">
                                <div class="avatar {{ $p->color ?? 'teal' }}">
                                    {{ $p->initials ?? '' }}
                                </div>
                                <div class="avatar-info">
                                    <p>{{ $p->name }}</p>
                                    <span>{{ $p->email }}</span>
                                </div>
                            </div>
                        </td>

                        <td><span class="text-mono">{{ $p->cin }}</span></td>

                        <td>{{ $p->age }} ans</td>

                        <td>
                            <span class="badge badge-{{ $p->gender === 'F' ? 'rose' : 'blue' }}">
                                {{ $p->gender === 'F' ? 'Féminin' : 'Masculin' }}
                            </span>
                        </td>

                        <td>
                            <span style="font-size:12.5px;font-weight:700;color:var(--teal-700);
                                background:rgba(52,168,140,.08);padding:3px 10px;border-radius:6px;">
                                {{ $p->blood }}
                            </span>
                        </td>

                        <td style="color:var(--muted);">
                            {{ $p->last_visit ?? '-' }}
                        </td>

                        <td>{{ $p->phone }}</td>

                        <td>
                            <div style="display:flex;gap:4px;">
                                <button class="btn btn-outline btn-sm btn-icon-only" title="Voir">👁</button>
                                <button class="btn btn-outline btn-sm btn-icon-only" title="Modifier">✏️</button>
                                <button class="btn btn-danger btn-sm btn-icon-only" title="Supprimer">🗑</button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:20px;color:var(--muted);">
                            Aucun patient trouvé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span>Affichage {{ $patients->count() ?? 0 }} patients</span>
    </div>
</div>

@endsection
