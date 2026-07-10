<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Facture;
use App\Models\FileAttente;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\StaffMedical;

class DashboardController extends Controller
{
    private const MOIS_ABBR = [1 => 'Jan', 2 => 'Fév', 3 => 'Mar', 4 => 'Avr', 5 => 'Mai', 6 => 'Juin', 7 => 'Juil', 8 => 'Août', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Déc'];

    public function show_dashboard()
    {
        $staff = StaffMedical::all();
        $activeStaffCount = $staff->where('status', 'actif')->count();

        $now = now();

        $newPatientsThisMonth = Patient::where('created_at', '>=', $now->copy()->startOfMonth())->count();

        $patientsInQueue = FileAttente::whereDate('arrived_at', today())
            ->whereIn('statut', ['en_attente', 'en_cours'])
            ->count();

        $revenueThisMonth = (float) Facture::where('statut', 'paye')
            ->whereYear('date_facturation', $now->year)
            ->whereMonth('date_facturation', $now->month)
            ->sum('total_ttc');

        $lastMonth = $now->copy()->subMonthNoOverflow();
        $revenueLastMonth = (float) Facture::where('statut', 'paye')
            ->whereYear('date_facturation', $lastMonth->year)
            ->whereMonth('date_facturation', $lastMonth->month)
            ->sum('total_ttc');

        $revenueTrend = $revenueLastMonth > 0
            ? round((($revenueThisMonth - $revenueLastMonth) / $revenueLastMonth) * 100)
            : ($revenueThisMonth > 0 ? 100 : 0);

        // ── 12-month activity chart ──
        $rangeStart = $now->copy()->subMonths(11)->startOfMonth();

        $consultationsByMonth = Consultation::where('date_consultation', '>=', $rangeStart)
            ->get()
            ->groupBy(fn ($c) => $c->date_consultation->format('Y-m'));

        $newPatientsByMonth = Patient::where('created_at', '>=', $rangeStart)
            ->get()
            ->groupBy(fn ($p) => $p->created_at->format('Y-m'));

        $appointmentsByMonth = RendezVous::where('date', '>=', $rangeStart)
            ->get()
            ->groupBy(fn ($r) => $r->date->format('Y-m'));

        $chartLabels = [];
        $chartConsultations = [];
        $chartNewPatients = [];
        $chartAppointments = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = $now->copy()->subMonths($i);
            $key = $month->format('Y-m');

            $chartLabels[] = self::MOIS_ABBR[$month->month];
            $chartConsultations[] = $consultationsByMonth->get($key, collect())->count();
            $chartNewPatients[] = $newPatientsByMonth->get($key, collect())->count();
            $chartAppointments[] = $appointmentsByMonth->get($key, collect())->count();
        }

        // ── Specialty breakdown (donut) ──
        $specialtyCounts = Consultation::with('staff')
            ->get()
            ->groupBy(fn ($c) => $c->staff->specialite ?? 'Autre')
            ->map->count()
            ->sortDesc();

        $specialtyTotal = $specialtyCounts->sum();
        $specialtyLabels = $specialtyCounts->keys()->all();
        $specialtyData = $specialtyTotal > 0
            ? $specialtyCounts->map(fn ($c) => round($c / $specialtyTotal * 100))->values()->all()
            : [];

        // ── Recent activity feed ──
        $recentActivity = auth()->user()
            ->notifications()
            ->latest()
            ->take(8)
            ->get()
            ->map(fn ($n) => [
                'icon' => $n->data['icon'] ?? 'check',
                'color' => $n->data['color'] ?? 'teal',
                'text' => $n->data['message'] ?? '',
                'url' => $n->data['url'] ?? null,
                'time' => $n->created_at->diffForHumans(),
            ]);

        // ── Queue preview (today) ──
        $queuePreview = FileAttente::with(['patient', 'staff'])
            ->whereDate('arrived_at', today())
            ->orderByRaw("CASE WHEN statut IN ('en_attente', 'en_cours') THEN 0 ELSE 1 END")
            ->orderByRaw("CASE priorite WHEN 'urgente' THEN 0 WHEN 'haute' THEN 1 WHEN 'normale' THEN 2 ELSE 3 END")
            ->orderBy('position')
            ->take(5)
            ->get()
            ->map(fn ($e) => [
                'patient_name' => $e->patient->full_name,
                'doctor_name' => $e->staff->full_name,
                'specialty' => $e->staff->specialite ?? '—',
                'status' => $e->statut,
            ]);

        // ── Upcoming appointments ──
        $upcomingAppointments = RendezVous::with(['patient', 'staff'])
            ->where('date', '>=', today())
            ->whereIn('statut', ['programme', 'confirme'])
            ->orderBy('date')
            ->orderBy('heure')
            ->take(6)
            ->get()
            ->map(fn ($r) => [
                'patient_name' => $r->patient->full_name,
                'doctor_name' => $r->staff->full_name,
                'date' => $r->date->format('Y-m-d'),
                'time' => $r->heure->format('H:i'),
                'reason' => $r->motif ?? '—',
                'status' => $r->statut,
            ]);

        return view('dashboard.index', compact(
            'staff',
            'activeStaffCount',
            'newPatientsThisMonth',
            'patientsInQueue',
            'revenueThisMonth',
            'revenueTrend',
            'chartLabels',
            'chartConsultations',
            'chartNewPatients',
            'chartAppointments',
            'specialtyLabels',
            'specialtyData',
            'recentActivity',
            'queuePreview',
            'upcomingAppointments',
        ));
    }
}
