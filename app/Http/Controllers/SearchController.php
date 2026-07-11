<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\StaffMedical;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Global header search: patients + staff by name/CIN/phone/specialty.
     * Returns JSON consumed by the header search dropdown.
     */
    public function index(Request $request)
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 2) {
            return response()->json([]);
        }

        $patients = Patient::query()
            ->where(function ($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                    ->orWhere('prenom', 'like', "%{$query}%")
                    ->orWhere('cin', 'like', "%{$query}%")
                    ->orWhere('telephone', 'like', "%{$query}%");
            })
            ->orderBy('nom')
            ->limit(5)
            ->get()
            ->map(fn ($p) => [
                'type' => 'patient',
                'label' => 'Patient',
                'title' => $p->full_name,
                'subtitle' => $p->cin,
                'url' => route('patients.edit', $p->id),
            ]);

        // The staff management page (staff.edit) is admin-only — don't hand out
        // links other roles can't follow.
        $staff = auth()->user()->isAdmin()
            ? StaffMedical::query()
                ->where(function ($q) use ($query) {
                    $q->where('nom', 'like', "%{$query}%")
                        ->orWhere('prenom', 'like', "%{$query}%")
                        ->orWhere('specialite', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                })
                ->orderBy('nom')
                ->limit(5)
                ->get()
                ->map(fn ($s) => [
                    'type' => 'staff',
                    'label' => 'Personnel',
                    'title' => $s->full_name,
                    'subtitle' => $s->specialite ?? ucfirst($s->role),
                    'url' => route('staff.edit', $s->id),
                ])
            : collect();

        return response()->json($patients->concat($staff)->values());
    }
}
