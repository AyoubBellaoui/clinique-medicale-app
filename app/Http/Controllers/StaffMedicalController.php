<?php

namespace App\Http\Controllers;

use App\Models\StaffMedical;
use Illuminate\Http\Request;

class StaffMedicalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staff = StaffMedical::all();

        return view('staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('staff.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([

            // Section 1
            'prenom'         => 'required|min:2|max:50',
            'nom'            => 'required|min:2|max:50',
            'email'          => 'required|email|unique:staff_medicals,email',
            'cin'            => 'required|string|max:20|unique:staff_medicals,cin',
            'telephone'      => 'required|string|max:20',
            'date_naissance' => 'required|date|before:today',
            'gender'         => 'required|in:M,F',
            'adresse'        => 'required|string|max:255',
            'color'          => 'required|in:teal,blue,amber,rose,violet',

            // Section 2
            // Section 2
            'role'           => 'required|in:medecin,infirmier,secretaire,admin,technicien',
            'specialite'     => 'required_if:role,medecin,infirmier|nullable|string|max:100',
            'license_number' => 'required|string|max:50|unique:staff_medicals,license_number',
            'degree'         => 'required|string|max:100',
            'school'         => 'required|string|max:150',
            'grad_year'      => 'required|integer|min:1970|max:2030',
            'languages'      => 'required|string|max:255',

            // Section 3
            'contract_type'  => 'required|in:CDI,CDD,Vacation,Libéral',
            'date_embauche'  => 'required|date',
            'salaire'        => 'required|numeric|min:0',
            'schedule'       => 'required|string|max:100',
            'status'         => 'required|in:actif,conge,inactif',
            'notes'          => 'nullable|string|max:1000',

        ], [

            // prénom / nom
            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.min'      => 'Minimum 2 caractères pour le prénom.',
            'prenom.max'      => 'Maximum 50 caractères pour le prénom.',

            'nom.required' => 'Le nom est obligatoire.',
            'nom.min'      => 'Minimum 2 caractères pour le nom.',
            'nom.max'      => 'Maximum 50 caractères pour le nom.',

            // email
            'email.required' => 'Email obligatoire.',
            'email.email'    => 'Format email invalide.',
            'email.unique'   => 'Cet email est déjà utilisé.',

            // cin / téléphone
            'cin.required' => 'CIN obligatoire.',
            'cin.max'      => 'CIN trop long.',
            'cin.unique'   => 'Ce CIN est déjà enregistré dans le système.',

            'telephone.required' => 'Téléphone obligatoire.',
            'telephone.max'      => 'Numéro trop long.',

            // date naissance
            'date_naissance.required' => 'Date de naissance obligatoire.',
            'date_naissance.date'     => 'Date invalide.',
            'date_naissance.before'   => 'Doit être dans le passé.',

            // gender / adresse / color
            'gender.required' => 'Sexe obligatoire.',
            'gender.in'       => 'Sexe invalide.',

            'adresse.required' => 'Adresse obligatoire.',
            'adresse.max'      => 'Adresse trop longue.',

            'color.required' => 'Couleur obligatoire.',
            'color.in'       => 'Couleur invalide.',

            // role
            'role.required' => 'Rôle obligatoire.',
            'role.in'       => 'Rôle invalide.',

            // specialité
            'specialite.required_if' => 'Spécialité obligatoire pour médecin/infirmier.',
            'specialite.max'      => 'Spécialité trop longue.',

            // licence
            'license_number.required' => 'Numéro de licence obligatoire.',
            'license_number.max'      => 'Numéro trop long.',
            'license_number.unique'   => 'Ce numéro de licence est déjà enregistré dans le système.',

            // diplôme
            'degree.required' => 'Diplôme obligatoire.',
            'degree.max'      => 'Diplôme trop long.',

            'school.required' => 'École obligatoire.',
            'school.max'      => 'Nom de l’école trop long.',

            'grad_year.required' => 'Année obligatoire.',
            'grad_year.integer'  => 'Doit être un nombre.',
            'grad_year.min'      => 'Année invalide.',
            'grad_year.max'      => 'Année invalide.',

            'languages.required' => 'Langues obligatoires.',
            'languages.max'      => 'Trop long.',

            // contrat
            'contract_type.required' => 'Type de contrat obligatoire.',
            'contract_type.in'       => 'Type invalide.',

            'date_embauche.required' => 'Date d’embauche obligatoire.',
            'date_embauche.date'     => 'Date invalide.',

            'salaire.required' => 'Salaire obligatoire.',
            'salaire.numeric'  => 'Doit être un nombre.',
            'salaire.min'      => 'Salaire invalide.',

            'schedule.required' => 'Horaire obligatoire.',
            'schedule.max'      => 'Horaire trop long.',

            'status.required' => 'Statut obligatoire.',
            'status.in'       => 'Statut invalide.',

            'notes.max' => 'Max 1000 caractères.',
        ]);

        StaffMedical::create($validated);

        flash()->success('Membre du personnel ajouté avec succès.');

        return redirect()->route('staff.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $s = StaffMedical::findOrFail($id);
        return view('staff.edit', compact('s'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $s = StaffMedical::findOrFail($id);

        $validated = $request->validate([

            // Section 1
            'prenom'         => 'required|min:2|max:50',
            'nom'            => 'required|min:2|max:50',
            'email'          => 'required|email|unique:staff_medicals,email,' . $id,
            'cin'            => 'required|string|max:20|unique:staff_medicals,cin,' . $id,
            'telephone'      => 'required|string|max:20',
            'date_naissance' => 'required|date|before:today',
            'gender'         => 'required|in:M,F',
            'adresse'        => 'required|string|max:255',
            'color'          => 'required|in:teal,blue,amber,rose,violet',

            // Section 2
            'role'           => 'required|in:medecin,infirmier,secretaire,admin,technicien',
            'specialite'     => 'required_if:role,medecin,infirmier|nullable|string|max:100',
            'license_number' => 'required|string|max:50|unique:staff_medicals,license_number,' . $id,
            'degree'         => 'required|string|max:100',
            'school'         => 'required|string|max:150',
            'grad_year'      => 'required|integer|min:1970|max:2030',
            'languages'      => 'required|string|max:255',

            // Section 3
            'contract_type'  => 'required|in:CDI,CDD,Vacation,Libéral',
            'date_embauche'  => 'required|date',
            'salaire'        => 'required|numeric|min:0',
            'schedule'       => 'required|string|max:100',
            'status'         => 'required|in:actif,conge,inactif',
            'notes'          => 'nullable|string|max:1000',

        ], [

            'prenom.required' => 'Le prénom est obligatoire.',
            'prenom.min'      => 'Minimum 2 caractères pour le prénom.',
            'prenom.max'      => 'Maximum 50 caractères pour le prénom.',

            'nom.required' => 'Le nom est obligatoire.',
            'nom.min'      => 'Minimum 2 caractères pour le nom.',
            'nom.max'      => 'Maximum 50 caractères pour le nom.',

            'email.required' => 'Email obligatoire.',
            'email.email'    => 'Format email invalide.',
            'email.unique'   => 'Cet email est déjà utilisé.',

            'cin.required' => 'CIN obligatoire.',
            'cin.max'      => 'CIN trop long.',
            'cin.unique'   => 'Ce CIN est déjà enregistré dans le système.',

            'telephone.required' => 'Téléphone obligatoire.',
            'telephone.max'      => 'Numéro trop long.',

            'date_naissance.required' => 'Date de naissance obligatoire.',
            'date_naissance.date'     => 'Date invalide.',
            'date_naissance.before'   => 'Doit être dans le passé.',

            'gender.required' => 'Sexe obligatoire.',
            'gender.in'       => 'Sexe invalide.',

            'adresse.required' => 'Adresse obligatoire.',
            'adresse.max'      => 'Adresse trop longue.',

            'color.required' => 'Couleur obligatoire.',
            'color.in'       => 'Couleur invalide.',

            'role.required' => 'Rôle obligatoire.',
            'role.in'       => 'Rôle invalide.',

            'specialite.required_if' => 'Spécialité obligatoire pour médecin/infirmier.',
            'specialite.max'         => 'Spécialité trop longue.',

            'license_number.required' => 'Numéro de licence obligatoire.',
            'license_number.max'      => 'Numéro trop long.',
            'license_number.unique'   => 'Ce numéro de licence est déjà enregistré dans le système.',

            'degree.required' => 'Diplôme obligatoire.',
            'degree.max'      => 'Diplôme trop long.',

            'school.required' => 'École obligatoire.',
            'school.max'      => "Nom de l'école trop long.",

            'grad_year.required' => 'Année obligatoire.',
            'grad_year.integer'  => 'Doit être un nombre.',
            'grad_year.min'      => 'Année invalide.',
            'grad_year.max'      => 'Année invalide.',

            'languages.required' => 'Langues obligatoires.',
            'languages.max'      => 'Trop long.',

            'contract_type.required' => 'Type de contrat obligatoire.',
            'contract_type.in'       => 'Type invalide.',

            'date_embauche.required' => "Date d'embauche obligatoire.",
            'date_embauche.date'     => 'Date invalide.',

            'salaire.required' => 'Salaire obligatoire.',
            'salaire.numeric'  => 'Doit être un nombre.',
            'salaire.min'      => 'Salaire invalide.',

            'schedule.required' => 'Horaire obligatoire.',
            'schedule.max'      => 'Horaire trop long.',

            'status.required' => 'Statut obligatoire.',
            'status.in'       => 'Statut invalide.',

            'notes.max' => 'Max 1000 caractères.',
        ]);

        $s->update($validated);

        flash()->success('Membre du personnel mis à jour avec succès.');

        return redirect()->route('staff.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $s = StaffMedical::findOrFail($id);
        $s->delete();

        flash()->success('Membre du personnel supprimé.');

        return redirect()->route('staff.index');
    }
}
