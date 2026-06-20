@extends('layouts.app')

@section('title', 'Patients')
@section('page-title', 'Patients')
@section('page-subtitle', 'Gestion des patients')

@section('content')

{{-- ─── PATIENT DATA (for modal) ──────────────────────────────── --}}
@php
$patientsForJs = $patients->map(function($p) {
    return [
        'id'                  => $p->id,
        'prenom'              => $p->prenom,
        'nom'                 => $p->nom,
        'email'               => $p->email,
        'cin'                 => $p->cin,
        'telephone'           => $p->telephone,
        'date_naissance'      => $p->date_naissance,
        'genre'               => $p->genre,
        'adresse'             => $p->adresse,
        'statut_marital'      => $p->statut_marital,
        'groupe_sanguin'      => $p->groupe_sanguin,
        'allergies'           => $p->allergies,
        'antecedents'         => $p->antecedents,
        'statut_dossier'      => $p->statut_dossier,
        'assurance_type'      => $p->assurance_type,
        'assurance_numero'    => $p->assurance_numero,
        'contact_urgence_nom' => $p->contact_urgence_nom,
        'contact_urgence_tel' => $p->contact_urgence_tel,
        'lien_urgence'        => $p->lien_urgence,
        'color'               => $p->color ?? 'teal',
    ];
});
@endphp
<script>
const PATIENT_DATA = {!! json_encode($patientsForJs) !!};
</script>

{{-- STATS --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ count($patients) }}</div>
            <div class="stat-label">Total Patients</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ collect($patients)->where('genre', 'M')->count() }}</div>
            <div class="stat-label">Hommes</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon rose">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5zM12 14.25v5.25M9.75 16.5h4.5M12 3v3"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ collect($patients)->where('genre', 'F')->count() }}</div>
            <div class="stat-label">Femmes</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ $patients->where('statut_dossier', 'actif')->count() }}</div>
            <div class="stat-label">Actifs</div>
        </div>
    </div>

</div>

{{-- TABLE --}}
<div class="card">
    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div>
                <h3>Patients</h3>
                <span>{{ count($patients) }} patients enregistrés</span>
            </div>
        </div>

        <div style="display:flex;gap:8px;align-items:center;">
            <div style="display:flex;align-items:center;gap:8px;background:var(--teal-50);border:1.5px solid rgba(52,168,140,.2);border-radius:10px;padding:8px 14px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text"
                    id="patient-search"
                    placeholder="Rechercher un patient..."
                    style="border:none;outline:none;background:none;font-size:13px;font-family:inherit;color:var(--teal-800);width:200px;"
                    autocomplete="off">
            </div>

            <button class="btn btn-outline btn-sm">Exporter</button>

            <a href="{{ route('patients.create') }}" class="btn btn-primary btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Nouveau Patient
            </a>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>CIN</th>
                    <th>Âge</th>
                    <th>Genre</th>
                    <th>Groupe sanguin</th>
                    <th>Dernière visite</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody id="patients-tbody">
                @forelse($patients as $p)
                    <tr style="cursor:pointer;" onclick="openPatientModal({{ $p->id }})"
                        data-search="{{ strtolower($p->prenom . ' ' . $p->nom . ' ' . $p->cin . ' ' . ($p->email ?? '') . ' ' . ($p->telephone ?? '') . ' ' . ($p->groupe_sanguin ?? '')) }}">
                        <td>
                            <div class="avatar-chip">
                                @php
                                    $initials = strtoupper(substr($p->prenom ?? '', 0, 1) . substr($p->nom ?? '', 0, 1));
                                @endphp
                                <div class="avatar {{ $p->color ?? 'teal' }}">
                                    {{ $initials ?: '?' }}
                                </div>
                                <div class="avatar-info">
                                    <p>{{ $p->prenom }} {{ $p->nom }}</p>
                                    <span>{{ $p->email }}</span>
                                </div>
                            </div>
                        </td>

                        <td><span class="text-mono">{{ $p->cin }}</span></td>

                        <td>
                            @if($p->date_naissance)
                                {{ \Carbon\Carbon::parse($p->date_naissance)->age }} ans
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>

                        <td>
                            <span class="badge badge-{{ $p->genre === 'F' ? 'rose' : 'blue' }}">
                                {{ $p->genre === 'F' ? 'Féminin' : 'Masculin' }}
                            </span>
                        </td>

                        <td>
                            @if($p->groupe_sanguin)
                                <span style="font-size:12.5px;font-weight:700;color:var(--teal-700);
                                    background:rgba(52,168,140,.08);padding:3px 10px;border-radius:6px;">
                                    {{ $p->groupe_sanguin }}
                                </span>
                            @else
                                <span style="color:var(--muted);">—</span>
                            @endif
                        </td>

                        <td style="color:var(--muted);">—</td>

                        <td>{{ $p->telephone ?? '—' }}</td>

                        <td onclick="event.stopPropagation()">
                            <div style="display:flex;gap:4px;">
                                {{-- VIEW --}}
                                <button onclick="openPatientModal({{ $p->id }})"
                                        class="btn btn-outline btn-sm btn-icon-only" title="Voir la fiche">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </button>
                                {{-- EDIT --}}
                                <a href="{{ route('patients.edit', $p->id) }}"
                                   class="btn btn-outline btn-sm btn-icon-only" title="Modifier">
                                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z"/>
                                    </svg>
                                </a>
                                {{-- DELETE --}}
                                <form method="POST" action="{{ route('patients.delete', $p->id) }}"
                                      onsubmit="return confirm('Supprimer ce patient ?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm btn-icon-only" title="Supprimer">
                                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;padding:20px;color:var(--muted);">
                            Aucun patient trouvé
                        </td>
                    </tr>
                @endforelse
                <tr id="patient-no-results" style="display:none;">
                    <td colspan="8" style="text-align:center;padding:30px 20px;color:var(--muted);">
                        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 8px;display:block;opacity:.5;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        Aucun patient ne correspond à votre recherche
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <span>Affichage {{ $patients->count() ?? 0 }} patients</span>
    </div>
