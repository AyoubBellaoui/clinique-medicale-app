<?php

namespace App\Http\Controllers;

use App\Models\FileAttente;
use App\Notifications\ClinicNotification;
use Illuminate\Http\Request;

class FileAttenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fileAttente = FileAttente::all();

        return view('fileAttente.index', compact('fileAttente'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('fileAttente.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'staff_id'   => 'nullable|exists:staff_medicals,id',
            'statut'     => 'nullable|in:en_attente,en_cours,termine,annule',
        ]);

        $validated['arrived_at'] = now();
        $validated['statut']     = $validated['statut'] ?? 'en_attente';

        $entry   = FileAttente::create($validated);
        $patient = $entry->patient;

        ClinicNotification::broadcast(
            'queue', "Patient ajouté à la file d'attente : {$patient->full_name}",
            'queue', 'amber', route('fileAttente.index')
        );

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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
