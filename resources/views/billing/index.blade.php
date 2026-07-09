@extends('layouts.app')

@section('title', 'Factures')
@section('page-title', 'Factures')
@section('page-subtitle', $factures->count() . ' facture' . ($factures->count() > 1 ? 's' : '') . ' au total')

@section('content')

{{-- Revenue summary --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <div style="padding:20px;border-radius:14px;background:linear-gradient(135deg,#fff,rgba(16,185,129,.06));border:1px solid rgba(16,185,129,.2);position:relative;overflow:hidden;">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Encaissé</div>
        <div style="font-size:26px;font-weight:800;color:var(--teal-800);font-variant-numeric:tabular-nums;letter-spacing:-.4px;">
            {{ number_format($paidTotal,0,',',' ') }} <span style="font-size:13px;font-weight:600;color:var(--muted);">MAD</span>
        </div>
    </div>
    <div style="padding:20px;border-radius:14px;background:linear-gradient(135deg,#fff,rgba(245,158,11,.06));border:1px solid rgba(245,158,11,.2);">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">En attente</div>
        <div style="font-size:26px;font-weight:800;color:var(--teal-800);font-variant-numeric:tabular-nums;letter-spacing:-.4px;">
            {{ number_format($pendingTotal,0,',',' ') }} <span style="font-size:13px;font-weight:600;color:var(--muted);">MAD</span>
        </div>
        <div class="stat-trend warn" style="margin-top:8px;">● {{ $pendingCount }} facture{{ $pendingCount > 1 ? 's' : '' }} en attente</div>
    </div>
    <div style="padding:20px;border-radius:14px;background:linear-gradient(135deg,#fff,rgba(244,63,94,.06));border:1px solid rgba(244,63,94,.2);">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">En retard</div>
        <div style="font-size:26px;font-weight:800;color:var(--teal-800);font-variant-numeric:tabular-nums;letter-spacing:-.4px;">
            {{ number_format($overdueTotal,0,',',' ') }} <span style="font-size:13px;font-weight:600;color:var(--muted);">MAD</span>
        </div>
        <div class="stat-trend down" style="margin-top:8px;">↓ {{ $overdueCount }} facture{{ $overdueCount > 1 ? 's' : '' }} en retard</div>
    </div>
</div>

{{-- Charts row --}}
<div class="dash-grid-main" style="margin-bottom:20px;">
    <div class="card chart-card">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div><h3>Revenus mensuels</h3><span>6 derniers mois</span></div>
        </div>
        <div class="chart-container"><canvas id="revenueChart"></canvas></div>
    </div>
    <div class="card">
        <div class="card-header">
            <div class="section-title">
                <div class="accent-bar"></div>
                <div><h3>Modes de paiement</h3></div>
            </div>
        </div>
        <div style="padding:20px;">
            <div class="chart-container" style="height:200px;"><canvas id="paymentChart"></canvas></div>
        </div>
    </div>
</div>

{{-- Invoices table --}}
<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div><h3>Factures</h3><span>{{ $factures->count() }} facture{{ $factures->count() > 1 ? 's' : '' }}</span></div>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('billing.create') }}" class="btn btn-primary btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Nouvelle Facture
            </a>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Facture N°</th>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($factures as $f)
                @php
                    if ($f->statut === 'paye') { [$sc, $sl] = ['green', 'Payé']; }
                    elseif ($f->isOverdue()) { [$sc, $sl] = ['rose', 'En retard']; }
                    else { [$sc, $sl] = ['amber', 'En attente']; }
                    $initials = strtoupper(substr($f->patient->prenom ?? '', 0, 1) . substr($f->patient->nom ?? '', 0, 1));
                @endphp
                <tr>
                    <td><span class="text-mono" style="font-weight:600;color:var(--teal-700);">{{ $f->numero }}</span></td>
                    <td>
                        <div class="avatar-chip">
                            <div class="avatar {{ $f->patient->color ?? 'teal' }}">{{ $initials ?: '?' }}</div>
                            <div class="avatar-info"><p>{{ $f->patient->full_name ?? 'Patient supprimé' }}</p></div>
                        </div>
                    </td>
                    <td style="color:var(--muted);">{{ $f->date_facturation->format('d/m/Y') }}</td>
                    <td><strong style="color:var(--teal-800);font-variant-numeric:tabular-nums;">{{ number_format($f->total_ttc,0,',',' ') }} MAD</strong></td>
                    <td><span class="badge badge-{{ $sc }}">{{ $sl }}</span></td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <a href="{{ route('billing.edit', $f->id) }}" class="btn btn-outline btn-sm btn-icon-only" title="Modifier">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
                            </a>
                            <form method="POST" action="{{ route('billing.delete', $f->id) }}" onsubmit="return confirm('Supprimer cette facture ?')" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline btn-sm btn-icon-only" title="Supprimer">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/></svg>
                                </button>
                            </form>
                            @if($f->statut !== 'paye')
                                <form method="POST" action="{{ route('billing.markPaid', $f->id) }}" style="display:inline;">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-success btn-sm">Marquer payé</button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:40px 20px;text-align:center;color:var(--muted);font-size:13px;">
                        Aucune facture enregistrée. <a href="{{ route('billing.create') }}" style="color:var(--teal-600);font-weight:600;">En créer une</a>.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function(){
    const revenueCtx = document.getElementById('revenueChart');
    if(revenueCtx) {
        new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: @json(array_column($monthlyRevenue, 'label')),
                datasets: [{
                    label: 'Revenus',
                    data: @json(array_column($monthlyRevenue, 'total')),
                    backgroundColor: (ctx) => {
                        const g = ctx.chart.ctx.createLinearGradient(0,0,0,220);
                        g.addColorStop(0,'rgba(52,168,140,.9)');
                        g.addColorStop(1,'rgba(52,168,140,.3)');
                        return g;
                    },
                    borderRadius: 8, borderSkipped: false, borderWidth: 0
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: { backgroundColor:'#133c35', padding:12, cornerRadius:10,
                        callbacks: { label: ctx => ctx.raw.toLocaleString('fr-FR') + ' MAD' }
                    }
                },
                scales: {
                    x: { grid:{display:false}, ticks:{color:'#7bbfb0',font:{family:'Plus Jakarta Sans',size:11}} },
                    y: { grid:{color:'rgba(52,168,140,.08)',drawBorder:false},
                         ticks:{color:'#7bbfb0',font:{family:'Plus Jakarta Sans',size:11},callback: v => (v/1000)+'k'} }
                }
            }
        });
    }

    const paymentCtx = document.getElementById('paymentChart');
    if(paymentCtx) {
        const labels = @json(array_values($paymentLabels));
        const keys = @json(array_keys($paymentLabels));
        const breakdown = @json($paymentBreakdown);
        const data = keys.map(k => breakdown[k] || 0);

        new Chart(paymentCtx, {
            type: 'polarArea',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: ['rgba(52,168,140,.7)','rgba(59,130,246,.7)','rgba(245,158,11,.7)','rgba(139,92,246,.7)','rgba(244,63,94,.7)'],
                    borderWidth: 2, borderColor: '#fff'
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { position:'right', labels:{font:{family:'Plus Jakarta Sans',size:11,weight:'600'},padding:10,usePointStyle:true} }
                },
                scales: { r: { grid:{color:'rgba(52,168,140,.1)'}, ticks:{display:false} } }
            }
        });
    }
})();
</script>
@endpush
