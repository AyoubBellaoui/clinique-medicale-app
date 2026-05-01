@extends('layouts.app')

@section('title', 'Nouveau Membre du Personnel')
@section('page-title', 'Nouveau Membre')
@section('page-subtitle', 'Ajouter un médecin, infirmier ou administratif')

@section('content')

{{-- Back --}}
<a href="{{ url('/staff') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;transition:color .15s;" onmouseenter="this.style.color='var(--teal-600)'" onmouseleave="this.style.color='var(--muted)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
    </svg>
    Retour au personnel
</a>

{{-- Progress steps --}}
<div style="display:flex;align-items:center;gap:0;margin-bottom:28px;">
    @foreach([['1','Identité','user'],['2','Rôle & Formation','briefcase'],['3','Contrat','file-text']] as [$n,$label,$icon])
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

<!--------------------------------------------------- Staff Form --------------------------------------------------->
<form action="{{ url('/staff') }}" method="POST" id="staffForm">
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
                            <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                            </svg>
                        </div>
                        <div>
                            <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Identité du membre</h3>
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
                                        <svg width="14" height="14" fill="none" stroke="white" stroke-width="3" viewBox="0 0 24 24" class="color-check">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                        </svg>
                                        @endif
                                    </div>
                                </label>
                                @endforeach
                                <div style="margin-left:8px;font-size:12px;color:var(--muted);">Les initiales seront générées automatiquement</div>
                            </div>
                        </div>

                        {{-- CIN --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">CIN</label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                </svg>
                                <input type="text" class="form-control pf-input" name="cin" placeholder="CIN" style="padding-left:36px;">
                            </div>
                        </div>

                        {{-- First name --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Prénom <span style="color:#f43f5e;">*</span></label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                </svg>
                                <input type="text" class="form-control pf-input" name="prenom" id="first_name" placeholder="Prénom" oninput="updatePreview()" style="padding-left:36px;">
                            </div>
                        </div>

                        {{-- Last name --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Nom <span style="color:#f43f5e;">*</span></label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                </svg>
                                <input type="text" class="form-control pf-input" name="nom" id="last_name" placeholder="Nom de famille" oninput="updatePreview()" style="padding-left:36px;">
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Email professionnel <span style="color:#f43f5e;">*</span></label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                                <input type="email" class="form-control pf-input" name="email" id="email_field" placeholder="prenom.nom@clinique.ma" oninput="updatePreview()" style="padding-left:36px;">
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Téléphone</label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.338c0 .907.214 1.764.595 2.524M2.25 6.338C2.25 3.926 4.176 2 6.588 2c.37 0 .742.05 1.109.153m-5.447 4.185a11.28 11.28 0 001.69 4.073m0 0a11.31 11.31 0 006.303 4.476m0 0c.37.103.741.153 1.11.153 2.412 0 4.338-1.926 4.338-4.338 0-.907-.214-1.764-.596-2.524m-11.155 2.233l2.37 2.37m0 0l2.37 2.37m-2.37-2.37l-2.37 2.37" />
                                </svg>
                                <input type="tel" class="form-control pf-input" name="telephone" id="phone_field" placeholder="+212 6xx xxx xxx" oninput="updatePreview()" style="padding-left:36px;">
                            </div>
                        </div>

                        {{-- DOB --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Date de naissance</label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <input type="date" class="form-control pf-input" name="date_naissance" id="dob_field" oninput="updatePreview()" style="padding-left:36px;">
                            </div>
                        </div>

                        {{-- Gender toggle --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Sexe</label>
                            <input type="hidden" name="gender" id="gender_input" value="">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                                <button type="button" class="gender-btn" id="btn-M" onclick="selectGender('M')" style="padding:10px;border-radius:10px;border:1.5px solid rgba(52,168,140,.2);background:#fff;color:var(--muted);font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:all .2s;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 14.25c-1.242 0-2.25-1.008-2.25-2.25s1.008-2.25 2.25-2.25 2.25 1.008 2.25 2.25-1.008 2.25-2.25 2.25zM12 14.25v5.25m0 0H9.75m2.25 0H14.25M12 5.25a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5z" />
                                    </svg>
                                    Masculin
                                </button>
                                <button type="button" class="gender-btn" id="btn-F" onclick="selectGender('F')" style="padding:10px;border-radius:10px;border:1.5px solid rgba(52,168,140,.2);background:#fff;color:var(--muted);font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:7px;transition:all .2s;">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5zM12 14.25v5.25M9.75 16.5h4.5M12 3v3" />
                                    </svg>
                                    Féminin
                                </button>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                            <label class="form-label">Adresse</label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                </svg>
                                <input type="text" class="form-control pf-input" name="adresse" placeholder="Adresse complète" style="padding-left:36px;">
                            </div>
                        </div>
                    </div>

                    {{-- Section footer nav --}}
                    <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:flex-end;">
                        <button type="button" onclick="goToSection(2)" class="btn btn-primary btn-sm">
                            Suivant : Rôle & Formation
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ═══ SECTION 2: Role & Formation ═══ --}}
            <div class="form-section" id="section-2" style="display:none;">
                <div class="card" style="margin-bottom:16px;overflow:hidden;">

                    {{-- Section header --}}
                    <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(99,102,241,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(99,102,241,.1);display:flex;align-items:center;gap:14px;">
                        <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#818cf8,#6366f1);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(99,102,241,.3);">
                            <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5" />
                            </svg>
                        </div>
                        <div>
                            <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Rôle & Formation</h3>
                            <span style="font-size:12px;color:var(--muted);font-weight:500;">Spécialité, diplômes et qualifications</span>
                        </div>
                        <div style="margin-left:auto;padding:4px 10px;border-radius:20px;background:rgba(99,102,241,.1);font-size:11px;font-weight:700;color:#6366f1;">Étape 2/3</div>
                    </div>

                    <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                        {{-- Role pills --}}
                        <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                            <label class="form-label">Rôle <span style="color:#f43f5e;">*</span></label>

                            <input type="hidden" name="role" id="role_input" value="">

                            <div style="display:flex;flex-wrap:wrap;gap:8px;">

                                @foreach([
                                'medecin' => ['Médecin', '#6366f1'],
                                'infirmier' => ['Infirmier·ière', '#0ea5e9'],
                                'secretaire' => ['Secrétariat', '#ec4899'],
                                'admin' => ['Administratif', '#f59e0b'],
                                'technicien' => ['Technicien lab.', '#10b981'],
                                ] as $val => [$label, $color])

                                <button type="button"
                                    class="role-btn"
                                    data-role="{{ $val }}"
                                    data-color="{{ $color }}"
                                    onclick="selectRole('{{ $val }}','{{ $color }}','{{ $label }}')"
                                    style="padding:9px 16px;border-radius:10px;border:1.5px solid rgba(52,168,140,.2);
                                    background:#fff;color:var(--muted);font-size:13px;font-weight:600;
                                    cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:7px;">

                                    <span class="role-dot"
                                        style="width:8px;height:8px;border-radius:50%;
                                        background:{{ $color }};opacity:.4;transition:opacity .2s;">
                                    </span>

                                    {{ $label }}
                                </button>

                                @endforeach

                            </div>
                        </div>

                        {{-- Specialty --}}
                        <div class="form-group" style="margin-bottom:0;" id="specialty-wrap">
                            <label class="form-label">Spécialité</label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714a2.25 2.25 0 001.357 2.059l.537.268a1.5 1.5 0 01.423 2.371L12 20.25" />
                                </svg>
                                <select class="form-control form-select pf-input" name="specialite" id="specialty_field" onchange="updatePreview()" style="padding-left:36px;">
                                    <option value="">— Choisir une spécialité —</option>
                                    <option>Cardiologie</option>
                                    <option>Médecine Générale</option>
                                    <option>Pédiatrie</option>
                                    <option>Gynécologie</option>
                                    <option>Dermatologie</option>
                                    <option>Ophtalmologie</option>
                                    <option>Neurologie</option>
                                    <option>Orthopédie</option>
                                    <option>Urgences</option>
                                    <option>Anesthésie</option>
                                    <option>Autre</option>
                                </select>
                            </div>
                        </div>

                        {{-- License number --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">N° d'ordre professionnel</label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15m-16.5 7.5h15m-1.8-13.5l-3.9 19.5m-2.1-19.5l-3.9 19.5" />
                                </svg>
                                <input type="text" class="form-control pf-input" name="license_number" id="license_field" placeholder="Numéro d'inscription" oninput="updatePreview()" style="padding-left:36px;letter-spacing:.04em;">
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div style="grid-column:1/-1;display:flex;align-items:center;gap:12px;margin:4px 0;">
                            <div style="flex:1;height:1px;background:rgba(52,168,140,.12);"></div>
                            <span style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;">Formation académique</span>
                            <div style="flex:1;height:1px;background:rgba(52,168,140,.12);"></div>
                        </div>

                        {{-- Degree --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Diplôme / Formation</label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84" />
                                </svg>
                                <input type="text" class="form-control pf-input" name="degree" placeholder="Ex: Doctorat en Médecine" style="padding-left:36px;">
                            </div>
                        </div>

                        {{-- School --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Établissement de formation</label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" />
                                </svg>
                                <input type="text" class="form-control pf-input" name="school" placeholder="Ex: Faculté de Médecine de Casablanca" style="padding-left:36px;">
                            </div>
                        </div>

                        {{-- Grad year --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Année d'obtention</label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25" />
                                </svg>
                                <input type="number" class="form-control pf-input" name="grad_year" placeholder="Ex: 2015" min="1970" max="2030" style="padding-left:36px;">
                            </div>
                        </div>

                        {{-- Languages --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Langues parlées</label>
                            <input type="hidden" name="languages" id="lang_input" value="">
                            <div style="display:flex;flex-wrap:wrap;gap:6px;">
                                @foreach(['Arabe','Français','Anglais','Amazigh','Espagnol'] as $lang)
                                <button type="button" class="lang-btn" data-lang="{{ $lang }}" onclick="toggleLang('{{ $lang }}')"
                                    style="padding:7px 12px;border-radius:8px;border:1.5px solid rgba(99,102,241,.2);background:#fff;color:var(--muted);font-size:12.5px;font-weight:600;cursor:pointer;transition:all .2s;">
                                    {{ $lang }}
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:space-between;">
                        <button type="button" onclick="goToSection(1)" class="btn btn-outline btn-sm">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                            Précédent
                        </button>
                        <button type="button" onclick="goToSection(3)" class="btn btn-primary btn-sm">
                            Suivant : Contrat
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ═══ SECTION 3: Contract ═══ --}}
            <div class="form-section" id="section-3" style="display:none;">
                <div class="card" style="margin-bottom:16px;overflow:hidden;">

                    {{-- Section header --}}
                    <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(245,158,11,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(245,158,11,.1);display:flex;align-items:center;gap:14px;">
                        <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#fbbf24,#f59e0b);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(245,158,11,.3);">
                            <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                            </svg>
                        </div>
                        <div>
                            <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Contrat & Rémunération</h3>
                            <span style="font-size:12px;color:var(--muted);font-weight:500;">Type de contrat, salaire et horaires</span>
                        </div>
                        <div style="margin-left:auto;padding:4px 10px;border-radius:20px;background:rgba(245,158,11,.1);font-size:11px;font-weight:700;color:#d97706;">Étape 3/3</div>
                    </div>

                    <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                        {{-- Contract type pills --}}
                        <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                            <label class="form-label">Type de contrat <span style="color:#f43f5e;">*</span></label>
                            <input type="hidden" name="contract_type" id="contract_input" value="">
                            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                @foreach(['CDI'=>'Indéterminé','CDD'=>'Déterminé','Vacation'=>'Vacation','Libéral'=>'Libéral'] as $val=>$desc)
                                <button type="button" class="contract-btn" data-contract="{{ $val }}" onclick="selectContract('{{ $val }}')"
                                    style="padding:10px 18px;border-radius:10px;border:1.5px solid rgba(245,158,11,.2);background:#fff;color:var(--muted);font-size:13px;font-weight:700;cursor:pointer;transition:all .2s;text-align:left;">
                                    <div style="font-size:13px;font-weight:700;">{{ $val }}</div>
                                    <div style="font-size:10.5px;font-weight:500;opacity:.7;">{{ $desc }}</div>
                                </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Hire date --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Date d'embauche <span style="color:#f43f5e;">*</span></label>
                            <div style="position:relative;">
                                <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5" />
                                </svg>
                                <input type="date" class="form-control pf-input" name="date_embauche" id="hire_date_field" oninput="updatePreview()" style="padding-left:36px;">
                            </div>
                        </div>

                        {{-- Salary --}}
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Salaire mensuel (MAD)</label>
                            <div style="position:relative;">
                                <span style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:13px;font-weight:700;pointer-events:none;">د.م</span>
                                <input type="number" class="form-control" name="salaire" placeholder="Ex: 15 000" min="0" style="padding-left:40px;">
                            </div>
                        </div>

                        {{-- Schedule --}}
                        <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                            <label class="form-label">Horaires de travail</label>
                            <input type="hidden" name="schedule" id="schedule_input" value="">
                            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                                @foreach(['Lun–Ven 08:00–17:00','Lun–Sam 08:00–14:00','Temps partiel','Gardes / Nuits','Flexible'] as $sched)
                                <button type="button" class="sched-btn" data-sched="{{ $sched }}" onclick="selectSchedule('{{ $sched }}')"
                                    style="padding:8px 14px;border-radius:10px;border:1.5px solid rgba(245,158,11,.2);background:#fff;color:var(--muted);font-size:12.5px;font-weight:600;cursor:pointer;transition:all .2s;">
                                    {{ $sched }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                            <label class="form-label">Statut</label>
                            <input type="hidden" name="status" id="status_input" value="actif">
                            <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;">
                                <button type="button" id="btn-actif" onclick="selectStatus('actif')" style="padding:10px;border-radius:10px;border:1.5px solid rgba(16,185,129,.4);background:rgba(16,185,129,.08);color:#059669;font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all .2s;">
                                    <span style="width:8px;height:8px;border-radius:50%;background:#10b981;"></span> Actif
                                </button>
                                <button type="button" id="btn-conge" onclick="selectStatus('conge')" style="padding:10px;border-radius:10px;border:1.5px solid rgba(245,158,11,.2);background:#fff;color:var(--muted);font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all .2s;">
                                    <span style="width:8px;height:8px;border-radius:50%;background:#f59e0b;"></span> En congé
                                </button>
                                <button type="button" id="btn-inactif" onclick="selectStatus('inactif')" style="padding:10px;border-radius:10px;border:1.5px solid rgba(52,168,140,.2);background:#fff;color:var(--muted);font-size:13px;font-weight:600;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:6px;transition:all .2s;">
                                    <span style="width:8px;height:8px;border-radius:50%;background:#94a3b8;"></span> Inactif
                                </button>
                            </div>
                        </div>

                        {{-- Notes --}}
                        <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                            <label class="form-label">Notes internes</label>
                            <textarea class="form-control" name="notes" rows="3" placeholder="Informations complémentaires sur le membre du personnel…" style="resize:vertical;line-height:1.6;"></textarea>
                        </div>
                    </div>

                    <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:space-between;">
                        <button type="button" onclick="goToSection(2)" class="btn btn-outline btn-sm">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                            </svg>
                            Précédent
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Ajouter le membre
                        </button>
                    </div>
                </div>
            </div>

        </div>{{-- end left --}}

        {{-- ── RIGHT: Sticky preview card ── --}}
        <div style="position:sticky;top:calc(var(--header-h) + 20px);">

            {{-- Live staff card --}}
            <div class="card" style="overflow:hidden;margin-bottom:14px;">
                {{-- Banner --}}
                <div id="preview-banner" style="height:70px;background:linear-gradient(135deg,var(--teal-700),var(--teal-500));position:relative;overflow:hidden;">
                    <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 70% 30%,rgba(255,255,255,.12) 0%,transparent 60%);"></div>
                    <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);background-size:18px 18px;"></div>
                    {{-- Role badge on banner --}}
                    <div id="preview-role-badge-wrap" style="position:absolute;top:10px;right:12px;display:none;">
                        <span id="preview-role-badge" style="padding:3px 10px;border-radius:20px;background:rgba(255,255,255,.22);backdrop-filter:blur(4px);font-size:11px;font-weight:700;color:#fff;letter-spacing:.04em;"></span>
                    </div>
                </div>

                {{-- Avatar --}}
                <div style="padding:0 20px;position:relative;">
                    <div id="preview-avatar" style="width:62px;height:62px;border-radius:16px;background:linear-gradient(135deg,var(--teal-300),var(--teal-500));border:4px solid #fff;box-shadow:0 4px 14px rgba(52,168,140,.3);margin-top:-31px;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:800;color:#fff;letter-spacing:-.5px;transition:background .3s;">
                        ?
                    </div>
                </div>

                {{-- Info --}}
                <div style="padding:10px 20px 20px;">
                    <div id="preview-name" style="font-size:16px;font-weight:800;color:var(--teal-800);margin-bottom:2px;line-height:1.2;">Nouveau membre</div>
                    <div id="preview-specialty" style="font-size:12px;color:var(--muted);font-weight:500;margin-bottom:12px;">Rôle non défini</div>

                    {{-- Mini stats --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-bottom:14px;">
                        <div style="text-align:center;padding:8px 6px;border-radius:10px;background:var(--teal-50);border:1px solid rgba(52,168,140,.12);">
                            <div id="preview-age" style="font-size:15px;font-weight:800;color:var(--teal-700);">—</div>
                            <div style="font-size:10px;color:var(--muted);font-weight:600;">Âge</div>
                        </div>
                        <div style="text-align:center;padding:8px 6px;border-radius:10px;background:var(--teal-50);border:1px solid rgba(52,168,140,.12);">
                            <div id="preview-gender-icon" style="font-size:15px;font-weight:800;color:var(--teal-700);">—</div>
                            <div style="font-size:10px;color:var(--muted);font-weight:600;">Sexe</div>
                        </div>
                        <div style="text-align:center;padding:8px 6px;border-radius:10px;background:var(--teal-50);border:1px solid rgba(52,168,140,.12);">
                            <div id="preview-contract" style="font-size:13px;font-weight:800;color:var(--teal-700);">—</div>
                            <div style="font-size:10px;color:var(--muted);font-weight:600;">Contrat</div>
                        </div>
                    </div>

                    {{-- Detail rows --}}
                    <div style="display:flex;flex-direction:column;gap:7px;">
                        <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;">
                            <svg width="13" height="13" fill="none" stroke="var(--muted)" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                            </svg>
                            <span id="preview-email" style="color:var(--muted);">Email non renseigné</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;">
                            <svg width="13" height="13" fill="none" stroke="var(--muted)" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.338c0 .907.214 1.764.595 2.524" />
                            </svg>
                            <span id="preview-phone" style="color:var(--muted);">Téléphone non renseigné</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;">
                            <svg width="13" height="13" fill="none" stroke="var(--muted)" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 8.25h15" />
                            </svg>
                            <span id="preview-license" style="color:var(--muted);">N° ordre non renseigné</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;margin-top:4px;">
                            <span id="preview-status-badge" class="badge badge-green">Actif</span>
                            <span id="preview-hire" style="font-size:11.5px;color:var(--muted);"></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Progress summary --}}
            <div class="card" style="padding:16px;">
                <div style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:12px;">Progression du formulaire</div>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    @foreach([['1','Identité'],['2','Rôle & Formation'],['3','Contrat']] as [$n,$label])
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div id="prog-dot-{{ $n }}" style="width:20px;height:20px;border-radius:50%;background:{{ $n=='1' ? 'linear-gradient(135deg,var(--teal-400),var(--teal-600))' : 'rgba(52,168,140,.1)' }};display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:10px;font-weight:800;color:{{ $n=='1' ? '#fff' : 'var(--muted)' }};transition:all .3s;">
                            {{ $n }}
                        </div>
                        <div style="flex:1;">
                            <div style="font-size:12.5px;font-weight:600;color:var(--teal-800);">{{ $label }}</div>
                        </div>
                        <div id="prog-check-{{ $n }}" style="display:none;color:#10b981;">
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>{{-- end right --}}

    </div>{{-- end grid --}}
</form>

@endsection

<!----------------------------------------------------------------------------------------------------------------------->

@push('styles')
<style>
    /* ── Inputs (same as patient form) ── */
    .form-control {
        width: 100%;
        padding: 11px 14px;
        border: 1.5px solid rgba(52, 168, 140, .22);
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

    .form-control::placeholder {
        color: #a8c5bd;
        font-weight: 400;
    }

    .form-control:focus {
        border-color: var(--teal-400);
        box-shadow: 0 0 0 3px rgba(52, 168, 140, .14);
        background: #fff;
    }

    .form-control:hover:not(:focus) {
        border-color: rgba(52, 168, 140, .4);
    }

    .form-control.pf-input {
        padding-left: 40px;
    }

    select.form-control {
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237bbfb0' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 13px center;
        padding-right: 36px;
        cursor: pointer;
    }

    select.form-control.pf-input {
        padding-left: 40px;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
        line-height: 1.65;
    }

    .form-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--teal-600);
        letter-spacing: .07em;
        text-transform: uppercase;
        margin-bottom: 6px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* ── Toggle buttons ── */
    .gender-btn,
    .role-btn,
    .contract-btn,
    .sched-btn,
    .lang-btn {
        font-family: 'Plus Jakarta Sans', sans-serif;
        transition: all .18s;
    }

    .gender-btn.active {
        border-color: var(--teal-400) !important;
        background: rgba(52, 168, 140, .1) !important;
        color: var(--teal-700) !important;
    }

    .role-btn.active {
        border-color: var(--role-color, var(--teal-400)) !important;
        background: rgba(99, 102, 241, .1) !important;
        color: var(--role-color, var(--teal-700)) !important;
    }

    .contract-btn.active {
        border-color: #f59e0b !important;
        background: rgba(245, 158, 11, .08) !important;
        color: #d97706 !important;
    }

    .sched-btn.active {
        border-color: #f59e0b !important;
        background: rgba(245, 158, 11, .08) !important;
        color: #d97706 !important;
    }

    .lang-btn.active {
        border-color: #6366f1 !important;
        background: rgba(99, 102, 241, .1) !important;
        color: #6366f1 !important;
    }

    .gender-btn:hover:not(.active) {
        border-color: rgba(52, 168, 140, .4) !important;
        background: var(--teal-50) !important;
        color: var(--teal-600) !important;
    }

    .role-btn:hover:not(.active) {
        border-color: rgba(99, 102, 241, .35) !important;
        background: rgba(99, 102, 241, .05) !important;
        color: #6366f1 !important;
    }

    .contract-btn:hover:not(.active) {
        border-color: rgba(245, 158, 11, .4) !important;
        background: rgba(245, 158, 11, .05) !important;
        color: #d97706 !important;
    }

    .sched-btn:hover:not(.active) {
        border-color: rgba(245, 158, 11, .4) !important;
        background: rgba(245, 158, 11, .05) !important;
        color: #d97706 !important;
    }

    .lang-btn:hover:not(.active) {
        border-color: rgba(99, 102, 241, .3) !important;
        background: rgba(99, 102, 241, .04) !important;
        color: #6366f1 !important;
    }
</style>
@endpush

@push('scripts')
<script>
/* ─────────────────────────────
   SECTION NAVIGATION
──────────────────────────── */
let currentSection = 1;
const completedSections = new Set();

function goToSection(n) {
    document.getElementById('section-' + currentSection).style.display = 'none';
    completedSections.add(currentSection);

    currentSection = n;

    document.getElementById('section-' + n).style.display = 'block';
    updateSteps();

    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateSteps() {
    for (let i = 1; i <= 3; i++) {
        const sc = document.getElementById('sc-' + i);
        const sl = document.getElementById('sl-' + i);
        const pd = document.getElementById('prog-dot-' + i);
        const pc = document.getElementById('prog-check-' + i);

        const done = completedSections.has(i);
        const current = i === currentSection;

        if (done) {
            sc.style.background = '#10b981';
            sc.style.color = '#fff';
            sc.textContent = '✓';

            sl.style.color = '#059669';
            pd.style.background = '#10b981';
            pd.textContent = '✓';

            if (pc) pc.style.display = 'block';

        } else if (current) {
            sc.style.background = '#0ea5e9';
            sc.style.color = '#fff';
            sc.textContent = i;

            sl.style.color = '#0f766e';
            pd.style.background = '#0ea5e9';
            pd.textContent = i;

            if (pc) pc.style.display = 'none';

        } else {
            sc.style.background = '#e2e8f0';
            sc.style.color = '#64748b';
            sc.textContent = i;

            sl.style.color = '#64748b';
            pd.style.background = '#e2e8f0';
            pd.textContent = i;

            if (pc) pc.style.display = 'none';
        }
    }
}

/* ─────────────────────────────
   LIVE PREVIEW
──────────────────────────── */
function updatePreview() {
    const fn = document.getElementById('first_name')?.value.trim() || '';
    const ln = document.getElementById('last_name')?.value.trim() || '';

    const full = (fn + ' ' + ln).trim();
    const initials = (fn[0] || '') + (ln[0] || '');

    document.getElementById('preview-name').textContent = full || 'Nouveau membre';
    document.getElementById('preview-avatar').textContent = initials.toUpperCase() || '?';

    const dob = document.getElementById('dob_field')?.value;
    if (dob) {
        const age = Math.floor((new Date() - new Date(dob)) / 31557600000);
        document.getElementById('preview-age').textContent = age + ' ans';
    }

    const email = document.getElementById('email_field')?.value;
    document.getElementById('preview-email').textContent = email || 'Email non renseigné';

    const phone = document.getElementById('phone_field')?.value;
    document.getElementById('preview-phone').textContent = phone || 'Téléphone non renseigné';

    const specSel = document.getElementById('specialty_field');
    if (specSel?.value) {
        document.getElementById('preview-specialty').textContent =
            specSel.options[specSel.selectedIndex].text;
    }

    const lic = document.getElementById('license_field')?.value;
    document.getElementById('preview-license').textContent =
        lic ? 'N° ' + lic : 'N° ordre non renseigné';
}

/* ─────────────────────────────
   GENDER
──────────────────────────── */
function selectGender(g) {
    document.getElementById('gender_input').value = g;

    document.getElementById('btn-M')?.classList.toggle('active', g === 'M');
    document.getElementById('btn-F')?.classList.toggle('active', g === 'F');

    document.getElementById('preview-gender-icon').textContent = g === 'M' ? '♂' : '♀';
}

/* ─────────────────────────────
   ROLE (FIXED SPECIALTY LOGIC)
──────────────────────────── */
const roleBanners = {
    medecin: 'linear-gradient(135deg,#4f46e5,#6366f1)',
    infirmier: 'linear-gradient(135deg,#0284c7,#0ea5e9)',
    admin: 'linear-gradient(135deg,#d97706,#f59e0b)',
    technicien: 'linear-gradient(135deg,#059669,#10b981)',
    secretaire: 'linear-gradient(135deg,#ec4899,#f472b6)'
};

function selectRole(val, color, label) {
    document.getElementById('role_input').value = val;

    document.querySelectorAll('.role-btn').forEach(b => {
        const active = b.dataset.role === val;

        b.classList.toggle('active', active);

        const dot = b.querySelector('.role-dot');
        if (dot) dot.style.opacity = active ? '1' : '0.3';

        if (active) {
            b.style.borderColor = color;
            b.style.color = color;
            b.style.background = color + '18';
        } else {
            b.style.borderColor = '';
            b.style.color = '';
            b.style.background = '';
        }
    });

    // banner
    const banner = document.getElementById('preview-banner');
    if (banner && roleBanners[val]) {
        banner.style.background = roleBanners[val];
    }

    // badge
    const wrap = document.getElementById('preview-role-badge-wrap');
    const badge = document.getElementById('preview-role-badge');

    if (wrap && badge) {
        badge.textContent = label;
        wrap.style.display = 'block';
    }

    /* ── SPECIALTY FIX (IMPORTANT) ── */
    const specWrap = document.getElementById('specialty-wrap');
    const specSelect = document.getElementById('specialty_field');

    const allowSpecialty = (val === 'medecin' || val === 'infirmier');

    if (specWrap && specSelect) {
        specWrap.style.opacity = allowSpecialty ? '1' : '0.4';
        specSelect.disabled = !allowSpecialty;

        if (!allowSpecialty) {
            specSelect.selectedIndex = 0;
        }
    }

    const previewSpec = document.getElementById('preview-specialty');
    if (previewSpec) {
        previewSpec.textContent = allowSpecialty && specSelect?.value
            ? specSelect.options[specSelect.selectedIndex].text
            : label;
    }
}

/* ─────────────────────────────
   CONTRACT
──────────────────────────── */
function selectContract(val) {
    document.getElementById('contract_input').value = val;

    document.querySelectorAll('.contract-btn').forEach(b =>
        b.classList.toggle('active', b.dataset.contract === val)
    );

    document.getElementById('preview-contract').textContent = val;
}

/* ─────────────────────────────
   SCHEDULE
──────────────────────────── */
function selectSchedule(val) {
    document.getElementById('schedule_input').value = val;

    document.querySelectorAll('.sched-btn').forEach(b =>
        b.classList.toggle('active', b.dataset.sched === val)
    );
}

/* ─────────────────────────────
   LANGUAGES
──────────────────────────── */
const selectedLangs = new Set();

function toggleLang(lang) {
    selectedLangs.has(lang)
        ? selectedLangs.delete(lang)
        : selectedLangs.add(lang);

    document.getElementById('lang_input').value = [...selectedLangs].join(',');

    document.querySelectorAll('.lang-btn').forEach(b =>
        b.classList.toggle('active', selectedLangs.has(b.dataset.lang))
    );
}

/* ─────────────────────────────
   STATUS
──────────────────────────── */
function selectStatus(s) {
    document.getElementById('status_input').value = s;

    const map = {
        actif: '#10b981',
        conge: '#f59e0b',
        inactif: '#64748b'
    };

    document.querySelectorAll('.status-btn').forEach(b => {
        b.classList.toggle('active', b.dataset.status === s);
    });

    document.getElementById('preview-status-badge').textContent = s;
}

/* ─────────────────────────────
   COLOR PREVIEW
──────────────────────────── */
function updatePreviewColor(color) {
    const avatar = document.getElementById('preview-avatar');
    const banner = document.getElementById('preview-banner');

    if (avatar) avatar.style.background = color;

    if (banner && !document.getElementById('role_input')?.value) {
        banner.style.background = color;
    }
}
</script>
@endpush
