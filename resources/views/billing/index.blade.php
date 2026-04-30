@extends('layouts.app')

@section('title', 'Factures')
@section('page-title', 'Factures')
@section('page-subtitle', '8 factures ce mois')

@section('content')

@php
$invoices = [
    ['id'=>'FAC-2026-0047','initials'=>'OB','color'=>'teal',  'patient'=>'Omar Benhaddou',  'date'=>'Aujourd\'hui 09:00','amount'=>450, 'status'=>'payé'],
    ['id'=>'FAC-2026-0046','initials'=>'MT','color'=>'blue',  'patient'=>'Meriem Tahiri',   'date'=>'Aujourd\'hui 06:30','amount'=>650, 'status'=>'en_attente'],
    ['id'=>'FAC-2026-0045','initials'=>'RA','color'=>'amber', 'patient'=>'Rachid Amrani',   'date'=>'Hier 10:00',        'amount'=>300, 'status'=>'payé'],
    ['id'=>'FAC-2026-0044','initials'=>'NF','color'=>'rose',  'patient'=>'Nadia Filali',    'date'=>'Hier 06:00',        'amount'=>400, 'status'=>'payé'],
    ['id'=>'FAC-2026-0043','initials'=>'FE','color'=>'teal',  'patient'=>'Fatima El Idrissi','date'=>'il y a 2j',        'amount'=>250, 'status'=>'payé'],
    ['id'=>'FAC-2026-0042','initials'=>'AM','color'=>'amber', 'patient'=>'Aicha Moussaoui', 'date'=>'il y a 3j',         'amount'=>850, 'status'=>'retard'],
    ['id'=>'FAC-2026-0041','initials'=>'HO','color'=>'rose',  'patient'=>'Hassan Ouazzani', 'date'=>'il y a 7j',         'amount'=>320, 'status'=>'payé'],
    ['id'=>'FAC-2026-0040','initials'=>'YB','color'=>'blue',  'patient'=>'Youssef Benali',  'date'=>'il y a 10j',        'amount'=>250, 'status'=>'payé'],
];
$paid    = array_sum(array_column(array_filter($invoices, fn($i) => $i['status']==='payé'),    'amount'));
$pending = array_sum(array_column(array_filter($invoices, fn($i) => $i['status']==='en_attente'),'amount'));
$overdue = array_sum(array_column(array_filter($invoices, fn($i) => $i['status']==='retard'),  'amount'));
$statusMap = [
    'payé'       => ['green','Payé'],
    'en_attente' => ['amber','En attente'],
    'retard'     => ['rose', 'En retard'],
];
@endphp

{{-- Revenue summary --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    <div style="padding:20px;border-radius:14px;background:linear-gradient(135deg,#fff,rgba(16,185,129,.06));border:1px solid rgba(16,185,129,.2);position:relative;overflow:hidden;">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">Encaissé</div>
        <div style="font-size:26px;font-weight:800;color:var(--teal-800);font-variant-numeric:tabular-nums;letter-spacing:-.4px;">
            {{ number_format($paid,0,',',' ') }} <span style="font-size:13px;font-weight:600;color:var(--muted);">MAD</span>
        </div>
        <div class="stat-trend up" style="margin-top:8px;">↑ +18% vs mois dernier</div>
    </div>
    <div style="padding:20px;border-radius:14px;background:linear-gradient(135deg,#fff,rgba(245,158,11,.06));border:1px solid rgba(245,158,11,.2);">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">En attente</div>
        <div style="font-size:26px;font-weight:800;color:var(--teal-800);font-variant-numeric:tabular-nums;letter-spacing:-.4px;">
            {{ number_format($pending,0,',',' ') }} <span style="font-size:13px;font-weight:600;color:var(--muted);">MAD</span>
        </div>
        <div class="stat-trend warn" style="margin-top:8px;">● 1 facture en attente</div>
    </div>
    <div style="padding:20px;border-radius:14px;background:linear-gradient(135deg,#fff,rgba(244,63,94,.06));border:1px solid rgba(244,63,94,.2);">
        <div style="font-size:11px;color:var(--muted);font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin-bottom:8px;">En retard</div>
        <div style="font-size:26px;font-weight:800;color:var(--teal-800);font-variant-numeric:tabular-nums;letter-spacing:-.4px;">
            {{ number_format($overdue,0,',',' ') }} <span style="font-size:13px;font-weight:600;color:var(--muted);">MAD</span>
        </div>
        <div class="stat-trend down" style="margin-top:8px;">↓ 1 facture en retard</div>
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
            <div><h3>Factures</h3><span>{{ count($invoices) }} factures</span></div>
        </div>
        <div style="display:flex;gap:8px;">
            <button class="btn btn-outline btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                Exporter
            </button>
            <a href="{{ url('/billing/create') }}" class="btn btn-primary btn-sm">
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
                @foreach($invoices as $inv)
                @php [$sc,$sl] = $statusMap[$inv['status']] ?? ['gray',$inv['status']]; @endphp
                <tr>
                    <td><span class="text-mono" style="font-weight:600;color:var(--teal-700);">{{ $inv['id'] }}</span></td>
                    <td>
                        <div class="avatar-chip">
                            <div class="avatar {{ $inv['color'] }}">{{ $inv['initials'] }}</div>
                            <div class="avatar-info"><p>{{ $inv['patient'] }}</p></div>
                        </div>
                    </td>
                    <td style="color:var(--muted);">{{ $inv['date'] }}</td>
                    <td><strong style="color:var(--teal-800);font-variant-numeric:tabular-nums;">{{ number_format($inv['amount'],0,',',' ') }} MAD</strong></td>
                    <td><span class="badge badge-{{ $sc }}">{{ $sl }}</span></td>
                    <td>
                        <div style="display:flex;gap:4px;">
                            <button class="btn btn-outline btn-sm btn-icon-only" title="Voir">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </button>
                            <button class="btn btn-outline btn-sm btn-icon-only" title="Télécharger">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            </button>
                            @if($inv['status'] !== 'payé')
                                <button class="btn btn-success btn-sm">Marquer payé</button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span>Affichage 1–8 sur 8</span>
        <div class="pagination-btns">
            <button class="page-btn" disabled>‹</button>
            <button class="page-btn active">1</button>
            <button class="page-btn" disabled>›</button>
        </div>
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
                labels: ['Nov','Déc','Jan','Fév','Mar','Avr'],
                datasets: [{
                    label: 'Revenus',
                    data: [18500, 22300, 24800, 21200, 27500, 32400],
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
        new Chart(paymentCtx, {
            type: 'polarArea',
            data: {
                labels: ['Espèces','Carte','Chèque','Virement'],
                datasets: [{
                    data: [45,35,12,8],
                    backgroundColor: ['rgba(52,168,140,.7)','rgba(59,130,246,.7)','rgba(245,158,11,.7)','rgba(139,92,246,.7)'],
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
