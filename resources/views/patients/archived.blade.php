@extends('layouts.app')

@section('title', 'Patients archivés')
@section('page-title', 'Patients archivés')
@section('page-subtitle', 'Dossiers archivés (données conservées, masquées de la liste active)')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div>
                <h3>Patients archivés</h3>
                <span>{{ count($patients) }} dossier(s) archivé(s)</span>
            </div>
        </div>

        <a href="{{ route('patients.index') }}" class="btn btn-outline btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Retour aux patients actifs
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>CIN</th>
                    <th>Médecin traitant</th>
                    <th>Archivé le</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($patients as $p)
                    <tr>
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

                        <td>{{ $p->medecin?->full_name ?? '—' }}</td>

                        <td>{{ $p->deleted_at?->format('d/m/Y H:i') }}</td>

                        <td>
                            <form method="POST" action="{{ route('patients.restore', $p->id) }}"
                                  onsubmit="return confirm('Restaurer ce patient dans la liste active ?')">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                    </svg>
                                    Restaurer
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;padding:20px;color:var(--muted);">
                            Aucun patient archivé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
