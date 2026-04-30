@extends('layouts.app')

@section('title', 'Nouvelle Ordonnance')
@section('page-title', 'Nouvelle Ordonnance')
@section('page-subtitle', 'Rédiger une ordonnance médicale')

@section('content')

<div style="max-width:860px;margin:0 auto;">

    <a href="{{ url('/prescriptions') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        Retour aux ordonnances
    </a>

    <form action="{{ url('/prescriptions') }}" method="POST" id="rxForm">
        @csrf

        {{-- Header info --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Informations de l'ordonnance</h3></div>
                </div>
            </div>
            <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                <div class="form-group">
                    <label class="form-label">Patient <span style="color:#f43f5e;">*</span></label>
                    <select class="form-control form-select" name="patient_id">
                        <option value="">— Sélectionner un patient —</option>
                        <option>Omar Benhaddou</option>
                        <option>Meriem Tahiri</option>
                        <option>Rachid Amrani</option>
                        <option>Nadia Filali</option>
                        <option>Fatima El Idrissi</option>
                        <option>Aicha Moussaoui</option>
                        <option>Hassan Ouazzani</option>
                        <option>Youssef Benali</option>
                        <option>Zineb Chraibi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Médecin prescripteur <span style="color:#f43f5e;">*</span></label>
                    <select class="form-control form-select" name="doctor_id">
                        <option value="">— Sélectionner —</option>
                        <option>Dr. Mehdi Alaoui — Cardiologie</option>
                        <option>Dr. Sara Tazi — Méd. Générale</option>
                        <option>Dr. Karim Fassi — Pédiatrie</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date <span style="color:#f43f5e;">*</span></label>
                    <input type="date" class="form-control" name="date" value="2026-04-29">
                </div>
                <div class="form-group">
                    <label class="form-label">Durée de validité</label>
                    <select class="form-control form-select" name="duration">
                        <option>5 jours</option>
                        <option>7 jours</option>
                        <option>10 jours</option>
                        <option>15 jours</option>
                        <option>1 mois</option>
                        <option>3 mois</option>
                        <option>6 mois</option>
                        <option>1 an</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Liée à la consultation</label>
                    <select class="form-control form-select" name="consultation_id">
                        <option value="">— Optionnel —</option>
                        <option>Consultation du 29/04/2026 — Omar Benhaddou</option>
                        <option>Consultation du 29/04/2026 — Meriem Tahiri</option>
                        <option>Consultation du 28/04/2026 — Rachid Amrani</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Diagnostic associé</label>
                    <input type="text" class="form-control" name="diagnosis" placeholder="Ex: Hypertension artérielle, grippe…">
                </div>
            </div>
        </div>

        {{-- Medications --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Médicaments</h3><span id="med-count">1 médicament</span></div>
                </div>
                <button type="button" class="btn btn-outline btn-sm" onclick="addMedRow()">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Ajouter un médicament
                </button>
            </div>
            <div style="padding:16px 24px 8px;" id="med-rows">
                {{-- Row 1 --}}
                <div class="med-row" style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:12px;align-items:end;padding-bottom:14px;border-bottom:1px solid rgba(52,168,140,.1);margin-bottom:14px;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Médicament / DCI <span style="color:#f43f5e;">*</span></label>
                        <input type="text" class="form-control" name="meds[0][name]" placeholder="Ex: Amlodipine 5mg, Paracétamol 1g…">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Posologie</label>
                        <input type="text" class="form-control" name="meds[0][dosage]" placeholder="Ex: 1/jour, 3×/jour">
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Durée</label>
                        <input type="text" class="form-control" name="meds[0][duration]" placeholder="Ex: 7 jours, 1 mois">
                    </div>
                    <div style="padding-bottom:1px;">
                        <button type="button" class="btn btn-ghost btn-sm btn-icon-only" onclick="removeMedRow(this)" title="Supprimer" style="color:#f43f5e;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>
                </div>
            </div>
            <div style="padding:0 24px 20px;">
                <div class="form-group">
                    <label class="form-label">Instructions générales</label>
                    <textarea class="form-control" name="instructions" rows="2" placeholder="Ex: Prendre les médicaments pendant les repas. Éviter le soleil pendant le traitement." style="resize:vertical;"></textarea>
                </div>
            </div>
        </div>

        {{-- Renewal & Notes --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Options supplémentaires</h3></div>
                </div>
            </div>
            <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                <div class="form-group">
                    <label class="form-label">Renouvellement</label>
                    <select class="form-control form-select" name="renewable">
                        <option value="0">Non renouvelable</option>
                        <option value="1">Renouvelable 1 fois</option>
                        <option value="2">Renouvelable 2 fois</option>
                        <option value="3">Renouvelable 3 fois</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Substitution générique</label>
                    <select class="form-control form-select" name="generic_allowed">
                        <option value="1">Autorisée</option>
                        <option value="0">Non substituable</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Notes confidentielles (usage interne)</label>
                    <textarea class="form-control" name="private_notes" rows="2" placeholder="Notes non imprimées sur l'ordonnance…" style="resize:vertical;"></textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;padding-bottom:32px;">
            <a href="{{ url('/prescriptions') }}" class="btn btn-outline">Annuler</a>
            <button type="button" class="btn btn-outline" onclick="window.print()">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
                Aperçu / Imprimer
            </button>
            <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Émettre l'ordonnance
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
let medIndex = 1;

function updateMedCount() {
    const count = document.querySelectorAll('.med-row').length;
    document.getElementById('med-count').textContent = count + ' médicament' + (count > 1 ? 's' : '');
}

function addMedRow() {
    const i = medIndex++;
    const row = document.createElement('div');
    row.className = 'med-row';
    row.style.cssText = 'display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:12px;align-items:end;padding-bottom:14px;border-bottom:1px solid rgba(52,168,140,.1);margin-bottom:14px;';
    row.innerHTML = `
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Médicament / DCI <span style="color:#f43f5e;">*</span></label>
            <input type="text" class="form-control" name="meds[${i}][name]" placeholder="Ex: Amlodipine 5mg…">
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Posologie</label>
            <input type="text" class="form-control" name="meds[${i}][dosage]" placeholder="Ex: 1/jour">
        </div>
        <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Durée</label>
            <input type="text" class="form-control" name="meds[${i}][duration]" placeholder="Ex: 7 jours">
        </div>
        <div style="padding-bottom:1px;">
            <button type="button" class="btn btn-ghost btn-sm btn-icon-only" onclick="removeMedRow(this)" title="Supprimer" style="color:#f43f5e;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    `;
    document.getElementById('med-rows').appendChild(row);
    updateMedCount();
}

function removeMedRow(btn) {
    const rows = document.querySelectorAll('.med-row');
    if (rows.length <= 1) return;
    btn.closest('.med-row').remove();
    updateMedCount();
}
</script>
@endpush
