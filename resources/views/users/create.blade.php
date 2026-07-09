@extends('layouts.app')

@section('title', 'Nouveau Compte Utilisateur')
@section('page-title', 'Nouveau Compte Utilisateur')
@section('page-subtitle', 'Créer un accès pour un membre du personnel')

@section('content')

{{-- Back --}}
<a href="{{ route('users.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;transition:color .15s;" onmouseenter="this.style.color='var(--teal-600)'" onmouseleave="this.style.color='var(--muted)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
    Retour aux utilisateurs
</a>

<form action="{{ route('users.store') }}" method="POST">
    @csrf

    <div class="card" style="margin-bottom:16px;overflow:hidden;">

        <div style="padding:20px 24px 18px;background:linear-gradient(135deg,var(--teal-50),rgba(255,255,255,0));border-bottom:1px solid rgba(52,168,140,.1);display:flex;align-items:center;gap:14px;">
            <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--teal-400),var(--teal-600));display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(52,168,140,.3);">
                <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
            </div>
            <div>
                <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Informations du compte</h3>
                <span style="font-size:12px;color:var(--muted);font-weight:500;">Identité, rôle et accès</span>
            </div>
        </div>

        <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:18px;">
            <div class="form-group">
                <label class="form-label">Nom complet</label>
                <input type="text" class="form-control @error('name') input-error @enderror" name="name" value="{{ old('name') }}" placeholder="Ex: Dr. Karim Fassi">
                @error('name')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email <span style="color:#f43f5e;">*</span></label>
                <input type="email" class="form-control @error('email') input-error @enderror" name="email" value="{{ old('email') }}">
                @error('email')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Rôle <span style="color:#f43f5e;">*</span></label>
                <select class="form-control form-select @error('role') input-error @enderror" name="role">
                    <option value="">— Sélectionner —</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                    <option value="medecin" {{ old('role') == 'medecin' ? 'selected' : '' }}>Médecin</option>
                    <option value="infirmier" {{ old('role') == 'infirmier' ? 'selected' : '' }}>Infirmier</option>
                    <option value="secretariat" {{ old('role') == 'secretariat' ? 'selected' : '' }}>Secrétariat</option>
                    <option value="technicien" {{ old('role') == 'technicien' ? 'selected' : '' }}>Technicien</option>
                </select>
                @error('role')<div class="field-error">{{ $message }}</div>@enderror
                <div style="margin-top:6px;font-size:11.5px;color:var(--muted);">Détermine les modules accessibles : Médecin/Infirmier → dossiers médicaux ; Secrétariat/Technicien → facturation.</div>
            </div>
            <div class="form-group">
                <label class="form-label">Lié au membre du personnel</label>
                <select class="form-control form-select @error('staff_id') input-error @enderror" name="staff_id">
                    <option value="">— Aucun (compte autonome) —</option>
                    @foreach($availableStaff as $s)
                        <option value="{{ $s->id }}" {{ old('staff_id') == $s->id ? 'selected' : '' }}>{{ $s->full_name }} @if($s->specialite) — {{ $s->specialite }} @endif</option>
                    @endforeach
                </select>
                @error('staff_id')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Mot de passe <span style="color:#f43f5e;">*</span></label>
                <input type="password" class="form-control @error('password') input-error @enderror" name="password" placeholder="Min. 8 caractères">
                @error('password')<div class="field-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Confirmer le mot de passe <span style="color:#f43f5e;">*</span></label>
                <input type="password" class="form-control" name="password_confirmation" placeholder="Confirmer">
            </div>
        </div>

        <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:flex-end;gap:10px;">
            <a href="{{ route('users.index') }}" class="btn btn-outline">Annuler</a>
            <button type="submit" class="btn btn-primary">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Créer le compte
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
.form-label{font-size:11px;font-weight:700;color:var(--teal-600);letter-spacing:.07em;text-transform:uppercase;margin-bottom:6px;display:flex;align-items:center;gap:5px;}
</style>
@endpush
