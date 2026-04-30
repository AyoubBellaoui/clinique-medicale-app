@extends('layouts.app')

@section('title', 'Mon Compte')
@section('page-title', 'Mon Compte')
@section('page-subtitle', 'Gérez vos informations personnelles')

@section('content')

<div style="display:grid;grid-template-columns:300px 1fr;gap:22px;align-items:start;">

    {{-- Profile card --}}
    <div style="background:#fff;border-radius:14px;border:1px solid rgba(52,168,140,.1);box-shadow:0 1px 4px rgba(20,90,75,.05);overflow:hidden;position:sticky;top:20px;">
        <div style="height:72px;background:linear-gradient(135deg,var(--teal-800),var(--teal-600));position:relative;overflow:hidden;">
            <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 80% 50%,rgba(52,168,140,.3),transparent 60%);"></div>
        </div>
        <div style="padding:0 20px 20px;text-align:center;">
            <div style="margin-top:-28px;margin-bottom:10px;display:flex;justify-content:center;position:relative;z-index:2;">
                <div style="width:56px;height:56px;border-radius:14px;background:linear-gradient(135deg,var(--teal-400),var(--teal-700));display:flex;align-items:center;justify-content:center;font-size:20px;font-weight:700;color:#fff;border:3px solid #fff;box-shadow:0 2px 8px rgba(20,90,75,.16);font-family:'Fraunces',serif;">DA</div>
            </div>
            <div style="font-size:16px;font-weight:700;color:var(--teal-800);margin-bottom:4px;font-family:'Fraunces',serif;letter-spacing:-.2px;">Dr. Admin</div>
            <div style="display:inline-flex;align-items:center;gap:5px;font-size:11px;color:var(--teal-600);font-weight:600;background:rgba(52,168,140,.07);padding:3px 10px;border-radius:999px;border:1px solid rgba(52,168,140,.12);margin-bottom:16px;">
                <span style="width:6px;height:6px;border-radius:50%;background:var(--teal-500);display:inline-block;"></span>
                Administrateur
            </div>

            <div style="display:flex;border:1px solid rgba(52,168,140,.1);border-radius:10px;overflow:hidden;margin-bottom:14px;">
                <div style="flex:1;padding:12px 6px;text-align:center;border-right:1px solid rgba(52,168,140,.1);">
                    <div style="font-size:17px;font-weight:700;color:var(--teal-700);font-family:'Fraunces',serif;">6</div>
                    <div style="font-size:9px;color:var(--soft);font-weight:700;text-transform:uppercase;letter-spacing:.09em;margin-top:2px;">Consult.</div>
                </div>
                <div style="flex:1;padding:12px 6px;text-align:center;border-right:1px solid rgba(52,168,140,.1);">
                    <div style="font-size:17px;font-weight:700;color:var(--teal-700);font-family:'Fraunces',serif;">9</div>
                    <div style="font-size:9px;color:var(--soft);font-weight:700;text-transform:uppercase;letter-spacing:.09em;margin-top:2px;">Patients</div>
                </div>
                <div style="flex:1;padding:12px 6px;text-align:center;">
                    <div style="font-size:17px;font-weight:700;color:var(--teal-700);font-family:'Fraunces',serif;">6</div>
                    <div style="font-size:9px;color:var(--soft);font-weight:700;text-transform:uppercase;letter-spacing:.09em;margin-top:2px;">Staff</div>
                </div>
            </div>

            <div style="display:flex;flex-direction:column;border:1px solid rgba(52,168,140,.1);border-radius:10px;overflow:hidden;margin-bottom:14px;text-align:left;">
                @php
                $infos = [
                    ['icon'=>'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75','label'=>'Email','value'=>'admin@clinicpro.ma'],
                    ['icon'=>'M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z','label'=>'Téléphone','value'=>'+212 6 61 23 45 67'],
                    ['icon'=>'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75','label'=>'Membre depuis','value'=>'Janvier 2024'],
                    ['icon'=>'M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z','label'=>'Localisation','value'=>'Casablanca, Maroc'],
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

            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                <button style="display:flex;align-items:center;justify-content:center;gap:6px;padding:9px;border-radius:9px;font-size:12px;font-weight:600;cursor:pointer;background:var(--teal-600);color:#fff;border:none;">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z"/><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/></svg>
                    Photo
                </button>
                <button style="display:flex;align-items:center;justify-content:center;gap:6px;padding:9px;border-radius:9px;font-size:12px;font-weight:600;cursor:pointer;background:none;color:var(--teal-700);border:1px solid rgba(52,168,140,.2);">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 100 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186l9.566-5.314m-9.566 7.5l9.566 5.314m0 0a2.25 2.25 0 103.935 2.186 2.25 2.25 0 00-3.935-2.186zm0-12.814a2.25 2.25 0 103.933-2.185 2.25 2.25 0 00-3.933 2.185z"/></svg>
                    Partager
                </button>
            </div>
        </div>
    </div>

    {{-- Right panel --}}
    <div>
        {{-- Tabs --}}
        <div style="display:flex;gap:4px;background:#fff;padding:5px;border-radius:12px;border:1px solid rgba(52,168,140,.1);box-shadow:var(--shadow);margin-bottom:18px;overflow-x:auto;" id="account-tabs">
            @foreach([['profile','Profil','M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0'],['security','Sécurité','M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z'],['prefs','Préférences','M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827'],['notifs','Notifications','M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0']] as [$tab, $label, $icon])
            <button class="account-tab {{ $tab === 'profile' ? 'active' : '' }}" onclick="switchAccountTab('{{ $tab }}')" data-tab="{{ $tab }}">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                {{ $label }}
            </button>
            @endforeach
        </div>

        {{-- Tab: Profil --}}
        <div class="account-panel" id="panel-profile">
            <div style="background:#fff;border-radius:16px;border:1px solid rgba(52,168,140,.1);box-shadow:var(--shadow);overflow:hidden;">
                <div style="padding:18px 22px;border-bottom:1px solid rgba(52,168,140,.08);">
                    <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);font-family:'Fraunces',serif;">Informations personnelles</h3>
                    <p style="font-size:12px;color:var(--soft);margin-top:2px;">Mettez à jour votre nom et votre email</p>
                </div>
                <div style="padding:22px;">
                    <div class="form-grid">
                        <div class="form-group"><label class="form-label">Nom complet</label><input class="form-input" value="Dr. Admin"></div>
                        <div class="form-group"><label class="form-label">Titre</label><input class="form-input" value="Administrateur"></div>
                        <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-input" value="admin@clinicpro.ma"></div>
                        <div class="form-group"><label class="form-label">Téléphone</label><input class="form-input" value="+212 6 61 23 45 67"></div>
                        <div class="form-group full"><label class="form-label">Bio</label><textarea class="form-textarea" rows="3">Administrateur principal de ClinicPro depuis janvier 2024.</textarea></div>
                    </div>
                    <div style="display:flex;justify-content:flex-end;gap:10px;margin-top:20px;">
                        <button class="btn btn-ghost">Annuler</button>
                        <button class="btn btn-primary">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Enregistrer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Sécurité --}}
        <div class="account-panel hidden" id="panel-security">
            <div style="background:#fff;border-radius:16px;border:1px solid rgba(52,168,140,.1);box-shadow:var(--shadow);overflow:hidden;margin-bottom:16px;">
                <div style="padding:18px 22px;border-bottom:1px solid rgba(52,168,140,.08);">
                    <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);font-family:'Fraunces',serif;">Changer le mot de passe</h3>
                    <p style="font-size:12px;color:var(--soft);margin-top:2px;">Utilisez un mot de passe fort et unique</p>
                </div>
                <div style="padding:22px;">
                    <div class="form-grid">
                        <div class="form-group full"><label class="form-label">Mot de passe actuel</label><input type="password" class="form-input" placeholder="••••••••"></div>
                        <div class="form-group"><label class="form-label">Nouveau mot de passe</label><input type="password" class="form-input" placeholder="Min. 8 caractères"></div>
                        <div class="form-group"><label class="form-label">Confirmer</label><input type="password" class="form-input" placeholder="Confirmer"></div>
                    </div>
                    <div style="display:flex;justify-content:flex-end;margin-top:20px;">
                        <button class="btn btn-primary">Mettre à jour</button>
                    </div>
                </div>
            </div>
            <div style="background:rgba(244,63,94,.04);border:1px solid rgba(244,63,94,.15);border-radius:12px;padding:16px 18px;display:flex;align-items:center;justify-content:space-between;gap:16px;">
                <div>
                    <p style="font-size:13px;font-weight:600;color:#e11d48;">Supprimer mon compte</p>
                    <span style="font-size:11.5px;color:#fb7185;">Toutes vos données seront définitivement effacées.</span>
                </div>
                <button class="btn btn-danger btn-sm">Supprimer</button>
            </div>
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
</style>
@endpush

@push('scripts')
<script>
function switchAccountTab(tab) {
    document.querySelectorAll('.account-tab').forEach(t => t.classList.toggle('active', t.dataset.tab === tab));
    document.querySelectorAll('.account-panel').forEach(p => p.classList.toggle('hidden', p.id !== 'panel-' + tab));
}
</script>
@endpush
