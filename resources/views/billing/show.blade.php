@extends('layouts.app')

@php
    $patient = $facture->patient;
    $clinic  = config('clinic');

    if ($facture->statut === 'paye') { [$statusClass, $statusLabel] = ['paid', 'Payée']; }
    elseif ($facture->isOverdue()) { [$statusClass, $statusLabel] = ['overdue', 'En retard']; }
    else { [$statusClass, $statusLabel] = ['pending', 'En attente']; }

    // Derive the intermediate amounts from the stored totals so the printed
    // lines always add up exactly to the total_ttc saved on the invoice.
    $sousTotal     = (float) $facture->sous_total;
    $totalTtc      = (float) $facture->total_ttc;
    $montantRemise = round($sousTotal * $facture->remise / 100, 2);
    $totalHt       = $sousTotal - $montantRemise;
    $montantTva    = $totalTtc - $totalHt;

    $partAssurance = ($facture->assurance && $facture->taux_remboursement)
        ? round($totalTtc * $facture->taux_remboursement / 100, 2)
        : 0;
    $resteAPayer = $totalTtc - $partAssurance;

    $mad = fn ($amount) => number_format($amount, 2, ',', ' ') . ' MAD';

    // "Arrêtée la présente facture à la somme de …" — standard on French/Moroccan invoices.
    $enLettres = null;
    if (class_exists(\NumberFormatter::class)) {
        $spell    = new \NumberFormatter('fr', \NumberFormatter::SPELLOUT);
        $cents    = (int) round($totalTtc * 100);
        $dirhams  = intdiv($cents, 100);
        $centimes = $cents % 100;
        $enLettres = \Illuminate\Support\Str::ucfirst($spell->format($dirhams)) . ' dirham' . ($dirhams > 1 ? 's' : '')
            . ($centimes ? ' et ' . $spell->format($centimes) . ' centime' . ($centimes > 1 ? 's' : '') : '');
    }

    $clinicContact = array_filter([$clinic['address'], $clinic['phone'], $clinic['email']]);
@endphp

@section('title', 'Facture ' . $facture->numero)
@section('page-title', 'Facture')
@section('page-subtitle', $facture->numero . ' — ' . ($patient->full_name ?? 'Patient supprimé'))

