@extends('layouts.app')

@section('title', 'Nouvelle Facture')
@section('page-title', 'Nouvelle Facture')
@section('page-subtitle', 'Créer une facture patient')

@section('content')

{{-- Back --}}
<a href="{{ url('/billing') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;transition:color .15s;" onmouseenter="this.style.color='var(--teal-600)'" onmouseleave="this.style.color='var(--muted)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
    Retour aux factures
</a>

<form action="{{ url('/billing') }}" method="POST" id="invoiceForm">
    @csrf

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
            <div style="font-size:13px;color:var(--muted);">N° <strong style="color:var(--teal-700);font-variant-numeric:tabular-nums;">FAC-2026-0048</strong></div>
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
                <label class="form-label">Médecin</label>
                <select class="form-control form-select" name="doctor_id">
                    <option value="">— Sélectionner —</option>
                    <option>Dr. Mehdi Alaoui</option>
                    <option>Dr. Sara Tazi</option>
                    <option>Dr. Karim Fassi</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Date de facturation</label>
                <div style="position:relative;">
                    <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" type="text" class="form-control" name="date" value="2026-04-29" placeholder="aaaa-mm-jj" autocomplete="off" style="padding-left:36px;">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Date d'échéance</label>
                <div style="position:relative;">
                    <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" type="text" class="form-control" name="due_date" value="2026-05-29" placeholder="aaaa-mm-jj" autocomplete="off" style="padding-left:36px;">
                </div>
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
                <label class="form-label">Mode de paiement</label>
                <select class="form-control form-select" name="payment_method">
                    <option value="especes">Espèces</option>
                    <option value="carte">Carte bancaire</option>
                    <option value="cheque">Chèque</option>
                    <option value="virement">Virement</option>
                    <option value="assurance">Assurance</option>
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
                    <span id="service-count" style="font-size:12px;color:var(--muted);font-weight:500;">1 prestation</span>
                </div>
            </div>
            <button type="button" class="btn btn-outline btn-sm" onclick="addServiceRow()">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Ajouter une prestation
            </button>
        </div>

        <div style="padding:16px 24px 0;">
            <div style="display:grid;grid-template-columns:3fr 1fr 1.2fr 1.2fr auto;gap:12px;padding-bottom:8px;border-bottom:2px solid rgba(52,168,140,.12);margin-bottom:8px;">
                <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Désignation</div>
                <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Qté</div>
                <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Prix unit. (MAD)</div>
                <div style="font-size:11px;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:.06em;">Total</div>
                <div></div>
            </div>
            <div id="service-rows">
                <div class="svc-row" style="display:grid;grid-template-columns:3fr 1fr 1.2fr 1.2fr auto;gap:12px;align-items:center;margin-bottom:10px;">
                    <select class="form-control form-select" name="services[0][name]" onchange="updateTotal(this)">
                        <option value="">— Choisir une prestation —</option>
                        <option data-price="300">Consultation standard — 300 MAD</option>
                        <option data-price="500">Consultation spécialisée — 500 MAD</option>
                        <option data-price="150">Bilan biologique — 150 MAD</option>
                        <option data-price="800">Scanner / IRM — 800 MAD</option>
                        <option data-price="200">Radiographie — 200 MAD</option>
                        <option data-price="100">Électrocardiogramme — 100 MAD</option>
                        <option data-price="250">Acte chirurgical mineur — 250 MAD</option>
                        <option data-price="0">Autre (saisie manuelle)</option>
                    </select>
                    <input type="number" class="form-control" name="services[0][qty]" value="1" min="1" onchange="recalcRow(this)" style="text-align:center;">
                    <input type="number" class="form-control" name="services[0][price]" placeholder="0" min="0" onchange="recalcRow(this)" style="font-variant-numeric:tabular-nums;">
                    <div class="svc-total" style="font-weight:700;color:var(--teal-800);font-variant-numeric:tabular-nums;font-size:14px;padding:0 4px;">0 MAD</div>
                    <button type="button" class="btn btn-ghost btn-sm btn-icon-only" onclick="removeServiceRow(this)" style="color:#f43f5e;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
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
                    <input type="number" class="form-control" name="discount" id="discount" value="0" min="0" max="100" style="width:80px;text-align:center;" onchange="updateGrandTotal()">
                </div>
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;font-size:13.5px;">
                    <span style="color:var(--muted);">TVA (%)</span>
                    <select class="form-control form-select" name="tax" id="tax" style="width:100px;" onchange="updateGrandTotal()">
                        <option value="0">0%</option>
                        <option value="7">7%</option>
                        <option value="10">10%</option>
                        <option value="20">20%</option>
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
                    <option value="">Aucune (paiement direct)</option>
                    <option>CNSS</option>
                    <option>CNOPS</option>
                    <option>FAR</option>
                    <option>Assurance privée</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Taux de remboursement (%)</label>
                <input type="number" class="form-control" name="coverage_rate" placeholder="Ex: 70" min="0" max="100">
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Notes / Observations</label>
                <textarea class="form-control" name="notes" rows="2" placeholder="Informations complémentaires…"></textarea>
            </div>
        </div>

        <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:flex-end;gap:10px;">
            <a href="{{ url('/billing') }}" class="btn btn-outline">Annuler</a>
            <button type="button" class="btn btn-outline" onclick="window.print()">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
                Aperçu facture
            </button>
            <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Émettre la facture
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
let svcIndex = 1;

function fmt(n) { return n.toLocaleString('fr-FR') + ' MAD'; }

function updateTotal(sel) {
    const opt = sel.options[sel.selectedIndex];
    const price = parseFloat(opt.getAttribute('data-price') || 0);
    const row = sel.closest('.svc-row');
    const priceInput = row.querySelector('[name$="[price]"]');
    if (price > 0) priceInput.value = price;
    recalcRow(priceInput);
}

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
        <select class="form-control form-select" name="services[${i}][name]" onchange="updateTotal(this)">
            <option value="">— Choisir une prestation —</option>
            <option data-price="300">Consultation standard — 300 MAD</option>
            <option data-price="500">Consultation spécialisée — 500 MAD</option>
            <option data-price="150">Bilan biologique — 150 MAD</option>
            <option data-price="800">Scanner / IRM — 800 MAD</option>
            <option data-price="200">Radiographie — 200 MAD</option>
            <option data-price="100">Électrocardiogramme — 100 MAD</option>
            <option data-price="250">Acte chirurgical mineur — 250 MAD</option>
            <option data-price="0">Autre (saisie manuelle)</option>
        </select>
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
</script>
@endpush
