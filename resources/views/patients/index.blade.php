@extends('layouts.app')

@section('title', 'Patients')
@section('page-title', 'Patients')
@section('page-subtitle', 'Gestion des patients')

@section('content')

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