@push('styles')
<style>
    /* ─── Toolbar (screen only) ─── */
    .invoice-toolbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        max-width: 210mm;
        margin: 0 auto 20px;
    }

    .invoice-toolbar .back-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: var(--muted);
        font-weight: 500;
        transition: color .15s;
    }

    .invoice-toolbar .back-link:hover { color: var(--teal-600); }

    /* ─── A4 sheet — always paper-white, in both themes ─── */
    .invoice-sheet {
        --ink: #133c35;
        --ink-soft: #4d6b65;
        --line: #d9e8e4;
        --brand: #1a7260;
        --brand-tint: #f0faf7;

        max-width: 210mm;
        margin: 0 auto;
        padding: 16mm 15mm;
        background: #fff;
        color: var(--ink);
        border-radius: 14px;
        box-shadow: 0 1px 3px rgba(19, 60, 53, .06), 0 12px 32px rgba(19, 60, 53, .08);
        font-size: 12.5px;
        line-height: 1.5;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .invoice-sheet .inv-head {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 24px;
        padding-bottom: 18px;
        border-bottom: 2px solid var(--brand);
    }

    .invoice-sheet .inv-brand { display: flex; gap: 12px; align-items: flex-start; }

    .invoice-sheet .inv-logo {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: var(--brand);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .invoice-sheet .inv-clinic { font-family: 'Fraunces', serif; font-size: 20px; font-weight: 700; line-height: 1.2; }
    .invoice-sheet .inv-tagline { color: var(--ink-soft); font-size: 12px; margin-bottom: 4px; }
    .invoice-sheet .inv-contact { color: var(--ink-soft); font-size: 11.5px; }

    .invoice-sheet .inv-title { text-align: right; }
    .invoice-sheet .inv-title h1 { font-family: 'Fraunces', serif; font-size: 26px; font-weight: 700; letter-spacing: .06em; color: var(--brand); line-height: 1.1; }
    .invoice-sheet .inv-number { font-family: ui-monospace, SFMono-Regular, Menlo, monospace; font-weight: 600; font-size: 13px; margin: 4px 0 8px; }

    .invoice-sheet .inv-status {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .04em;
        text-transform: uppercase;
        border: 1px solid currentColor;
    }

    .invoice-sheet .inv-status.paid { color: #047857; background: #ecfdf5; }
    .invoice-sheet .inv-status.pending { color: #b45309; background: #fffbeb; }
    .invoice-sheet .inv-status.overdue { color: #be123c; background: #fff1f2; }

    .invoice-sheet .inv-parties {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin: 22px 0;
    }

    .invoice-sheet .inv-box { border: 1px solid var(--line); border-radius: 10px; padding: 14px 16px; }
    .invoice-sheet .inv-label { font-size: 10.5px; font-weight: 700; color: var(--brand); letter-spacing: .08em; text-transform: uppercase; margin-bottom: 6px; }
    .invoice-sheet .inv-name { font-size: 14px; font-weight: 700; margin-bottom: 2px; }
    .invoice-sheet .inv-muted { color: var(--ink-soft); }

    .invoice-sheet .inv-meta { display: grid; grid-template-columns: auto 1fr; gap: 4px 14px; }
    .invoice-sheet .inv-meta dt { color: var(--ink-soft); }
    .invoice-sheet .inv-meta dd { font-weight: 600; text-align: right; }

    /* Line items — selectors outrank the layout's global (and html.dark) table rules */
    .invoice-sheet .inv-lines { width: 100%; border-collapse: collapse; }
    .invoice-sheet .inv-lines thead tr { background: var(--brand-tint); }

    .invoice-sheet .inv-lines thead th {
        padding: 9px 12px;
        font-size: 10.5px;
        font-weight: 700;
        color: var(--brand);
        letter-spacing: .08em;
        text-transform: uppercase;
        border-bottom: 1px solid var(--line);
        text-align: left;
    }

    .invoice-sheet .inv-lines tbody tr { border-top: none; border-bottom: 1px solid var(--line); cursor: default; break-inside: avoid; }
    .invoice-sheet .inv-lines tbody tr:hover { background: transparent; }
    .invoice-sheet .inv-lines tbody td { padding: 10px 12px; font-size: 12.5px; color: var(--ink); }
    .invoice-sheet .inv-lines .num { text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap; }

    .invoice-sheet .inv-summary {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 24px;
        margin-top: 18px;
        break-inside: avoid;
    }

    .invoice-sheet .inv-words { flex: 1; font-size: 12px; color: var(--ink-soft); padding-top: 6px; }
    .invoice-sheet .inv-words strong { display: block; color: var(--ink); margin-top: 2px; }

    .invoice-sheet .inv-totals { width: 280px; flex-shrink: 0; }

    .invoice-sheet .inv-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 5px 0;
        font-variant-numeric: tabular-nums;
    }

    .invoice-sheet .inv-row span:first-child { color: var(--ink-soft); }

    .invoice-sheet .inv-row.grand {
        margin: 6px 0;
        padding: 10px 12px;
        background: var(--brand);
        color: #fff;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 700;
    }

    .invoice-sheet .inv-row.grand span:first-child { color: #fff; }
    .invoice-sheet .inv-row.due { border-top: 1px solid var(--line); font-weight: 700; }
    .invoice-sheet .inv-row.due span:first-child { color: var(--ink); }

    .invoice-sheet .inv-notes { margin-top: 18px; padding: 12px 14px; border-left: 3px solid var(--brand); background: var(--brand-tint); border-radius: 0 8px 8px 0; break-inside: avoid; }

    .invoice-sheet .inv-foot { margin-top: 28px; break-inside: avoid; }
    .invoice-sheet .inv-sign { display: flex; justify-content: flex-end; }

    .invoice-sheet .inv-sign-box {
        width: 220px;
        height: 90px;
        border: 1px dashed var(--line);
        border-radius: 8px;
        padding: 8px 10px;
        font-size: 11px;
        color: var(--ink-soft);
        text-align: center;
    }

    .invoice-sheet .inv-legal { margin-top: 22px; padding-top: 10px; border-top: 1px solid var(--line); text-align: center; font-size: 10.5px; color: var(--ink-soft); }

    @media (max-width: 768px) {
        .invoice-sheet { padding: 20px 16px; }
        .invoice-sheet .inv-head,
        .invoice-sheet .inv-summary { flex-direction: column; }
        .invoice-sheet .inv-title { text-align: left; }
        .invoice-sheet .inv-parties { grid-template-columns: 1fr; }
        .invoice-sheet .inv-totals { width: 100%; }
        .invoice-sheet .inv-lines thead th { padding: 8px 6px; white-space: normal; }
        .invoice-sheet .inv-lines tbody td { padding: 9px 6px; font-size: 12px; }
    }

    /* ─── Print: only the sheet, edge to edge on A4 ─── */
    @page { size: A4; margin: 0; }

    @media print {
        html, body { height: auto; background: #fff !important; }
        body > *:not(.main),
        .main > *:not(.content),
        .content > *:not(.invoice-sheet) { display: none !important; }
        .main { margin-left: 0 !important; min-height: 0; display: block; }
        .content { padding: 0 !important; }
        .invoice-sheet { max-width: none; margin: 0; padding: 14mm 15mm; border-radius: 0; box-shadow: none; }
    }
</style>
@endpush

@section('content')

<div class="invoice-toolbar">
    <a href="{{ route('billing.index') }}" class="back-link">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        Retour aux factures
    </a>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('billing.edit', $facture->id) }}" class="btn btn-outline btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/></svg>
            Modifier
        </a>
        <button type="button" class="btn btn-primary btn-sm" onclick="window.print()" title="Imprimer, ou choisir « Enregistrer au format PDF » dans la fenêtre d'impression">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z"/></svg>
            Imprimer / PDF
        </button>
    </div>
</div>

<article class="invoice-sheet">

    <header class="inv-head">
        <div class="inv-brand">
            <div class="inv-logo">
                <svg width="22" height="22" viewBox="0 0 32 32" fill="none">
                    <rect x="11" y="2" width="10" height="28" rx="3" fill="white" />
                    <rect x="2" y="11" width="28" height="10" rx="3" fill="white" />
                </svg>
            </div>
            <div>
                <div class="inv-clinic">{{ $clinic['name'] }}</div>
                @if($clinic['tagline'])<div class="inv-tagline">{{ $clinic['tagline'] }}</div>@endif
                @foreach($clinicContact as $line)
                    <div class="inv-contact">{{ $line }}</div>
                @endforeach
            </div>
        </div>
        <div class="inv-title">
            <h1>FACTURE</h1>
            <div class="inv-number">N° {{ $facture->numero }}</div>
            <span class="inv-status {{ $statusClass }}">{{ $statusLabel }}</span>
        </div>
    </header>

    <section class="inv-parties">
        <div class="inv-box">
            <div class="inv-label">Facturé à</div>
            <div class="inv-name">{{ $patient->full_name ?? 'Patient supprimé' }}</div>
            @if($patient?->cin)<div class="inv-muted">CIN : {{ $patient->cin }}</div>@endif
            @if($patient?->telephone)<div class="inv-muted">Tél : {{ $patient->telephone }}</div>@endif
            @if($patient?->adresse)<div class="inv-muted">{{ $patient->adresse }}</div>@endif
            @if($facture->assurance)
                <div class="inv-muted" style="margin-top:6px;">
                    Assurance : <strong style="color:var(--ink);">{{ $facture->assurance }}</strong>
                    @if($patient?->assurance_numero) — N° {{ $patient->assurance_numero }}@endif
                </div>
            @endif
        </div>
        <div class="inv-box">
            <div class="inv-label">Détails</div>
            <dl class="inv-meta">
                <dt>Date de facturation</dt>
                <dd>{{ $facture->date_facturation->format('d/m/Y') }}</dd>
                @if($facture->date_echeance)
                    <dt>Échéance</dt>
                    <dd>{{ $facture->date_echeance->format('d/m/Y') }}</dd>
                @endif
                @if($facture->staff)
                    <dt>Praticien</dt>
                    <dd>{{ $facture->staff->isDoctor() ? 'Dr ' : '' }}{{ $facture->staff->full_name }}@if($facture->staff->specialite)<br><span class="inv-muted" style="font-weight:500;">{{ $facture->staff->specialite }}</span>@endif</dd>
                @endif
                @if($facture->consultation)
                    <dt>Consultation du</dt>
                    <dd>{{ $facture->consultation->date_consultation->format('d/m/Y') }}</dd>
                @endif
                <dt>Mode de paiement</dt>
                <dd>{{ \App\Models\Facture::PAYMENT_LABELS[$facture->mode_paiement] ?? $facture->mode_paiement }}</dd>
                @if($facture->statut === 'paye' && $facture->paid_at)
                    <dt>Réglée le</dt>
                    <dd>{{ $facture->paid_at->format('d/m/Y') }}</dd>
                @endif
            </dl>
        </div>
    </section>

    <table class="inv-lines">
        <thead>
            <tr>
                <th>Désignation</th>
                <th class="num">Qté</th>
                <th class="num">Prix unitaire</th>
                <th class="num">Montant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($facture->lignes as $ligne)
                <tr>
                    <td>{{ $ligne->designation }}</td>
                    <td class="num">{{ $ligne->quantite }}</td>
                    <td class="num">{{ $mad($ligne->prix_unitaire) }}</td>
                    <td class="num">{{ $mad($ligne->total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <section class="inv-summary">
        <div class="inv-words">
            @if($enLettres)
                Arrêtée la présente facture à la somme de :
                <strong>{{ $enLettres }}</strong>
            @endif
        </div>
        <div class="inv-totals">
            <div class="inv-row"><span>Sous-total</span><span>{{ $mad($sousTotal) }}</span></div>
            @if($facture->remise)
                <div class="inv-row"><span>Remise ({{ $facture->remise }} %)</span><span>− {{ $mad($montantRemise) }}</span></div>
            @endif
            @if($facture->tva)
                <div class="inv-row"><span>Total HT</span><span>{{ $mad($totalHt) }}</span></div>
                <div class="inv-row"><span>TVA ({{ $facture->tva }} %)</span><span>{{ $mad($montantTva) }}</span></div>
            @endif
            <div class="inv-row grand"><span>Total TTC</span><span>{{ $mad($totalTtc) }}</span></div>
            @if($partAssurance)
                <div class="inv-row"><span>Part {{ $facture->assurance }} ({{ $facture->taux_remboursement }} %)</span><span>− {{ $mad($partAssurance) }}</span></div>
                <div class="inv-row due"><span>Reste à charge du patient</span><span>{{ $mad($resteAPayer) }}</span></div>
            @endif
        </div>
    </section>

    @if($facture->notes)
        <div class="inv-notes">
            <div class="inv-label" style="margin-bottom:2px;">Notes</div>
            {{ $facture->notes }}
        </div>
    @endif

    <footer class="inv-foot">
        <div class="inv-sign">
            <div class="inv-sign-box">Cachet et signature</div>
        </div>
        <div class="inv-legal">
            {{ $clinic['name'] }}@if($clinic['tagline']) — {{ $clinic['tagline'] }}@endif
            @if($clinic['ice']) · ICE : {{ $clinic['ice'] }}@endif
            @foreach($clinicContact as $line) · {{ $line }}@endforeach
        </div>
    </footer>

</article>

@endsection
