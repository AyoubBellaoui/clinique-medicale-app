@extends('layouts.app')

@section('title', 'Nouvelle Consultation')
@section('page-title', 'Nouvelle Consultation')
@section('page-subtitle', 'Enregistrer une consultation médicale')

@section('content')

{{-- Back --}}
<a href="{{ url('/consultations') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;transition:color .15s;" onmouseenter="this.style.color='var(--teal-600)'" onmouseleave="this.style.color='var(--muted)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
    Retour aux consultations
</a>

<form action="{{ url('/consultations') }}" method="POST">
    @csrf

    {{-- Patient & Doctor --}}
    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,var(--teal-50),rgba(255,255,255,0));border-bottom:1px solid rgba(52,168,140,.1);display:flex;align-items:center;gap:14px;">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--teal-400),var(--teal-600));display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(52,168,140,.3);">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Patient & Médecin</h3>
                <span style="font-size:12px;color:var(--muted);font-weight:500;">Informations générales de la consultation</span>
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
                <div style="position:relative;">
                    <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" type="text" class="form-control" name="date" value="2026-04-29" placeholder="aaaa-mm-jj" autocomplete="off" style="padding-left:36px;">
                </div>
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
    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(59,130,246,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(59,130,246,.1);display:flex;align-items:center;gap:14px;">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#60a5fa,#3b82f6);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(59,130,246,.3);">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/></svg>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Examen clinique & Diagnostic</h3>
                <span style="font-size:12px;color:var(--muted);font-weight:500;">Signes vitaux, diagnostic et traitement</span>
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
                <textarea class="form-control" name="diagnosis" rows="3" placeholder="Diagnostic clinique…"></textarea>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Traitement prescrit</label>
                <textarea class="form-control" name="treatment" rows="3" placeholder="Traitement médical recommandé…"></textarea>
            </div>
        </div>
    </div>

    {{-- Exams & Follow-up --}}
    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(245,158,11,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(245,158,11,.1);display:flex;align-items:center;gap:14px;">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#fbbf24,#f59e0b);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(245,158,11,.3);">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375"/></svg>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Examens complémentaires & Suivi</h3>
                <span style="font-size:12px;color:var(--muted);font-weight:500;">Prescriptions d'examens et prochain rendez-vous</span>
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
                <label class="exam-check-row">
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
                    <div style="position:relative;">
                        <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                        <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" type="text" class="form-control" name="next_visit" placeholder="aaaa-mm-jj" autocomplete="off" style="padding-left:36px;">
                    </div>
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
                    <textarea class="form-control" name="notes" rows="3" placeholder="Observations, recommandations particulières…"></textarea>
                </div>
            </div>
        </div>

        <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:flex-end;gap:10px;">
            <a href="{{ url('/consultations') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Enregistrer la consultation
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
.exam-check-row{display:flex;align-items:flex-start;gap:12px;padding:14px;border-radius:10px;border:1.5px solid rgba(52,168,140,.15);cursor:pointer;background:#fff;transition:all .15s;}
.exam-check-row:has(input:checked){border-color:var(--teal-400);background:rgba(52,168,140,.05);}
</style>
@endpush
