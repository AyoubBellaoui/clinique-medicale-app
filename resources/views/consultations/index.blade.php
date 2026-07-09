@extends('layouts.app')

@section('title', 'Consultations')
@section('page-title', 'Consultations')
@section('page-subtitle', $totalCount . ' consultation' . ($totalCount > 1 ? 's' : '') . ' enregistrée' . ($totalCount > 1 ? 's' : ''))

@section('content')

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $totalCount }}</div>
            <div class="stat-label">Total consultations</div>
            <div class="stat-trend up">↑ {{ $todayCount }} aujourd'hui</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $ordonnanceCount }}</div>
            <div class="stat-label">Ordonnances demandées</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $scannerCount }}</div>
            <div class="stat-label">Scanners demandés</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon violet">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.776c.112-.017.227-.026.344-.026h3.812c.117 0 .232.009.344.026m-4.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H15.75a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-4.5 0V6.75A2.25 2.25 0 018.25 4.5h1.5a2.25 2.25 0 012.25 2.25v3.026"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $analyseCount }}</div>
            <div class="stat-label">Analyses prescrites</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div><h3>Consultations récentes</h3><span>{{ $totalCount }} enregistrée{{ $totalCount > 1 ? 's' : '' }}</span></div>
        </div>
        <a href="{{ route('consultations.create') }}" class="btn btn-primary btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Nouvelle Consultation
        </a>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Médecin</th>
                    <th>Date</th>
                    <th>Diag.</th>
                    <th>Trait.</th>
                    <th>Ordon.</th>
                    <th>Scanner</th>
                    <th>Analyse</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($consultations as $c)
                @php
                    $initials = strtoupper(substr($c->patient->prenom ?? '', 0, 1) . substr($c->patient->nom ?? '', 0, 1));
                    $flags = [
                        'diag' => !empty($c->diagnostic),
                        'trait' => !empty($c->traitement),
                        'ord' => $c->ordonnance_demandee,
                        'scan' => $c->scanner_demande,
                        'anal' => $c->analyse_demandee,
                    ];
                @endphp
                <tr>
                    <td>
                        <div class="avatar-chip">
                            <div class="avatar {{ $c->patient->color ?? 'teal' }}">{{ $initials ?: '?' }}</div>
                            <div class="avatar-info"><p>{{ $c->patient->full_name ?? 'Patient supprimé' }}</p></div>
                        </div>
                    </td>
                    <td>
                        <div style="font-size:13px;font-weight:600;color:var(--teal-800);">Dr. {{ $c->staff->full_name ?? '—' }}</div>
                        <div style="font-size:11px;color:var(--muted);">{{ $c->staff->specialite ?? '' }}</div>
                    </td>
                    <td style="color:var(--muted);white-space:nowrap;">{{ $c->date_consultation->format('d/m/Y H:i') }}</td>
                    @foreach($flags as $on)
                    <td>
                        @if($on)
                            <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:8px;background:rgba(16,185,129,.12);color:#059669;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                            </span>
                        @else
                            <span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:8px;background:#f0f7f5;color:#94a3b8;">–</span>
                        @endif
                    </td>
                    @endforeach
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('consultations.edit', $c->id) }}" class="btn btn-outline btn-sm btn-icon-only" title="Modifier">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('consultations.delete', $c->id) }}" onsubmit="return confirm('Supprimer cette consultation ?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon-only" title="Supprimer">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="padding:40px 20px;text-align:center;color:var(--muted);font-size:13px;">
                        Aucune consultation enregistrée. <a href="{{ route('consultations.create') }}" style="color:var(--teal-600);font-weight:600;">En créer une</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection
