@extends('layouts.app')

@section('title', 'Mon Compte')
@section('page-title', 'Mon Compte')
@section('page-subtitle', 'Gérez vos informations personnelles')

@section('content')

@php
    $moisFr = [1=>'Janvier',2=>'Février',3=>'Mars',4=>'Avril',5=>'Mai',6=>'Juin',7=>'Juillet',8=>'Août',9=>'Septembre',10=>'Octobre',11=>'Novembre',12=>'Décembre'];
    $roleLabels = ['admin' => 'Administrateur', 'medecin' => 'Médecin', 'infirmier' => 'Infirmier', 'secretariat' => 'Secrétariat', 'technicien' => 'Technicien'];
    $initials = strtoupper(collect(explode(' ', $user->name))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode(''));
@endphp

<div style="display:grid;grid-template-columns:300px 1fr;gap:22px;align-items:start;">

    {{-- Profile card --}}
    <div style="background:#fff;border-radius:14px;border:1px solid rgba(52,168,140,.1);box-shadow:0 1px 4px rgba(20,90,75,.05);overflow:hidden;position:sticky;top:20px;">
        <div style="height:72px;background:linear-gradient(135deg,var(--teal-800),var(--teal-600));position:relative;overflow:hidden;">
            <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 80% 50%,rgba(52,168,140,.3),transparent 60%);"></div>
        </div>
        <div style="padding:0 20px 20px;text-align:center;">
            <div style="margin-top:-28px;margin-bottom:10px;display:flex;justify-content:center;position:relative;z-index:2;">
                <div style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,var(--teal-400),var(--teal-700));display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;color:#fff;border:3px solid #fff;box-shadow:0 2px 8px rgba(20,90,75,.16);font-family:'Fraunces',serif;">{{ $initials ?: '?' }}</div>
            </div>
            <div style="font-size:16px;font-weight:700;color:var(--teal-800);margin-bottom:4px;font-family:'Fraunces',serif;letter-spacing:-.2px;">{{ $user->name }}</div>
            <div style="display:inline-flex;align-items:center;gap:5px;font-size:11px;color:var(--teal-600);font-weight:600;background:rgba(52,168,140,.07);padding:3px 10px;border-radius:999px;border:1px solid rgba(52,168,140,.12);margin-bottom:16px;">
                <span style="width:6px;height:6px;border-radius:50%;background:var(--teal-500);display:inline-block;"></span>
                {{ $roleLabels[$user->role] ?? ucfirst($user->role) }}
            </div>

            <div style="display:flex;border:1px solid rgba(52,168,140,.1);border-radius:10px;overflow:hidden;margin-bottom:14px;">
                <div style="flex:1;padding:12px 6px;text-align:center;border-right:1px solid rgba(52,168,140,.1);">
                    <div style="font-size:17px;font-weight:700;color:var(--teal-700);font-family:'Fraunces',serif;">{{ $consultationsCount }}</div>
                    <div style="font-size:9px;color:var(--soft);font-weight:700;text-transform:uppercase;letter-spacing:.09em;margin-top:2px;">Consult.</div>
                </div>
                <div style="flex:1;padding:12px 6px;text-align:center;border-right:1px solid rgba(52,168,140,.1);">
                    <div style="font-size:17px;font-weight:700;color:var(--teal-700);font-family:'Fraunces',serif;">{{ $patientsCount }}</div>
                    <div style="font-size:9px;color:var(--soft);font-weight:700;text-transform:uppercase;letter-spacing:.09em;margin-top:2px;">Patients</div>
                </div>
                <div style="flex:1;padding:12px 6px;text-align:center;">
                    <div style="font-size:17px;font-weight:700;color:var(--teal-700);font-family:'Fraunces',serif;">{{ $staffCount }}</div>
                    <div style="font-size:9px;color:var(--soft);font-weight:700;text-transform:uppercase;letter-spacing:.09em;margin-top:2px;">Staff</div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;border:1px solid rgba(52,168,140,.1);border-radius:10px;overflow:hidden;text-align:left;">
                @php
                $infos = [
                    ['icon'=>'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75','label'=>'Email','value'=>$user->email],
                    ['icon'=>'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75','label'=>'Membre depuis','value'=>$moisFr[$user->created_at->month].' '.$user->created_at->year],
                ];
                @endphp
                @foreach($infos as $info)
                <div style="display:flex;align-items:center;gap:11px;padding:9px 13px;border-bottom:1px solid rgba(52,168,140,.07);">
                    <div style="width:28px;height:28px;border-radius:8px;background:rgba(52,168,140,.07);color:var(--teal-500);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $info['icon'] }}"/></svg>
                    </div>
                    <div style="min-width:0;flex:1;">
                        <div style="font-size:9px;color:var(--soft);font-weight:700;text-transform:uppercase;letter-spacing:.07em;margin-bottom:1px;">{{ $info['label'] }}</div>
                        <div style="font-size:12.5px;color:var(--teal-800);font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $info['value'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Right panel --}}
    <div>
        {{-- Tabs --}}
        <div style="display:flex;gap:4px;background:#fff;padding:5px;border-radius:12px;border:1px solid rgba(52,168,140,.1);box-shadow:var(--shadow);margin-bottom:18px;overflow-x:auto;" id="account-tabs">
            @foreach([['profile','Profil','M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0'],['security','Sécurité','M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z'],['prefs','Préférences','M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75'],['notifs','Notifications','M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0']] as [$tab, $label, $icon])
            <button class="account-tab {{ $tab === 'profile' ? 'active' : '' }}" onclick="switchAccountTab('{{ $tab }}')" data-tab="{{ $tab }}">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- Tab: Profil --}}
        <div class="account-panel" id="panel-profile">
            <form action="{{ route('account.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div style="background:#fff;border-radius:16px;border:1px solid rgba(52,168,140,.1);box-shadow:var(--shadow);overflow:hidden;">
                    <div style="padding:18px 22px;border-bottom:1px solid rgba(52,168,140,.08);">
                        <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);font-family:'Fraunces',serif;">Informations personnelles</h3>
                        <p style="font-size:12px;color:var(--soft);margin-top:2px;">Mettez à jour votre nom et votre email</p>
                    </div>
                    <div style="padding:22px;">
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">Nom complet</label>
                                <input name="name" class="form-input @error('name') input-error @enderror" value="{{ old('name', $user->getRawOriginal('name')) }}" placeholder="{{ $user->name }}">
                                @error('name')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Rôle</label>
                                <input class="form-input" value="{{ $roleLabels[$user->role] ?? ucfirst($user->role) }}" disabled style="opacity:.6;cursor:not-allowed;">
                            </div>
                            <div class="form-group full">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-input @error('email') input-error @enderror" value="{{ old('email', $user->email) }}">
                                @error('email')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;">
                            <button type="submit" class="btn btn-primary">
                                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tab: Sécurité --}}
        <div class="account-panel hidden" id="panel-security">
            <form action="{{ route('account.password') }}" method="POST">
                @csrf
                @method('PUT')
                <div style="background:#fff;border-radius:16px;border:1px solid rgba(52,168,140,.1);box-shadow:var(--shadow);overflow:hidden;">
                    <div style="padding:18px 22px;border-bottom:1px solid rgba(52,168,140,.08);">
                        <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);font-family:'Fraunces',serif;">Changer le mot de passe</h3>
                        <p style="font-size:12px;color:var(--soft);margin-top:2px;">Utilisez un mot de passe fort et unique</p>
                    </div>
                    <div style="padding:22px;">
                        <div class="form-grid">
                            <div class="form-group full">
                                <label class="form-label">Mot de passe actuel</label>
                                <div style="position:relative;">
                                    <input type="password" name="current_password" class="form-input @error('current_password') input-error @enderror" placeholder="••••••••" style="width:100%;padding-right:38px;">
                                    <button type="button" class="account-toggle-pw" onclick="toggleAccountPw(this)" tabindex="-1">
                                        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('current_password')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Nouveau mot de passe</label>
                                <div style="position:relative;">
                                    <input type="password" name="password" class="form-input @error('password') input-error @enderror" placeholder="Min. 8 caractères" style="width:100%;padding-right:38px;">
                                    <button type="button" class="account-toggle-pw" onclick="toggleAccountPw(this)" tabindex="-1">
                                        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </button>
                                </div>
                                @error('password')<div class="field-error">{{ $message }}</div>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Confirmer</label>
                                <div style="position:relative;">
                                    <input type="password" name="password_confirmation" class="form-input" placeholder="Confirmer" style="width:100%;padding-right:38px;">
                                    <button type="button" class="account-toggle-pw" onclick="toggleAccountPw(this)" tabindex="-1">
                                        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div style="display:flex;justify-content:flex-end;margin-top:20px;">
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        {{-- Tab: Préférences --}}
        <div class="account-panel hidden" id="panel-prefs">
            <div style="background:#fff;border-radius:16px;border:1px solid rgba(52,168,140,.1);box-shadow:var(--shadow);overflow:hidden;">
                <div style="padding:18px 22px;border-bottom:1px solid rgba(52,168,140,.08);">
                    <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);font-family:'Fraunces',serif;">Préférences générales</h3>
                    <p style="font-size:12px;color:var(--soft);margin-top:2px;">Personnalisez votre expérience ClinicPro</p>
                </div>
                <div style="padding:22px;">
                    @foreach([['Mode sombre','Interface en thème foncé',false],['Effets sonores','Sons de confirmation et alertes',true]] as [$title,$sub,$on])
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;padding:14px 0;border-bottom:1px solid rgba(52,168,140,.08);">
                        <div>
                            <p style="font-size:13px;font-weight:600;color:var(--teal-800);">{{ $title }}</p>
                            <span style="font-size:11.5px;color:var(--soft);">{{ $sub }}</span>
                        </div>
                        <div class="toggle-switch {{ $on ? 'on' : '' }}" onclick="this.classList.toggle('on')"></div>
                    </div>
                    @endforeach
                    <div class="form-grid" style="margin-top:18px;">
                        <div class="form-group">
                            <label class="form-label">Langue</label>
                            <select class="form-select"><option>Français</option><option>العربية</option><option>English</option></select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Fuseau horaire</label>
                            <select class="form-select"><option>Casablanca (GMT+1)</option><option>Paris (GMT+2)</option></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Notifications --}}
        <div class="account-panel hidden" id="panel-notifs">
            <div style="background:#fff;border-radius:16px;border:1px solid rgba(52,168,140,.1);box-shadow:var(--shadow);overflow:hidden;">
                <div style="padding:18px 22px;border-bottom:1px solid rgba(52,168,140,.08);">
                    <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);font-family:'Fraunces',serif;">Notifications</h3>
                    <p style="font-size:12px;color:var(--soft);margin-top:2px;">Choisissez comment vous souhaitez être alerté</p>
                </div>
                <div style="padding:22px;">
                    @foreach([
                        ['Notifications par email','Recevez un résumé des événements importants',true],
                        ['Notifications SMS','Alertes urgentes envoyées par SMS',false],
                        ['Rappels de rendez-vous','Notifications avant chaque RDV',true],
                        ['Rapport hebdomadaire','Bilan chaque lundi matin',true],
                    ] as [$title,$sub,$on])
                    <div style="display:flex;align-items:center;justify-content:space-between;gap:16px;padding:14px 0;border-bottom:1px solid rgba(52,168,140,.08);">
                        <div>
                            <p style="font-size:13px;font-weight:600;color:var(--teal-800);">{{ $title }}</p>
                            <span style="font-size:11.5px;color:var(--soft);">{{ $sub }}</span>
                        </div>
                        <div class="toggle-switch {{ $on ? 'on' : '' }}" onclick="this.classList.toggle('on')"></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.account-tab { flex:1;padding:9px 14px;font-size:12.5px;font-weight:600;color:var(--muted);background:transparent;border:none;cursor:pointer;border-radius:8px;display:flex;align-items:center;justify-content:center;gap:7px;white-space:nowrap;transition:all .18s; }
.account-tab:hover { color:var(--teal-700); }
.account-tab.active { background:linear-gradient(135deg,var(--teal-600),var(--teal-500));color:#fff;box-shadow:0 4px 10px rgba(52,168,140,.25); }
.toggle-switch { position:relative;width:40px;height:22px;flex-shrink:0;background:rgba(52,168,140,.18);border-radius:999px;cursor:pointer;transition:background .22s; }
.toggle-switch::after { content:'';position:absolute;top:2px;left:2px;width:18px;height:18px;border-radius:50%;background:#fff;box-shadow:0 2px 4px rgba(0,0,0,.15);transition:transform .22s; }
.toggle-switch.on { background:var(--teal-500); }
.toggle-switch.on::after { transform:translateX(18px); }
.account-toggle-pw { position:absolute;right:10px;top:50%;transform:translateY(-50%);background:none;border:none;padding:2px;display:flex;align-items:center;cursor:pointer;color:var(--muted); }
.account-toggle-pw:hover { color:var(--teal-600); }
html.dark .account-toggle-pw { color:#4d8a7e; }
html.dark .account-toggle-pw:hover { color:#7ecab5; }
.field-error{font-size:11.5px;color:#e11d48;margin-top:5px;font-weight:600;}
</style>
@endpush

@push('scripts')
<script>
function switchAccountTab(tab) {
    document.querySelectorAll('.account-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === tab));
    document.querySelectorAll('.account-panel').forEach(p => p.classList.toggle('hidden', p.id !== 'panel-' + tab));
}

function toggleAccountPw(btn) {
    const input = btn.previousElementSibling;
    const showing = input.type === 'text';
    input.type = showing ? 'password' : 'text';
    btn.querySelector('svg').innerHTML = showing
        ? '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>'
        : '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88"/>';
}
</script>
@endpush
