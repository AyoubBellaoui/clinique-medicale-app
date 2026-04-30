@extends('layouts.app')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', "Vue d'ensemble de votre cabinet médical")

@section('content')

{{-- ─── Stats Cards ─── --}}
<div class="stats-grid">

    {{-- Patients --}}
    <a href="#" class="stat-card">
        <div class="stat-icon teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $totalPatients ?? 0 }}</div>
            <div class="stat-label">Total Patients</div>
            <div class="stat-trend up">
                ↑ {{ $newPatientsThisMonth ?? 0 }} ce mois
            </div>
        </div>
        <svg class="stat-sparkline" viewBox="0 0 80 32" preserveAspectRatio="none">
            <path d="M0,24 L7,20 L14,22 L21,16 L28,18 L35,12 L42,14 L49,8 L56,10 L63,5 L70,7 L80,2"
                  fill="none" stroke="#34a88c" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>

    {{-- Staff --}}
    <a href="#" class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $totalStaff ?? 0 }}</div>
            <div class="stat-label">Staff Médical</div>
            <div class="stat-trend up">↑ actifs aujourd'hui</div>
        </div>
        <svg class="stat-sparkline" viewBox="0 0 80 32" preserveAspectRatio="none">
            <path d="M0,28 L7,24 L14,26 L21,20 L28,22 L35,16 L42,18 L49,12 L56,14 L63,8 L70,10 L80,5"
                  fill="none" stroke="#3b82f6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>

    {{-- Queue --}}
    <a href="#" class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $patientsInQueue ?? 0 }}</div>
            <div class="stat-label">File d'Attente</div>
            <div class="stat-trend warn">● En temps réel</div>
        </div>
        <svg class="stat-sparkline" viewBox="0 0 80 32" preserveAspectRatio="none">
            <path d="M0,20 L7,18 L14,22 L21,16 L28,20 L35,14 L42,18 L49,12 L56,16 L63,10 L70,14 L80,8"
                  fill="none" stroke="#f59e0b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>

    {{-- Revenue --}}
    <a href="#" class="stat-card">
        <div class="stat-icon violet">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value" style="font-size:22px;">{{ number_format($revenueThisMonth ?? 0, 0, ',', ' ') }} <span style="font-size:13px;font-weight:600;color:var(--muted);">MAD</span></div>
            <div class="stat-label">Revenus ce mois</div>
            <div class="stat-trend up">↑ vs mois dernier</div>
        </div>
        <svg class="stat-sparkline" viewBox="0 0 80 32" preserveAspectRatio="none">
            <path d="M0,26 L7,22 L14,24 L21,18 L28,20 L35,14 L42,16 L49,10 L56,12 L63,6 L70,8 L80,3"
                  fill="none" stroke="#8b5cf6" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </a>
</div>

{{-- ─── Main Grid: Chart + Activity Feed ─── --}}
<div class="dash-grid-main">

    {{-- Activity Chart --}}
    <div class="card chart-card">
        <div style="display:flex;justify-content:space-between;align-items:flex-start;">
            <div class="section-title">
                <div class="accent-bar"></div>
                <div>
                    <h3>Activité de la clinique</h3>
                    <span>Consultations et patients · 12 derniers mois</span>
                </div>
            </div>
            <div class="tabs">
                <button class="tab active" onclick="switchChartRange(this, 12)">12M</button>
                <button class="tab" onclick="switchChartRange(this, 6)">6M</button>
                <button class="tab" onclick="switchChartRange(this, 3)">3M</button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="mainChart"></canvas>
        </div>
        <div class="chart-legend">
            <div class="legend-item">
                <span class="legend-dot" style="background:var(--teal-400);"></span>
                Consultations
            </div>
            <div class="legend-item">
                <span class="legend-dot" style="background:#3b82f6;"></span>
                Nouveaux patients
            </div>
            <div class="legend-item">
                <span class="legend-dot" style="background:#f59e0b;"></span>
                Rendez-vous
            </div>
        </div>
    </div>

    {{-- Activity Feed --}}
    <div class="card">
        <div class="card-header">
            <div class="section-title">
                <div class="accent-bar"></div>
                <div>
                    <h3>Activité récente</h3>
                    <span>En direct</span>
                </div>
            </div>
        </div>

        {{-- Replace this static block with @forelse($recentActivity as $activity) --}}
        @forelse($recentActivity ?? [] as $activity)
            <div class="activity-item">
                <div class="activity-icon {{ $activity['color'] ?? 'green' }}">
                    {!! $activity['icon'] ?? '' !!}
                </div>
                <div class="activity-info">
                    <p>{!! $activity['text'] !!}</p>
                    <span class="time">{{ $activity['time'] }}</span>
                </div>
            </div>
        @empty
            {{-- Static placeholder items – remove once backend sends data --}}
            <div class="activity-item">
                <div class="activity-icon green">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                </div>
                <div class="activity-info">
                    <p>Aucune activité récente enregistrée.</p>
                    <span class="time">–</span>
                </div>
            </div>
        @endforelse
    </div>
