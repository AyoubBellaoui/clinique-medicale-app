@extends('layouts.app')

@section('title', 'Staff Médical')
@section('page-title', 'Staff Médical')
@section('page-subtitle', count($staff) . ' membres du personnel')

@section('content')

{{-- ─── STAFF DATA (for modal) ─────────────────────────────────── --}}
@php
$staffForJs = $staff->map(function($s) {
    return [
        'id'            => $s->id,
        'prenom'        => $s->prenom,
        'nom'           => $s->nom,
        'email'         => $s->email,
        'cin'           => $s->cin,
        'telephone'     => $s->telephone,
        'date_naissance'=> $s->date_naissance,
        'gender'        => $s->gender,
        'adresse'       => $s->adresse,
        'specialite'    => $s->specialite ?: '—',
        'role'          => $s->role,
        'license_number'=> $s->license_number,
        'degree'        => $s->degree,
        'school'        => $s->school,
        'languages'     => $s->languages,
        'date_embauche' => $s->date_embauche,
        'salaire'       => $s->salaire,
        'contract_type' => $s->contract_type,
        'schedule'      => $s->schedule,
        'status'        => $s->status,
        'notes'         => $s->notes,
        'color'         => $s->color ?? 'teal',
    ];
});
@endphp
<script>
const STAFF_DATA = {!! json_encode($staffForJs) !!};
</script>

{{-- STATS --}}
<div class="stats-grid">

    <div class="stat-card">
        <div class="stat-icon teal">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ count($staff) }}</div>
            <div class="stat-label">Total Staff</div>
            <div class="stat-trend up">↑ Membres actifs</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon blue">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ collect($staff)->where('role', 'medecin')->count() }}</div>
            <div class="stat-label">Médecins</div>
            <div class="stat-trend up">↑ Praticiens</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon amber">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">{{ collect($staff)->where('role', 'infirmier')->count() }}</div>
            <div class="stat-label">Infirmiers</div>
            <div class="stat-trend up">↑ Personnel soignant</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon violet">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div class="stat-body">
            <div class="stat-value">
                {{ number_format(collect($staff)->sum('salaire'), 0, ',', ' ') }}
                <span style="font-size:12px;color:var(--muted);">MAD</span>
            </div>
            <div class="stat-label">Masse salariale</div>
            <div class="stat-trend up">↑ Mensuelle</div>
        </div>
    </div>

</div>

