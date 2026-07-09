@extends('layouts.app')

@section('title', "Modifier l'Ordonnance")
@section('page-title', "Modifier l'Ordonnance")
@section('page-subtitle', 'Mettre à jour une ordonnance existante')

@section('content')

{{-- Back --}}
<a href="{{ route('prescriptions.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;transition:color .15s;" onmouseenter="this.style.color='var(--teal-600)'" onmouseleave="this.style.color='var(--muted)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
    Retour aux ordonnances
</a>

<form action="{{ route('prescriptions.update', $ordonnance->id) }}" method="POST" id="rxForm">
    @csrf
    @method('PUT')

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
                <label class="form-label">Liée à la consultation</label>
                <select class="form-control form-select" name="consultation_id" id="consultation_select" onchange="applyConsultation()">
                    <option value="">— Optionnel —</option>
                    @foreach($consultations as $consult)
                        <option value="{{ $consult->id }}" data-patient="{{ $consult->patient_id }}" data-staff="{{ $consult->staff_id }}" data-diagnostic="{{ $consult->diagnostic }}" {{ old('consultation_id', $ordonnance->consultation_id) == $consult->id ? 'selected' : '' }}>
                            Consultation du {{ $consult->date_consultation->format('d/m/Y') }} — {{ $consult->patient->full_name ?? '—' }}
                        </option>
                    @endforeach
                </select>
                @error('consultation_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Diagnostic associé</label>
                <input type="text" class="form-control @error('diagnostic_associe') input-error @enderror" name="diagnostic_associe" id="diagnostic_associe" value="{{ old('diagnostic_associe', $ordonnance->diagnostic_associe) }}" placeholder="Ex: Hypertension artérielle, grippe…">
                @error('diagnostic_associe')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Patient <span style="color:#f43f5e;">*</span></label>
                <select class="form-control form-select @error('patient_id') input-error @enderror" name="patient_id" id="patient_select" onchange="onPatientChange()">
                    <option value="">— Sélectionner un patient —</option>
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}" data-medecin="{{ $p->medecin_id }}" {{ old('patient_id', $ordonnance->patient_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->full_name }} @if($p->cin) (CIN: {{ $p->cin }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('patient_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Médecin prescripteur <span style="color:#f43f5e;">*</span></label>
                <select class="form-control form-select @error('staff_id') input-error @enderror" name="staff_id" id="doctor_select">
                    <option value="">— Sélectionner —</option>
                    @foreach($doctors as $d)
                        <option value="{{ $d->id }}" {{ old('staff_id', $ordonnance->staff_id) == $d->id ? 'selected' : '' }}>
                            Dr. {{ $d->full_name }} @if($d->specialite) — {{ $d->specialite }} @endif
                        </option>
                    @endforeach
                </select>
                @error('staff_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Date <span style="color:#f43f5e;">*</span></label>
                <div style="position:relative;">
                    <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" type="text" class="form-control @error('date') input-error @enderror" name="date" value="{{ old('date', $ordonnance->date_prescription->format('Y-m-d')) }}" placeholder="aaaa-mm-jj" autocomplete="off" style="padding-left:36px;">
                </div>
                @error('date')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Durée de validité</label>
                <select class="form-control form-select" name="duree_validite">
                    @foreach(['5 jours','7 jours','10 jours','15 jours','1 mois','3 mois','6 mois','1 an'] as $opt)
                        <option value="{{ $opt }}" {{ old('duree_validite', $ordonnance->duree_validite) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                    @endforeach
                </select>
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
                    <span id="med-count" style="font-size:12px;color:var(--muted);font-weight:500;">{{ $ordonnance->medicaments->count() ?: 1 }} médicament{{ $ordonnance->medicaments->count() > 1 ? 's' : '' }}</span>
                </div>
            </div>
            <button type="button" class="btn btn-outline btn-sm" onclick="addMedRow()">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Ajouter un médicament
            </button>
        </div>

        @error('meds')<div class="field-error" style="padding:12px 24px 0;">{{ $message }}</div>@enderror

        <div style="padding:16px 24px 8px;" id="med-rows">
            @forelse($ordonnance->medicaments as $i => $med)
            <div class="med-row" style="display:grid;grid-template-columns:2fr 1fr 1fr auto;gap:12px;align-items:end;padding-bottom:14px;border-bottom:1px solid rgba(52,168,140,.1);margin-bottom:14px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Médicament / DCI <span style="color:#f43f5e;">*</span></label>
                    <input type="text" class="form-control" name="meds[{{ $i }}][name]" value="{{ old("meds.$i.name", $med->nom) }}" placeholder="Ex: Amlodipine 5mg, Paracétamol 1g…">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Posologie</label>
                    <input type="text" class="form-control" name="meds[{{ $i }}][dosage]" value="{{ old("meds.$i.dosage", $med->posologie) }}" placeholder="Ex: 1/jour, 3×/jour">
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Durée</label>
                    <input type="text" class="form-control" name="meds[{{ $i }}][duration]" value="{{ old("meds.$i.duration", $med->duree) }}" placeholder="Ex: 7 jours, 1 mois">
                </div>
                <div style="padding-bottom:1px;">
                    <button type="button" class="btn btn-ghost btn-sm btn-icon-only" onclick="removeMedRow(this)" title="Supprimer" style="color:#f43f5e;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            @empty
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
            @endforelse
        </div>
        <div style="padding:0 24px 20px;">
            <div class="form-group">
                <label class="form-label">Instructions générales</label>
                <textarea class="form-control @error('instructions') input-error @enderror" name="instructions" rows="2" placeholder="Ex: Prendre les médicaments pendant les repas. Éviter le soleil pendant le traitement.">{{ old('instructions', $ordonnance->instructions) }}</textarea>
                @error('instructions')<div class="field-error">{{ $message }}</div>@enderror
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
                <select class="form-control form-select" name="renouvelable">
                    <option value="0" {{ old('renouvelable', $ordonnance->renouvelable) == 0 ? 'selected' : '' }}>Non renouvelable</option>
                    <option value="1" {{ old('renouvelable', $ordonnance->renouvelable) == 1 ? 'selected' : '' }}>Renouvelable 1 fois</option>
                    <option value="2" {{ old('renouvelable', $ordonnance->renouvelable) == 2 ? 'selected' : '' }}>Renouvelable 2 fois</option>
                    <option value="3" {{ old('renouvelable', $ordonnance->renouvelable) == 3 ? 'selected' : '' }}>Renouvelable 3 fois</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Substitution générique</label>
                <select class="form-control form-select" name="substitution_autorisee">
                    <option value="1" {{ old('substitution_autorisee', $ordonnance->substitution_autorisee ? '1' : '0') == '1' ? 'selected' : '' }}>Autorisée</option>
                    <option value="0" {{ old('substitution_autorisee', $ordonnance->substitution_autorisee ? '1' : '0') == '0' ? 'selected' : '' }}>Non substituable</option>
                </select>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Notes confidentielles (usage interne)</label>
                <textarea class="form-control @error('notes_privees') input-error @enderror" name="notes_privees" rows="2" placeholder="Notes non imprimées sur l'ordonnance…">{{ old('notes_privees', $ordonnance->notes_privees) }}</textarea>
                @error('notes_privees')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:flex-end;gap:10px;">
            <a href="{{ route('prescriptions.index') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Enregistrer les modifications
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
.form-control.input-error{border-color:#f43f5e;background:rgba(244,63,94,.04);}
.field-error{font-size:11.5px;color:#e11d48;margin-top:5px;font-weight:600;}
select.form-control{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237bbfb0' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 13px center;padding-right:36px;cursor:pointer;}
textarea.form-control{resize:vertical;min-height:80px;line-height:1.65;}
.form-label{font-size:11px;font-weight:700;color:var(--teal-600);letter-spacing:.07em;text-transform:uppercase;margin-bottom:6px;display:flex;align-items:center;gap:5px;}
</style>
@endpush

@push('scripts')
<script>
let medIndex = {{ max($ordonnance->medicaments->count(), 1) }};

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

function onPatientChange() {
    const patSel = document.getElementById('patient_select');
    const opt = patSel.options[patSel.selectedIndex];
    const medecinId = opt?.dataset.medecin;

    if (medecinId) {
        const docSel = document.getElementById('doctor_select');
        if ([...docSel.options].some(o => o.value === medecinId)) {
            docSel.value = medecinId;
        }
    }
}

function applyConsultation() {
    const consultSel = document.getElementById('consultation_select');
    const opt = consultSel.options[consultSel.selectedIndex];
    if (opt && opt.value) {
        document.getElementById('patient_select').value = opt.dataset.patient;
        document.getElementById('doctor_select').value = opt.dataset.staff;
        if (opt.dataset.diagnostic) {
            document.getElementById('diagnostic_associe').value = opt.dataset.diagnostic;
        }
    }
}
</script>
@endpush
