@extends('layouts.app')

@section('title', 'Nouvelle Consultation')
@section('page-title', 'Nouvelle Consultation')
@section('page-subtitle', 'Enregistrer une consultation médicale')

@section('content')

{{-- Back --}}
<a href="{{ route('consultations.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;transition:color .15s;" onmouseenter="this.style.color='var(--teal-600)'" onmouseleave="this.style.color='var(--muted)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
    Retour aux consultations
</a>

<form action="{{ route('consultations.store') }}" method="POST">
    @csrf
    @if($fileAttenteEntry)
        <input type="hidden" name="file_attente_id" value="{{ $fileAttenteEntry->id }}">
        <div class="card" style="margin-bottom:16px;padding:14px 20px;background:rgba(52,168,140,.08);border:1.5px solid rgba(52,168,140,.25);display:flex;align-items:center;gap:10px;">
            <svg width="16" height="16" fill="none" stroke="var(--teal-600)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
            <span style="font-size:13px;font-weight:600;color:var(--teal-800);">
                Consultation démarrée depuis la file d'attente — {{ $fileAttenteEntry->patient->full_name }}. À l'enregistrement, cette entrée passera automatiquement à « terminé » lorsque la facture sera payée.
            </span>
        </div>
    @endif

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
                <select class="form-control form-select @error('patient_id') input-error @enderror" name="patient_id" {{ $fileAttenteEntry ? 'disabled' : '' }}>
                    <option value="">— Sélectionner un patient —</option>
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}" {{ old('patient_id', $fileAttenteEntry->patient_id ?? null) == $p->id ? 'selected' : '' }}>
                            {{ $p->full_name }} @if($p->cin) (CIN: {{ $p->cin }}) @endif
                        </option>
                    @endforeach
                </select>
                @if($fileAttenteEntry)
                    <input type="hidden" name="patient_id" value="{{ $fileAttenteEntry->patient_id }}">
                @endif
                @error('patient_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Médecin <span style="color:#f43f5e;">*</span></label>
                <select class="form-control form-select @error('staff_id') input-error @enderror" name="staff_id" {{ $fileAttenteEntry ? 'disabled' : '' }}>
                    <option value="">— Sélectionner un médecin —</option>
                    @foreach($doctors as $d)
                        <option value="{{ $d->id }}" {{ old('staff_id', $fileAttenteEntry->staff_id ?? null) == $d->id ? 'selected' : '' }}>
                            Dr. {{ $d->full_name }} @if($d->specialite) — {{ $d->specialite }} @endif
                        </option>
                    @endforeach
                </select>
                @if($fileAttenteEntry)
                    <input type="hidden" name="staff_id" value="{{ $fileAttenteEntry->staff_id }}">
                @endif
                @error('staff_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Date <span style="color:#f43f5e;">*</span></label>
                <div style="position:relative;">
                    <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" type="text" class="form-control @error('date') input-error @enderror" name="date" id="consult_date" value="{{ old('date', today()->format('Y-m-d')) }}" placeholder="aaaa-mm-jj" autocomplete="off" style="padding-left:36px;">
                </div>
                @error('date')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Heure</label>
                <input type="time" class="form-control @error('heure') input-error @enderror" name="heure" value="{{ old('heure', now()->format('H:i')) }}">
                @error('heure')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Type de consultation</label>
                <select class="form-control form-select" name="type_consultation">
                    @foreach([
                        'standard' => 'Consultation standard',
                        'suivi' => 'Suivi',
                        'urgence' => 'Urgence',
                        'controle_post_operatoire' => 'Contrôle post-opératoire',
                        'bilan_complet' => 'Bilan complet',
                    ] as $value => $label)
                        <option value="{{ $value }}" {{ old('type_consultation') == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Motif de consultation</label>
                <input type="text" class="form-control @error('motif') input-error @enderror" name="motif" value="{{ old('motif') }}" placeholder="Ex: Douleurs thoraciques, toux persistante…">
                @error('motif')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Exam & Diagnosis --}}
    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(59,130,246,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(59,130,246,.1);display:flex;align-items:center;gap:14px;">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#60a5fa,#3b82f6);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(59,130,246,.3);">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/></svg>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Examen clinique & Diagnostic</h3>
                <span style="font-size:12px;color:var(--muted);font-weight:500;">Signes vitaux, diagnostic et traitement</span>
            </div>
        </div>

        <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
            {{-- Vital signs --}}
            <div class="form-group">
                <label class="form-label">Tension artérielle (mmHg)</label>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input type="number" class="form-control @error('tension_systolique') input-error @enderror" name="tension_systolique" value="{{ old('tension_systolique') }}" placeholder="Sys" style="width:80px;" min="50" max="300">
                    <span style="color:var(--muted);font-weight:700;">/</span>
                    <input type="number" class="form-control @error('tension_diastolique') input-error @enderror" name="tension_diastolique" value="{{ old('tension_diastolique') }}" placeholder="Dia" style="width:80px;" min="30" max="200">
                </div>
                @error('tension_systolique')<div class="field-error">{{ $message }}</div>@enderror
                @error('tension_diastolique')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Fréquence cardiaque (bpm)</label>
                <input type="number" class="form-control @error('frequence_cardiaque') input-error @enderror" name="frequence_cardiaque" value="{{ old('frequence_cardiaque') }}" placeholder="Ex: 72" min="30" max="250">
                @error('frequence_cardiaque')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Température (°C)</label>
                <input type="number" class="form-control @error('temperature') input-error @enderror" name="temperature" value="{{ old('temperature') }}" placeholder="Ex: 37.2" step="0.1" min="30" max="45">
                @error('temperature')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Saturation O₂ (%)</label>
                <input type="number" class="form-control @error('spo2') input-error @enderror" name="spo2" value="{{ old('spo2') }}" placeholder="Ex: 98" min="0" max="100">
                @error('spo2')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Poids (kg)</label>
                <input type="number" class="form-control @error('poids') input-error @enderror" name="poids" value="{{ old('poids') }}" placeholder="Ex: 72" step="0.1" min="0" max="500">
                @error('poids')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Taille (cm)</label>
                <input type="number" class="form-control @error('taille') input-error @enderror" name="taille" value="{{ old('taille') }}" placeholder="Ex: 175" min="0" max="250">
                @error('taille')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Diagnostic</label>
                <textarea class="form-control @error('diagnostic') input-error @enderror" name="diagnostic" rows="3" placeholder="Diagnostic clinique…">{{ old('diagnostic') }}</textarea>
                @error('diagnostic')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Traitement prescrit</label>
                <textarea class="form-control @error('traitement') input-error @enderror" name="traitement" rows="3" placeholder="Traitement médical recommandé…">{{ old('traitement') }}</textarea>
                @error('traitement')<div class="field-error">{{ $message }}</div>@enderror
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
                    ['ordonnance_demandee',  'Ordonnance médicale',   'Prescrire des médicaments'],
                    ['scanner_demande', 'Scanner / Imagerie',     'Demander une imagerie médicale'],
                    ['analyse_demandee', 'Analyses de laboratoire','Prescrire des analyses'],
                    ['kine_demandee', 'Kinésithérapie',         'Recommander une rééducation'],
                ] as [$key, $label, $desc])
                <label class="exam-check-row">
                    <input type="checkbox" name="{{ $key }}" value="1" {{ old($key) ? 'checked' : '' }} style="margin-top:2px;width:16px;height:16px;accent-color:var(--teal-500);">
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
                        <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" type="text" class="form-control @error('prochain_rdv_date') input-error @enderror" name="prochain_rdv_date" id="next_visit" value="{{ old('prochain_rdv_date') }}" placeholder="aaaa-mm-jj" autocomplete="off" style="padding-left:36px;">
                    </div>
                    @error('prochain_rdv_date')<div class="field-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Dans</label>
                    <select class="form-control form-select" name="next_visit_delay" id="next_visit_delay" onchange="applyDelay()">
                        <option value="">— Choisir —</option>
                        <option value="7">1 semaine</option>
                        <option value="14">2 semaines</option>
                        <option value="30">1 mois</option>
                        <option value="90">3 mois</option>
                        <option value="180">6 mois</option>
                        <option value="365">1 an</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Notes du médecin</label>
                    <textarea class="form-control @error('notes') input-error @enderror" name="notes" rows="3" placeholder="Observations, recommandations particulières…">{{ old('notes') }}</textarea>
                    @error('notes')<div class="field-error">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:flex-end;gap:10px;">
            <a href="{{ route('consultations.index') }}" class="btn btn-outline">Annuler</a>
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
.form-control.input-error{border-color:#f43f5e;background:rgba(244,63,94,.04);}
.field-error{font-size:11.5px;color:#e11d48;margin-top:5px;font-weight:600;}
select.form-control{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237bbfb0' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 13px center;padding-right:36px;cursor:pointer;}
textarea.form-control{resize:vertical;min-height:80px;line-height:1.65;}
.form-label{font-size:11px;font-weight:700;color:var(--teal-600);letter-spacing:.07em;text-transform:uppercase;margin-bottom:6px;display:flex;align-items:center;gap:5px;}
.exam-check-row{display:flex;align-items:flex-start;gap:12px;padding:14px;border-radius:10px;border:1.5px solid rgba(52,168,140,.15);cursor:pointer;background:#fff;transition:all .15s;}
.exam-check-row:has(input:checked){border-color:var(--teal-400);background:rgba(52,168,140,.05);}
</style>
@endpush

@push('scripts')
<script>
function applyDelay() {
    const days = parseInt(document.getElementById('next_visit_delay').value, 10);
    if (!days) return;

    const base = document.getElementById('consult_date').value || new Date().toISOString().slice(0, 10);
    const d = new Date(base + 'T00:00:00');
    d.setDate(d.getDate() + days);

    const yyyy = d.getFullYear();
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const dd = String(d.getDate()).padStart(2, '0');
    document.getElementById('next_visit').value = `${yyyy}-${mm}-${dd}`;
}
</script>
@endpush
