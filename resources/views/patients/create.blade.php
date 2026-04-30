@extends('layouts.app')

@section('title', 'Nouveau Patient')
@section('page-title', 'Nouveau Patient')
@section('page-subtitle', 'Enregistrer un nouveau dossier patient')

@section('content')

{{-- Back --}}
<a href="{{ url('/patients') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;transition:color .15s;" onmouseenter="this.style.color='var(--teal-600)'" onmouseleave="this.style.color='var(--muted)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
    Retour aux patients
</a>

{{-- Progress steps --}}
<div style="display:flex;align-items:center;gap:0;margin-bottom:28px;">
    @foreach([['1','Identité','user'],['2','Médical','heart'],['3','Assurance','shield']] as [$n,$label,$icon])
    <div class="step-item" data-step="{{ $n }}" style="display:flex;align-items:center;flex:1;">
        <div style="display:flex;align-items:center;gap:10px;flex:1;position:relative;">
            <div class="step-circle" id="sc-{{ $n }}" style="width:36px;height:36px;border-radius:50%;background:{{ $n=='1' ? 'linear-gradient(135deg,var(--teal-400),var(--teal-600))' : 'rgba(52,168,140,.1)' }};border:2px solid {{ $n=='1' ? 'var(--teal-400)' : 'rgba(52,168,140,.2)' }};display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:{{ $n=='1' ? '#fff' : 'var(--muted)' }};transition:all .3s;flex-shrink:0;z-index:1;">
                {{ $n }}
            </div>
            <div>
                <div style="font-size:12.5px;font-weight:700;color:{{ $n=='1' ? 'var(--teal-800)' : 'var(--muted)' }};transition:color .3s;" id="sl-{{ $n }}">{{ $label }}</div>
            </div>
            @if($n != '3')
            <div style="flex:1;height:2px;background:rgba(52,168,140,.12);margin:0 12px;border-radius:2px;position:relative;overflow:hidden;">
                <div id="sp-{{ $n }}" style="position:absolute;left:0;top:0;height:100%;width:0%;background:linear-gradient(90deg,var(--teal-400),var(--teal-500));border-radius:2px;transition:width .4s ease;"></div>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>

<form action="{{ url('/patients') }}" method="POST" id="patientForm">
@csrf

