@extends('layouts.app')

@section('title', "Ajouter à la File")
@section('page-title', "Ajouter à la File d'Attente")
@section('page-subtitle', "Enregistrer l'arrivée d'un patient")

@section('content')

<div style="max-width:700px;margin:0 auto;">

    <a href="{{ url('/queue') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        Retour à la file d'attente
    </a>

    {{-- Current queue status --}}
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:20px;">
        <div style="padding:14px 18px;border-radius:12px;background:linear-gradient(135deg,#fff,rgba(245,158,11,.07));border:1px solid rgba(245,158,11,.2);text-align:center;">
            <div style="font-size:24px;font-weight:800;color:var(--teal-800);">5</div>
            <div style="font-size:11.5px;color:var(--muted);font-weight:600;margin-top:2px;">Patients en attente</div>
        </div>
        <div style="padding:14px 18px;border-radius:12px;background:linear-gradient(135deg,#fff,rgba(52,168,140,.07));border:1px solid rgba(52,168,140,.2);text-align:center;">
            <div style="font-size:24px;font-weight:800;color:var(--teal-800);">06</div>
            <div style="font-size:11.5px;color:var(--muted);font-weight:600;margin-top:2px;">Prochain numéro</div>
        </div>
        <div style="padding:14px 18px;border-radius:12px;background:linear-gradient(135deg,#fff,rgba(59,130,246,.07));border:1px solid rgba(59,130,246,.2);text-align:center;">
            <div style="font-size:24px;font-weight:800;color:var(--teal-800);">~22 min</div>
            <div style="font-size:11.5px;color:var(--muted);font-weight:600;margin-top:2px;">Attente estimée</div>
        </div>
    </div>

    <form action="{{ url('/queue') }}" method="POST">
        @csrf

        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Informations patient</h3></div>
                </div>
            </div>
            <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                <div class="form-group" style="grid-column:1/-1;">
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
                    <div style="margin-top:6px;font-size:12px;color:var(--muted);">Patient non trouvé ? <a href="{{ url('/patients/create') }}" style="color:var(--teal-600);text-decoration:none;font-weight:600;">Créer un nouveau dossier</a></div>
                </div>
                <div class="form-group">
                    <label class="form-label">Médecin assigné <span style="color:#f43f5e;">*</span></label>
                    <select class="form-control form-select" name="doctor_id">
                        <option value="">— Sélectionner —</option>
                        <option>Dr. Mehdi Alaoui — Cardiologie</option>
                        <option>Dr. Sara Tazi — Méd. Générale</option>
                        <option>Dr. Karim Fassi — Pédiatrie</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Heure d'arrivée</label>
                    <input type="time" class="form-control" name="arrived_at" id="arrived_at">
                </div>
                <div class="form-group">
                    <label class="form-label">Priorité</label>
                    <select class="form-control form-select" name="priority">
                        <option value="normale">Normale</option>
                        <option value="haute">Haute</option>
                        <option value="urgente" style="color:#f43f5e;font-weight:700;">Urgente</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Type de visite</label>
                    <select class="form-control form-select" name="visit_type">
                        <option>Sans rendez-vous</option>
                        <option>Avec rendez-vous</option>
                        <option>Urgence</option>
                        <option>Suivi</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Motif / Symptômes</label>
                    <textarea class="form-control" name="symptoms" rows="3" placeholder="Décrire brièvement les symptômes ou le motif de la visite…" style="resize:vertical;"></textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;padding-bottom:32px;">
            <a href="{{ url('/queue') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Ajouter à la file
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
// Set current time as default arrival time
(function() {
    const now = new Date();
    const hh = String(now.getHours()).padStart(2,'0');
    const mm = String(now.getMinutes()).padStart(2,'0');
    document.getElementById('arrived_at').value = hh + ':' + mm;
})();
</script>
@endpush