</div>

{{-- ─── Bottom Grid: Queue + Specialty Donut ─── --}}
<div class="dash-grid-bottom">

    {{-- Queue preview --}}
    <div class="card">
        <div class="card-header">
            <div class="section-title">
                <div class="accent-bar"></div>
                <div>
                    <h3>File d'Attente</h3>
                    <span>Aujourd'hui</span>
                </div>
            </div>
            <a href="#" class="btn btn-outline btn-sm">Voir tout →</a>
        </div>

        @forelse($queuePreview ?? [] as $index => $entry)
            <div class="queue-item">
                <div class="queue-number {{ $entry['status'] === 'en_cours' ? 'active' : '' }}">
                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                </div>
                <div class="queue-info">
                    <p>{{ $entry['patient_name'] }}</p>
                    <span>{{ $entry['doctor_name'] }} · {{ $entry['specialty'] }}</span>
                </div>
                @php
                    $badgeMap = [
                        'en_attente' => ['cls' => 'amber', 'label' => 'En attente'],
                        'en_cours'   => ['cls' => 'green', 'label' => 'En cours'],
                        'terminé'    => ['cls' => 'teal',  'label' => 'Terminé'],
                    ];
                    $badge = $badgeMap[$entry['status']] ?? ['cls' => 'gray', 'label' => $entry['status']];
                @endphp
                <span class="badge badge-{{ $badge['cls'] }}">{{ $badge['label'] }}</span>
            </div>
        @empty
            <div style="padding:32px;text-align:center;color:var(--muted);font-size:13.5px;">
                <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 10px;display:block;opacity:.4;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"/>
                </svg>
                Aucun patient en attente
            </div>
        @endforelse
    </div>

    {{-- Specialty Donut --}}
    <div class="card chart-card">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div>
                <h3>Spécialités</h3>
                <span>Répartition des consultations</span>
            </div>
        </div>
        <div class="chart-container" style="height:240px;">
            <canvas id="specialtyChart"></canvas>
        </div>
    </div>
</div>