</div>

{{-- ── PATIENT DETAIL MODAL ────────────────────────────────────────── --}}
<div id="patient-modal-backdrop" class="modal-backdrop"
     onclick="if(event.target===this)closePatientModal()">

    <div id="patient-modal" class="modal" style="max-width:700px;">

        {{-- Hero Banner --}}
        <div style="height:92px;position:relative;border-radius:20px 20px 0 0;overflow:hidden;flex-shrink:0;">
            <div id="pmodal-hero-bg" style="position:absolute;inset:0;"></div>
            <div style="position:absolute;inset:0;background:linear-gradient(160deg,transparent 35%,rgba(0,0,0,.22));"></div>
            <button onclick="closePatientModal()"
                    style="position:absolute;top:13px;right:13px;width:30px;height:30px;border:none;background:rgba(255,255,255,.22);backdrop-filter:blur(4px);border-radius:8px;cursor:pointer;color:#fff;display:flex;align-items:center;justify-content:center;transition:background .15s;"
                    onmouseenter="this.style.background='rgba(255,255,255,.38)'"
                    onmouseleave="this.style.background='rgba(255,255,255,.22)'">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Profile Row --}}
        <div style="padding:0 24px 16px;margin-top:-32px;position:relative;border-bottom:1px solid rgba(52,168,140,.08);">
            <div style="display:flex;align-items:flex-end;gap:14px;">
                <div id="pmodal-avatar"
                     style="width:62px;height:62px;border-radius:17px;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:800;color:#fff;flex-shrink:0;border:3px solid #fff;box-shadow:0 8px 24px rgba(52,168,140,.3);">
                </div>
                <div style="flex:1;min-width:0;padding-bottom:3px;">
                    <h3 id="pmodal-name" style="font-size:18px;font-weight:700;color:var(--teal-800);letter-spacing:-.3px;font-family:'Fraunces',serif;line-height:1.15;"></h3>
                    <div style="display:flex;align-items:center;gap:7px;margin-top:5px;flex-wrap:wrap;">
                        <span id="pmodal-genre-badge" class="badge"></span>
                        <span id="pmodal-age" style="font-size:12px;color:var(--muted);"></span>
                    </div>
                </div>
                <div id="pmodal-status-wrap" style="padding-bottom:3px;flex-shrink:0;"></div>
            </div>
        </div>

        {{-- Tabs --}}
        <div style="padding:14px 24px 0;border-bottom:1px solid rgba(52,168,140,.08);">
            <div class="tabs">
                <button class="tab active pmodal-tab" onclick="switchPTab(this,'identity')">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    Identité
                </button>
                <button class="tab pmodal-tab" onclick="switchPTab(this,'medical')">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Médical
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="modal-body">

            {{-- TAB: Identité --}}
            <div id="ptab-identity" class="ptab-panel">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>Nom complet</div>
                        <div class="info-cell-value" id="pc-fullname"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg>CIN</div>
                        <div class="info-cell-value" id="pc-cin" style="font-family:ui-monospace,monospace;letter-spacing:.05em;"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>Date de naissance</div>
                        <div class="info-cell-value" id="pc-dob"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>Genre</div>
                        <div class="info-cell-value" id="pc-genre"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>Statut marital</div>
                        <div class="info-cell-value" id="pc-marital"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>Téléphone</div>
                        <div class="info-cell-value" id="pc-phone"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>Email</div>
                        <div class="info-cell-value" id="pc-email" style="word-break:break-all;font-size:12.5px;"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>Adresse</div>
                        <div class="info-cell-value" id="pc-adresse"></div>
                    </div>
                </div>
            </div>

            {{-- TAB: Médical --}}
            <div id="ptab-medical" class="ptab-panel" style="display:none;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">

                    {{-- Blood group highlight --}}
                    <div style="background:linear-gradient(135deg,rgba(244,63,94,.07),#fff);border:1.5px solid rgba(244,63,94,.18);border-radius:14px;padding:16px 18px;display:flex;align-items:center;gap:14px;">
                        <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,#f43f5e,#be123c);display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;box-shadow:0 4px 12px rgba(244,63,94,.35);">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3c0 0-7 6.5-7 11a7 7 0 0014 0c0-4.5-7-11-7-11z"/></svg>
                        </div>
                        <div>
                            <div style="font-size:10px;font-weight:700;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px;">Groupe sanguin</div>
                            <div id="pc-blood" style="font-size:24px;font-weight:800;color:#be123c;letter-spacing:-.5px;line-height:1;"></div>
                        </div>
                    </div>

                    {{-- Assurance --}}
                    <div style="background:var(--teal-50);border:1.5px solid rgba(52,168,140,.15);border-radius:14px;padding:16px 18px;display:flex;flex-direction:column;gap:6px;">
                        <div style="font-size:10px;font-weight:700;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;">Assurance</div>
                        <div id="pc-assurance-type" style="font-size:13.5px;font-weight:600;color:var(--teal-800);"></div>
                        <div id="pc-assurance-num" style="font-size:12px;color:var(--muted);font-family:ui-monospace,monospace;"></div>
                    </div>

                    <div class="info-cell" style="grid-column:1/-1;" id="pc-allergies-wrap">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/></svg>Allergies</div>
                        <div class="info-cell-value" id="pc-allergies" style="white-space:pre-line;font-size:13px;line-height:1.6;color:#dc2626;"></div>
                    </div>

                    <div class="info-cell" style="grid-column:1/-1;" id="pc-antecedents-wrap">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>Antécédents médicaux</div>
                        <div class="info-cell-value" id="pc-antecedents" style="white-space:pre-line;font-size:13px;line-height:1.6;"></div>
                    </div>

                    <div class="info-cell" style="grid-column:1/-1;" id="pc-urgence-wrap">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>Contact d'urgence</div>
                        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-top:4px;">
                            <span id="pc-urgence-nom" style="font-size:13.5px;font-weight:600;color:var(--teal-800);"></span>
                            <span id="pc-urgence-lien" style="font-size:12px;color:var(--muted);"></span>
                            <span id="pc-urgence-tel" style="font-size:13px;font-family:ui-monospace,monospace;color:var(--teal-700);margin-left:auto;"></span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="modal-footer" style="justify-content:space-between;">
            <span style="font-size:12px;color:var(--muted);font-weight:500;display:flex;align-items:center;gap:6px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                <span id="pmodal-age-footer"></span>
            </span>
            <div style="display:flex;gap:8px;">
                <button onclick="closePatientModal()" class="btn btn-outline btn-sm">Fermer</button>
                <a id="pmodal-edit-link" href="#" class="btn btn-primary btn-sm">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/></svg>
                    Modifier
                </a>
            </div>
        </div>

    </div>
