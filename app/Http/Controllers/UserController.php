<?php

namespace App\Http\Controllers;

use App\Models\StaffMedical;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    private const ROLES = ['admin', 'medecin', 'infirmier', 'secretariat', 'technicien'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('staff')->orderBy('email')->get();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $availableStaff = StaffMedical::whereDoesntHave('user')->orderBy('nom')->get();

        return view('users.create', compact('availableStaff'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'nullable|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role'     => 'required|in:' . implode(',', self::ROLES),
            'staff_id' => 'nullable|exists:staff_medicals,id',
        ], [
            'email.required'   => "L'email est obligatoire.",
            'email.unique'      => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min'       => 'Minimum 8 caractères.',
            'password.confirmed' => 'La confirmation ne correspond pas.',
            'role.required'    => 'Le rôle est obligatoire.',
            'role.in'           => 'Rôle invalide.',
        ]);

        User::create([
            'name'     => $validated['name'] ?? null,
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
            'staff_id' => $validated['staff_id'] ?? null,
        ]);

        flash()->success('Compte utilisateur créé avec succès.');

        return redirect()->route('users.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);

        $availableStaff = StaffMedical::where(function ($query) use ($user) {
                $query->whereDoesntHave('user')->orWhere('id', $user->staff_id);
            })
            ->orderBy('nom')
            ->get();

        return view('users.edit', compact('user', 'availableStaff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'     => 'nullable|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'role'     => 'required|in:' . implode(',', self::ROLES),
            'staff_id' => 'nullable|exists:staff_medicals,id',
        ], [
            'email.required' => "L'email est obligatoire.",
            'email.unique'    => 'Cet email est déjà utilisé.',
            'role.required'  => 'Le rôle est obligatoire.',
            'role.in'         => 'Rôle invalide.',
        ]);

        if ($user->isAdmin() && $validated['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            throw ValidationException::withMessages([
                'role' => 'Impossible de retirer le dernier compte administrateur.',
            ]);
        }

        $user->update($validated);

        flash()->success('Compte utilisateur mis à jour avec succès.');

        return redirect()->route('users.index');
    }

    /**
     * Reset a user's password on their behalf (no self-service reset in this app).
     */
    public function resetPassword(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required'  => 'Le mot de passe est obligatoire.',
            'password.min'        => 'Minimum 8 caractères.',
            'password.confirmed'  => 'La confirmation ne correspond pas.',
        ]);

        $user->update(['password' => Hash::make($validated['password'])]);

        flash()->success("Mot de passe de {$user->name} réinitialisé avec succès.");

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->id === auth()->id()) {
            flash()->error('Vous ne pouvez pas supprimer votre propre compte.');

            return redirect()->route('users.index');
        }

        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            flash()->error('Impossible de supprimer le dernier compte administrateur.');

            return redirect()->route('users.index');
        }

        $label = $user->name;
        $user->delete();

        flash()->success("Compte {$label} supprimé.");

        return redirect()->route('users.index');
    }
}
