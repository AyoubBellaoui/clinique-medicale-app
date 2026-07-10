<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\FileAttente;
use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\StaffMedical;
use App\Notifications\ClinicNotification;
use Illuminate\Http\Request;

class ConsultationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $consultations = Consultation::with(['patient', 'staff'])
            ->orderByDesc('date_consultation')
            ->get();

        $totalCount = $consultations->count();
        $todayCount = $consultations->filter(fn($c) => $c->date_consultation->isToday())->count();
        $ordonnanceCount = $consultations->where('ordonnance_demandee', true)->count();
        $scannerCount = $consultations->where('scanner_demande', true)->count();
        $analyseCount = $consultations->where('analyse_demandee', true)->count();

        return view('consultations.index', compact(
            'consultations', 'totalCount', 'todayCount', 'ordonnanceCount', 'scannerCount', 'analyseCount'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $patients = Patient::orderBy('nom')->get();

        $doctors = StaffMedical::whereIn('role', ['medecin', 'infirmier'])
            ->orderBy('nom')
            ->get();

        $fileAttenteEntry = $request->query('fa')
            ? FileAttente::find($request->query('fa'))
            : null;

        return view('consultations.create', compact('patients', 'doctors', 'fileAttenteEntry'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateConsultation($request);

        $validated['date_consultation'] = "{$validated['date']} {$validated['heure']}";
        unset($validated['date'], $validated['heure']);

        foreach (['ordonnance_demandee', 'scanner_demande', 'analyse_demandee', 'kine_demandee'] as $flag) {
            $validated[$flag] = $request->boolean($flag);
        }

        if (empty($validated['file_attente_id'])) {
            $validated['file_attente_id'] = $this->matchTodaysQueueEntry($validated['patient_id']);
        }

        $consultation = Consultation::create($validated);

        if ($consultation->file_attente_id) {
            $entry = FileAttente::find($consultation->file_attente_id);

            if ($entry && $entry->statut === 'en_attente') {
                $entry->update(['statut' => 'en_cours']);
            }
        }

        ClinicNotification::broadcast(
            'consultation', "Nouvelle consultation : {$consultation->patient->full_name} avec Dr. {$consultation->staff->full_name}",
            'clipboard', 'blue', route('consultations.index')
        );

        $followUpMessage = null;

        if (!empty($validated['prochain_rdv_date'])) {
            $followUpMessage = $this->scheduleFollowUp($consultation, $validated['prochain_rdv_date']);
        }

        flash()->success('Consultation enregistrée avec succès.' . ($followUpMessage ? ' ' . $followUpMessage : ''));

        return redirect()->route('consultations.index');
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
        $consultation = Consultation::findOrFail($id);

        $patients = Patient::orderBy('nom')->get();

        $doctors = StaffMedical::whereIn('role', ['medecin', 'infirmier'])
            ->orderBy('nom')
            ->get();

        return view('consultations.edit', compact('consultation', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $consultation = Consultation::findOrFail($id);

        $validated = $this->validateConsultation($request);

        $validated['date_consultation'] = "{$validated['date']} {$validated['heure']}";
        unset($validated['date'], $validated['heure']);

        foreach (['ordonnance_demandee', 'scanner_demande', 'analyse_demandee', 'kine_demandee'] as $flag) {
            $validated[$flag] = $request->boolean($flag);
        }

        $consultation->update($validated);

        ClinicNotification::broadcast(
            'consultation', "Consultation mise à jour : {$consultation->patient->full_name}",
            'edit', 'blue', route('consultations.index')
        );

        flash()->success('Consultation mise à jour avec succès.');

        return redirect()->route('consultations.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $consultation = Consultation::findOrFail($id);
        $label = $consultation->patient->full_name ?? 'Patient';
        $consultation->delete();

        ClinicNotification::broadcast(
            'consultation', "Consultation supprimée : {$label}",
            'delete', 'rose'
        );

        flash()->success('Consultation supprimée.');

        return redirect()->route('consultations.index');
    }

    /**
     * When a consultation is filed for a patient without going through the "Consultation"
     * button on the queue (e.g. the plain "Nouvelle consultation" menu entry), still pick up
     * that patient's active queue entry for today so it isn't left stuck on "en_cours".
     * Historical/backdated consultations naturally won't match anything and stay unlinked.
     */
    private function matchTodaysQueueEntry(int $patientId): ?int
    {
        return FileAttente::where('patient_id', $patientId)
            ->whereDate('arrived_at', today())
            ->where('statut', 'en_cours')
            ->whereDoesntHave('consultation')
            ->value('id');
    }

    /**
     * Shared validation rules for store() and update().
     */
    private function validateConsultation(Request $request): array
    {
        return $request->validate([
            'patient_id'          => 'required|exists:patients,id',
            'staff_id'            => 'required|exists:staff_medicals,id',
            'file_attente_id'     => 'nullable|exists:file_attentes,id',
            'date'                => 'required|date|before_or_equal:today',
            'heure'               => 'required|date_format:H:i',
            'type_consultation'   => 'required|in:standard,suivi,urgence,controle_post_operatoire,bilan_complet',
            'motif'               => 'nullable|string|max:255',
            'tension_systolique'  => 'nullable|integer|min:50|max:300',
            'tension_diastolique' => 'nullable|integer|min:30|max:200',
            'frequence_cardiaque' => 'nullable|integer|min:30|max:250',
            'temperature'         => 'nullable|numeric|min:30|max:45',
            'spo2'                => 'nullable|integer|min:0|max:100',
            'poids'               => 'nullable|numeric|min:0|max:500',
            'taille'              => 'nullable|integer|min:0|max:250',
            'diagnostic'          => 'nullable|string|max:2000',
            'traitement'          => 'nullable|string|max:2000',
            'notes'               => 'nullable|string|max:1000',
            'prochain_rdv_date'   => 'nullable|date|after:date',
        ], [
            'patient_id.required' => 'Le patient est obligatoire.',
            'patient_id.exists'   => 'Patient invalide.',

            'staff_id.required' => 'Le médecin est obligatoire.',
            'staff_id.exists'   => 'Médecin invalide.',

            'date.required'         => 'La date est obligatoire.',
            'date.date'              => 'Date invalide.',
            'date.before_or_equal'   => 'Une consultation ne peut pas être datée dans le futur.',

            'heure.required'    => "L'heure est obligatoire.",
            'heure.date_format' => 'Heure invalide.',

            'type_consultation.required' => 'Le type de consultation est obligatoire.',
            'type_consultation.in'       => 'Type de consultation invalide.',

            'motif.max'      => 'Motif trop long (255 caractères max).',
            'diagnostic.max' => 'Diagnostic trop long (2000 caractères max).',
            'traitement.max' => 'Traitement trop long (2000 caractères max).',
            'notes.max'      => 'Max 1000 caractères.',

            'prochain_rdv_date.date'  => 'Date invalide.',
            'prochain_rdv_date.after' => 'Le prochain rendez-vous doit être après la date de consultation.',
        ]);
    }

    /**
     * Auto-schedule a follow-up rendez-vous from a consultation, skipping silently
     * (with a flash notice) if the doctor already has something booked at the slot.
     */
    private function scheduleFollowUp(Consultation $consultation, string $date): ?string
    {
        $defaultHeure = '09:00';

        $conflict = RendezVous::where('staff_id', $consultation->staff_id)
            ->whereDate('date', $date)
            ->where('heure', $defaultHeure)
            ->where('statut', '!=', 'annule')
            ->exists();

        if ($conflict) {
            return "Le médecin a déjà un rendez-vous le {$date} à {$defaultHeure} : le suivi n'a pas été programmé automatiquement.";
        }

        $followUp = RendezVous::create([
            'patient_id'        => $consultation->patient_id,
            'staff_id'          => $consultation->staff_id,
            'date'              => $date,
            'heure'             => $defaultHeure,
            'type_consultation' => 'suivi',
            'duree'             => 30,
            'priorite'          => 'normale',
            'motif'             => 'Suivi de consultation',
        ]);

        ClinicNotification::broadcast(
            'appointment', "Rendez-vous de suivi programmé : {$consultation->patient->full_name} le {$followUp->date->format('d/m/Y')}",
            'calendar', 'teal', route('appointments.index')
        );

        return "Rendez-vous de suivi programmé le {$date} à {$defaultHeure}.";
    }
}
