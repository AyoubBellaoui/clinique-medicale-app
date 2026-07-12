<?php

namespace App\Http\Controllers;

use App\Models\FileAttente;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\StaffMedical;
use App\Notifications\ClinicNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FileAttenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fileAttente = FileAttente::with(['patient', 'staff', 'rendezVous', 'consultation'])
            ->whereDate('arrived_at', today())
            ->orderByRaw("CASE WHEN statut IN ('en_attente', 'en_cours') THEN 0 ELSE 1 END")
            ->orderByRaw("CASE priorite WHEN 'urgente' THEN 0 WHEN 'haute' THEN 1 WHEN 'normale' THEN 2 ELSE 3 END")
            ->orderBy('position')
            ->get();

        $lateAppointments = RendezVous::whereDate('date', today())
            ->where('statut', 'programme')
            ->where('heure', '<', now()->format('H:i:s'))
            ->whereDoesntHave('fileAttente')
            ->with(['patient', 'staff'])
            ->orderBy('heure')
            ->get();

        return view('fileAttente.index', compact('fileAttente', 'lateAppointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $preselectedRdv = $request->query('rdv');

        $patients = Patient::orderBy('nom')->get();

        $doctors = StaffMedical::whereIn('role', ['medecin', 'infirmier'])
            ->orderBy('nom')
            ->get();

        $nextPosition = FileAttente::whereDate('arrived_at', today())->count() + 1;

        $waitingCount = FileAttente::whereDate('arrived_at', today())
            ->whereIn('statut', ['en_attente', 'en_cours'])
            ->count();

        $todaysAppointments = RendezVous::whereDate('date', today())
            ->whereIn('statut', ['programme', 'confirme'])
            ->whereDoesntHave('fileAttente')
            ->with(['patient', 'staff'])
            ->orderBy('heure')
            ->get();

        return view('fileAttente.create', compact('patients', 'doctors', 'nextPosition', 'waitingCount', 'todaysAppointments', 'preselectedRdv'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'     => 'required|exists:patients,id',
            'staff_id'       => 'required|exists:staff_medicals,id',
            'rendez_vous_id' => 'nullable|exists:rendez_vous,id|unique:file_attentes,rendez_vous_id',
            'priorite'       => 'required|in:normale,haute,urgente',
            'type_visite'    => 'required|in:sans_rdv,avec_rdv,urgence,suivi',
            'motif'          => 'nullable|string|max:500',
            'arrived_at'     => 'nullable|date_format:H:i',
        ], [
            'patient_id.required' => 'Le patient est obligatoire.',
            'patient_id.exists'   => 'Patient invalide.',

            'staff_id.required' => 'Le médecin est obligatoire.',
            'staff_id.exists'   => 'Médecin invalide.',

            'rendez_vous_id.exists' => 'Rendez-vous invalide.',
            'rendez_vous_id.unique' => 'Ce rendez-vous est déjà lié à une entrée de la file d\'attente.',

            'priorite.required' => 'La priorité est obligatoire.',
            'priorite.in'        => 'Priorité invalide.',

            'type_visite.required' => 'Le type de visite est obligatoire.',
            'type_visite.in'        => 'Type de visite invalide.',

            'motif.max' => 'Motif trop long (500 caractères max).',

            'arrived_at.date_format' => 'Heure invalide.',
        ]);

        $validated['arrived_at'] = !empty($validated['arrived_at'])
            ? today()->setTimeFromTimeString($validated['arrived_at'])
            : now();

        $validated['statut'] = 'en_attente';

        $entry = $this->createQueueEntryWithPosition($validated);
        $patient = $entry->patient;

        ClinicNotification::broadcast(
            'queue', "Patient ajouté à la file d'attente : {$patient->full_name}",
            'queue', 'amber', route('fileAttente.index')
        );

        if ($entry->rendez_vous_id) {
            $rdv = $entry->rendezVous;

            if ($rdv && $rdv->statut === 'programme') {
                $rdv->update(['statut' => 'confirme']);

                ClinicNotification::broadcast(
                    'appointment', "Rendez-vous confirmé (arrivée) : {$patient->full_name}",
                    'check', 'green', route('appointments.index')
                );
            }
        }

        flash()->success('Patient ajouté à la file d\'attente.');

        return redirect()->route('fileAttente.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $entry = FileAttente::findOrFail($id);

        $patients = Patient::orderBy('nom')->get();

        $doctors = StaffMedical::whereIn('role', ['medecin', 'infirmier'])
            ->orderBy('nom')
            ->get();

        return view('fileAttente.edit', compact('entry', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $entry = FileAttente::findOrFail($id);

        $validated = $request->validate([
            'patient_id'  => 'required|exists:patients,id',
            'staff_id'    => 'required|exists:staff_medicals,id',
            'statut'      => 'required|in:en_attente,en_cours,termine,annule',
            'priorite'    => 'required|in:normale,haute,urgente',
            'type_visite' => 'required|in:sans_rdv,avec_rdv,urgence,suivi',
            'motif'       => 'nullable|string|max:500',
        ], [
            'patient_id.required' => 'Le patient est obligatoire.',
            'patient_id.exists'   => 'Patient invalide.',

            'staff_id.required' => 'Le médecin est obligatoire.',
            'staff_id.exists'   => 'Médecin invalide.',

            'statut.required' => 'Le statut est obligatoire.',
            'statut.in'        => 'Statut invalide.',

            'priorite.required' => 'La priorité est obligatoire.',
            'priorite.in'        => 'Priorité invalide.',

            'type_visite.required' => 'Le type de visite est obligatoire.',
            'type_visite.in'        => 'Type de visite invalide.',

            'motif.max' => 'Motif trop long (500 caractères max).',
        ]);

        $entry->update($validated);

        $statusLabels = [
            'en_attente' => 'en attente',
            'en_cours'   => 'en cours de consultation',
            'termine'    => 'terminé',
            'annule'     => 'annulé',
        ];

        ClinicNotification::broadcast(
            'queue', "File d'attente mise à jour : {$entry->patient->full_name} ({$statusLabels[$entry->statut]})",
            'edit', 'blue', route('fileAttente.index')
        );

        flash()->success('File d\'attente mise à jour avec succès.');

        return redirect()->route('fileAttente.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $entry = FileAttente::findOrFail($id);
        $label = $entry->patient->full_name ?? 'Patient';
        $entry->delete();

        ClinicNotification::broadcast(
            'queue', "Retiré de la file d'attente : {$label}",
            'delete', 'rose'
        );

        flash()->success('Patient retiré de la file d\'attente.');

        return redirect()->route('fileAttente.index');
    }

    /**
     * Assign the next queue position and create the entry inside a transaction that
     * locks today's existing rows, so two concurrent walk-ins can't both read the same
     * count and land on the same position (a plain count-then-insert is a race).
     */
    private function createQueueEntryWithPosition(array $validated): FileAttente
    {
        return DB::transaction(function () use ($validated) {
            $count = FileAttente::whereDate('arrived_at', today())->lockForUpdate()->count();

            $validated['position'] = $count + 1;

            return FileAttente::create($validated);
        });
    }
}