{{-- TABLE --}}
<div class="card">

    <div class="card-header">
        <div class="section-title">
            <div class="accent-bar"></div>
            <div>
                <h3>Staff Médical</h3>
                <span>{{ count($staff) }} membres actifs</span>
            </div>
        </div>

        <div style="display:flex;align-items:center;gap:8px;">
            <div style="display:flex;align-items:center;gap:8px;background:var(--teal-50);border:1.5px solid rgba(52,168,140,.2);border-radius:10px;padding:8px 14px;">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="color:var(--muted);flex-shrink:0;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                </svg>
                <input type="text"
                    id="staff-search"
                    placeholder="Rechercher un membre..."
                    style="border:none;outline:none;background:none;font-size:13px;font-family:inherit;color:var(--teal-800);width:180px;"
                    autocomplete="off">
            </div>

            <a href="{{ route('staff.create') }}" class="btn btn-primary btn-sm">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Nouveau Membre
            </a>
        </div>
    </div>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Membre</th>
                    <th>Spécialité</th>
                    <th>Téléphone</th>
                    <th>Embauché</th>
                    <th>Salaire</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody id="staff-tbody">
                @forelse($staff as $s)
                <tr style="cursor:pointer;" onclick="openStaffModal({{ $s->id }})"
                    data-search="{{ strtolower($s->prenom . ' ' . $s->nom . ' ' . ($s->email ?? '') . ' ' . ($s->telephone ?? '') . ' ' . ($s->specialite ?? '') . ' ' . ($s->role ?? '')) }}">

                    {{-- MEMBER --}}
                    <td>
                        <div class="avatar-chip">
                            <div class="avatar {{ $s->color ?? 'teal' }}">
                                @php
                                    $initials = strtoupper(substr($s->prenom ?? '', 0, 1) . substr($s->nom ?? '', 0, 1));
                                @endphp
                                {{ $initials ?: '?' }}
                            </div>
                            <div class="avatar-info">
                                <p>{{ $s->prenom }} {{ $s->nom }}</p>
                                <span>{{ $s->email }}</span>
                            </div>
                        </div>
                    </td>

                    {{-- SPECIALTY --}}
                    <td>{{ $s->specialite ?: '—' }}</td>

                    {{-- PHONE --}}
                    <td>{{ $s->telephone }}</td>

                    {{-- HIRED --}}
                    <td style="color:var(--muted);">{{ $s->date_embauche }}</td>

                    {{-- SALARY --}}
                    <td>
                        <strong style="color:var(--teal-700);">
                            {{ number_format($s->salaire, 0, ',', ' ') }} MAD
                        </strong>
                    </td>

                    {{-- ROLE --}}
                    <td>
                        @php
                            $role = trim(mb_strtolower($s->role ?? ''));
                            $badgeColor = match($role) {
                                'medecin'             => 'teal',
                                'infirmier'           => 'blue',
                                'secretaire', 'admin' => 'amber',
                                'technicien'          => 'violet',
                                default               => 'gray',
                            };
                            $roleLabel = match($role) {
                                'medecin'    => 'Médecin',
                                'infirmier'  => 'Infirmier(e)',
                                'secretaire' => 'Secrétaire',
                                'admin'      => 'Administrateur',
                                'technicien' => 'Technicien',
                                default      => ucfirst($s->role ?? ''),
                            };
                        @endphp
                        <span class="badge badge-{{ $badgeColor }}">{{ $roleLabel }}</span>
                    </td>

                    {{-- ACTIONS --}}
                    <td onclick="event.stopPropagation()">
                        <div style="display:flex;gap:4px;">

                            {{-- VIEW --}}
                            <button onclick="openStaffModal({{ $s->id }})"
                                    class="btn btn-outline btn-sm btn-icon-only" title="Voir la fiche">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>

                            {{-- EDIT --}}
                            <a href="{{ url('/staff/' . $s->id . '/edit') }}"
                               class="btn btn-outline btn-sm btn-icon-only" title="Modifier">
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125"/>
                                </svg>
                            </a>

                            {{-- DELETE --}}
                            <form method="POST" action="{{ url('/Staff/delete/' . $s->id) }}"
                                  style="display:inline;"
                                  onsubmit="return confirm('Supprimer ce membre du staff ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm btn-icon-only" title="Supprimer">
                                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                </button>
                            </form>

                        </div>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px 20px;">
                        <div style="display:flex;flex-direction:column;align-items:center;gap:10px;">
                            <svg width="40" height="40" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="color:var(--soft);">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                            </svg>
                            <span style="font-size:14px;font-weight:500;color:var(--muted);">Aucun membre trouvé</span>
                        </div>
                    </td>
                </tr>
                @endforelse
                <tr id="staff-no-results" style="display:none;">
                    <td colspan="7" style="text-align:center;padding:30px 20px;color:var(--muted);">
                        <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 8px;display:block;opacity:.5;"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/></svg>
                        Aucun membre ne correspond à votre recherche
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