</div>

<style>
.info-cell {
    background: var(--teal-50);
    border: 1px solid rgba(52,168,140,.1);
    border-radius: 12px;
    padding: 11px 14px;
}
.info-cell-label {
    display: flex;
    align-items: center;
    gap: 5px;
    font-size: 10px;
    font-weight: 700;
    color: var(--muted);
    letter-spacing: .08em;
    text-transform: uppercase;
    margin-bottom: 5px;
}
.info-cell-label svg { color: var(--teal-400); flex-shrink: 0; }
.info-cell-value {
    font-size: 13.5px;
    font-weight: 600;
    color: var(--teal-800);
    line-height: 1.4;
}
</style>

@endsection

@push('scripts')
<script>
/* ── Live search ── */
document.getElementById('patient-search').addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#patients-tbody tr[data-search]');
    let visible = 0;
    rows.forEach(row => {
        const match = !q || row.dataset.search.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('patient-no-results').style.display = (visible === 0 && q) ? '' : 'none';
});

/* ── Patient Modal ── */
const HERO_GRADIENTS_P = {
    teal:   'linear-gradient(135deg,#2e9278,#155c4e)',
    blue:   'linear-gradient(135deg,#3b82f6,#1d4ed8)',
    amber:  'linear-gradient(135deg,#f59e0b,#b45309)',
    rose:   'linear-gradient(135deg,#f43f5e,#be123c)',
    violet: 'linear-gradient(135deg,#8b5cf6,#6d28d9)',
};

