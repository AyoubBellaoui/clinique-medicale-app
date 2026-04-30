@extends('layouts.app')

@section('title', 'Nouvelle Facture')
@section('page-title', 'Nouvelle Facture')
@section('page-subtitle', 'Créer une facture patient')

@section('content')

<div style="max-width:900px;margin:0 auto;">

    <a href="{{ url('/billing') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        Retour aux factures
    </a>

    <form action="{{ url('/billing') }}" method="POST" id="invoiceForm">
        @csrf

        {{-- Billing info --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Informations de facturation</h3></div>
                </div>
                <div style="font-size:13px;color:var(--muted);">N° <strong class="text-mono" style="color:var(--teal-700);">FAC-2026-0048</strong></div>
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
                    <input type="date" class="form-control" name="date" value="2026-04-29">
                </div>
                <div class="form-group">
                    <label class="form-label">Date d'échéance</label>
                    <input type="date" class="form-control" name="due_date" value="2026-05-29">
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
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Prestations</h3><span id="service-count">1 prestation</span></div>
                </div>
                <button type="button" class="btn btn-outline btn-sm" onclick="addServiceRow()">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                    Ajouter une prestation
                </button>
            </div>

            <div style="padding:16px 24px 0;">
                {{-- Table header --}}
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

        {{-- Notes --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Notes & Assurance</h3></div>
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
                    <textarea class="form-control" name="notes" rows="2" placeholder="Informations complémentaires…" style="resize:vertical;"></textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;padding-bottom:32px;">
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
    </form>
</div>

@endsection

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
