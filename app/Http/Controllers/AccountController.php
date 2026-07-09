<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Patient;
use App\Models\StaffMedical;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class AccountController extends Controller
{
    /**
     * Display the current user's account page.
     */
    public function index()
    {
        $user = auth()->user();

        $consultationsCount = Consultation::count();
        $patientsCount = Patient::count();
        $staffCount = StaffMedical::count();

        return view('account.index', compact('user', 'consultationsCount', 'patientsCount', 'staffCount'));
    }

    /**
     * Update the profile (name/email) fields.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'  => 'nullable|string|max:255',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        ], [
            'email.required' => "L'email est obligatoire.",
            'email.email'     => 'Email invalide.',
            'email.unique'    => 'Cet email est déjà utilisé.',
            'name.max'        => 'Nom trop long (255 caractères max).',
        ]);

        $user->update($validated);

        flash()->success('Profil mis à jour avec succès.');

        return redirect()->route('account.index');
    }

    /**
     * Update the account password.
     */
    public function updatePassword(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'password'          => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required'          => 'Le nouveau mot de passe est obligatoire.',
            'password.min'                => 'Minimum 8 caractères.',
            'password.confirmed'          => 'La confirmation ne correspond pas.',
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Mot de passe actuel incorrect.',
            ]);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        flash()->success('Mot de passe mis à jour avec succès.');

        return redirect()->route('account.index');
    }
}
