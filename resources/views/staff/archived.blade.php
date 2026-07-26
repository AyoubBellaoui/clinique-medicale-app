@extends('layouts.app')

@section('title', 'Personnel archivé')
@section('page-title', 'Personnel archivé')
@section('page-subtitle', 'Profils archivés (données conservées, masqués de la liste active)')

@section('content')

<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div>
                <h3>Personnel archivé</h3>
                <span>{{ count($staff) }} profil(s) archivé(s)</span>
            </div>
        </div>

        <a href="{{ route('staff.index') }}" class="btn btn-outline btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Retour au personnel actif
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Membre</th>
                    <th>CIN</th>
                    <th>Rôle</th>
                    <th>Spécialité</th>
                    <th>Archivé le</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse($staff as $s)
                    <tr>
                        <td>
                            <div class="avatar-chip">
                                @php
                                    $initials = strtoupper(substr($s->prenom ?? '', 0, 1) . substr($s->nom ?? '', 0, 1));
                                @endphp
                                <div class="avatar {{ $s->color ?? 'teal' }}">
                                    {{ $initials ?: '?' }}
                                </div>
                                <div class="avatar-info">
                                    <p>{{ $s->prenom }} {{ $s->nom }}</p>
                                    <span>{{ $s->email }}</span>
                                </div>
                            </div>
                        </td>

                        <td><span class="text-mono">{{ $s->cin }}</span></td>

                        <td><span class="badge badge-blue">{{ ucfirst($s->role) }}</span></td>

                        <td>{{ $s->specialite ?: '—' }}</td>

                        <td>{{ $s->deleted_at?->format('d/m/Y H:i') }}</td>

                        <td>
                            <form method="POST" action="{{ route('staff.restore', $s->id) }}"
                                  onsubmit="return confirm('Restaurer ce membre dans la liste active ?')">
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
                        <td colspan="6" style="text-align:center;padding:20px;color:var(--muted);">
                            Aucun membre archivé
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
