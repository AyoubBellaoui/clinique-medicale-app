@extends('layouts.app')

@section('title', 'Modifier la Facture')
@section('page-title', 'Modifier la Facture')
@section('page-subtitle', 'Mettre à jour une facture existante')

@section('content')

{{-- Back --}}
<a href="{{ route('billing.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;transition:color .15s;" onmouseenter="this.style.color='var(--teal-600)'" onmouseleave="this.style.color='var(--muted)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
    Retour aux factures
</a>

<form action="{{ route('billing.update', $facture->id) }}" method="POST" id="invoiceForm">
    @csrf
    @method('PUT')

    {{-- Billing info --}}
    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,var(--teal-50),rgba(255,255,255,0));border-bottom:1px solid rgba(52,168,140,.1);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--teal-400),var(--teal-600));display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(52,168,140,.3);">
                    <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                </div>
                <div>
                    <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Informations de facturation</h3>
                    <span style="font-size:12px;color:var(--muted);font-weight:500;">Patient, médecin et modalités de paiement</span>
                </div>
            </div>
            <div style="font-size:13px;color:var(--muted);">N° <strong style="color:var(--teal-700);font-variant-numeric:tabular-nums;">{{ $facture->numero }}</strong></div>
        </div>

        <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
            <div class="form-group">
                <label class="form-label">Liée à la consultation</label>
                <select class="form-control form-select" name="consultation_id" id="consultation_select" onchange="applyConsultation()">
                    <option value="">— Optionnel —</option>
                    @foreach($consultations as $consult)
                        <option value="{{ $consult->id }}" data-patient="{{ $consult->patient_id }}" data-staff="{{ $consult->staff_id }}" {{ old('consultation_id', $facture->consultation_id) == $consult->id ? 'selected' : '' }}>
                            Consultation du {{ $consult->date_consultation->format('d/m/Y') }} — {{ $consult->patient->full_name ?? '—' }}
                        </option>
                    @endforeach
                </select>
                @error('consultation_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Statut <span style="color:#f43f5e;">*</span></label>
                <select class="form-control form-select" name="statut">
                    <option value="en_attente" {{ old('statut', $facture->statut) == 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="paye" {{ old('statut', $facture->statut) == 'paye' ? 'selected' : '' }}>Payé</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Patient <span style="color:#f43f5e;">*</span></label>
                <select class="form-control form-select @error('patient_id') input-error @enderror" name="patient_id" id="patient_select" onchange="onPatientChange()">
                    <option value="">— Sélectionner un patient —</option>
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}" data-medecin="{{ $p->medecin_id }}" {{ old('patient_id', $facture->patient_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->full_name }} @if($p->cin) (CIN: {{ $p->cin }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('patient_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Médecin</label>
                <select class="form-control form-select @error('staff_id') input-error @enderror" name="staff_id" id="doctor_select">
                    <option value="">— Sélectionner —</option>
                    @foreach($doctors as $d)
                        <option value="{{ $d->id }}" {{ old('staff_id', $facture->staff_id) == $d->id ? 'selected' : '' }}>
                            Dr. {{ $d->full_name }} @if($d->specialite) — {{ $d->specialite }} @endif
                        </option>
                    @endforeach
                </select>
                @error('staff_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Date de facturation</label>
                <div style="position:relative;">
                    <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" type="text" class="form-control @error('date') input-error @enderror" name="date" value="{{ old('date', $facture->date_facturation->format('Y-m-d')) }}" placeholder="aaaa-mm-jj" autocomplete="off" style="padding-left:36px;">
                </div>
                @error('date')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Date d'échéance</label>
                <div style="position:relative;">
                    <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" type="text" class="form-control @error('due_date') input-error @enderror" name="due_date" value="{{ old('due_date', optional($facture->date_echeance)->format('Y-m-d')) }}" placeholder="aaaa-mm-jj" autocomplete="off" style="padding-left:36px;">
                </div>
                @error('due_date')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Mode de paiement</label>
                <select class="form-control form-select" name="payment_method">
                    <option value="especes" {{ old('payment_method', $facture->mode_paiement) == 'especes' ? 'selected' : '' }}>Espèces</option>
                    <option value="carte" {{ old('payment_method', $facture->mode_paiement) == 'carte' ? 'selected' : '' }}>Carte bancaire</option>
                    <option value="cheque" {{ old('payment_method', $facture->mode_paiement) == 'cheque' ? 'selected' : '' }}>Chèque</option>
                    <option value="virement" {{ old('payment_method', $facture->mode_paiement) == 'virement' ? 'selected' : '' }}>Virement</option>
                    <option value="assurance" {{ old('payment_method', $facture->mode_paiement) == 'assurance' ? 'selected' : '' }}>Assurance</option>
                </select>
            </div>
        </div>
    </div>

    {{-- Services --}}
    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(245,158,11,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(245,158,11,.1);display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:14px;">
                <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#fbbf24,#f59e0b);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(245,158,11,.3);">
                    <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/></svg>
                </div>
                <div>
                    <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Prestations</h3>
                    <span id="service-count" style="font-size:12px;color:var(--muted);font-weight:500;">{{ $facture->lignes->count() ?: 1 }} prestation{{ $facture->lignes->count() > 1 ? 's' : '' }}</span>
                </div>
            </div>
            <button type="button" class="btn btn-outline btn-sm" onclick="addServiceRow()">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Ajouter une prestation
            </button>
        </div>

        @error('services')<div class="field-error" style="padding:12px 24px 0;">{{ $message }}</div>@enderror

        <div style="padding:16px 24px 0;">
            <div style="display:grid;grid-template-columns:3fr 1fr 1.2fr 1.2fr auto;gap:12px;padding-bottom:8px;border-bottom:2px solid rgba(52,168,140,.12);margin-bottom:8px;">
                <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Désignation</div>
                <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Qté</div>
                <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Prix unit. (MAD)</div>
                <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Total</div>
                <div></div>
            </div>
            <div id="service-rows">
                @forelse($facture->lignes as $i => $ligne)
                <div class="svc-row" style="display:grid;grid-template-columns:3fr 1fr 1.2fr 1.2fr auto;gap:12px;align-items:center;margin-bottom:10px;">
                    <input type="text" class="form-control" name="services[{{ $i }}][name]" value="{{ old("services.$i.name", $ligne->designation) }}" placeholder="Désignation de la prestation">
                    <input type="number" class="form-control" name="services[{{ $i }}][qty]" value="{{ old("services.$i.qty", $ligne->quantite) }}" min="1" onchange="recalcRow(this)" style="text-align:center;">
                    <input type="number" class="form-control" name="services[{{ $i }}][price]" value="{{ old("services.$i.price", $ligne->prix_unitaire) }}" min="0" onchange="recalcRow(this)" style="font-variant-numeric:tabular-nums;">
                    <div class="svc-total" style="font-weight:700;color:var(--teal-800);font-variant-numeric:tabular-nums;font-size:14px;padding:0 4px;">0 MAD</div>
                    <button type="button" class="btn btn-ghost btn-sm btn-icon-only" onclick="removeServiceRow(this)" style="color:#f43f5e;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                @empty
                <div class="svc-row" style="display:grid;grid-template-columns:3fr 1fr 1.2fr 1.2fr auto;gap:12px;align-items:center;margin-bottom:10px;">
                    <input type="text" class="form-control" name="services[0][name]" placeholder="Désignation de la prestation">
                    <input type="number" class="form-control" name="services[0][qty]" value="1" min="1" onchange="recalcRow(this)" style="text-align:center;">
                    <input type="number" class="form-control" name="services[0][price]" placeholder="0" min="0" onchange="recalcRow(this)" style="font-variant-numeric:tabular-nums;">
                    <div class="svc-total" style="font-weight:700;color:var(--teal-800);font-variant-numeric:tabular-nums;font-size:14px;padding:0 4px;">0 MAD</div>
                    <button type="button" class="btn btn-ghost btn-sm btn-icon-only" onclick="removeServiceRow(this)" style="color:#f43f5e;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Totals --}}
        <div style="padding:16px 24px 24px;">
            <div style="max-width:320px;margin-left:auto;border-top:2px solid rgba(52,168,140,.15);padding-top:16px;">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:13.5px;">
                    <span style="color:var(--muted);">Sous-total</span>
                    <span id="subtotal" style="font-weight:600;color:var(--teal-800);font-variant-numeric:tabular-nums;">0 MAD</span>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;font-size:13.5px;">
                    <span style="color:var(--muted);">Remise (%)</span>
                    <input type="number" class="form-control" name="discount" id="discount" value="{{ old('discount', $facture->remise) }}" min="0" max="100" style="width:80px;text-align:center;" onchange="updateGrandTotal()">
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;font-size:13.5px;">
                    <span style="color:var(--muted);">TVA (%)</span>
                    <select class="form-control form-select" name="tax" id="tax" style="width:100px;" onchange="updateGrandTotal()">
                        <option value="0" {{ old('tax', $facture->tva) == 0 ? 'selected' : '' }}>0%</option>
                        <option value="7" {{ old('tax', $facture->tva) == 7 ? 'selected' : '' }}>7%</option>
                        <option value="10" {{ old('tax', $facture->tva) == 10 ? 'selected' : '' }}>10%</option>
                        <option value="20" {{ old('tax', $facture->tva) == 20 ? 'selected' : '' }}>20%</option>
                    </select>
                </div>
                <div style="display:flex;justify-content:space-between;padding:12px;border-radius:10px;background:linear-gradient(135deg,rgba(52,168,140,.1),rgba(52,168,140,.05));border:1px solid rgba(52,168,140,.2);">
                    <span style="font-size:14px;font-weight:700;color:var(--teal-800);">Total TTC</span>
                    <span id="grand-total" style="font-size:18px;font-weight:800;color:var(--teal-700);font-variant-numeric:tabular-nums;">0 MAD</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Notes & Insurance --}}
    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(59,130,246,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(59,130,246,.1);display:flex;align-items:center;gap:14px;">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#60a5fa,#3b82f6);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(59,130,246,.3);">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Notes & Assurance</h3>
                <span style="font-size:12px;color:var(--muted);font-weight:500;">Couverture assurance et observations</span>
            </div>
        </div>

        <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
            <div class="form-group">
                <label class="form-label">Couverture assurance</label>
                <select class="form-control form-select" name="insurance">
                    <option value="" {{ old('insurance', $facture->assurance) == '' ? 'selected' : '' }}>Aucune (paiement direct)</option>
                    <option {{ old('insurance', $facture->assurance) == 'CNSS' ? 'selected' : '' }}>CNSS</option>
                    <option {{ old('insurance', $facture->assurance) == 'CNOPS' ? 'selected' : '' }}>CNOPS</option>
                    <option {{ old('insurance', $facture->assurance) == 'FAR' ? 'selected' : '' }}>FAR</option>
                    <option {{ old('insurance', $facture->assurance) == 'Assurance privée' ? 'selected' : '' }}>Assurance privée</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Taux de remboursement (%)</label>
                <input type="number" class="form-control @error('coverage_rate') input-error @enderror" name="coverage_rate" value="{{ old('coverage_rate', $facture->taux_remboursement) }}" placeholder="Ex: 70" min="0" max="100">
                @error('coverage_rate')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Notes / Observations</label>
                <textarea class="form-control @error('notes') input-error @enderror" name="notes" rows="2" placeholder="Informations complémentaires…">{{ old('notes', $facture->notes) }}</textarea>
                @error('notes')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:flex-end;gap:10px;">
            <a href="{{ route('billing.index') }}" class="btn btn-outline">Annuler</a>
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
let svcIndex = {{ max($facture->lignes->count(), 1) }};

function fmt(n) { return n.toLocaleString('fr-FR') + ' MAD'; }

function recalcRow(input) {
    const row = input.closest('.svc-row');
    const qty = parseFloat(row.querySelector('[name$="[qty]"]').value) || 0;
    const price = parseFloat(row.querySelector('[name$="[price]"]').value) || 0;
    row.querySelector('.svc-total').textContent = fmt(qty * price);
    updateGrandTotal();
}

function updateGrandTotal() {
    let sub = 0;
    document.querySelectorAll('.svc-row').forEach(row => {
        const qty = parseFloat(row.querySelector('[name$="[qty]"]').value) || 0;
        const price = parseFloat(row.querySelector('[name$="[price]"]').value) || 0;
        sub += qty * price;
    });
    const discount = parseFloat(document.getElementById('discount').value) || 0;
    const tax = parseFloat(document.getElementById('tax').value) || 0;
    const afterDiscount = sub * (1 - discount / 100);
    const total = afterDiscount * (1 + tax / 100);
    document.getElementById('subtotal').textContent = fmt(sub);
    document.getElementById('grand-total').textContent = fmt(Math.round(total));
}

function addServiceRow() {
    const i = svcIndex++;
    const row = document.createElement('div');
    row.className = 'svc-row';
    row.style.cssText = 'display:grid;grid-template-columns:3fr 1fr 1.2fr 1.2fr auto;gap:12px;align-items:center;margin-bottom:10px;';
    row.innerHTML = `
        <input type="text" class="form-control" name="services[${i}][name]" placeholder="Désignation de la prestation">
        <input type="number" class="form-control" name="services[${i}][qty]" value="1" min="1" onchange="recalcRow(this)" style="text-align:center;">
        <input type="number" class="form-control" name="services[${i}][price]" placeholder="0" min="0" onchange="recalcRow(this)" style="font-variant-numeric:tabular-nums;">
        <div class="svc-total" style="font-weight:700;color:var(--teal-800);font-variant-numeric:tabular-nums;font-size:14px;padding:0 4px;">0 MAD</div>
        <button type="button" class="btn btn-ghost btn-sm btn-icon-only" onclick="removeServiceRow(this)" style="color:#f43f5e;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
    `;
    document.getElementById('service-rows').appendChild(row);
    updateServiceCount();
}

function removeServiceRow(btn) {
    const rows = document.querySelectorAll('.svc-row');
    if (rows.length <= 1) return;
    btn.closest('.svc-row').remove();
    updateGrandTotal();
    updateServiceCount();
}

function updateServiceCount() {
    const c = document.querySelectorAll('.svc-row').length;
    document.getElementById('service-count').textContent = c + ' prestation' + (c > 1 ? 's' : '');
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
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.svc-row').forEach(row => recalcRow(row.querySelector('[name$="[qty]"]')));
});
</script>
@endpush