{{-- Two-column layout --}}
<div style="display:grid;grid-template-columns:1fr 320px;gap:20px;align-items:start;">

    {{-- ── LEFT: Form sections ── --}}
    <div>

        {{-- ═══ SECTION 1: Identity ═══ --}}
        <div class="form-section" id="section-1">
            <div class="card" style="margin-bottom:16px;overflow:hidden;">

                {{-- Section header --}}
                <div style="padding:20px 24px 18px;background:linear-gradient(135deg,var(--teal-50),rgba(255,255,255,0));border-bottom:1px solid rgba(52,168,140,.1);display:flex;align-items:center;gap:14px;">
                    <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--teal-400),var(--teal-600));display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(52,168,140,.3);">
                        <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Identité du patient</h3>
                        <span style="font-size:12px;color:var(--muted);font-weight:500;">Informations personnelles et coordonnées</span>
                    </div>
                    <div style="margin-left:auto;padding:4px 10px;border-radius:20px;background:rgba(52,168,140,.1);font-size:11px;font-weight:700;color:var(--teal-600);">Étape 1/3</div>
                </div>

                <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                    {{-- Avatar color picker --}}
                    <div style="grid-column:1/-1;padding:16px;border-radius:12px;background:rgba(52,168,140,.04);border:1.5px dashed rgba(52,168,140,.2);">
                        <div style="font-size:11px;font-weight:700;color:var(--teal-600);letter-spacing:.08em;text-transform:uppercase;margin-bottom:10px;">Couleur d'avatar</div>
                        <div style="display:flex;gap:8px;align-items:center;">
                            @foreach(['teal'=>'#2e9278','blue'=>'#3b82f6','amber'=>'#f59e0b','rose'=>'#f43f5e','violet'=>'#7c3aed'] as $cname=>$chex)
                            <label style="cursor:pointer;">
                                <input type="radio" name="color" value="{{ $cname }}" {{ $cname=='teal'?'checked':'' }} style="display:none;" onchange="updatePreviewColor('{{ $cname }}','{{ $chex }}')">
                                <div class="color-dot {{ $cname=='teal'?'selected':'' }}" data-color="{{ $cname }}" style="width:32px;height:32px;border-radius:50%;background:{{ $chex }};cursor:pointer;border:3px solid {{ $cname=='teal'?'white':'transparent' }};box-shadow:{{ $cname=='teal'?'0 0 0 2px '.$chex.', 0 3px 8px rgba(0,0,0,.15)':'0 2px 6px rgba(0,0,0,.1)' }};transition:all .2s;display:flex;align-items:center;justify-content:center;">
                                    @if($cname=='teal')
                                    <svg width="14" height="14" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24" class="color-check"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                                    @endif
                                </div>
                            </label>
                            @endforeach
                            <div style="margin-left:8px;font-size:12px;color:var(--muted);">Les initiales seront générées automatiquement</div>
                        </div>
                    </div>

                    {{-- First name --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Prénom <span style="color:#f43f5e;">*</span></label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                            <input type="text" class="form-control pf-input" name="first_name" id="first_name" placeholder="Prénom" oninput="updatePreview()" style="padding-left:36px;">
                        </div>
                    </div>

                    {{-- Last name --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Nom <span style="color:#f43f5e;">*</span></label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                            <input type="text" class="form-control pf-input" name="last_name" id="last_name" placeholder="Nom de famille" oninput="updatePreview()" style="padding-left:36px;">
                        </div>
                    </div>

                    {{-- CIN --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">CIN / N° Dossier <span style="color:#f43f5e;">*</span></label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg>
                            <input type="text" class="form-control text-mono pf-input" name="cin" id="cin_field" placeholder="Ex: A123456" oninput="updatePreview()" style="padding-left:36px;letter-spacing:.05em;">
                        </div>
                    </div>

                    {{-- DOB --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Date de naissance</label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                            <input type="date" class="form-control" name="dob" id="dob_field" oninput="updatePreview()" style="padding-left:36px;">
                        </div>
                    </div>

                    {{-- Gender toggle --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Sexe</label>
                        <input type="hidden" name="gender" id="gender_input" value="">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                            <button type="button" class="gender-btn" id="btn-M" onclick="selectGender('M')" style="padding:10px;border-radius:10px;border:1.5px solid rgba(52,168,140,.2);background:#fff;color:var(--muted);font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:all .2s;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25c-1.242 0-2.25-1.008-2.25-2.25s1.008-2.25 2.25-2.25 2.25 1.008 2.25 2.25-1.008 2.25-2.25 2.25zM12 14.25v5.25m0 0H9.75m2.25 0H14.25M12 5.25a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5z"/></svg>
                                Masculin
                            </button>
                            <button type="button" class="gender-btn" id="btn-F" onclick="selectGender('F')" style="padding:10px;border-radius:10px;border:1.5px solid rgba(52,168,140,.2);background:#fff;color:var(--muted);font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:all .2s;">
                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5zM12 14.25v5.25M9.75 16.5h4.5M12 3v3"/></svg>
                                Féminin
                            </button>
                        </div>
                    </div>

                    {{-- Blood type pills --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Groupe sanguin</label>
                        <input type="hidden" name="blood_type" id="blood_input" value="">
                        <div style="display:flex;flex-wrap:wrap;gap:6px;">
                            @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bt)
                            <button type="button" class="blood-btn" data-bt="{{ $bt }}" onclick="selectBlood('{{ $bt }}')" style="padding:7px 12px;border-radius:8px;border:1.5px solid rgba(52,168,140,.2);background:#fff;color:var(--muted);font-size:12.5px;font-weight:700;cursor:pointer;transition:all .2s;font-variant-numeric:tabular-nums;">
                                {{ $bt }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Phone --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Téléphone</label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.338c0 .907.214 1.764.595 2.524M2.25 6.338C2.25 3.926 4.176 2 6.588 2c.37 0 .742.05 1.109.153m-5.447 4.185a11.28 11.28 0 001.69 4.073m0 0a11.31 11.31 0 006.303 4.476m0 0c.37.103.741.153 1.11.153 2.412 0 4.338-1.926 4.338-4.338 0-.907-.214-1.764-.596-2.524m-11.155 2.233l2.37 2.37m0 0l2.37 2.37m-2.37-2.37l-2.37 2.37"/></svg>
                            <input type="tel" class="form-control pf-input" name="phone" id="phone_field" placeholder="+212 6xx xxx xxx" oninput="updatePreview()" style="padding-left:36px;">
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Email</label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>
                            <input type="email" class="form-control" name="email" placeholder="patient@email.com" style="padding-left:36px;">
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                        <label class="form-label">Adresse</label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                            <input type="text" class="form-control" name="address" placeholder="Adresse complète" style="padding-left:36px;">
                        </div>
                    </div>

                    {{-- City --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Ville</label>
                        <input type="text" class="form-control" name="city" placeholder="Ex: Casablanca">
                    </div>

                    {{-- ZIP --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Code postal</label>
                        <input type="text" class="form-control" name="zip" placeholder="Ex: 20000">
                    </div>
                </div>

                {{-- Section footer nav --}}
                <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:flex-end;">
                    <button type="button" onclick="goToSection(2)" class="btn btn-primary btn-sm">
                        Suivant : Infos médicales
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══ SECTION 2: Medical ═══ --}}
        <div class="form-section" id="section-2" style="display:none;">
            <div class="card" style="margin-bottom:16px;overflow:hidden;">
                <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(239,68,68,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(239,68,68,.1);display:flex;align-items:center;gap:14px;">
                    <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#f87171,#ef4444);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(239,68,68,.3);">
                        <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Informations médicales</h3>
                        <span style="font-size:12px;color:var(--muted);font-weight:500;">Antécédents, allergies et médecin traitant</span>
                    </div>
                    <div style="margin-left:auto;padding:4px 10px;border-radius:20px;background:rgba(239,68,68,.1);font-size:11px;font-weight:700;color:#ef4444;">Étape 2/3</div>
                </div>
                <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                    {{-- Allergies --}}
                    <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                        <label class="form-label">
                            <span style="display:inline-flex;align-items:center;gap:6px;">
                                <span style="display:inline-flex;width:18px;height:18px;border-radius:5px;background:rgba(245,158,11,.15);align-items:center;justify-content:center;">
                                    <svg width="11" height="11" fill="none" stroke="#d97706" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z"/></svg>
                                </span>
                                Allergies connues
                            </span>
                        </label>
                        <div style="position:relative;">
                            <input type="text" class="form-control" name="allergies" placeholder="Ex: Pénicilline, Arachides… (laisser vide si aucune)" style="border-color:rgba(245,158,11,.3);" onfocus="this.style.borderColor='#f59e0b';this.style.boxShadow='0 0 0 3px rgba(245,158,11,.14)'" onblur="this.style.borderColor='rgba(245,158,11,.3)';this.style.boxShadow=''">
                        </div>
                    </div>

                    {{-- History --}}
                    <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                        <label class="form-label">Antécédents médicaux</label>
                        <textarea class="form-control" name="history" rows="4" placeholder="Maladies chroniques, chirurgies antérieures, hospitalisations, traitements en cours…" style="resize:vertical;line-height:1.6;"></textarea>
                    </div>

                    {{-- Doctor --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Médecin traitant</label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5"/></svg>
                            <select class="form-control form-select" name="doctor_id" id="doctor_field" onchange="updatePreview()" style="padding-left:36px;">
                                <option value="">— Sélectionner —</option>
                                <option value="1">Dr. Mehdi Alaoui — Cardiologie</option>
                                <option value="2">Dr. Sara Tazi — Méd. Générale</option>
                                <option value="3">Dr. Karim Fassi — Pédiatrie</option>
                            </select>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Statut du dossier</label>
                        <input type="hidden" name="status" id="status_input" value="actif">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                            <button type="button" id="btn-actif" onclick="selectStatus('actif')" style="padding:10px;border-radius:10px;border:1.5px solid rgba(16,185,129,.4);background:rgba(16,185,129,.08);color:#059669;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all .2s;">
                                <span style="width:8px;height:8px;border-radius:50%;background:#10b981;"></span> Actif
                            </button>
                            <button type="button" id="btn-inactif" onclick="selectStatus('inactif')" style="padding:10px;border-radius:10px;border:1.5px solid rgba(52,168,140,.2);background:#fff;color:var(--muted);font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all .2s;">
                                <span style="width:8px;height:8px;border-radius:50%;background:#94a3b8;"></span> Inactif
                            </button>
                        </div>
                    </div>
                </div>
                <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:space-between;">
                    <button type="button" onclick="goToSection(1)" class="btn btn-outline btn-sm">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                        Précédent
                    </button>
                    <button type="button" onclick="goToSection(3)" class="btn btn-primary btn-sm">
                        Suivant : Assurance
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══ SECTION 3: Insurance ═══ --}}
        <div class="form-section" id="section-3" style="display:none;">
            <div class="card" style="margin-bottom:16px;overflow:hidden;">
                <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(59,130,246,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(59,130,246,.1);display:flex;align-items:center;gap:14px;">
                    <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#60a5fa,#3b82f6);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(59,130,246,.3);">
                        <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Assurance & Urgences</h3>
                        <span style="font-size:12px;color:var(--muted);font-weight:500;">Couverture médicale et contact d'urgence</span>
                    </div>
                    <div style="margin-left:auto;padding:4px 10px;border-radius:20px;background:rgba(59,130,246,.1);font-size:11px;font-weight:700;color:#3b82f6;">Étape 3/3</div>
                </div>
                <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                    {{-- Insurance type pills --}}
                    <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                        <label class="form-label">Type d'assurance</label>
                        <input type="hidden" name="insurance" id="insurance_input" value="">
                        <div style="display:flex;flex-wrap:wrap;gap:8px;">
                            @foreach(['Aucune','CNSS','CNOPS','FAR','Privée','Autre'] as $ins)
                            <button type="button" class="ins-btn" data-ins="{{ $ins }}" onclick="selectInsurance('{{ $ins }}')" style="padding:8px 16px;border-radius:10px;border:1.5px solid rgba(59,130,246,.2);background:#fff;color:var(--muted);font-size:13px;font-weight:600;cursor:pointer;transition:all .2s;">
                                {{ $ins }}
                            </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Insurance number --}}
                    <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                        <label class="form-label">N° police / adhérent</label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5"/></svg>
                            <input type="text" class="form-control" name="insurance_number" placeholder="Numéro d'affiliation" style="padding-left:36px;">
                        </div>
                    </div>

                    {{-- Divider --}}
                    <div style="grid-column:1/-1;display:flex;align-items:center;gap:12px;margin:4px 0;">
                        <div style="flex:1;height:1px;background:rgba(52,168,140,.12);"></div>
                        <span style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;">Contact d'urgence</span>
                        <div style="flex:1;height:1px;background:rgba(52,168,140,.12);"></div>
                    </div>

                    {{-- Emergency name --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Nom complet</label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                            <input type="text" class="form-control" name="emergency_name" placeholder="Nom complet" style="padding-left:36px;">
                        </div>
                    </div>

                    {{-- Emergency phone --}}
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Téléphone d'urgence</label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.338c0 .907.214 1.764.595 2.524"/></svg>
                            <input type="tel" class="form-control" name="emergency_phone" placeholder="+212 6xx xxx xxx" style="padding-left:36px;">
                        </div>
                    </div>

                    {{-- Relation --}}
                    <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                        <label class="form-label">Lien avec le patient</label>
                        <div style="display:flex;flex-wrap:wrap;gap:8px;">
                            @foreach(['Conjoint(e)','Parent','Enfant','Frère / Sœur','Ami(e)','Autre'] as $rel)
                            <button type="button" class="rel-btn" data-rel="{{ $rel }}" onclick="selectRelation('{{ $rel }}')" style="padding:7px 14px;border-radius:8px;border:1.5px solid rgba(52,168,140,.2);background:#fff;color:var(--muted);font-size:12.5px;font-weight:600;cursor:pointer;transition:all .2s;">
                                {{ $rel }}
                            </button>
                            @endforeach
                        </div>
                        <input type="hidden" name="emergency_relation" id="relation_input" value="">
                    </div>
                </div>
                <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:space-between;">
                    <button type="button" onclick="goToSection(2)" class="btn btn-outline btn-sm">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
                        Précédent
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Enregistrer le patient
                    </button>
                </div>
            </div>
        </div>

    </div>{{-- end left --}}

    {{-- ── RIGHT: Sticky preview card ── --}}
    <div style="position:sticky;top:calc(var(--header-h) + 20px);">

        {{-- Live patient card --}}
        <div class="card" style="overflow:hidden;margin-bottom:14px;">
            {{-- Banner --}}
            <div id="preview-banner" style="height:70px;background:linear-gradient(135deg,var(--teal-700),var(--teal-500));position:relative;overflow:hidden;">
                <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 70% 30%,rgba(255,255,255,.12) 0%,transparent 60%);"></div>
                <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);background-size:18px 18px;"></div>
            </div>

            {{-- Avatar --}}
            <div style="padding:0 20px;position:relative;">
                <div id="preview-avatar" style="width:62px;height:62px;border-radius:16px;background:linear-gradient(135deg,var(--teal-300),var(--teal-500));border:4px solid #fff;box-shadow:0 4px 14px rgba(52,168,140,.3);margin-top:-31px;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:800;color:#fff;letter-spacing:-.5px;transition:background .3s;">
                    ?
                </div>
            </div>

            {{-- Info --}}
            <div style="padding:10px 20px 20px;">
                <div id="preview-name" style="font-size:16px;font-weight:800;color:var(--teal-800);margin-bottom:2px;line-height:1.2;">Nouveau patient</div>
                <div id="preview-cin" style="font-size:12px;color:var(--muted);font-family:monospace;margin-bottom:12px;">CIN: —</div>

                {{-- Mini stats --}}
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:14px;">
                    <div style="text-align:center;padding:8px 6px;border-radius:10px;background:var(--teal-50);border:1px solid rgba(52,168,140,.12);">
                        <div id="preview-age" style="font-size:16px;font-weight:800;color:var(--teal-700);">—</div>
                        <div style="font-size:10px;color:var(--muted);font-weight:600;">Âge</div>
                    </div>
                    <div style="text-align:center;padding:8px 6px;border-radius:10px;background:var(--teal-50);border:1px solid rgba(52,168,140,.12);">
                        <div id="preview-blood" style="font-size:16px;font-weight:800;color:var(--teal-700);">—</div>
                        <div style="font-size:10px;color:var(--muted);font-weight:600;">Groupe</div>
                    </div>
                    <div style="text-align:center;padding:8px 6px;border-radius:10px;background:var(--teal-50);border:1px solid rgba(52,168,140,.12);">
                        <div id="preview-gender-icon" style="font-size:15px;font-weight:800;color:var(--teal-700);">—</div>
                        <div style="font-size:10px;color:var(--muted);font-weight:600;">Sexe</div>
                    </div>
                </div>

                {{-- Detail rows --}}
                <div style="display:flex;flex-direction:column;gap:7px;">
                    <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;">
                        <svg width="13" height="13" fill="none" stroke="var(--muted)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.338c0 .907.214 1.764.595 2.524"/></svg>
                        <span id="preview-phone" style="color:var(--muted);">Téléphone non renseigné</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;">
                        <svg width="13" height="13" fill="none" stroke="var(--muted)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5"/></svg>
                        <span id="preview-doctor" style="color:var(--muted);">Médecin non assigné</span>
                    </div>
                    <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;margin-top:4px;">
                        <span id="preview-status-badge" class="badge badge-green">Actif</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Progress summary --}}
        <div class="card" style="padding:16px;">
            <div style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:12px;">Progression du formulaire</div>
            <div style="display:flex;flex-direction:column;gap:8px;">
                @foreach([['1','Identité'],['2','Médical'],['3','Assurance']] as [$n,$label])
                <div style="display:flex;align-items:center;gap:10px;">
                    <div id="prog-dot-{{ $n }}" style="width:20px;height:20px;border-radius:50%;background:{{ $n=='1' ? 'linear-gradient(135deg,var(--teal-400),var(--teal-600))' : 'rgba(52,168,140,.1)' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:10px;font-weight:800;color:{{ $n=='1' ? '#fff' : 'var(--muted)' }};transition:all .3s;">
                        {{ $n }}
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:12.5px;font-weight:600;color:var(--teal-800);">{{ $label }}</div>
                    </div>
                    <div id="prog-check-{{ $n }}" style="display:none;color:#10b981;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </div>{{-- end right --}}

</div>{{-- end grid --}}
</form>

@endsection

@push('styles')
<style>
/* ── Inputs ── */
.form-control {
    width: 100%;
    padding: 11px 14px;
    border: 1.5px solid rgba(52,168,140,.22);
    border-radius: 12px;
    background: #f4faf8;
    color: #133c35;
    font-size: 13.5px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 500;
    outline: none;
    transition: border-color .2s, box-shadow .2s, background .2s;
    line-height: 1.4;
}
.form-control::placeholder { color: #a8c5bd; font-weight: 400; }
.form-control:focus {
    border-color: var(--teal-400);
    box-shadow: 0 0 0 3px rgba(52,168,140,.14);
    background: #fff;
}
.form-control:hover:not(:focus) { border-color: rgba(52,168,140,.4); }

/* icon-prefixed inputs */
.form-control.pf-input { padding-left: 40px; }

/* selects */
select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237bbfb0' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 13px center;
    padding-right: 36px;
    cursor: pointer;
}
select.form-control.pf-input { padding-left: 40px; }

/* textareas */
textarea.form-control { resize: vertical; min-height: 90px; line-height: 1.65; }

/* field-icon SVGs */
.field-icon-wrap { position: relative; }
.field-icon-wrap svg {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: #7bbfb0; pointer-events: none; flex-shrink: 0;
}
.field-icon-wrap textarea ~ svg,
.field-icon-wrap svg.top { top: 14px; transform: none; }

/* form-label */
.form-label {
    font-size: 11px; font-weight: 700;
    color: var(--teal-600);
    letter-spacing: .07em; text-transform: uppercase; margin-bottom: 6px;
    display: flex; align-items: center; gap: 5px;
}

/* toggle buttons */
.gender-btn.active  { border-color:var(--teal-400)!important; background:rgba(52,168,140,.1)!important; color:var(--teal-700)!important; }
.blood-btn.active   { border-color:var(--teal-500)!important; background:var(--teal-500)!important; color:#fff!important; box-shadow:0 2px 8px rgba(52,168,140,.35)!important; }
.ins-btn.active     { border-color:#3b82f6!important; background:rgba(59,130,246,.1)!important; color:#2563eb!important; }
.rel-btn.active     { border-color:var(--teal-400)!important; background:rgba(52,168,140,.1)!important; color:var(--teal-700)!important; }

/* gender / blood / toggle buttons base */
.gender-btn, .blood-btn, .ins-btn, .rel-btn {
    font-family: 'Plus Jakarta Sans', sans-serif;
    transition: all .18s;
}
.gender-btn:hover:not(.active)  { border-color:rgba(52,168,140,.4)!important; background:var(--teal-50)!important; color:var(--teal-600)!important; }
.blood-btn:hover:not(.active)   { border-color:rgba(52,168,140,.4)!important; background:var(--teal-50)!important; color:var(--teal-700)!important; }
.ins-btn:hover:not(.active)     { border-color:rgba(59,130,246,.3)!important; background:rgba(59,130,246,.04)!important; color:#3b82f6!important; }
.rel-btn:hover:not(.active)     { border-color:rgba(52,168,140,.4)!important; background:var(--teal-50)!important; color:var(--teal-600)!important; }
</style>
@endpush

@push('scripts')
<script>
/* ── Section navigation ── */
let currentSection = 1;
const completedSections = new Set();

function goToSection(n) {
    document.getElementById('section-' + currentSection).style.display = 'none';
    completedSections.add(currentSection);
    currentSection = n;
    document.getElementById('section-' + n).style.display = 'block';
    updateSteps();
    window.scrollTo({top: 0, behavior: 'smooth'});
}

function updateSteps() {
    for (let i = 1; i <= 3; i++) {
        const sc = document.getElementById('sc-' + i);
        const sl = document.getElementById('sl-' + i);
        const pd = document.getElementById('prog-dot-' + i);
        const pc = document.getElementById('prog-check-' + i);
        if (completedSections.has(i)) {
            sc.style.background = 'linear-gradient(135deg,#10b981,#059669)';
            sc.style.borderColor = '#10b981';
            sc.style.color = '#fff';
            sc.innerHTML = '<svg width="14" height="14" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';
            sl.style.color = '#059669';
            pd.style.background = 'linear-gradient(135deg,#10b981,#059669)';
            pd.style.color = '#fff';
            pd.innerHTML = '<svg width="10" height="10" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>';
            if (pc) pc.style.display = 'block';
            if (i < 3 && document.getElementById('sp-' + i)) {
                document.getElementById('sp-' + i).style.width = '100%';
            }
        } else if (i === currentSection) {
            sc.style.background = 'linear-gradient(135deg,var(--teal-400),var(--teal-600))';
            sc.style.borderColor = 'var(--teal-400)';
            sc.style.color = '#fff';
            sc.textContent = i;
            sl.style.color = 'var(--teal-800)';
            pd.style.background = 'linear-gradient(135deg,var(--teal-400),var(--teal-600))';
            pd.style.color = '#fff';
            pd.textContent = i;
            if (pc) pc.style.display = 'none';
        } else {
            sc.style.background = 'rgba(52,168,140,.1)';
            sc.style.borderColor = 'rgba(52,168,140,.2)';
            sc.style.color = 'var(--muted)';
            sc.textContent = i;
            sl.style.color = 'var(--muted)';
            pd.style.background = 'rgba(52,168,140,.1)';
            pd.style.color = 'var(--muted)';
            pd.textContent = i;
            if (pc) pc.style.display = 'none';
        }
    }
}

/* ── Live preview ── */
function updatePreview() {
    const fn = document.getElementById('first_name').value.trim();
    const ln = document.getElementById('last_name').value.trim();
    const fullName = [fn, ln].filter(Boolean).join(' ');
    const initials = [fn[0], ln[0]].filter(Boolean).join('').toUpperCase() || '?';

    document.getElementById('preview-name').textContent = fullName || 'Nouveau patient';
    document.getElementById('preview-avatar').textContent = initials;

    const cin = document.getElementById('cin_field').value.trim();
    document.getElementById('preview-cin').textContent = cin ? 'CIN: ' + cin.toUpperCase() : 'CIN: —';

    const dob = document.getElementById('dob_field').value;
    if (dob) {
        const age = Math.floor((new Date() - new Date(dob)) / (365.25 * 24 * 3600 * 1000));
        document.getElementById('preview-age').textContent = age + ' ans';
    } else {
        document.getElementById('preview-age').textContent = '—';
    }

    const phone = document.getElementById('phone_field').value.trim();
    document.getElementById('preview-phone').textContent = phone || 'Téléphone non renseigné';
    document.getElementById('preview-phone').style.color = phone ? 'var(--teal-700)' : 'var(--muted)';

    const doctorSel = document.getElementById('doctor_field');
    const doctorText = doctorSel.options[doctorSel.selectedIndex]?.text || '';
    document.getElementById('preview-doctor').textContent = doctorText.includes('—') ? 'Médecin non assigné' : doctorText.split('—')[0].trim();
    document.getElementById('preview-doctor').style.color = doctorText.includes('—') && !doctorText.startsWith('—') ? 'var(--teal-700)' : 'var(--muted)';
}

/* ── Gender ── */
function selectGender(g) {
    document.getElementById('gender_input').value = g;
    document.getElementById('btn-M').classList.toggle('active', g === 'M');
    document.getElementById('btn-F').classList.toggle('active', g === 'F');
    document.getElementById('preview-gender-icon').textContent = g === 'M' ? '♂' : '♀';
}

/* ── Blood type ── */
function selectBlood(bt) {
    document.getElementById('blood_input').value = bt;
    document.querySelectorAll('.blood-btn').forEach(b => b.classList.toggle('active', b.dataset.bt === bt));
    document.getElementById('preview-blood').textContent = bt;
}

/* ── Status ── */
function selectStatus(s) {
    document.getElementById('status_input').value = s;
    const isActive = s === 'actif';
    document.getElementById('btn-actif').style.borderColor   = isActive ? 'rgba(16,185,129,.4)' : 'rgba(52,168,140,.2)';
    document.getElementById('btn-actif').style.background    = isActive ? 'rgba(16,185,129,.08)' : '#fff';
    document.getElementById('btn-actif').style.color         = isActive ? '#059669' : 'var(--muted)';
    document.getElementById('btn-inactif').style.borderColor = !isActive ? 'rgba(100,116,139,.3)' : 'rgba(52,168,140,.2)';
    document.getElementById('btn-inactif').style.background  = !isActive ? 'rgba(100,116,139,.08)' : '#fff';
    document.getElementById('btn-inactif').style.color       = !isActive ? '#475569' : 'var(--muted)';
    const badge = document.getElementById('preview-status-badge');
    badge.className = 'badge ' + (isActive ? 'badge-green' : 'badge-gray');
    badge.textContent = isActive ? 'Actif' : 'Inactif';
}

/* ── Avatar color ── */
const colorGrads = {
    teal:   'linear-gradient(135deg,var(--teal-300),var(--teal-500))',
    blue:   'linear-gradient(135deg,#60a5fa,#3b82f6)',
    amber:  'linear-gradient(135deg,#fbbf24,#f59e0b)',
    rose:   'linear-gradient(135deg,#fb7185,#f43f5e)',
    violet: 'linear-gradient(135deg,#a78bfa,#7c3aed)',
};
const colorBanners = {
    teal:   'linear-gradient(135deg,var(--teal-700),var(--teal-500))',
    blue:   'linear-gradient(135deg,#2563eb,#3b82f6)',
    amber:  'linear-gradient(135deg,#d97706,#f59e0b)',
    rose:   'linear-gradient(135deg,#e11d48,#f43f5e)',
    violet: 'linear-gradient(135deg,#5b21b6,#7c3aed)',
};
const colorHex = { teal:'#2e9278', blue:'#3b82f6', amber:'#f59e0b', rose:'#f43f5e', violet:'#7c3aed' };

function updatePreviewColor(cname, chex) {
    document.getElementById('preview-avatar').style.background = colorGrads[cname];
    document.getElementById('preview-avatar').style.boxShadow  = '0 4px 14px ' + chex + '55';
    document.getElementById('preview-banner').style.background = colorBanners[cname];
    document.querySelectorAll('.color-dot').forEach(d => {
        const active = d.dataset.color === cname;
        d.style.border = active ? '3px solid white' : '3px solid transparent';
        d.style.boxShadow = active
            ? '0 0 0 2px ' + colorHex[d.dataset.color] + ', 0 3px 8px rgba(0,0,0,.15)'
            : '0 2px 6px rgba(0,0,0,.1)';
        d.innerHTML = active ? '<svg width="14" height="14" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>' : '';
    });
}

/* ── Insurance ── */
function selectInsurance(ins) {
    document.getElementById('insurance_input').value = ins;
    document.querySelectorAll('.ins-btn').forEach(b => b.classList.toggle('active', b.dataset.ins === ins));
}

/* ── Relation ── */
function selectRelation(rel) {
    document.getElementById('relation_input').value = rel;
    document.querySelectorAll('.rel-btn').forEach(b => b.classList.toggle('active', b.dataset.rel === rel));
}
</script>
@endpush
