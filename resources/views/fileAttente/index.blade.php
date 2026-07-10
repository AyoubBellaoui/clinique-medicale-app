@extends('layouts.app')

@php
    $waitingCount   = $fileAttente->where('statut', 'en_attente')->count();
    $inProgress     = $fileAttente->where('statut', 'en_cours')->first();
    $inProgressCount = $fileAttente->where('statut', 'en_cours')->count();
    $completedCount = $fileAttente->where('statut', 'termine')->count();

    $waitingTimes = $fileAttente->where('statut', 'en_attente')->map(fn($e) => now()->diffInMinutes($e->arrived_at));
    $avgWait = $waitingTimes->count() ? (int) round($waitingTimes->avg()) : null;

    $statusMap = [
        'en_attente' => ['amber', 'En attente'],
        'en_cours'   => ['green', 'En cours'],
        'termine'    => ['teal',  'Terminé'],
        'annule'     => ['rose',  'Annulé'],
    ];

    $prioDot = ['normale' => '#10b981', 'haute' => '#f59e0b', 'urgente' => '#ef4444'];
    $prioLabel = ['normale' => 'Normale', 'haute' => 'Haute', 'urgente' => 'Urgente'];
@endphp

@section('title', "File d'Attente")
@section('page-title', "File d'Attente")
@section('page-subtitle', $waitingCount . ' patient' . ($waitingCount > 1 ? 's' : '') . ' en attente')

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $waitingCount }}</div>
            <div class="stat-label">En attente</div>
            <div class="stat-trend warn">● Temps réel</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green" style="background:linear-gradient(135deg,rgba(16,185,129,.18),rgba(16,185,129,.08));color:#059669;">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $inProgressCount }}</div>
            <div class="stat-label">En cours de consultation</div>
            <div class="stat-trend up">{{ $inProgress?->patient?->full_name ?? '—' }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $avgWait !== null ? '~'.$avgWait.' min' : '—' }}</div>
            <div class="stat-label">Attente moyenne</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $completedCount }}</div>
            <div class="stat-label">Consultations terminées</div>
            <div class="stat-trend up">↑ Aujourd'hui</div>
        </div>
    </div>
</div>

