@extends('layouts.app')
@section('title', 'Mon Compte — ClinicPro')
@section('page-title', 'Mon Compte')
@section('page-subtitle', 'Gérez votre compte utilisateur — table USERS')

@section('content')
<div class="account-grid">
    {{-- Profile card --}}
    <div class="account-profile-card">
        <div class="account-profile-banner"></div>
        <div class="account-profile-body">
            <div class="account-avatar-wrap">
                <div class="account-avatar">{{ strtoupper(substr(auth()->user()->name,0,2)) }}</div>
            </div>
            <div class="account-name">{{ auth()->user()->name }}</div>
            <div class="account-role">{{ auth()->user()->role ?? 'Administrateur' }}</div>
            <div class="account-stat-row">
                <div class="account-stat-item"><div class="val">{{ $patientsCount }}</div><div class="lbl">Patients</div></div>
                <div class="account-stat-item"><div class="val">{{ $staffCount }}</div><div class="lbl">Staff</div></div>
                <div class="account-stat-item"><div class="val">{{ $queueCount }}</div><div class="lbl">File</div></div>
            </div>
            <div class="account-info-list">
                <div class="account-info-row">
                    <div class="account-info-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg></div>
                    <div class="account-info-text"><div class="lbl">Email</div><div class="val">{{ auth()->user()->email }}</div></div>
                </div>
                <div class="account-info-row">
                    <div class="account-info-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z"/></svg></div>
                    <div class="account-info-text"><div class="lbl">Rôle</div><div class="val">{{ auth()->user()->role ?? 'Administrateur' }}</div></div>
                </div>
                <div class="account-info-row">
                    <div class="account-info-icon"><svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25"/></svg></div>
                    <div class="account-info-text"><div class="lbl">Membre depuis</div><div class="val">{{ auth()->user()->created_at->translatedFormat('F Y') }}</div></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Forms --}}
    <div style="display:flex;flex-direction:column;gap:18px;">

        {{-- Update profile --}}
        <div class="form-card">
            <div class="form-card-header">
                <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);">Informations du compte</h3>
                <p style="font-size:12px;color:var(--soft);margin-top:3px;">Table USERS — champs email et role</p>
            </div>
            <div class="form-card-body">
                <form method="POST" action="{{ route('account.update') }}">
                    @csrf @method('PUT')
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Nom complet</label>
                            <input class="form-input" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Email <span style="font-weight:400;text-transform:none;color:var(--muted)">(unique)</span></label>
                            <input type="email" class="form-input" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                        </div>
                    </div>
                    <div style="margin-top:18px;display:flex;justify-content:flex-end;">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Change password --}}
        <div class="form-card">
            <div class="form-card-header">
                <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);">Changer le mot de passe</h3>
                <p style="font-size:12px;color:var(--soft);margin-top:3px;">Champ password (VARCHAR 255, haché)</p>
            </div>
            <div class="form-card-body">
                <form method="POST" action="{{ route('account.password') }}">
                    @csrf @method('PUT')
                    <div class="form-grid">
                        <div class="form-group full">
                            <label class="form-label">Mot de passe actuel</label>
                            <input type="password" class="form-input" name="current_password" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-input" name="password" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Confirmer</label>
                            <input type="password" class="form-input" name="password_confirmation" required>
                        </div>
                    </div>
                    <div style="margin-top:18px;display:flex;justify-content:flex-end;">
                        <button type="submit" class="btn btn-primary">Changer le mot de passe</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
