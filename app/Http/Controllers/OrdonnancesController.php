<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Ordonnance;
use App\Models\Patient;
use App\Models\StaffMedical;
use App\Notifications\ClinicNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrdonnancesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ordonnances = Ordonnance::with(['patient', 'staff', 'medicaments'])
            ->orderByDesc('date_prescription')
            ->orderByDesc('id')
            ->get();

        $totalCount = $ordonnances->count();
        $todayCount = $ordonnances->filter(fn($o) => $o->date_prescription->isToday())->count();
        $medsCount = $ordonnances->sum(fn($o) => $o->medicaments->count());

        return view('prescriptions.index', compact('ordonnances', 'totalCount', 'todayCount', 'medsCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::orderBy('nom')->get();

        $doctors = StaffMedical::whereIn('role', ['medecin', 'infirmier'])
            ->orderBy('nom')
            ->get();

        $consultations = Consultation::with('patient')
            ->orderByDesc('date_consultation')
            ->limit(30)
            ->get();

        return view('prescriptions.create', compact('patients', 'doctors', 'consultations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateOrdonnance($request);

        $ordonnance = Ordonnance::create($this->mapToOrdonnanceFields($request, $validated));

        $this->syncMedicaments($ordonnance, $validated['meds']);

        ClinicNotification::broadcast(
            'ordonnance', "Nouvelle ordonnance : {$ordonnance->patient->full_name} — {$ordonnance->staff->full_name}",
            'clipboard', 'blue', route('prescriptions.index')
        );

        flash()->success('Ordonnance émise avec succès.');

        return redirect()->route('prescriptions.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $ordonnance = Ordonnance::with('medicaments')->findOrFail($id);

        $patients = Patient::orderBy('nom')->get();

        $doctors = StaffMedical::whereIn('role', ['medecin', 'infirmier'])
            ->orderBy('nom')
            ->get();

        $consultations = Consultation::with('patient')
            ->orderByDesc('date_consultation')
            ->limit(30)
            ->get();

        return view('prescriptions.edit', compact('ordonnance', 'patients', 'doctors', 'consultations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $ordonnance = Ordonnance::findOrFail($id);

        $validated = $this->validateOrdonnance($request);

        $ordonnance->update($this->mapToOrdonnanceFields($request, $validated));

        $this->syncMedicaments($ordonnance, $validated['meds']);

        ClinicNotification::broadcast(
            'ordonnance', "Ordonnance mise à jour : {$ordonnance->patient->full_name}",
            'edit', 'blue', route('prescriptions.index')
        );

        flash()->success('Ordonnance mise à jour avec succès.');

        return redirect()->route('prescriptions.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $ordonnance = Ordonnance::findOrFail($id);
        $label = $ordonnance->patient->full_name ?? 'Patient';
        $ordonnance->delete();

        ClinicNotification::broadcast(
            'ordonnance', "Ordonnance supprimée : {$label}",
            'delete', 'rose'
        );

        flash()->success('Ordonnance supprimée.');

        return redirect()->route('prescriptions.index');
    }

    /**
     * Shared validation rules for store() and update().
     */
    private function validateOrdonnance(Request $request): array
    {
        return $request->validate([
            'patient_id'          => 'required|exists:patients,id',
            'staff_id'            => 'required|exists:staff_medicals,id',
            'consultation_id'     => [
                'nullable',
                Rule::exists('consultations', 'id')->where(
                    fn ($query) => $query->where('patient_id', $request->patient_id)
                ),
            ],
            'date'                => 'required|date|before_or_equal:today',
            'duree_validite'      => 'nullable|string|max:50',
            'diagnostic_associe'  => 'nullable|string|max:255',
            'meds'                => 'required|array|min:1',
            'meds.*.name'         => 'required|string|max:255',
            'meds.*.dosage'       => 'nullable|string|max:255',
            'meds.*.duration'     => 'nullable|string|max:255',
            'instructions'        => 'nullable|string|max:1000',
            'renouvelable'        => 'required|integer|min:0|max:3',
            'notes_privees'       => 'nullable|string|max:1000',
        ], [
            'patient_id.required' => 'Le patient est obligatoire.',
            'patient_id.exists'   => 'Patient invalide.',

            'staff_id.required' => 'Le médecin prescripteur est obligatoire.',
            'staff_id.exists'   => 'Médecin invalide.',

            'consultation_id.exists' => "Consultation invalide ou n'appartenant pas à ce patient.",

            'date.required'       => 'La date est obligatoire.',
            'date.date'            => 'Date invalide.',
            'date.before_or_equal' => 'Une ordonnance ne peut pas être datée dans le futur.',

            'meds.required'  => 'Au moins un médicament est obligatoire.',
            'meds.min'        => 'Au moins un médicament est obligatoire.',
            'meds.*.name.required' => 'Le nom du médicament est obligatoire.',

            'renouvelable.required' => 'Le renouvellement est obligatoire.',
            'renouvelable.min'       => 'Valeur invalide.',
            'renouvelable.max'       => 'Maximum 3 renouvellements.',

            'instructions.max'   => 'Max 1000 caractères.',
            'notes_privees.max'  => 'Max 1000 caractères.',
        ]);
    }

    /**
     * Map validated request data to Ordonnance's own column names/flags.
     */
    private function mapToOrdonnanceFields(Request $request, array $validated): array
    {
        return [
            'patient_id'              => $validated['patient_id'],
            'staff_id'                 => $validated['staff_id'],
            'consultation_id'          => $validated['consultation_id'] ?? null,
            'date_prescription'        => $validated['date'],
            'duree_validite'           => $validated['duree_validite'] ?? null,
            'diagnostic_associe'       => $validated['diagnostic_associe'] ?? null,
            'instructions'             => $validated['instructions'] ?? null,
            'renouvelable'             => $validated['renouvelable'],
            'substitution_autorisee'   => $request->boolean('substitution_autorisee'),
            'notes_privees'            => $validated['notes_privees'] ?? null,
            'contenu'                  => collect($validated['meds'])->map(function ($med) {
                $line = $med['name'];
                if (!empty($med['dosage'])) {
                    $line .= ' — ' . $med['dosage'];
                }
                if (!empty($med['duration'])) {
                    $line .= ', ' . $med['duration'];
                }
                return $line;
            })->implode("\n"),
        ];
    }

    /**
     * Replace an ordonnance's medication lines with the submitted set.
     */
    private function syncMedicaments(Ordonnance $ordonnance, array $meds): void
    {
        $ordonnance->medicaments()->delete();

        foreach ($meds as $med) {
            $ordonnance->medicaments()->create([
                'nom'       => $med['name'],
                'posologie' => $med['dosage'] ?? null,
                'duree'     => $med['duration'] ?? null,
            ]);
        }
    }
}
