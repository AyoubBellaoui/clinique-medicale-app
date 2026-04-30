@extends('layouts.app')

@section('title', 'Nouveau Membre')
@section('page-title', 'Nouveau Membre du Personnel')
@section('page-subtitle', 'Ajouter un médecin ou infirmier')

@section('content')

<div style="max-width:860px;margin:0 auto;">

    <a href="{{ url('/staff') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
        Retour au personnel
    </a>

    <form action="{{ url('/staff') }}" method="POST">
        @csrf

        {{-- Identity --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Identité</h3><span>Informations personnelles</span></div>
                </div>
            </div>
            <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                <div class="form-group">
                    <label class="form-label">Prénom <span style="color:#f43f5e;">*</span></label>
                    <input type="text" class="form-control" name="first_name" placeholder="Prénom">
                </div>
                <div class="form-group">
                    <label class="form-label">Nom <span style="color:#f43f5e;">*</span></label>
                    <input type="text" class="form-control" name="last_name" placeholder="Nom de famille">
                </div>
                <div class="form-group">
                    <label class="form-label">Email professionnel <span style="color:#f43f5e;">*</span></label>
                    <input type="email" class="form-control" name="email" placeholder="prenom.nom@clinique.ma">
                </div>
                <div class="form-group">
                    <label class="form-label">Téléphone</label>
                    <input type="tel" class="form-control" name="phone" placeholder="+212 6xx xxx xxx">
                </div>
                <div class="form-group">
                    <label class="form-label">Date de naissance</label>
                    <input type="date" class="form-control" name="dob">
                </div>
                <div class="form-group">
                    <label class="form-label">Sexe</label>
                    <select class="form-control form-select" name="gender">
                        <option value="">— Choisir —</option>
                        <option value="M">Masculin</option>
                        <option value="F">Féminin</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Adresse</label>
                    <input type="text" class="form-control" name="address" placeholder="Adresse complète">
                </div>
            </div>
        </div>

        {{-- Role & Specialty --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Rôle & Spécialité</h3></div>
                </div>
            </div>
            <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                <div class="form-group">
                    <label class="form-label">Rôle <span style="color:#f43f5e;">*</span></label>
                    <select class="form-control form-select" name="role" onchange="toggleSpecialty(this.value)">
                        <option value="">— Choisir —</option>
                        <option value="medecin">Médecin</option>
                        <option value="infirmier">Infirmier / Infirmière</option>
                        <option value="admin">Administratif</option>
                        <option value="technicien">Technicien de laboratoire</option>
                    </select>
                </div>
                <div class="form-group" id="specialty-group">
                    <label class="form-label">Spécialité</label>
                    <select class="form-control form-select" name="specialty">
                        <option value="">— Choisir —</option>
                        <option>Cardiologie</option>
                        <option>Médecine Générale</option>
                        <option>Pédiatrie</option>
                        <option>Gynécologie</option>
                        <option>Dermatologie</option>
                        <option>Ophtalmologie</option>
                        <option>Neurologie</option>
                        <option>Orthopédie</option>
                        <option>Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">N° d'ordre professionnel</label>
                    <input type="text" class="form-control" name="license_number" placeholder="Numéro d'inscription">
                </div>
                <div class="form-group">
                    <label class="form-label">Diplôme / Formation</label>
                    <input type="text" class="form-control" name="degree" placeholder="Ex: Doctorat en Médecine">
                </div>
                <div class="form-group">
                    <label class="form-label">Établissement de formation</label>
                    <input type="text" class="form-control" name="school" placeholder="Ex: Faculté de Médecine de Casablanca">
                </div>
                <div class="form-group">
                    <label class="form-label">Année d'obtention</label>
                    <input type="number" class="form-control" name="grad_year" placeholder="Ex: 2015" min="1970" max="2026">
                </div>
            </div>
        </div>

        {{-- Contract & Salary --}}
        <div class="card" style="margin-bottom:16px;">
            <div class="card-header">
                <div class="section-title">
                    <div class="accent-bar"></div>
                    <div><h3>Contrat & Rémunération</h3></div>
                </div>
            </div>
            <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
                <div class="form-group">
                    <label class="form-label">Type de contrat</label>
                    <select class="form-control form-select" name="contract_type">
                        <option>CDI</option>
                        <option>CDD</option>
                        <option>Vacation</option>
                        <option>Libéral</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Date d'embauche</label>
                    <input type="date" class="form-control" name="hire_date">
                </div>
                <div class="form-group">
                    <label class="form-label">Salaire mensuel (MAD)</label>
                    <input type="number" class="form-control" name="salary" placeholder="Ex: 15000" min="0">
                </div>
                <div class="form-group">
                    <label class="form-label">Horaires</label>
                    <select class="form-control form-select" name="schedule">
                        <option>Lun–Ven 08:00–17:00</option>
                        <option>Lun–Sam 08:00–14:00</option>
                        <option>Temps partiel</option>
                        <option>Gardes / Nuits</option>
                        <option>Flexible</option>
                    </select>
                </div>
                <div class="form-group" style="grid-column:1/-1;">
                    <label class="form-label">Notes</label>
                    <textarea class="form-control" name="notes" rows="2" placeholder="Informations complémentaires…" style="resize:vertical;"></textarea>
                </div>
            </div>
        </div>

        <div style="display:flex;justify-content:flex-end;gap:10px;padding-bottom:32px;">
            <a href="{{ url('/staff') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Ajouter le membre
            </button>
        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
function toggleSpecialty(role) {
    const sg = document.getElementById('specialty-group');
    sg.style.opacity = ['medecin','infirmier'].includes(role) ? '1' : '.4';
    sg.querySelector('select').disabled = !['medecin','infirmier'].includes(role);
}
</script>
@endpush
