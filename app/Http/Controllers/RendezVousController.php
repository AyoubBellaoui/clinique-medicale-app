<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\RendezVous;
use App\Models\StaffMedical;
use App\Notifications\ClinicNotification;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class RendezVousController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = RendezVous::with(['patient', 'staff'])
            ->orderBy('date')
            ->orderBy('heure')
            ->get();

        return view('appointments.index', compact('appointments'));
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

        return view('appointments.create', compact('patients', 'doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id'         => 'required|exists:patients,id',
            'staff_id'           => 'required|exists:staff_medicals,id',
            'date'                => 'required|date|after_or_equal:today',
            'heure'               => [
                'required',
                'date_format:H:i',
                Rule::unique('rendez_vous', 'heure')->where(function ($query) use ($request) {
                    return $query->where('staff_id', $request->staff_id)
                        ->whereDate('date', $request->date)
                        ->where('statut', '!=', 'annule');
                }),
            ],
            'type_consultation'   => 'required|in:standard,suivi,urgence,controle_post_operatoire,bilan_complet',
            'duree'               => 'required|integer|in:15,30,45,60,90',
            'motif'               => 'nullable|string|max:255',
            'priorite'            => 'required|in:normale,haute,urgente',
            'notes'               => 'nullable|string|max:1000',
        ], [
            'patient_id.required' => 'Le patient est obligatoire.',
            'patient_id.exists'   => 'Patient invalide.',

            'staff_id.required' => 'Le médecin est obligatoire.',
            'staff_id.exists'   => 'Médecin invalide.',

            'date.required'         => 'La date est obligatoire.',
            'date.date'              => 'Date invalide.',
            'date.after_or_equal'    => 'La date doit être aujourd\'hui ou dans le futur.',

            'heure.required'    => "L'heure est obligatoire.",
            'heure.date_format' => 'Heure invalide.',
            'heure.unique'       => 'Ce médecin a déjà un rendez-vous à cette heure ce jour-là.',

            'type_consultation.required' => 'Le type de consultation est obligatoire.',
            'type_consultation.in'       => 'Type de consultation invalide.',

            'duree.required' => 'La durée est obligatoire.',
            'duree.in'        => 'Durée invalide.',

            'motif.max' => 'Motif trop long (255 caractères max).',

            'priorite.required' => 'La priorité est obligatoire.',
            'priorite.in'        => 'Priorité invalide.',

            'notes.max' => 'Max 1000 caractères.',
        ]);

        if ($validated['date'] === today()->format('Y-m-d') && $validated['heure'] < now()->format('H:i')) {
            throw ValidationException::withMessages([
                'heure' => "L'heure sélectionnée est déjà passée pour aujourd'hui.",
            ]);
        }

        $appointment = RendezVous::create($validated);

        ClinicNotification::broadcast(
            'appointment', "Nouveau rendez-vous : {$appointment->patient->full_name} le {$appointment->date->format('d/m/Y')} à {$appointment->heure->format('H:i')}",
            'calendar', 'teal', route('appointments.index')
        );

        flash()->success('Rendez-vous créé avec succès.');

        return redirect()->route('appointments.index');
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
        $appointment = RendezVous::findOrFail($id);

        $patients = Patient::orderBy('nom')->get();

        $doctors = StaffMedical::whereIn('role', ['medecin', 'infirmier'])
            ->orderBy('nom')
            ->get();

        return view('appointments.edit', compact('appointment', 'patients', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $appointment = RendezVous::findOrFail($id);

        $validated = $request->validate([
            'patient_id'         => 'required|exists:patients,id',
            'staff_id'           => 'required|exists:staff_medicals,id',
            'date'                => 'required|date',
            'heure'               => [
                'required',
                'date_format:H:i',
                Rule::unique('rendez_vous', 'heure')->ignore($appointment->id)->where(function ($query) use ($request) {
                    return $query->where('staff_id', $request->staff_id)
                        ->whereDate('date', $request->date)
                        ->where('statut', '!=', 'annule');
                }),
            ],
            'statut'              => 'required|in:programme,confirme,annule,termine',
            'type_consultation'   => 'required|in:standard,suivi,urgence,controle_post_operatoire,bilan_complet',
            'duree'               => 'required|integer|in:15,30,45,60,90',
            'motif'               => 'nullable|string|max:255',
            'priorite'            => 'required|in:normale,haute,urgente',
            'notes'               => 'nullable|string|max:1000',
        ], [
            'patient_id.required' => 'Le patient est obligatoire.',
            'patient_id.exists'   => 'Patient invalide.',

            'staff_id.required' => 'Le médecin est obligatoire.',
            'staff_id.exists'   => 'Médecin invalide.',

            'date.required' => 'La date est obligatoire.',
            'date.date'      => 'Date invalide.',

            'heure.required'    => "L'heure est obligatoire.",
            'heure.date_format' => 'Heure invalide.',
            'heure.unique'       => 'Ce médecin a déjà un rendez-vous à cette heure ce jour-là.',

            'statut.required' => 'Le statut est obligatoire.',
            'statut.in'        => 'Statut invalide.',

            'type_consultation.required' => 'Le type de consultation est obligatoire.',
            'type_consultation.in'       => 'Type de consultation invalide.',

            'duree.required' => 'La durée est obligatoire.',
            'duree.in'        => 'Durée invalide.',

            'motif.max' => 'Motif trop long (255 caractères max).',

            'priorite.required' => 'La priorité est obligatoire.',
            'priorite.in'        => 'Priorité invalide.',

            'notes.max' => 'Max 1000 caractères.',
        ]);

        $appointment->update($validated);

        ClinicNotification::broadcast(
            'appointment', "Rendez-vous mis à jour : {$appointment->patient->full_name} le {$appointment->date->format('d/m/Y')}",
            'edit', 'blue', route('appointments.index')
        );

        flash()->success('Rendez-vous mis à jour avec succès.');

        return redirect()->route('appointments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = RendezVous::findOrFail($id);
        $label = "{$appointment->patient->full_name} le {$appointment->date->format('d/m/Y')}";
        $appointment->delete();

        ClinicNotification::broadcast(
            'appointment', "Rendez-vous annulé/supprimé : {$label}",
            'delete', 'rose'
        );

        flash()->success('Rendez-vous supprimé.');

        return redirect()->route('appointments.index');
    }
}
