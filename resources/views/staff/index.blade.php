@extends('layouts.app')

@section('title', 'Staff Médical')
@section('page-title', 'Staff Médical')
@section('page-subtitle', count($staff) . ' membres du personnel')

@section('content')

@php
$staff = $staff ?? [];

$roleColors = [
    'Médecin' => 'teal',
    'Infirmier(e)' => 'blue',
    'Administratif' => 'amber',
    'Technicien' => 'violet',
];

function badgeColor($role, $map) {
    return $map[$role] ?? 'gray';
}
@endphp

{{-- STATS --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-icon teal">👥</div>
        <div class="stat-body">
            <div class="stat-value">{{ count($staff) }}</div>
            <div class="stat-label">Total Staff</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">🩺</div>
        <div class="stat-body">
            <div class="stat-value">
                {{ collect($staff)->where('role', 'Médecin')->count() }}
            </div>
            <div class="stat-label">Médecins</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon amber">⚕️</div>
        <div class="stat-body">
            <div class="stat-value">
                {{ collect($staff)->where('role', 'Infirmier(e)')->count() }}
            </div>
            <div class="stat-label">Infirmiers</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon violet">💰</div>
        <div class="stat-body">
            <div class="stat-value">
                {{ number_format(collect($staff)->sum('salary'), 0, ',', ' ') }}
                <span style="font-size:12px;color:var(--muted);">MAD</span>
            </div>
            <div class="stat-label">Masse salariale</div>
        </div>
    </div>

</div>

{{-- TABLE --}}
<div class="card">

    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div>
                <h3>Staff Médical</h3>
                <span>{{ count($staff) }} membres actifs</span>
            </div>
        </div>

        <a href="{{ url('/staff/create') }}" class="btn btn-primary btn-sm">
            ➕ Nouveau Membre
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
                    <th>Salaire</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($staff as $s)
                <tr>

                    {{-- MEMBER --}}
                    <td>
                        <div class="avatar-chip">
                            <div class="avatar {{ $s['color'] }}">
                                {{ $s['initials'] }}
                            </div>

                            <div class="avatar-info">
                                <p>{{ $s['name'] }}</p>
                                <span>{{ $s['email'] }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- SPECIALTY --}}
                    <td>
                        {{ $s['specialty'] !== '–' ? $s['specialty'] : '—' }}
                    </td>

                    {{-- ROLE --}}
                    <td>
                        <span class="badge badge-{{ badgeColor($s['role'], $roleColors) }}">
                            {{ $s['role'] }}
                        </span>
                    </td>

                    {{-- PHONE --}}
                    <td>{{ $s['phone'] }}</td>

                    {{-- HIRED --}}
                    <td style="color:var(--muted);">
                        {{ $s['hired'] }}
                    </td>

                    {{-- SALARY --}}
                    <td>
                        <strong style="color:var(--teal-700);">
                            {{ number_format($s['salary'], 0, ',', ' ') }} MAD
                        </strong>
                    </td>

                    {{-- ACTIONS --}}
                    <td>
                        <div style="display:flex;gap:6px;">

                            <button class="btn btn-outline btn-sm btn-icon-only" title="Modifier">
                                ✏️
                            </button>

                            <button class="btn btn-danger btn-sm btn-icon-only" title="Supprimer">
                                🗑️
                            </button>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;color:var(--muted);padding:20px;">
                        Aucun membre trouvé
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

@endsection