{{-- ─── Recent Appointments ─── --}}
<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div>
                <h3>Prochains Rendez-vous</h3>
                <span>{{ isset($upcomingAppointments) ? count($upcomingAppointments) . ' planifiés' : '0 planifiés' }}</span>
            </div>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="#" class="btn btn-outline btn-sm">Voir tout →</a>
            <a href="#" class="btn btn-primary btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Nouveau RDV
            </a>
        </div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Médecin</th>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Motif</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @forelse($upcomingAppointments ?? [] as $appt)
                    <tr>
                        <td>
                            <div class="avatar-chip">
                                <div class="avatar">{{ strtoupper(substr($appt['patient_name'], 0, 2)) }}</div>
                                <div class="avatar-info">
                                    <p>{{ $appt['patient_name'] }}</p>
                                </div>
                            </div>
                        </td>
                        <td>{{ $appt['doctor_name'] }}</td>
                        <td>{{ \Carbon\Carbon::parse($appt['date'])->format('d M Y') }}</td>
                        <td>{{ $appt['time'] }}</td>
                        <td>{{ $appt['reason'] }}</td>
                        <td>
                            @php
                                $s = match($appt['status'] ?? '') {
                                    'confirmé'   => ['cls' => 'green', 'label' => 'Confirmé'],
                                    'en_attente' => ['cls' => 'amber', 'label' => 'En attente'],
                                    'annulé'     => ['cls' => 'rose',  'label' => 'Annulé'],
                                    default      => ['cls' => 'gray',  'label' => $appt['status'] ?? '–'],
                                };
                            @endphp
                            <span class="badge badge-{{ $s['cls'] }}">{{ $s['label'] }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="table-empty">Aucun rendez-vous planifié</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
// ─── Main Activity Chart ───
(function () {
    const ctx = document.getElementById('mainChart');
    if (!ctx) return;

    // Replace these arrays with real data from the backend if needed:
    // e.g. const labels = @json($chartLabels ?? []);
    const labels = ['Mai','Juin','Juil','Août','Sep','Oct','Nov','Déc','Jan','Fév','Mar','Avr'];
    const consultations   = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; // {{ json_encode($chartConsultations ?? []) }}
    const newPatients     = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; // {{ json_encode($chartNewPatients ?? []) }}
    const appointments    = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]; // {{ json_encode($chartAppointments ?? []) }}

    const mainChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Consultations',
                    data: consultations,
                    borderColor: '#34a88c',
                    backgroundColor: 'rgba(52,168,140,.12)',
                    fill: true, tension: .4, borderWidth: 2.5,
                    pointBackgroundColor: '#34a88c', pointBorderColor: '#fff',
                    pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6
                },
                {
                    label: 'Nouveaux patients',
                    data: newPatients,
                    borderColor: '#3b82f6',
                    backgroundColor: 'rgba(59,130,246,.08)',
                    fill: true, tension: .4, borderWidth: 2.5,
                    pointBackgroundColor: '#3b82f6', pointBorderColor: '#fff',
                    pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6
                },
                {
                    label: 'Rendez-vous',
                    data: appointments,
                    borderColor: '#f59e0b',
                    backgroundColor: 'rgba(245,158,11,.06)',
                    fill: false, tension: .4, borderWidth: 2,
                    borderDash: [4, 4],
                    pointBackgroundColor: '#f59e0b', pointBorderColor: '#fff',
                    pointBorderWidth: 2, pointRadius: 3, pointHoverRadius: 5
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#133c35', padding: 12, cornerRadius: 10,
                    titleFont: { family: 'Plus Jakarta Sans', weight: 700 },
                    bodyFont:  { family: 'Plus Jakarta Sans' }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#7bbfb0', font: { family: 'Plus Jakarta Sans', size: 11 } }
                },
                y: {
                    grid: { color: 'rgba(52,168,140,.08)', drawBorder: false },
                    ticks: { color: '#7bbfb0', font: { family: 'Plus Jakarta Sans', size: 11 } }
                }
            }
        }
    });

    // Tab range switching
    window.switchChartRange = function(btn, range) {
        btn.closest('.tabs').querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        btn.classList.add('active');
        // Slice labels/data to the last `range` months and update chart
        mainChart.data.labels           = labels.slice(-range);
        mainChart.data.datasets[0].data = consultations.slice(-range);
        mainChart.data.datasets[1].data = newPatients.slice(-range);
        mainChart.data.datasets[2].data = appointments.slice(-range);
        mainChart.update();
    };
})();

// ─── Specialty Donut Chart ───
(function () {
    const ctx = document.getElementById('specialtyChart');
    if (!ctx) return;

    // Replace with real data: @json($specialtyLabels ?? []) / @json($specialtyData ?? [])
    const specialtyLabels = ['Cardiologie', 'Méd. Générale', 'Pédiatrie', 'Dermatologie'];
    const specialtyData   = [0, 0, 0, 0];

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: specialtyLabels,
            datasets: [{
                data: specialtyData,
                backgroundColor: ['#34a88c', '#3b82f6', '#f59e0b', '#8b5cf6'],
                borderWidth: 3,
                borderColor: '#fff',
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: '#133c35',
                        font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' },
                        padding: 12, boxWidth: 10, boxHeight: 10,
                        usePointStyle: true, pointStyle: 'rectRounded'
                    }
                },
                tooltip: {
                    backgroundColor: '#133c35', padding: 12, cornerRadius: 10,
                    callbacks: { label: ctx => `${ctx.label}: ${ctx.raw}%` }
                }
            }
        }
    });
})();
</script>
@endpush