{{-- ── STAFF DETAIL MODAL ──────────────────────────────────────────── --}}
{{-- Reuses .modal-backdrop / .modal CSS from layout for CSS-class animation --}}
<div id="staff-modal-backdrop" class="modal-backdrop"
     onclick="if(event.target===this)closeStaffModal()">

    <div id="staff-modal" class="modal" style="max-width:700px;">

        {{-- Hero Banner --}}
        <div style="height:92px;position:relative;border-radius:20px 20px 0 0;overflow:hidden;flex-shrink:0;">
            <div id="smodal-hero-bg" style="position:absolute;inset:0;"></div>
            <div style="position:absolute;inset:0;background:linear-gradient(160deg,transparent 35%,rgba(0,0,0,.22));"></div>
            <button onclick="closeStaffModal()"
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
                <div id="smodal-avatar"
                     style="width:62px;height:62px;border-radius:17px;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:800;color:#fff;flex-shrink:0;border:3px solid #fff;box-shadow:0 8px 24px rgba(52,168,140,.3);">
                </div>
                <div style="flex:1;min-width:0;padding-bottom:3px;">
                    <h3 id="smodal-name" style="font-size:18px;font-weight:700;color:var(--teal-800);letter-spacing:-.3px;font-family:'Fraunces',serif;line-height:1.15;"></h3>
                    <div style="display:flex;align-items:center;gap:7px;margin-top:5px;flex-wrap:wrap;">
                        <span id="smodal-badge" class="badge"></span>
                        <span id="smodal-specialty" style="font-size:12px;color:var(--muted);"></span>
                    </div>
                </div>
                <div id="smodal-status-wrap" style="padding-bottom:3px;flex-shrink:0;"></div>
            </div>
        </div>

        {{-- Tabs --}}
        <div style="padding:14px 24px 0;border-bottom:1px solid rgba(52,168,140,.08);">
            <div class="tabs">
                <button class="tab active smodal-tab" onclick="switchSTab(this,'identity')">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    Identité
                </button>
                <button class="tab smodal-tab" onclick="switchSTab(this,'professional')">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                    Formation
                </button>
                <button class="tab smodal-tab" onclick="switchSTab(this,'contract')">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" style="display:inline;vertical-align:middle;margin-right:4px;"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    Contrat
                </button>
            </div>
        </div>

        {{-- Body --}}
        <div class="modal-body">

            {{-- TAB: Identité --}}
            <div id="stab-identity" class="stab-panel">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>Nom complet</div>
                        <div class="info-cell-value" id="sc-fullname"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z"/></svg>CIN</div>
                        <div class="info-cell-value" id="sc-cin" style="font-family:ui-monospace,monospace;letter-spacing:.05em;"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>Date de naissance</div>
                        <div class="info-cell-value" id="sc-dob"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>Genre</div>
                        <div class="info-cell-value" id="sc-gender"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>Téléphone</div>
                        <div class="info-cell-value" id="sc-phone"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/></svg>Email</div>
                        <div class="info-cell-value" id="sc-email" style="word-break:break-all;font-size:12.5px;"></div>
                    </div>
                    <div class="info-cell" style="grid-column:1/-1;">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>Adresse</div>
                        <div class="info-cell-value" id="sc-adresse"></div>
                    </div>
                </div>
            </div>

            {{-- TAB: Formation --}}
            <div id="stab-professional" class="stab-panel" style="display:none;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z"/></svg>Rôle</div>
                        <div class="info-cell-value" id="sc-role"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z"/></svg>Spécialité</div>
                        <div class="info-cell-value" id="sc-specialite"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>N° de licence</div>
                        <div class="info-cell-value" id="sc-license" style="font-family:ui-monospace,monospace;letter-spacing:.05em;"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>Diplôme</div>
                        <div class="info-cell-value" id="sc-degree"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"/></svg>École / Université</div>
                        <div class="info-cell-value" id="sc-school"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>Année de diplomation</div>
                        <div class="info-cell-value" id="sc-gradyear"></div>
                    </div>
                    <div class="info-cell" style="grid-column:1/-1;">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253M3.284 14.253A8.96 8.96 0 013 12c0-1.925.571-3.715 1.557-5.207"/></svg>Langues</div>
                        <div class="info-cell-value" id="sc-languages"></div>
                    </div>
                </div>
            </div>

            {{-- TAB: Contrat --}}
            <div id="stab-contract" class="stab-panel" style="display:none;">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;">

                    {{-- Salary highlight --}}
                    <div style="grid-column:1/-1;background:linear-gradient(135deg,var(--teal-50),#fff);border:1.5px solid rgba(52,168,140,.18);border-radius:14px;padding:16px 18px;display:flex;align-items:center;gap:14px;">
                        <div style="width:44px;height:44px;border-radius:12px;background:linear-gradient(135deg,var(--teal-400),var(--teal-600));display:flex;align-items:center;justify-content:center;color:#fff;flex-shrink:0;box-shadow:0 4px 12px rgba(52,168,140,.35);">
                            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <div style="font-size:10px;font-weight:700;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:4px;">Salaire mensuel</div>
                            <div id="sc-salary" style="font-size:24px;font-weight:800;color:var(--teal-700);letter-spacing:-.5px;font-variant-numeric:tabular-nums;line-height:1;"></div>
                        </div>
                    </div>

                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>Date d'embauche</div>
                        <div class="info-cell-value" id="sc-hired"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>Type de contrat</div>
                        <div class="info-cell-value" id="sc-contract"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Horaires</div>
                        <div class="info-cell-value" id="sc-schedule"></div>
                    </div>
                    <div class="info-cell">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Statut</div>
                        <div class="info-cell-value" id="sc-status"></div>
                    </div>
                    <div class="info-cell" style="grid-column:1/-1;" id="sc-notes-wrap">
                        <div class="info-cell-label"><svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>Notes</div>
                        <div class="info-cell-value" id="sc-notes" style="white-space:pre-line;font-size:13px;line-height:1.6;"></div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Footer --}}
        <div class="modal-footer" style="justify-content:space-between;">
            <span style="font-size:12px;color:var(--muted);font-weight:500;display:flex;align-items:center;gap:6px;">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span id="smodal-hired-since"></span>
            </span>
            <div style="display:flex;gap:8px;">
                <button onclick="closeStaffModal()" class="btn btn-outline btn-sm">Fermer</button>
                <a id="smodal-edit-link" href="#" class="btn btn-primary btn-sm">
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

