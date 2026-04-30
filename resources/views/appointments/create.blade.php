@extends('layouts.app')

@section('title', 'Nouveau Rendez-vous')
@section('page-title', 'Nouveau Rendez-vous')
@section('page-subtitle', 'Planifier un rendez-vous patient')

@section('content')

<div style="max-width:860px;margin:0 auto;">

    {{-- Back link --}}
    <a href="{{ url('/appointments') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        Retour aux rendez-vous
    </a>

    <form action="{{ url('/appointments') }}" method="POST">
        @csrf

        {{-- Section 1: Patient & Doctor --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Informations de base</h3><span>Patient et médecin</span></div>
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
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Date & Heure</h3></div>
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
                        onclick="selectSlot(this, '{{ $slot }}')"
                        style="padding:6px 14px;border-radius:8px;font-size:12.5px;font-weight:600;border:1.5px solid {{ $taken ? 'rgba(0,0,0,.08)' : 'rgba(52,168,140,.3)' }};background:{{ $taken ? '#f1f5f9' : 'rgba(52,168,140,.06)' }};color:{{ $taken ? '#94a3b8' : 'var(--teal-700)' }};cursor:{{ $taken ? 'not-allowed' : 'pointer' }};transition:all .15s;"
                        {{ $taken ? 'disabled' : '' }}>
                        {{ $slot }}{{ $taken ? ' ✕' : '' }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Section 3: Details --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Détails du rendez-vous</h3></div>
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
                    <textarea class="form-control" name="notes" rows="3" placeholder="Informations complémentaires pour le médecin…" style="resize:vertical;"></textarea>
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
        </div>

        {{-- Actions --}}
        <div style="display:flex;justify-content:flex-end;gap:10px;padding-bottom:32px;">
            <a href="{{ url('/appointments') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Créer le rendez-vous
            </button>
        </div>
    </form>
</div>

@endsection

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
