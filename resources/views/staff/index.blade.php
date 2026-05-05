@extends('layouts.app')

@section('title', 'Staff Médical')
@section('page-title', 'Staff Médical')
@section('page-subtitle', count($staff) . ' membres du personnel')

@section('content')

{{-- STATS --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ count($staff) }}</div>
            <div class="stat-label">Total Staff</div>
            <div class="stat-trend up">↑ Membres actifs</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ collect($staff)->where('role', 'medecin')->count() }}</div>
            <div class="stat-label">Médecins</div>
            <div class="stat-trend up">↑ Praticiens</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ collect($staff)->where('role', 'infirmier')->count() }}</div>
            <div class="stat-label">Infirmiers</div>
            <div class="stat-trend up">↑ Personnel soignant</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon violet">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">
                {{ number_format(collect($staff)->sum('salaire'), 0, ',', ' ') }}
                <span style="font-size:12px;color:var(--muted);">MAD</span>
            </div>
            <div class="stat-label">Masse salariale</div>
            <div class="stat-trend up">↑ Mensuelle</div>
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
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Nouveau Membre
        </a>
    </div>

    <div class="table-wrap">
        <table>

            <thead>
                <tr>
                    <th>Membre</th>
                    <th>Spécialité</th>
                    <th>Téléphone</th>
                    <th>Embauché</th>
                    <th>Salaire</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($staff as $s)
                <tr>

                    {{-- MEMBER --}}
                    <td>
                        <div class="avatar-chip">
                            <div class="avatar {{ $s->color ?? 'teal' }}">
                                @php
                                    $nom      = trim($s->nom    ?? '');
                                    $prenom   = trim($s->prenom ?? '');
                                    $initials = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
                                @endphp
                                {{ $initials ?: '?' }}
                            </div>
                            <div class="avatar-info">
                                <p>{{ $s->prenom }} {{ $s->nom }}</p>
                                <span>{{ $s->email }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- SPECIALTY --}}
                    <td>{{ $s->specialite ?: '—' }}</td>

                    {{-- PHONE --}}
                    <td>{{ $s->telephone }}</td>

                    {{-- HIRED --}}
                    <td style="color:var(--muted);">{{ $s->date_embauche }}</td>

                    {{-- SALARY --}}
                    <td>
                        <strong style="color:var(--teal-700);">
                            {{ number_format($s->salaire, 0, ',', ' ') }} MAD
                        </strong>
                    </td>

                    {{-- ROLE --}}
                    <td>
                        @php
                            $role = trim(mb_strtolower($s->role ?? ''));
                            $badgeColor = match($role) {
                                'medecin'                => 'teal',
                                'infirmier'              => 'blue',
                                'secretaire', 'admin'    => 'amber',
                                'technicien'             => 'violet',
                                default                  => 'gray',
                            };
                            $roleLabel = match($role) {
                                'medecin'    => 'Médecin',
                                'infirmier'  => 'Infirmier(e)',
                                'secretaire' => 'Secrétaire',
                                'admin'      => 'Administrateur',
                                'technicien' => 'Technicien',
                                default      => ucfirst($s->role ?? ''),
                            };
                        @endphp
                        <span class="badge badge-{{ $badgeColor }}">{{ $roleLabel }}</span>
                    </td>

                    {{-- ACTIONS --}}
                    <td onclick="event.stopPropagation()">
                        <div style="display:flex;gap:4px;">

                            <a href="{{ url('/staff/' . $s->id . '/edit') }}"
                               class="btn btn-outline btn-sm btn-icon-only" title="Modifier">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                                </svg>
                            </a>

                            <form method="POST" action="{{ url('/Staff/delete/' . $s->id) }}"
                                  style="display:inline;"
                                  onsubmit="return confirm('Supprimer ce membre du staff ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon-only" title="Supprimer">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px 20px;">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:10px;">
                            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--soft);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                            <span style="font-size:14px;font-weight:500;color:var(--muted);">Aucun membre trouvé</span>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

@endsection