<script>
const ROLE_LABELS = {
    medecin:'Médecin', infirmier:'Infirmier(e)',
    secretaire:'Secrétaire', admin:'Administrateur', technicien:'Technicien'
};
const ROLE_COLORS = {
    medecin:'teal', infirmier:'blue',
    secretaire:'amber', admin:'amber', technicien:'violet'
};
const HERO_GRADIENTS = {
    teal:   'linear-gradient(135deg,#2e9278,#155c4e)',
    blue:   'linear-gradient(135deg,#3b82f6,#1d4ed8)',
    amber:  'linear-gradient(135deg,#f59e0b,#b45309)',
    rose:   'linear-gradient(135deg,#f43f5e,#be123c)',
    violet: 'linear-gradient(135deg,#8b5cf6,#6d28d9)',
};

function val(v) { return v || '—'; }

function openStaffModal(id) {
    const s = STAFF_DATA.find(x => x.id == id);
    if (!s) return;

    const fullName  = (s.prenom + ' ' + s.nom).trim();
    const initials  = ((s.prenom?.[0] || '') + (s.nom?.[0] || '')).toUpperCase();
    const role      = (s.role || '').toLowerCase();
    const roleLabel = ROLE_LABELS[role] || s.role || '—';
    const roleCls   = ROLE_COLORS[role]  || 'gray';

    // Hero
    document.getElementById('smodal-hero-bg').style.background = HERO_GRADIENTS[s.color] || HERO_GRADIENTS.teal;

    // Avatar
    const av = document.getElementById('smodal-avatar');
    av.textContent  = initials;
    av.style.background = HERO_GRADIENTS[s.color] || HERO_GRADIENTS.teal;

    // Name / badge
    document.getElementById('smodal-name').textContent = fullName;
    const badge = document.getElementById('smodal-badge');
    badge.textContent = roleLabel;
    badge.className   = 'badge badge-' + roleCls;
    document.getElementById('smodal-specialty').textContent = (s.specialite && s.specialite !== '—') ? '· ' + s.specialite : '';

    // Status in header
    const stMap = { actif:['green','Actif'], conge:['amber','En congé'], inactif:['rose','Inactif'] };
    const [stCls, stLabel] = stMap[s.status] || ['gray', s.status || ''];
    document.getElementById('smodal-status-wrap').innerHTML = s.status
        ? `<span class="badge badge-${stCls}">${stLabel}</span>` : '';

    // Identity tab
    document.getElementById('sc-fullname').textContent = fullName;
    document.getElementById('sc-cin').textContent      = val(s.cin);
    document.getElementById('sc-dob').textContent      = val(s.date_naissance);
    document.getElementById('sc-gender').textContent   = s.gender === 'homme' ? 'Homme' : s.gender === 'femme' ? 'Femme' : val(s.gender);
    document.getElementById('sc-phone').textContent    = val(s.telephone);
    document.getElementById('sc-email').textContent    = val(s.email);
    document.getElementById('sc-adresse').textContent  = val(s.adresse);

    // Formation tab
    document.getElementById('sc-role').textContent       = roleLabel;
    document.getElementById('sc-specialite').textContent = val(s.specialite);
    document.getElementById('sc-license').textContent    = val(s.license_number);
    document.getElementById('sc-degree').textContent     = val(s.degree);
    document.getElementById('sc-school').textContent     = val(s.school);
    document.getElementById('sc-gradyear').textContent   = val(s.grad_year);
    document.getElementById('sc-languages').textContent  = val(s.languages);

    // Contract tab
    document.getElementById('sc-hired').textContent    = val(s.date_embauche);
    document.getElementById('sc-contract').textContent = val(s.contract_type);
    document.getElementById('sc-salary').textContent   = s.salaire ? Number(s.salaire).toLocaleString('fr-FR') + ' MAD' : '—';
    document.getElementById('sc-schedule').textContent = val(s.schedule);
    document.getElementById('sc-status').innerHTML = s.status
        ? `<span class="badge badge-${stCls}">${stLabel}</span>` : '—';
    document.getElementById('sc-notes').textContent    = val(s.notes);
    document.getElementById('sc-notes-wrap').style.display = s.notes ? '' : 'none';

    // Footer seniority
    if (s.date_embauche) {
        const yrs = Math.floor((new Date() - new Date(s.date_embauche)) / (365.25*24*3600*1000));
        document.getElementById('smodal-hired-since').textContent =
            yrs > 0 ? `${yrs} an${yrs > 1 ? 's' : ''} d'ancienneté` : 'Embauché(e) cette année';
    } else {
        document.getElementById('smodal-hired-since').textContent = '';
    }
    document.getElementById('smodal-edit-link').href = '/staff/' + s.id + '/edit';

    // Reset tabs
    switchSTab(document.querySelector('.smodal-tab'), 'identity');

    // Animate in
    document.getElementById('staff-modal-backdrop').classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeStaffModal() {
    document.getElementById('staff-modal-backdrop').classList.remove('show');
    document.body.style.overflow = '';
}

function switchSTab(btn, tab) {
    document.querySelectorAll('.smodal-tab').forEach(t => t.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.stab-panel').forEach(p => p.style.display = 'none');
    document.getElementById('stab-' + tab).style.display = '';
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') closeStaffModal();
});

/* ── Live search ── */
document.getElementById('staff-search').addEventListener('input', function () {
    const q = this.value.toLowerCase().trim();
    const rows = document.querySelectorAll('#staff-tbody tr[data-search]');
    let visible = 0;
    rows.forEach(row => {
        const match = !q || row.dataset.search.includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
    });
    document.getElementById('staff-no-results').style.display = (visible === 0 && q) ? '' : 'none';
});
</script>

@endsection
