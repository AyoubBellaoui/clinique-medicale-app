@extends('layouts.app')

@section('title', 'Nouvelle Consultation')
@section('page-title', 'Nouvelle Consultation')
@section('page-subtitle', 'Enregistrer une consultation médicale')

@section('content')

<div style="max-width:900px;margin:0 auto;">

    <a href="{{ url('/consultations') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        Retour aux consultations
    </a>

    <form action="{{ url('/consultations') }}" method="POST">
        @csrf

        {{-- Patient & Doctor --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Patient & Médecin</h3></div>
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
                    <label class="form-label">Médecin <span style="color:#f43f5e;">*</span></label>
                    <select class="form-control form-select" name="doctor_id">
                        <option value="">— Sélectionner un médecin —</option>
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
                    <label class="form-label">Heure</label>
                    <input type="time" class="form-control" name="time" value="09:00">
                </div>
                <div class="form-group">
                    <label class="form-label">Type de consultation</label>
                    <select class="form-control form-select" name="type">
                        <option>Consultation standard</option>
                        <option>Suivi</option>
                        <option>Urgence</option>
                        <option>Contrôle post-opératoire</option>
                        <option>Bilan complet</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Spécialité</label>
                    <select class="form-control form-select" name="specialty">
                        <option>Cardiologie</option>
                        <option>Médecine Générale</option>
                        <option>Pédiatrie</option>
                        <option>Gynécologie</option>
                        <option>Autre</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Exam & Diagnosis --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Examen clinique & Diagnostic</h3></div>
                </div>
            </div>
            <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Motif de consultation</label>
                    <input type="text" class="form-control" name="reason" placeholder="Ex: Douleurs thoraciques, toux persistante…">
                </div>

                {{-- Vital signs --}}
                <div class="form-group">
                    <label class="form-label">Tension artérielle (mmHg)</label>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <input type="number" class="form-control" name="bp_sys" placeholder="Sys" style="width:80px;" min="50" max="250">
                        <span style="color:var(--muted);font-weight:700;">/</span>
                        <input type="number" class="form-control" name="bp_dia" placeholder="Dia" style="width:80px;" min="30" max="150">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Fréquence cardiaque (bpm)</label>
                    <input type="number" class="form-control" name="heart_rate" placeholder="Ex: 72" min="30" max="250">
                </div>
                <div class="form-group">
                    <label class="form-label">Température (°C)</label>
                    <input type="number" class="form-control" name="temp" placeholder="Ex: 37.2" step="0.1" min="34" max="42">
                </div>
                <div class="form-group">
                    <label class="form-label">Saturation O₂ (%)</label>
                    <input type="number" class="form-control" name="spo2" placeholder="Ex: 98" min="50" max="100">
                </div>
                <div class="form-group">
                    <label class="form-label">Poids (kg)</label>
                    <input type="number" class="form-control" name="weight" placeholder="Ex: 72" step="0.1" min="1" max="300">
                </div>
                <div class="form-group">
                    <label class="form-label">Taille (cm)</label>
                    <input type="number" class="form-control" name="height" placeholder="Ex: 175" min="30" max="250">
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Diagnostic</label>
                    <textarea class="form-control" name="diagnosis" rows="3" placeholder="Diagnostic clinique…" style="resize:vertical;"></textarea>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Traitement prescrit</label>
                    <textarea class="form-control" name="treatment" rows="3" placeholder="Traitement médical recommandé…" style="resize:vertical;"></textarea>
                </div>
            </div>
        </div>

        {{-- Exams & Follow-up --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Examens complémentaires & Suivi</h3></div>
                </div>
            </div>
            <div style="padding:24px;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:18px;">
                    @foreach([
                        ['ord',  'Ordonnance médicale',   'Prescrire des médicaments'],
                        ['scan', 'Scanner / Imagerie',     'Demander une imagerie médicale'],
                        ['anal', 'Analyses de laboratoire','Prescrire des analyses'],
                        ['kine', 'Kinésithérapie',         'Recommander une rééducation'],
                    ] as [$key, $label, $desc])
                    <label style="display:flex;align-items:flex-start;gap:12px;padding:14px;border-radius:10px;border:1.5px solid rgba(52,168,140,.15);cursor:pointer;background:#fff;transition:all .15s;" class="exam-check-row">
                        <input type="checkbox" name="{{ $key }}" value="1" style="margin-top:2px;width:16px;height:16px;accent-color:var(--teal-500);">
                        <div>
                            <div style="font-size:13.5px;font-weight:600;color:var(--teal-800);">{{ $label }}</div>
                            <div style="font-size:12px;color:var(--muted);margin-top:2px;">{{ $desc }}</div>
                        </div>
                    </label>
                    @endforeach
                </div>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                    <div class="form-group">
                        <label class="form-label">Prochain rendez-vous</label>
                        <input type="date" class="form-control" name="next_visit">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Dans</label>
                        <select class="form-control form-select" name="next_visit_delay">
                            <option value="">— Choisir —</option>
                            <option>1 semaine</option>
                            <option>2 semaines</option>
                            <option>1 mois</option>
                            <option>3 mois</option>
                            <option>6 mois</option>
                            <option>1 an</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Notes du médecin</label>
                        <textarea class="form-control" name="notes" rows="3" placeholder="Observations, recommandations particulières…" style="resize:vertical;"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;padding-bottom:32px;">
            <a href="{{ url('/consultations') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Enregistrer la consultation
            </button>
        </div>
    </form>
</div>

@endsection

@push('styles')
<style>
.exam-check-row:has(input:checked) {
    border-color: var(--teal-400);
    background: rgba(52,168,140,.05);
}
</style>
@endpush
