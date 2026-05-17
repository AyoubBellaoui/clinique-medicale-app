@extends('layouts.app')

@section('title', 'Nouveau Rendez-vous')
@section('page-title', 'Nouveau Rendez-vous')
@section('page-subtitle', 'Planifier un rendez-vous patient')

@section('content')

{{-- Back --}}
<a href="{{ url('/appointments') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;transition:color .15s;" onmouseenter="this.style.color='var(--teal-600)'" onmouseleave="this.style.color='var(--muted)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
    Retour aux rendez-vous
</a>

<form action="{{ url('/appointments') }}" method="POST">
    @csrf

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
                <select class="form-control form-select" name="patient_id">
                    <option value="">— Sélectionner un patient —</option>
                    <option>Omar Benhaddou (CIN: A123456)</option>
                    <option>Meriem Tahiri (CIN: B789012)</option>
                    <option>Rachid Amrani (CIN: C345678)</option>
                    <option>Nadia Filali (CIN: D901234)</option>
                    <option>Fatima El Idrissi (CIN: E567890)</option>
                    <option>Aicha Moussaoui (CIN: F123456)</option>
                    <option>Hassan Ouazzani (CIN: G789012)</option>
                    <option>Youssef Benali (CIN: H345678)</option>
                    <option>Zineb Chraibi (CIN: I901234)</option>
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
                <label class="form-label">Type de consultation</label>
                <select class="form-control form-select" name="type">
                    <option>Consultation standard</option>
                    <option>Suivi</option>
                    <option>Urgence</option>
                    <option>Contrôle post-opératoire</option>
                    <option>Bilan complet</option>
                </select>
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
                <span style="font-size:12px;color:var(--muted);font-weight:500;">Planifier le créneau du rendez-vous</span>
            </div>
        </div>

        <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr 1fr;gap:18px;">
            <div class="form-group">
                <label class="form-label">Date <span style="color:#f43f5e;">*</span></label>
                <input type="date" class="form-control" name="date" value="2026-04-29">
            </div>
            <div class="form-group">
                <label class="form-label">Heure de début <span style="color:#f43f5e;">*</span></label>
                <input type="time" class="form-control" name="time_start" value="09:00">
            </div>
            <div class="form-group">
                <label class="form-label">Durée estimée</label>
                <select class="form-control form-select" name="duration">
                    <option>15 min</option>
                    <option selected>30 min</option>
                    <option>45 min</option>
                    <option>1 heure</option>
                    <option>1h30</option>
                </select>
            </div>
        </div>

        {{-- Time slots --}}
        <div style="padding:0 24px 24px;">
            <label class="form-label" style="margin-bottom:10px;">Créneaux disponibles ce jour</label>
            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                @foreach(['08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','14:00','14:30','15:00','15:30','16:00','16:30'] as $slot)
                @php $taken = in_array($slot, ['09:00','10:30','14:00']); @endphp
                <button type="button"
                    class="slot-btn {{ $taken ? 'slot-taken' : '' }}"
                    onclick="{{ $taken ? '' : "selectSlot(this, '$slot')" }}"
                    {{ $taken ? 'disabled' : '' }}>
                    {{ $slot }}{{ $taken ? ' ✕' : '' }}
                </button>
                @endforeach
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
                <span style="font-size:12px;color:var(--muted);font-weight:500;">Motif, priorité et rappels</span>
            </div>
        </div>

        <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
            <div class="form-group">
                <label class="form-label">Motif de consultation</label>
                <input type="text" class="form-control" name="reason" placeholder="Ex: Douleurs thoraciques, suivi tension…">
            </div>
            <div class="form-group">
                <label class="form-label">Priorité</label>
                <select class="form-control form-select" name="priority">
                    <option value="normale">Normale</option>
                    <option value="haute">Haute</option>
                    <option value="urgente">Urgente</option>
                </select>
            </div>
            <div class="form-group" style="grid-column:1/-1;">
                <label class="form-label">Notes additionnelles</label>
                <textarea class="form-control" name="notes" rows="3" placeholder="Informations complémentaires pour le médecin…"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Mode de rappel</label>
                <select class="form-control form-select" name="reminder">
                    <option>SMS</option>
                    <option>Email</option>
                    <option>SMS + Email</option>
                    <option>Aucun</option>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Rappel avant</label>
                <select class="form-control form-select" name="reminder_before">
                    <option>30 minutes</option>
                    <option selected>1 heure</option>
                    <option>2 heures</option>
                    <option>1 jour</option>
                </select>
            </div>
        </div>

        <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:flex-end;gap:10px;">
            <a href="{{ url('/appointments') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Créer le rendez-vous
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
.slot-btn{padding:6px 14px;border-radius:8px;font-size:12.5px;font-weight:600;border:1.5px solid rgba(52,168,140,.3);background:rgba(52,168,140,.06);color:var(--teal-700);cursor:pointer;transition:all .15s;font-family:'Plus Jakarta Sans',sans-serif;}
.slot-btn:hover:not(:disabled){background:var(--teal-500);border-color:var(--teal-500);color:#fff;}
.slot-btn.slot-taken{border-color:rgba(0,0,0,.08);background:#f1f5f9;color:#94a3b8;cursor:not-allowed;}
.slot-btn.slot-active{background:var(--teal-500);border-color:var(--teal-500);color:#fff;}
</style>
@endpush

@push('scripts')
<script>
function selectSlot(btn, time) {
    document.querySelectorAll('.slot-active').forEach(b => {
        b.classList.remove('slot-active');
        b.style.background = 'rgba(52,168,140,.06)';
        b.style.borderColor = 'rgba(52,168,140,.3)';
        b.style.color = 'var(--teal-700)';
    });
    btn.classList.add('slot-active');
    btn.style.background = 'var(--teal-500)';
    btn.style.borderColor = 'var(--teal-500)';
    btn.style.color = '#fff';
    document.querySelector('[name="time_start"]').value = time;
}
</script>
@endpush
