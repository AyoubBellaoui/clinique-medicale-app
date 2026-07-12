<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Facture;
use App\Models\FileAttente;
use App\Models\Patient;
use App\Models\StaffMedical;
use App\Notifications\ClinicNotification;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FacturesController extends Controller
{
    private const MOIS_ABBR = [1=>'Jan',2=>'Fév',3=>'Mar',4=>'Avr',5=>'Mai',6=>'Juin',7=>'Juil',8=>'Août',9=>'Sep',10=>'Oct',11=>'Nov',12=>'Déc'];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $factures = Facture::with(['patient', 'staff'])
            ->orderByDesc('date_facturation')
            ->orderByDesc('id')
            ->get();

        $paidTotal = $factures->where('statut', 'paye')->sum('total_ttc');
        $overdueTotal = $factures->filter(fn($f) => $f->isOverdue())->sum('total_ttc');
        $pendingTotal = $factures->filter(fn($f) => $f->statut === 'en_attente' && !$f->isOverdue())->sum('total_ttc');

        $pendingCount = $factures->filter(fn($f) => $f->statut === 'en_attente' && !$f->isOverdue())->count();
        $overdueCount = $factures->filter(fn($f) => $f->isOverdue())->count();

        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyRevenue[] = [
                'label' => self::MOIS_ABBR[$month->month],
                'total' => (float) $factures->where('statut', 'paye')
                    ->filter(fn($f) => $f->date_facturation->isSameMonth($month) && $f->date_facturation->year === $month->year)
                    ->sum('total_ttc'),
            ];
        }

        $paymentLabels = ['especes' => 'Espèces', 'carte' => 'Carte', 'cheque' => 'Chèque', 'virement' => 'Virement', 'assurance' => 'Assurance'];
        $paymentBreakdown = $factures->groupBy('mode_paiement')->map->count();

        return view('billing.index', compact(
            'factures', 'paidTotal', 'pendingTotal', 'overdueTotal', 'pendingCount', 'overdueCount',
            'monthlyRevenue', 'paymentLabels', 'paymentBreakdown'
        ));
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

        $nextNumero = $this->nextNumero();

        return view('billing.create', compact('patients', 'doctors', 'consultations', 'nextNumero'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateFacture($request);

        [$sousTotal, $totalTtc] = $this->computeTotals($validated);

        $facture = $this->createFactureWithUniqueNumero([
            'patient_id'          => $validated['patient_id'],
            'staff_id'            => $validated['staff_id'] ?? null,
            'consultation_id'     => $validated['consultation_id'] ?? null,
            'date_facturation'    => $validated['date'],
            'date_echeance'       => $validated['due_date'] ?? null,
            'mode_paiement'       => $validated['payment_method'],
            'statut'              => 'en_attente',
            'assurance'           => $validated['insurance'] ?? null,
            'taux_remboursement'  => $validated['coverage_rate'] ?? null,
            'remise'              => $validated['discount'] ?? 0,
            'tva'                 => $validated['tax'] ?? 0,
            'sous_total'          => $sousTotal,
            'total_ttc'           => $totalTtc,
            'notes'               => $validated['notes'] ?? null,
        ]);

        $this->syncLignes($facture, $validated['services']);

        ClinicNotification::broadcast(
            'facture', "Nouvelle facture {$facture->numero} : {$facture->patient->full_name} — " . number_format($totalTtc, 0, ',', ' ') . ' MAD',
            'receipt', 'blue', route('billing.index')
        );

        flash()->success('Facture émise avec succès.');

        return redirect()->route('billing.index');
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
        $facture = Facture::with('lignes')->findOrFail($id);

        $patients = Patient::orderBy('nom')->get();

        $doctors = StaffMedical::whereIn('role', ['medecin', 'infirmier'])
            ->orderBy('nom')
            ->get();

        $consultations = Consultation::with('patient')
            ->orderByDesc('date_consultation')
            ->limit(30)
            ->get();

        return view('billing.edit', compact('facture', 'patients', 'doctors', 'consultations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $facture = Facture::findOrFail($id);

        $validated = $this->validateFacture($request, withStatut: true);

        [$sousTotal, $totalTtc] = $this->computeTotals($validated);

        $becamePaid = $validated['statut'] === 'paye' && $facture->statut !== 'paye';

        $facture->update([
            'patient_id'          => $validated['patient_id'],
            'staff_id'            => $validated['staff_id'] ?? null,
            'consultation_id'     => $validated['consultation_id'] ?? null,
            'date_facturation'    => $validated['date'],
            'date_echeance'       => $validated['due_date'] ?? null,
            'mode_paiement'       => $validated['payment_method'],
            'statut'              => $validated['statut'],
            'paid_at'             => $becamePaid ? now() : $facture->paid_at,
            'assurance'           => $validated['insurance'] ?? null,
            'taux_remboursement'  => $validated['coverage_rate'] ?? null,
            'remise'              => $validated['discount'] ?? 0,
            'tva'                 => $validated['tax'] ?? 0,
            'sous_total'          => $sousTotal,
            'total_ttc'           => $totalTtc,
            'notes'               => $validated['notes'] ?? null,
        ]);

        $this->syncLignes($facture, $validated['services']);

        if ($becamePaid) {
            $this->terminateLinkedQueueEntry($facture);
        }

        ClinicNotification::broadcast(
            'facture', "Facture mise à jour : {$facture->numero} — {$facture->patient->full_name}",
            'edit', 'blue', route('billing.index')
        );

        flash()->success('Facture mise à jour avec succès.');

        return redirect()->route('billing.index');
    }

    /**
     * Mark an invoice as paid without touching its other fields.
     */
    public function markPaid(string $id)
    {
        $facture = Facture::findOrFail($id);

        if ($facture->statut !== 'paye') {
            $facture->update(['statut' => 'paye', 'paid_at' => now()]);

            $this->terminateLinkedQueueEntry($facture);

            ClinicNotification::broadcast(
                'facture', "Facture réglée : {$facture->numero} — {$facture->patient->full_name}",
                'check', 'green', route('billing.index')
            );

            flash()->success('Facture marquée comme payée.');
        }

        return redirect()->route('billing.index');
    }

    /**
     * When a facture tied to a consultation is settled, the patient's visit is
     * fully closed out (rdv -> file d'attente -> consultation -> facture), so the
     * originating queue entry can move from "en_cours" to "termine" automatically.
     */
    private function terminateLinkedQueueEntry(Facture $facture): void
    {
        $fileAttenteId = $facture->consultation?->file_attente_id;

        if (!$fileAttenteId) {
            return;
        }

        FileAttente::find($fileAttenteId)?->terminer();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $facture = Facture::findOrFail($id);
        $label = "{$facture->numero} — " . ($facture->patient->full_name ?? 'Patient');
        $facture->delete();

        ClinicNotification::broadcast(
            'facture', "Facture supprimée : {$label}",
            'delete', 'rose'
        );

        flash()->success('Facture supprimée.');

        return redirect()->route('billing.index');
    }

    /**
     * Shared validation rules for store() and update().
     */
    private function validateFacture(Request $request, bool $withStatut = false): array
    {
        $rules = [
            'patient_id'        => 'required|exists:patients,id',
            'staff_id'          => 'nullable|exists:staff_medicals,id',
            'consultation_id'   => [
                'nullable',
                Rule::exists('consultations', 'id')->where(
                    fn ($query) => $query->where('patient_id', $request->patient_id)
                ),
            ],
            'date'              => 'required|date|before_or_equal:today',
            'due_date'          => 'nullable|date|after_or_equal:date',
            'payment_method'    => 'required|in:especes,carte,cheque,virement,assurance',
            'services'          => 'required|array|min:1',
            'services.*.name'   => 'required|string|max:255',
            'services.*.qty'    => 'required|integer|min:1',
            'services.*.price'  => 'required|numeric|min:0',
            'discount'          => 'nullable|integer|min:0|max:100',
            'tax'               => 'nullable|integer|min:0|max:100',
            'insurance'         => 'nullable|string|max:255',
            'coverage_rate'     => 'nullable|integer|min:0|max:100',
            'notes'             => 'nullable|string|max:1000',
        ];

        $messages = [
            'patient_id.required' => 'Le patient est obligatoire.',
            'patient_id.exists'   => 'Patient invalide.',

            'staff_id.exists'        => 'Médecin invalide.',
            'consultation_id.exists' => "Consultation invalide ou n'appartenant pas à ce patient.",

            'date.required'       => 'La date est obligatoire.',
            'date.date'            => 'Date invalide.',
            'date.before_or_equal' => 'Une facture ne peut pas être datée dans le futur.',

            'due_date.after_or_equal' => "La date d'échéance doit être après la date de facturation.",

            'payment_method.required' => 'Le mode de paiement est obligatoire.',
            'payment_method.in'        => 'Mode de paiement invalide.',

            'services.required' => 'Au moins une prestation est obligatoire.',
            'services.min'       => 'Au moins une prestation est obligatoire.',
            'services.*.name.required' => 'La désignation de la prestation est obligatoire.',
            'services.*.qty.required'  => 'La quantité est obligatoire.',
            'services.*.qty.min'        => 'La quantité doit être au moins 1.',
            'services.*.price.required' => 'Le prix unitaire est obligatoire.',

            'notes.max' => 'Max 1000 caractères.',
        ];

        if ($withStatut) {
            $rules['statut'] = 'required|in:en_attente,paye';
            $messages['statut.required'] = 'Le statut est obligatoire.';
        }

        return $request->validate($rules, $messages);
    }

    /**
     * Compute [sous_total, total_ttc] from validated service lines + discount/tax.
     */
    private function computeTotals(array $validated): array
    {
        $sousTotal = collect($validated['services'])->sum(fn($s) => $s['qty'] * $s['price']);

        $discount = $validated['discount'] ?? 0;
        $tax = $validated['tax'] ?? 0;

        $afterDiscount = $sousTotal * (1 - $discount / 100);
        $totalTtc = round($afterDiscount * (1 + $tax / 100), 2);

        return [round($sousTotal, 2), $totalTtc];
    }

    /**
     * Replace an invoice's service lines with the submitted set.
     */
    private function syncLignes(Facture $facture, array $services): void
    {
        $facture->lignes()->delete();

        foreach ($services as $service) {
            $facture->lignes()->create([
                'designation'   => $service['name'],
                'quantite'      => $service['qty'],
                'prix_unitaire' => $service['price'],
            ]);
        }
    }

    /**
     * Generate the next sequential invoice number for the current year.
     */
    private function nextNumero(): string
    {
        $year = now()->year;
        $count = Facture::whereYear('date_facturation', $year)->count();

        return sprintf('FAC-%d-%04d', $year, $count + 1);
    }

    /**
     * Create a Facture, regenerating the numero and retrying if two concurrent
     * submits raced on nextNumero() and collided on the unique constraint.
     */
    private function createFactureWithUniqueNumero(array $attributes): Facture
    {
        $maxAttempts = 5;

        for ($attempt = 1; $attempt <= $maxAttempts; $attempt++) {
            try {
                return Facture::create(['numero' => $this->nextNumero()] + $attributes);
            } catch (QueryException $e) {
                $isDuplicateNumero = $e->getCode() === '23000' && str_contains($e->getMessage(), 'numero');

                if (!$isDuplicateNumero || $attempt === $maxAttempts) {
                    throw $e;
                }
            }
        }
    }
}