function pval(v) { return v || '—'; }

function openPatientModal(id) {
    const p = PATIENT_DATA.find(x => x.id == id);
    if (!p) return;

    const fullName = ((p.prenom || '') + ' ' + (p.nom || '')).trim();
    const initials = ((p.prenom?.[0] || '') + (p.nom?.[0] || '')).toUpperCase();
    const gradient = HERO_GRADIENTS_P[p.color] || HERO_GRADIENTS_P.teal;

    // Hero
    document.getElementById('pmodal-hero-bg').style.background = gradient;

    // Avatar
    const av = document.getElementById('pmodal-avatar');
    av.textContent = initials;
    av.style.background = gradient;

    // Name / genre badge / age
    document.getElementById('pmodal-name').textContent = fullName;
    const genreBadge = document.getElementById('pmodal-genre-badge');
    genreBadge.textContent = p.genre === 'F' ? 'Féminin' : 'Masculin';
    genreBadge.className = 'badge badge-' + (p.genre === 'F' ? 'rose' : 'blue');

    let ageText = '';
    if (p.date_naissance) {
        const age = Math.floor((new Date() - new Date(p.date_naissance)) / (365.25 * 24 * 3600 * 1000));
        ageText = '· ' + age + ' ans';
    }
    document.getElementById('pmodal-age').textContent = ageText;

    // Status badge
    const stMap = { actif: ['teal', 'Actif'], inactif: ['rose', 'Inactif'], archive: ['amber', 'Archivé'] };
    const [stCls, stLabel] = stMap[p.statut_dossier] || ['gray', p.statut_dossier || ''];
    document.getElementById('pmodal-status-wrap').innerHTML = p.statut_dossier
        ? `<span class="badge badge-${stCls}">${stLabel}</span>` : '';

    // Identity tab
    document.getElementById('pc-fullname').textContent = fullName;
    document.getElementById('pc-cin').textContent      = pval(p.cin);
    document.getElementById('pc-dob').textContent      = pval(p.date_naissance);
    document.getElementById('pc-genre').textContent    = p.genre === 'F' ? 'Féminin' : p.genre === 'M' ? 'Masculin' : pval(p.genre);
    document.getElementById('pc-marital').textContent  = pval(p.statut_marital);
    document.getElementById('pc-phone').textContent    = pval(p.telephone);
    document.getElementById('pc-email').textContent    = pval(p.email);
    document.getElementById('pc-adresse').textContent  = pval(p.adresse);

    // Medical tab
    document.getElementById('pc-blood').textContent   = pval(p.groupe_sanguin);

    const assType = p.assurance_type || '—';
    const assNum  = p.assurance_numero ? '#' + p.assurance_numero : '';
    document.getElementById('pc-assurance-type').textContent = assType;
    document.getElementById('pc-assurance-num').textContent  = assNum;

    const algWrap = document.getElementById('pc-allergies-wrap');
    document.getElementById('pc-allergies').textContent = p.allergies || '';
    algWrap.style.display = p.allergies ? '' : 'none';

    const antWrap = document.getElementById('pc-antecedents-wrap');
    document.getElementById('pc-antecedents').textContent = p.antecedents || '';
    antWrap.style.display = p.antecedents ? '' : 'none';

    const urgWrap = document.getElementById('pc-urgence-wrap');
    if (p.contact_urgence_nom) {
        document.getElementById('pc-urgence-nom').textContent = p.contact_urgence_nom;
        document.getElementById('pc-urgence-lien').textContent = p.lien_urgence ? '(' + p.lien_urgence + ')' : '';
        document.getElementById('pc-urgence-tel').textContent  = p.contact_urgence_tel || '';
        urgWrap.style.display = '';
    } else {
        urgWrap.style.display = 'none';
    }

    // Footer age
    if (p.date_naissance) {
        const age = Math.floor((new Date() - new Date(p.date_naissance)) / (365.25 * 24 * 3600 * 1000));
        document.getElementById('pmodal-age-footer').textContent = age + ' ans';
    } else {
        document.getElementById('pmodal-age-footer').textContent = '';
    }

    document.getElementById('pmodal-edit-link').href = '/patients/' + p.id + '/edit';

    // Reset tabs
    switchPTab(document.querySelector('.pmodal-tab'), 'identity');

    document.getElementById('patient-modal-backdrop').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closePatientModal() {
    document.getElementById('patient-modal-backdrop').classList.remove('show');
    document.body.style.overflow = '';
}

function switchPTab(btn, tab) {
    document.querySelectorAll('.pmodal-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.ptab-panel').forEach(panel => panel.style.display = 'none');
    document.getElementById('ptab-' + tab).style.display = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closePatientModal();
});
</script>
@endpush