@if($lateAppointments->count())
<div class="card" style="margin-bottom:20px;border:1.5px solid rgba(239,68,68,.25);overflow:hidden;">
    <div class="card-header" style="background:linear-gradient(135deg,rgba(239,68,68,.06),rgba(255,255,255,0));">
        <div class="section-title">
            <div class="accent-bar" style="background:#ef4444;"></div>
            <div><h3 style="color:#dc2626;">Patients non présentés</h3><span>{{ $lateAppointments->count() }} rendez-vous en retard, pas encore enregistré{{ $lateAppointments->count() > 1 ? 's' : '' }}</span></div>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Médecin</th>
                    <th>Heure prévue</th>
                    <th>Retard</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lateAppointments as $rdv)
                @php $lateMinutes = abs((int) round($rdv->heure->diffInMinutes(now()))); @endphp
                <tr>
                    <td>{{ $rdv->patient->full_name ?? 'Patient supprimé' }}</td>
                    <td>Dr. {{ $rdv->staff->full_name ?? '—' }}</td>
                    <td>{{ $rdv->heure->format('H:i') }}</td>
                    <td><span class="badge badge-rose">+{{ $lateMinutes }} min</span></td>
                    <td>
                        <a href="{{ route('fileAttente.create', ['rdv' => $rdv->id]) }}" class="btn btn-primary btn-sm">Enregistrer l'arrivée</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div><h3>File d'Attente</h3><span>{{ $fileAttente->count() }} patient{{ $fileAttente->count() > 1 ? 's' : '' }} aujourd'hui</span></div>
        </div>
        <a href="{{ route('fileAttente.create') }}" class="btn btn-primary btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Ajouter à la file
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Patient</th>
                    <th>Médecin assigné</th>
                    <th>Heure d'arrivée</th>
                    <th>Attente</th>
                    <th>Priorité</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($fileAttente as $entry)
                @php
                    [$sc, $sl] = $statusMap[$entry->statut] ?? ['gray', $entry->statut];
                    $initials = strtoupper(substr($entry->patient->prenom ?? '', 0, 1) . substr($entry->patient->nom ?? '', 0, 1));
                    $isActive = in_array($entry->statut, ['en_attente', 'en_cours']);
                    $waitMinutes = $isActive ? now()->diffInMinutes($entry->arrived_at) : null;
                @endphp
                <tr>
                    <td>
                        <div class="queue-number {{ $entry->statut === 'en_cours' ? 'active' : '' }}" style="margin:0;">
                            {{ str_pad($entry->position ?? $loop->iteration, 2, '0', STR_PAD_LEFT) }}
                        </div>
                    </td>
                    <td>
                        <div class="avatar-chip">
                            <div class="avatar {{ $entry->patient->color ?? 'teal' }}">{{ $initials ?: '?' }}</div>
                            <div class="avatar-info"><p>{{ $entry->patient->full_name ?? 'Patient supprimé' }}</p></div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <div style="font-size:13px;font-weight:600;color:var(--teal-800);">Dr. {{ $entry->staff->full_name ?? '—' }}</div>
                            <div style="font-size:11.5px;color:var(--muted);">{{ $entry->staff->specialite ?? '' }}</div>
                        </div>
                    </td>
                    <td>{{ $entry->arrived_at->format('H:i') }}</td>
                    <td style="color:var(--muted);">{{ $waitMinutes !== null ? $waitMinutes.' min' : '—' }}</td>
                    <td>
                        <span style="display:inline-flex;align-items:center;gap:6px;font-size:12.5px;font-weight:600;color:var(--muted);">
                            <span style="width:8px;height:8px;border-radius:50%;background:{{ $prioDot[$entry->priorite] ?? '#94a3b8' }};"></span>
                            {{ $prioLabel[$entry->priorite] ?? $entry->priorite }}
                        </span>
                    </td>
                    <td><span class="badge badge-{{ $sc }}">{{ $sl }}</span></td>
                    <td>
                        <div style="display:flex;gap:6px;align-items:center;">
                            @if($entry->statut === 'en_attente')
                                <form method="POST" action="{{ route('fileAttente.update', $entry->id) }}" style="display:inline;">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="patient_id" value="{{ $entry->patient_id }}">
                                    <input type="hidden" name="staff_id" value="{{ $entry->staff_id }}">
                                    <input type="hidden" name="priorite" value="{{ $entry->priorite }}">
                                    <input type="hidden" name="type_visite" value="{{ $entry->type_visite }}">
                                    <input type="hidden" name="motif" value="{{ $entry->motif }}">
                                    <input type="hidden" name="statut" value="en_cours">
                                    <button type="submit" class="btn btn-primary btn-sm">En cours</button>
                                </form>
                            @elseif($entry->statut === 'en_cours')
                                @if(!$entry->consultation)
                                    <a href="{{ route('consultations.create', ['fa' => $entry->id]) }}" class="btn btn-primary btn-sm">Consultation</a>
                                @else
                                    <span class="badge badge-blue" title="Se termine automatiquement au règlement de la facture">Consultation en cours</span>
                                @endif
                                <form method="POST" action="{{ route('fileAttente.update', $entry->id) }}" style="display:inline;" onsubmit="return confirm('Terminer manuellement cette entrée sans passer par la facturation ?')">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="patient_id" value="{{ $entry->patient_id }}">
                                    <input type="hidden" name="staff_id" value="{{ $entry->staff_id }}">
                                    <input type="hidden" name="priorite" value="{{ $entry->priorite }}">
                                    <input type="hidden" name="type_visite" value="{{ $entry->type_visite }}">
                                    <input type="hidden" name="motif" value="{{ $entry->motif }}">
                                    <input type="hidden" name="statut" value="termine">
                                    <button type="submit" class="btn btn-outline btn-sm" title="Terminer manuellement">✓ Terminer</button>
                                </form>
                            @endif
                            <a href="{{ route('fileAttente.edit', $entry->id) }}" class="btn btn-outline btn-sm btn-icon-only" title="Modifier">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('fileAttente.delete', $entry->id) }}" onsubmit="return confirm('Retirer ce patient de la file d\'attente ?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon-only" title="Retirer">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:40px 20px;text-align:center;color:var(--muted);font-size:13px;">
                        Aucun patient dans la file d'attente aujourd'hui. <a href="{{ route('fileAttente.create') }}" style="color:var(--teal-600);font-weight:600;">En ajouter un</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
