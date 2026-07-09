@extends('layouts.app')

@section('title', 'Utilisateurs')
@section('page-title', 'Utilisateurs')
@section('page-subtitle', $users->count() . ' compte' . ($users->count() > 1 ? 's' : '') . ' utilisateur' . ($users->count() > 1 ? 's' : ''))

@section('content')

@php
    $roleLabels = ['admin' => 'Administrateur', 'medecin' => 'Médecin', 'infirmier' => 'Infirmier', 'secretariat' => 'Secrétariat', 'technicien' => 'Technicien'];
    $roleColors = ['admin' => 'violet', 'medecin' => 'teal', 'infirmier' => 'blue', 'secretariat' => 'amber', 'technicien' => 'rose'];
@endphp

<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div><h3>Comptes utilisateurs</h3><span>{{ $users->count() }} compte{{ $users->count() > 1 ? 's' : '' }}</span></div>
        </div>
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Nouveau Compte
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Membre lié</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $u)
                @php $initials = strtoupper(collect(explode(' ', $u->name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode('')); @endphp
                <tr>
                    <td>
                        <div class="avatar-chip">
                            <div class="avatar teal">{{ $initials ?: '?' }}</div>
                            <div class="avatar-info"><p>{{ $u->name }}</p></div>
                        </div>
                    </td>
                    <td style="color:var(--muted);">{{ $u->email }}</td>
                    <td><span class="badge badge-{{ $roleColors[$u->role] ?? 'gray' }}">{{ $roleLabels[$u->role] ?? ucfirst($u->role) }}</span></td>
                    <td style="color:var(--muted);">{{ $u->staff->full_name ?? '—' }}</td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('users.edit', $u->id) }}" class="btn btn-outline btn-sm btn-icon-only" title="Modifier">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                            </a>
                            @if($u->id !== auth()->id())
                            <form method="POST" action="{{ route('users.delete', $u->id) }}" onsubmit="return confirm('Supprimer ce compte utilisateur ?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm btn-icon-only" title="Supprimer">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding:40px 20px;text-align:center;color:var(--muted);font-size:13px;">
                        Aucun compte utilisateur. <a href="{{ route('users.create') }}" style="color:var(--teal-600);font-weight:600;">En créer un</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
