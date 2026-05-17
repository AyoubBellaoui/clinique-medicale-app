@extends('layouts.app')

@section('title', 'Nouvelle Ordonnance')
@section('page-title', 'Nouvelle Ordonnance')
@section('page-subtitle', 'Rédiger une ordonnance médicale')

@section('content')

{{-- Back --}}
<a href="{{ url('/prescriptions') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;transition:color .15s;" onmouseenter="this.style.color='var(--teal-600)'" onmouseleave="this.style.color='var(--muted)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
    Retour aux ordonnances
</a>

<form action="{{ url('/prescriptions') }}" method="POST" id="rxForm">
    @csrf

    {{-- Header info --}}
    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,var(--teal-50),rgba(255,255,255,0));border-bottom:1px solid rgba(52,168,140,.1);display:flex;align-items:center;gap:14px;">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--teal-400),var(--teal-600));display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(52,168,140,.3);">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Informations de l'ordonnance</h3>
                <span style="font-size:12px;color:var(--muted);font-weight:500;">Patient, médecin et date de prescription</span>
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
    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(245,158,11,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(245,158,11,.1);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#fbbf24,#f59e0b);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(245,158,11,.3);">
                    <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg>
                </div>
                <div>
                    <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Médicaments</h3>
                    <span id="med-count" style="font-size:12px;color:var(--muted);font-weight:500;">1 médicament</span>
                </div>
            </div>
            <button type="button" class="btn btn-outline btn-sm" onclick="addMedRow()">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Ajouter un médicament
            </button>
        </div>

        <div style="padding:16px 24px 8px;" id="med-rows">
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
                <textarea class="form-control" name="instructions" rows="2" placeholder="Ex: Prendre les médicaments pendant les repas. Éviter le soleil pendant le traitement."></textarea>
            </div>
        </div>
    </div>

    {{-- Renewal & Notes --}}
    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(139,92,246,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(139,92,246,.1);display:flex;align-items:center;gap:14px;">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#a78bfa,#7c3aed);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(139,92,246,.3);">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.108-1.204l-.526-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Options supplémentaires</h3>
                <span style="font-size:12px;color:var(--muted);font-weight:500;">Renouvellement, substitution et notes</span>
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
                <textarea class="form-control" name="private_notes" rows="2" placeholder="Notes non imprimées sur l'ordonnance…"></textarea>
            </div>
        </div>

        <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:flex-end;gap:10px;">
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
    </div>

</form>

@endsection

@push('styles')
<style>
.form-control {
    width:100%;padding:11px 14px;border:1.5px solid rgba(52,168,140,.22);border-radius:12px;
    background:#f4faf8;color:#133c35;font-size:13.5px;font-family:'Plus Jakarta Sans',sans-serif;
    font-weight:500;outline:none;transition:border-color .2s,box-shadow .2s,background .2s;line-height:1.4;
}
.form-control::placeholder{color:#a8c5bd;font-weight:400;}
.form-control:focus{border-color:var(--teal-400);box-shadow:0 0 0 3px rgba(52,168,140,.14);background:#fff;}
.form-control:hover:not(:focus){border-color:rgba(52,168,140,.4);}
select.form-control{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237bbfb0' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 13px center;padding-right:36px;cursor:pointer;}
textarea.form-control{resize:vertical;min-height:80px;line-height:1.65;}
.form-label{font-size:11px;font-weight:700;color:var(--teal-600);letter-spacing:.07em;text-transform:uppercase;margin-bottom:6px;display:flex;align-items:center;gap:5px;}
</style>
@endpush

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
