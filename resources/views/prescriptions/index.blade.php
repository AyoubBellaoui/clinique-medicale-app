@extends('layouts.app')

@section('title', 'Ordonnances')
@section('page-title', 'Ordonnances')
@section('page-subtitle', $totalCount . ' ordonnance' . ($totalCount > 1 ? 's' : '') . ' émise' . ($totalCount > 1 ? 's' : ''))

@section('content')

{{-- Stats --}}
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);">
    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $totalCount }}</div>
            <div class="stat-label">Total Ordonnances</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $todayCount }}</div>
            <div class="stat-label">Aujourd'hui</div>
            <div class="stat-trend up">↑ Émises</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007z"/></svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $medsCount }}</div>
            <div class="stat-label">Médicaments prescrits</div>
            <div class="stat-trend up">↑ Au total</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div><h3>Ordonnances récentes</h3><span>{{ $totalCount }} ordonnance{{ $totalCount > 1 ? 's' : '' }}</span></div>
        </div>
        <a href="{{ route('prescriptions.create') }}" class="btn btn-primary btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
            Nouvelle Ordonnance
        </a>
    </div>

    <div style="padding:18px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
            @forelse($ordonnances as $rx)
            @php $initials = strtoupper(substr($rx->patient->prenom ?? '', 0, 1) . substr($rx->patient->nom ?? '', 0, 1)); @endphp
            <div class="rx-card">
                <div class="rx-head">
                    <div style="display:flex;gap:10px;align-items:center;">
                        <div class="avatar {{ $rx->patient->color ?? 'teal' }}">{{ $initials ?: '?' }}</div>
                        <div>
                            <h4 style="font-size:14px;font-weight:700;color:var(--teal-800);">{{ $rx->patient->full_name ?? 'Patient supprimé' }}</h4>
                            <span style="font-size:11.5px;color:var(--muted);">Dr. {{ $rx->staff->full_name ?? '—' }} · {{ $rx->date_prescription->format('d/m/Y') }} @if($rx->duree_validite) · {{ $rx->duree_validite }} @endif</span>
                        </div>
                    </div>
                    <div style="display:flex;gap:4px;">
                        <a href="{{ route('prescriptions.edit', $rx->id) }}" class="btn btn-ghost btn-sm btn-icon-only" title="Modifier">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('prescriptions.delete', $rx->id) }}" onsubmit="return confirm('Supprimer cette ordonnance ?')" style="display:inline;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-ghost btn-sm btn-icon-only" title="Supprimer" style="color:#f43f5e;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="rx-meds">
                    @forelse($rx->medicaments as $med)
                        @php
                            $medLine = $med->nom;
                            if ($med->posologie) { $medLine .= ' — ' . $med->posologie; }
                            if ($med->duree) { $medLine .= ', ' . $med->duree; }
                        @endphp
                        <div class="rx-med">{{ $medLine }}</div>
                    @empty
                        <div class="rx-med" style="color:var(--muted);">Aucun médicament listé</div>
                    @endforelse
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;padding:40px 20px;text-align:center;color:var(--muted);font-size:13px;">
                Aucune ordonnance enregistrée. <a href="{{ route('prescriptions.create') }}" style="color:var(--teal-600);font-weight:600;">En créer une</a>.
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.rx-card { padding:16px;border-radius:12px;background:linear-gradient(135deg,#fff,var(--teal-50));border:1px solid rgba(52,168,140,.15);margin-bottom:0;transition:all .2s; }
.rx-card:hover { transform:translateY(-2px);box-shadow:var(--shadow);border-color:var(--teal-400); }
.rx-head { display:flex;justify-content:space-between;margin-bottom:10px;align-items:start; }
.rx-meds { display:flex;flex-direction:column;gap:6px;margin-top:10px;padding-top:10px;border-top:1px dashed rgba(52,168,140,.2); }
.rx-med { display:flex;align-items:center;gap:8px;font-size:12.5px;color:var(--teal-700); }
.rx-med::before { content:'✦';font-weight:800;color:var(--teal-500);font-size:11px; }
</style>
@endpush
