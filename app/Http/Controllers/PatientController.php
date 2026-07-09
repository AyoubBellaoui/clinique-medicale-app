<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\StaffMedical;
use App\Notifications\ClinicNotification;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $patients = Patient::with('medecin')->get();
        return view('patients.index', compact('patients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = StaffMedical::whereIn('role', ['medecin', 'infirmier'])
            ->orderBy('nom')
            ->get();

        return view('patients.create', compact('doctors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            // Section 1 – Identity
            'prenom'         => 'required|string|min:2|max:50',
            'nom'            => 'required|string|min:2|max:50',
            'cin'            => 'required|string|max:20|unique:patients,cin',
            'date_naissance' => 'nullable|date|before:today',
            'genre'          => 'nullable|in:M,F',
            'groupe_sanguin' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'telephone'      => 'nullable|string|max:20',
            'email'          => 'nullable|email|unique:patients,email',
            'adresse'        => 'nullable|string|max:255',
            'color'          => 'nullable|in:teal,blue,amber,rose,violet',

            // Section 2 – Medical
            'allergies'      => 'nullable|string|max:500',
            'antecedents'    => 'nullable|string|max:2000',
            'medecin_id'     => 'nullable|exists:staff_medicals,id',
            'statut_dossier' => 'nullable|in:actif,inactif',

            // Section 3 – Insurance & Emergency
            'assurance_type'      => 'nullable|in:Aucune,CNSS,CNOPS,FAR,Privée,Autre',
            'assurance_numero'    => 'nullable|string|max:50',
            'contact_urgence_nom' => 'nullable|string|max:100',
            'contact_urgence_tel' => 'nullable|string|max:20',
            'lien_urgence'        => 'nullable|string|max:50',

        ], [

            // prenom / nom
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.min'      => 'Minimum 2 caractères pour le prénom.',
            'prenom.max'      => 'Maximum 50 caractères pour le prénom.',

            'nom.required'    => 'Le nom est obligatoire.',
            'nom.min'         => 'Minimum 2 caractères pour le nom.',
            'nom.max'         => 'Maximum 50 caractères pour le nom.',

            // cin
            'cin.required'    => 'Le CIN / N° dossier est obligatoire.',
            'cin.max'         => 'CIN trop long.',
            'cin.unique'      => 'Ce CIN est déjà enregistré dans le système.',

            // date / genre / groupe sanguin
            'date_naissance.date'   => 'Date de naissance invalide.',
            'date_naissance.before' => 'La date de naissance doit être dans le passé.',
            'genre.in'              => 'Sexe invalide.',
            'groupe_sanguin.in'     => 'Groupe sanguin invalide.',

            // contact
            'telephone.max'  => 'Numéro de téléphone trop long.',
            'email.email'    => 'Format email invalide.',
            'email.unique'   => 'Cet email est déjà utilisé.',
            'adresse.max'    => 'Adresse trop longue.',

            // medical
            'allergies.max'      => 'Max 500 caractères pour les allergies.',
            'antecedents.max'    => 'Max 2000 caractères pour les antécédents.',
            'medecin_id.exists'  => 'Médecin invalide.',
            'statut_dossier.in'  => 'Statut invalide.',

            // insurance / emergency
            'assurance_type.in'       => "Type d'assurance invalide.",
            'assurance_numero.max'    => "Numéro d'assurance trop long.",
            'contact_urgence_nom.max' => "Nom du contact d'urgence trop long.",
            'contact_urgence_tel.max' => "Téléphone d'urgence trop long.",
            'lien_urgence.max'        => 'Lien trop long.',
        ]);

        $patient = Patient::create($validated);

        ClinicNotification::broadcast(
            'patient', "Nouveau patient ajouté : {$patient->prenom} {$patient->nom}",
            'patient', 'teal', route('patients.index')
        );

        flash()->success('Patient ajouté avec succès.');

        return redirect()->route('patients.index');
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
        $p = Patient::findOrFail($id);

        // BUG FIX: compact('p') was missing $doctors — the edit view needs the
        // doctors list to populate the "Médecin traitant" dropdown, same as create().
        $doctors = StaffMedical::whereIn('role', ['medecin', 'infirmier'])
            ->orderBy('nom')
            ->get();

        return view('patients.edit', compact('p', 'doctors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $p = Patient::findOrFail($id);

        $validated = $request->validate([

            // Section 1 – Identity
            'prenom'         => 'required|string|min:2|max:50',
            'nom'            => 'required|string|min:2|max:50',
            // BUG FIX: unique:patients,cin alone always fails if the CIN didn't change,
            // because Laravel finds the patient's own row and thinks it's a duplicate.
            // The 4th parameter tells Laravel to ignore row $id during the uniqueness check.
            'cin'            => 'required|string|max:20|unique:patients,cin,' . $id,
            'date_naissance' => 'nullable|date|before:today',
            'genre'          => 'nullable|in:M,F',
            'groupe_sanguin' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'telephone'      => 'nullable|string|max:20',
            // BUG FIX: same issue for email — ignore the current patient's own row.
            'email'          => 'nullable|email|unique:patients,email,' . $id,
            'adresse'        => 'nullable|string|max:255',
            'color'          => 'nullable|in:teal,blue,amber,rose,violet',

            // Section 2 – Medical
            'allergies'      => 'nullable|string|max:500',
            'antecedents'    => 'nullable|string|max:2000',
            'medecin_id'     => 'nullable|exists:staff_medicals,id',
            'statut_dossier' => 'nullable|in:actif,inactif',

            // Section 3 – Insurance & Emergency
            'assurance_type'      => 'nullable|in:Aucune,CNSS,CNOPS,FAR,Privée,Autre',
            'assurance_numero'    => 'nullable|string|max:50',
            'contact_urgence_nom' => 'nullable|string|max:100',
            'contact_urgence_tel' => 'nullable|string|max:20',
            'lien_urgence'        => 'nullable|string|max:50',

        ], [

            // prenom / nom
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.min'      => 'Minimum 2 caractères pour le prénom.',
            'prenom.max'      => 'Maximum 50 caractères pour le prénom.',

            'nom.required'    => 'Le nom est obligatoire.',
            'nom.min'         => 'Minimum 2 caractères pour le nom.',
            'nom.max'         => 'Maximum 50 caractères pour le nom.',

            // cin
            'cin.required'    => 'Le CIN / N° dossier est obligatoire.',
            'cin.max'         => 'CIN trop long.',
            'cin.unique'      => 'Ce CIN est déjà enregistré dans le système.',

            // date / genre / groupe sanguin
            'date_naissance.date'   => 'Date de naissance invalide.',
            'date_naissance.before' => 'La date de naissance doit être dans le passé.',
            'genre.in'              => 'Sexe invalide.',
            'groupe_sanguin.in'     => 'Groupe sanguin invalide.',

            // contact
            'telephone.max'  => 'Numéro de téléphone trop long.',
            'email.email'    => 'Format email invalide.',
            'email.unique'   => 'Cet email est déjà utilisé.',
            'adresse.max'    => 'Adresse trop longue.',

            // medical
            'allergies.max'      => 'Max 500 caractères pour les allergies.',
            'antecedents.max'    => 'Max 2000 caractères pour les antécédents.',
            'medecin_id.exists'  => 'Médecin invalide.',
            'statut_dossier.in'  => 'Statut invalide.',

            // insurance / emergency
            'assurance_type.in'       => "Type d'assurance invalide.",
            'assurance_numero.max'    => "Numéro d'assurance trop long.",
            'contact_urgence_nom.max' => "Nom du contact d'urgence trop long.",
            'contact_urgence_tel.max' => "Téléphone d'urgence trop long.",
            'lien_urgence.max'        => 'Lien trop long.',
        ]);

        // BUG FIX: Patient::updated($validated) is wrong — "updated" is an Eloquent
        // model event (a hook), not an update method. You must call update() on the
        // instance ($p) you already fetched, not as a static method on the class.
        $p->update($validated);

        ClinicNotification::broadcast(
            'patient', "Dossier patient mis à jour : {$p->prenom} {$p->nom}",
            'edit', 'blue', route('patients.index')
        );

        flash()->success('Patient modifier avec succès.');

        return redirect()->route('patients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $p = Patient::findOrFail($id);
        $name = $p->full_name;
        $p->delete();

        ClinicNotification::broadcast(
            'patient', "Patient supprimé : {$name}",
            'delete', 'rose'
        );

        flash()->success('Patient Supprime avec succès.');

        return redirect()->route('patients.index');
    }
}
