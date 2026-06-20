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
            <div class="stat-value">{{ count($patients) }}</div>
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
            <div class="stat-value">{{ collect($patients)->where('genre', 'M')->count() }}</div>
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
            <div class="stat-value">{{ collect($patients)->where('genre', 'F')->count() }}</div>
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
            <div class="stat-value">{{ $patients->where('statut_dossier', 'actif')->count() }}</div>
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
                <span>{{ count($patients) }} patients enregistrés</span>
            </div>
        </div>

        <div style="display:flex;gap:8px;align-items:center;">
            <div style="display:flex;align-items:center;gap:8px;background:var(--teal-50);border:1.5px solid rgba(52,168,140,.2);border-radius:10px;padding:8px 14px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text"
                    id="patient-search"
                    placeholder="Rechercher un patient..."
                    style="border:none;outline:none;background:none;font-size:13px;font-family:inherit;color:var(--teal-800);width:200px;"
                    autocomplete="off">
            </div>

            <button class="btn btn-outline btn-sm">Exporter</button>

            <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
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

            <tbody id="patients-tbody">
                @forelse($patients as $p)
                    <tr data-search="{{ strtolower($p->prenom . ' ' . $p->nom . ' ' . $p->cin . ' ' . ($p->email ?? '') . ' ' . ($p->telephone ?? '') . ' ' . ($p->groupe_sanguin ?? '')) }}">
                        <td>
                            <div class="avatar-chip">
                                @php
                                    $initials = strtoupper(substr($p->prenom ?? '', 0, 1) . substr($p->nom ?? '', 0, 1));
                                @endphp
                                <div class="avatar {{ $p->color ?? 'teal' }}">
                                    {{ $initials ?: '?' }}
                                </div>
                                <div class="avatar-info">
                                    <p>{{ $p->prenom }} {{ $p->nom }}</p>
                                    <span>{{ $p->email }}</span>
                                </div>
                            </div>
                        </td>

                        <td><span class="text-mono">{{ $p->cin }}</span></td>

                        <td>
                            @if($p->date_naissance)
                                {{ \Carbon\Carbon::parse($p->date_naissance)->age }} ans
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>

                        <td>
                            <span class="badge badge-{{ $p->genre === 'F' ? 'rose' : 'blue' }}">
                                {{ $p->genre === 'F' ? 'Féminin' : 'Masculin' }}
                            </span>
                        </td>

                        <td>
                            @if($p->groupe_sanguin)
                                <span style="font-size:12.5px;font-weight:700;color:var(--teal-700);
                                    background:rgba(52,168,140,.08);padding:3px 10px;border-radius:6px;">
                                    {{ $p->groupe_sanguin }}
                                </span>
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>

                        <td style="color:var(--muted);">—</td>

                        <td>{{ $p->telephone ?? '—' }}</td>

                        <td>
                            <div style="display:flex;gap:4px;">
                                <a href="{{ route('patients.edit', $p->id) }}"
                                   class="btn btn-outline btn-sm btn-icon-only" title="Modifier">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('patients.delete', $p->id) }}"
                                      onsubmit="return confirm('Supprimer ce patient ?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-icon-only" title="Supprimer">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </form>
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
                <tr id="patient-no-results" style="display:none;">
                    <td colspan="8" style="text-align:center;padding:30px 20px;color:var(--muted);">
                        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 8px;display:block;opacity:.5;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        Aucun patient ne correspond à votre recherche
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span>Affichage {{ $patients->count() ?? 0 }} patients</span>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('patient-search').addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#patients-tbody tr[data-search]');
    let visible = 0;
    rows.forEach(row => {
        const match = !q || row.dataset.search.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('patient-no-results').style.display = (visible === 0 && q) ? '' : 'none';
});
</script>
@endpush
