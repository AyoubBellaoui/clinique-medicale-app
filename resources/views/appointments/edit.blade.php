@extends('layouts.app')

@section('title', 'Modifier le Rendez-vous')
@section('page-title', 'Modifier le Rendez-vous')
@section('page-subtitle', 'Mettre à jour un rendez-vous existant')

@section('content')

{{-- Back --}}
<a href="{{ route('appointments.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;transition:color .15s;" onmouseenter="this.style.color='var(--teal-600)'" onmouseleave="this.style.color='var(--muted)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
    Retour aux rendez-vous
</a>

<form action="{{ route('appointments.update', $appointment->id) }}" method="POST">
    @csrf
    @method('PUT')

    {{-- Section 1: Patient & Doctor --}}
    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,var(--teal-50),rgba(255,255,255,0));border-bottom:1px solid rgba(52,168,140,.1);display:flex;align-items:center;gap:14px;">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--teal-400),var(--teal-600));display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(52,168,140,.3);">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Informations de base</h3>
                <span style="font-size:12px;color:var(--muted);font-weight:500;">Patient et médecin</span>
            </div>
        </div>

        <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Patient <span style="color:#f43f5e;">*</span></label>
                <select class="form-control form-select @error('patient_id') input-error @enderror" name="patient_id">
                    <option value="">— Sélectionner un patient —</option>
                    @foreach($patients as $p)
                        <option value="{{ $p->id }}" {{ old('patient_id', $appointment->patient_id) == $p->id ? 'selected' : '' }}>
                            {{ $p->full_name }} @if($p->cin) (CIN: {{ $p->cin }}) @endif
                        </option>
                    @endforeach
                </select>
                @error('patient_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Médecin <span style="color:#f43f5e;">*</span></label>
                <select class="form-control form-select @error('staff_id') input-error @enderror" name="staff_id">
                    <option value="">— Sélectionner un médecin —</option>
                    @foreach($doctors as $d)
                        <option value="{{ $d->id }}" {{ old('staff_id', $appointment->staff_id) == $d->id ? 'selected' : '' }}>
                            Dr. {{ $d->full_name }} @if($d->specialite) — {{ $d->specialite }} @endif
                        </option>
                    @endforeach
                </select>
                @error('staff_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Type de consultation <span style="color:#f43f5e;">*</span></label>
                <select class="form-control form-select @error('type_consultation') input-error @enderror" name="type_consultation">
                    @foreach([
                        'standard' => 'Consultation standard',
                        'suivi' => 'Suivi',
                        'urgence' => 'Urgence',
                        'controle_post_operatoire' => 'Contrôle post-opératoire',
                        'bilan_complet' => 'Bilan complet',
                    ] as $value => $label)
                        <option value="{{ $value }}" {{ old('type_consultation', $appointment->type_consultation) == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('type_consultation')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Statut <span style="color:#f43f5e;">*</span></label>
                <select class="form-control form-select @error('statut') input-error @enderror" name="statut">
                    @foreach([
                        'programme' => 'Programmé',
                        'confirme' => 'Confirmé',
                        'termine' => 'Terminé',
                        'annule' => 'Annulé',
                    ] as $value => $label)
                        <option value="{{ $value }}" {{ old('statut', $appointment->statut) == $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('statut')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Section 2: Date & Time --}}
    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(245,158,11,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(245,158,11,.1);display:flex;align-items:center;gap:14px;">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#fbbf24,#f59e0b);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(245,158,11,.3);">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Date & Heure</h3>
                <span style="font-size:12px;color:var(--muted);font-weight:500;">Créneau du rendez-vous</span>
            </div>
        </div>

        <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px;">
            <div class="form-group">
                <label class="form-label">Date <span style="color:#f43f5e;">*</span></label>
                <div style="position:relative;">
                    <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                    <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" type="text" class="form-control @error('date') input-error @enderror" name="date" value="{{ old('date', $appointment->date->format('Y-m-d')) }}" placeholder="aaaa-mm-jj" autocomplete="off" style="padding-left:36px;">
                </div>
                @error('date')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Heure de début <span style="color:#f43f5e;">*</span></label>
                <input type="time" class="form-control @error('heure') input-error @enderror" name="heure" value="{{ old('heure', $appointment->heure->format('H:i')) }}">
                @error('heure')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Durée estimée</label>
                <select class="form-control form-select" name="duree">
                    @foreach([15 => '15 min', 30 => '30 min', 45 => '45 min', 60 => '1 heure', 90 => '1h30'] as $minutes => $label)
                        <option value="{{ $minutes }}" {{ old('duree', $appointment->duree) == $minutes ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    {{-- Section 3: Details --}}
    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(59,130,246,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(59,130,246,.1);display:flex;align-items:center;gap:14px;">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#60a5fa,#3b82f6);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(59,130,246,.3);">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375"/></svg>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Détails du rendez-vous</h3>
                <span style="font-size:12px;color:var(--muted);font-weight:500;">Motif et priorité</span>
            </div>
        </div>

        <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
            <div class="form-group">
                <label class="form-label">Motif de consultation</label>
                <input type="text" class="form-control @error('motif') input-error @enderror" name="motif" value="{{ old('motif', $appointment->motif) }}" placeholder="Ex: Douleurs thoraciques, suivi tension…">
                @error('motif')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Priorité <span style="color:#f43f5e;">*</span></label>
                <select class="form-control form-select @error('priorite') input-error @enderror" name="priorite">
                    <option value="normale" {{ old('priorite', $appointment->priorite) == 'normale' ? 'selected' : '' }}>Normale</option>
                    <option value="haute" {{ old('priorite', $appointment->priorite) == 'haute' ? 'selected' : '' }}>Haute</option>
                    <option value="urgente" {{ old('priorite', $appointment->priorite) == 'urgente' ? 'selected' : '' }}>Urgente</option>
                </select>
                @error('priorite')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Notes additionnelles</label>
                <textarea class="form-control @error('notes') input-error @enderror" name="notes" rows="3" placeholder="Informations complémentaires pour le médecin…">{{ old('notes', $appointment->notes) }}</textarea>
                @error('notes')<div class="field-error">{{ $message }}</div>@enderror
            </div>
        </div>

        <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:flex-end;gap:10px;">
            <a href="{{ route('appointments.index') }}" class="btn btn-outline">Annuler</a>
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
