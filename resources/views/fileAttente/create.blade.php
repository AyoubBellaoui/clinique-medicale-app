@extends('layouts.app')

@section('title', "Ajouter à la File d'Attente")
@section('page-title', "Ajouter à la File d'Attente")
@section('page-subtitle', "Enregistrer l'arrivée d'un patient")

@section('content')

{{-- Back --}}
<a href="{{ route('fileAttente.index') }}" style="display:inline-flex;align-items:center;gap:6px;font-size:13px;color:var(--muted);text-decoration:none;margin-bottom:20px;font-weight:500;transition:color .15s;" onmouseenter="this.style.color='var(--teal-600)'" onmouseleave="this.style.color='var(--muted)'">
    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/></svg>
    Retour à la file d'attente
</a>

{{-- Queue status mini-cards --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px;margin-bottom:24px;">
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

<form action="#" method="POST" id="queueForm">
    @csrf

    <div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">

        {{-- ── LEFT: Form ── --}}
        <div>

            {{-- Patient section --}}
            <div class="card" style="margin-bottom:16px;overflow:hidden;">

                <div style="padding:20px 24px 18px;background:linear-gradient(135deg,var(--teal-50),rgba(255,255,255,0));border-bottom:1px solid rgba(52,168,140,.1);display:flex;align-items:center;gap:14px;">
                    <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,var(--teal-400),var(--teal-600));display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(52,168,140,.3);">
                        <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Informations patient</h3>
                        <span style="font-size:12px;color:var(--muted);font-weight:500;">Sélectionner le patient et le médecin assigné</span>
                    </div>
                </div>

                <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                    <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                        <label class="form-label">Patient <span style="color:#f43f5e;">*</span></label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0"/></svg>
                            <select class="form-control form-select pf-input" name="patient_id" id="patient_select" onchange="updatePreview()">
                                <option value="">— Sélectionner un patient —</option>
                                <option value="1">Omar Benhaddou</option>
                                <option value="2">Meriem Tahiri</option>
                                <option value="3">Rachid Amrani</option>
                                <option value="4">Nadia Filali</option>
                                <option value="5">Fatima El Idrissi</option>
                                <option value="6">Aicha Moussaoui</option>
                                <option value="7">Hassan Ouazzani</option>
                                <option value="8">Youssef Benali</option>
                                <option value="9">Zineb Chraibi</option>
                            </select>
                        </div>
                        <div style="margin-top:6px;font-size:12px;color:var(--muted);">Patient non trouvé ? <a href="{{ url('/patients/create') }}" style="color:var(--teal-600);text-decoration:none;font-weight:600;">Créer un nouveau dossier</a></div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Médecin assigné <span style="color:#f43f5e;">*</span></label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5"/></svg>
                            <select class="form-control form-select pf-input" name="doctor_id" id="doctor_select" onchange="updatePreview()">
                                <option value="">— Sélectionner —</option>
                                <option value="1">Dr. Mehdi Alaoui — Cardiologie</option>
                                <option value="2">Dr. Sara Tazi — Méd. Générale</option>
                                <option value="3">Dr. Karim Fassi — Pédiatrie</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Heure d'arrivée</label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <input type="time" class="form-control pf-input" name="arrived_at" id="arrived_at">
                        </div>
                    </div>

                </div>
            </div>

            {{-- Visit details section --}}
            <div class="card" style="margin-bottom:16px;overflow:hidden;">

                <div style="padding:20px 24px 18px;background:linear-gradient(135deg,rgba(245,158,11,.04),rgba(255,255,255,0));border-bottom:1px solid rgba(245,158,11,.1);display:flex;align-items:center;gap:14px;">
                    <div style="width:40px;height:40px;border-radius:12px;background:linear-gradient(135deg,#fbbf24,#f59e0b);display:flex;align-items:center;justify-content:center;flex-shrink:0;box-shadow:0 4px 12px rgba(245,158,11,.3);">
                        <svg width="18" height="18" fill="none" stroke="#fff" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0118 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375"/></svg>
                    </div>
                    <div>
                        <h3 style="font-size:15px;font-weight:700;color:var(--teal-800);margin:0;line-height:1.2;">Détails de la visite</h3>
                        <span style="font-size:12px;color:var(--muted);font-weight:500;">Priorité, type de visite et motif</span>
                    </div>
                </div>

                <div style="padding:24px;display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Priorité</label>
                        <input type="hidden" name="priority" id="priority_input" value="normale">
                        <div style="display:flex;flex-wrap:wrap;gap:8px;">
                            <button type="button" class="prio-btn" data-prio="normale" onclick="selectPriority('normale')"
                                style="padding:9px 16px;border-radius:10px;border:1.5px solid rgba(52,168,140,.2);background:#fff;color:var(--muted);font-size:12.5px;font-weight:600;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:6px;">
                                <span style="width:8px;height:8px;border-radius:50%;background:#10b981;"></span> Normale
                            </button>
                            <button type="button" class="prio-btn" data-prio="haute" onclick="selectPriority('haute')"
                                style="padding:9px 16px;border-radius:10px;border:1.5px solid rgba(245,158,11,.2);background:#fff;color:var(--muted);font-size:12.5px;font-weight:600;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:6px;">
                                <span style="width:8px;height:8px;border-radius:50%;background:#f59e0b;"></span> Haute
                            </button>
                            <button type="button" class="prio-btn" data-prio="urgente" onclick="selectPriority('urgente')"
                                style="padding:9px 16px;border-radius:10px;border:1.5px solid rgba(239,68,68,.2);background:#fff;color:var(--muted);font-size:12.5px;font-weight:600;cursor:pointer;transition:all .2s;display:flex;align-items:center;gap:6px;">
                                <span style="width:8px;height:8px;border-radius:50%;background:#ef4444;"></span> Urgente
                            </button>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Type de visite</label>
                        <div style="position:relative;">
                            <svg style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:var(--muted);pointer-events:none;" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25"/></svg>
                            <select class="form-control form-select pf-input" name="visit_type" id="visit_type" onchange="updatePreview()">
                                <option value="sans_rdv">Sans rendez-vous</option>
                                <option value="avec_rdv">Avec rendez-vous</option>
                                <option value="urgence">Urgence</option>
                                <option value="suivi">Suivi</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom:0;grid-column:1/-1;">
                        <label class="form-label">Motif / Symptômes</label>
                        <textarea class="form-control" name="symptoms" id="symptoms" rows="3" placeholder="Décrire brièvement les symptômes ou le motif de la visite…" oninput="updatePreview()"></textarea>
                    </div>

                </div>

                <div style="padding:16px 24px;background:var(--teal-50);border-top:1px solid rgba(52,168,140,.1);display:flex;justify-content:flex-end;gap:10px;">
                    <a href="{{ route('fileAttente.index') }}" class="btn btn-outline">Annuler</a>
                    <button type="submit" class="btn btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                        Ajouter à la file
                    </button>
                </div>
            </div>

        </div>{{-- end left --}}

        {{-- ── RIGHT: Sticky preview ── --}}
        <div style="position:sticky;top:calc(var(--header-h,64px) + 20px);">

            {{-- Queue ticket preview --}}
            <div class="card" style="overflow:hidden;margin-bottom:14px;">
                <div id="preview-banner" style="height:70px;background:linear-gradient(135deg,var(--teal-700),var(--teal-500));position:relative;overflow:hidden;">
                    <div style="position:absolute;inset:0;background-image:radial-gradient(circle at 70% 30%,rgba(255,255,255,.12) 0%,transparent 60%);"></div>
                    <div style="position:absolute;inset:0;background-image:linear-gradient(rgba(255,255,255,.04) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.04) 1px,transparent 1px);background-size:18px 18px;"></div>
                    <div style="position:absolute;top:10px;right:14px;">
                        <span id="preview-prio-badge" style="padding:3px 10px;border-radius:20px;background:rgba(255,255,255,.22);backdrop-filter:blur(4px);font-size:11px;font-weight:700;color:#fff;">Normale</span>
                    </div>
                </div>

                <div style="padding:0 20px;position:relative;">
                    <div id="preview-num" style="width:62px;height:62px;border-radius:16px;background:linear-gradient(135deg,var(--teal-300),var(--teal-500));border:4px solid #fff;box-shadow:0 4px 14px rgba(52,168,140,.3);margin-top:-31px;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:800;color:#fff;letter-spacing:-.5px;">06</div>
                </div>

                <div style="padding:10px 20px 20px;">
                    <div id="preview-name" style="font-size:16px;font-weight:800;color:var(--teal-800);margin-bottom:2px;line-height:1.2;">Nouveau patient</div>
                    <div id="preview-visit" style="font-size:12px;color:var(--muted);font-weight:500;margin-bottom:12px;">Sans rendez-vous</div>

                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-bottom:14px;">
                        <div style="text-align:center;padding:8px 6px;border-radius:10px;background:var(--teal-50);border:1px solid rgba(52,168,140,.12);">
                            <div style="font-size:13px;font-weight:800;color:var(--teal-700);">~22 min</div>
                            <div style="font-size:10px;color:var(--muted);font-weight:600;">Attente estimée</div>
                        </div>
                        <div style="text-align:center;padding:8px 6px;border-radius:10px;background:var(--teal-50);border:1px solid rgba(52,168,140,.12);">
                            <div id="preview-arrived" style="font-size:13px;font-weight:800;color:var(--teal-700);">—</div>
                            <div style="font-size:10px;color:var(--muted);font-weight:600;">Arrivée</div>
                        </div>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:7px;">
                        <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;">
                            <svg width="13" height="13" fill="none" stroke="var(--muted)" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5"/></svg>
                            <span id="preview-doctor" style="color:var(--muted);">Médecin non assigné</span>
                        </div>
                        <div style="display:flex;align-items:center;gap:8px;font-size:12.5px;margin-top:4px;">
                            <span id="preview-status-badge" class="badge badge-amber">En attente</span>
                        </div>
                    </div>

                    <div id="preview-symptoms-wrap" style="display:none;margin-top:12px;padding:10px 12px;border-radius:10px;background:rgba(52,168,140,.05);border:1px solid rgba(52,168,140,.1);">
                        <div style="font-size:10.5px;font-weight:700;color:var(--teal-600);letter-spacing:.06em;text-transform:uppercase;margin-bottom:4px;">Motif</div>
                        <div id="preview-symptoms" style="font-size:12px;color:var(--teal-800);line-height:1.5;"></div>
                    </div>
                </div>
            </div>

            {{-- Info box --}}
            <div class="card" style="padding:16px;">
                <div style="font-size:11px;font-weight:700;color:var(--muted);letter-spacing:.08em;text-transform:uppercase;margin-bottom:12px;">Récapitulatif</div>
                <div style="display:flex;flex-direction:column;gap:8px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:12.5px;">
                        <span style="color:var(--muted);">Numéro d'ordre</span>
                        <strong style="color:var(--teal-700);">#06</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:12.5px;">
                        <span style="color:var(--muted);">Patients devant</span>
                        <strong style="color:var(--teal-700);">5</strong>
                    </div>
                    <div style="display:flex;justify-content:space-between;align-items:center;font-size:12.5px;">
                        <span style="color:var(--muted);">Attente estimée</span>
                        <strong style="color:var(--teal-700);">~22 min</strong>
                    </div>
                </div>
            </div>

        </div>{{-- end right --}}

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
.form-control.pf-input{padding-left:40px;}
select.form-control{appearance:none;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%237bbfb0' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");background-repeat:no-repeat;background-position:right 13px center;padding-right:36px;cursor:pointer;}
select.form-control.pf-input{padding-left:40px;}
textarea.form-control{resize:vertical;min-height:80px;line-height:1.65;}
.form-label{font-size:11px;font-weight:700;color:var(--teal-600);letter-spacing:.07em;text-transform:uppercase;margin-bottom:6px;display:flex;align-items:center;gap:5px;}
.prio-btn{font-family:'Plus Jakarta Sans',sans-serif;transition:all .18s;}
.prio-btn[data-prio="normale"].active{border-color:rgba(16,185,129,.4)!important;background:rgba(16,185,129,.08)!important;color:#059669!important;}
.prio-btn[data-prio="haute"].active{border-color:rgba(245,158,11,.4)!important;background:rgba(245,158,11,.08)!important;color:#d97706!important;}
.prio-btn[data-prio="urgente"].active{border-color:rgba(239,68,68,.4)!important;background:rgba(239,68,68,.08)!important;color:#dc2626!important;}
</style>
@endpush

@push('scripts')
<script>
// Set current time as default arrival time
(function() {
    const now = new Date();
    const hh = String(now.getHours()).padStart(2,'0');
    const mm = String(now.getMinutes()).padStart(2,'0');
    const timeInput = document.getElementById('arrived_at');
    if (timeInput) { timeInput.value = hh + ':' + mm; updatePreview(); }
    selectPriority('normale');
})();

const visitLabels = { sans_rdv:'Sans rendez-vous', avec_rdv:'Avec rendez-vous', urgence:'Urgence', suivi:'Suivi' };
const prioColors  = { normale:'rgba(255,255,255,.22)', haute:'rgba(245,158,11,.6)', urgente:'rgba(239,68,68,.7)' };

function updatePreview() {
    const patSel = document.getElementById('patient_select');
    const patName = patSel.options[patSel.selectedIndex]?.text || '';
    document.getElementById('preview-name').textContent = patName.includes('—') ? 'Nouveau patient' : patName;

    const docSel = document.getElementById('doctor_select');
    const docText = docSel.options[docSel.selectedIndex]?.text || '';
    const docEl = document.getElementById('preview-doctor');
    if (!docText.includes('—')) {
        docEl.textContent = docText.split('—')[0].trim();
        docEl.style.color = 'var(--teal-700)';
    } else {
        docEl.textContent = 'Médecin non assigné';
        docEl.style.color = 'var(--muted)';
    }

    const vtSel = document.getElementById('visit_type');
    document.getElementById('preview-visit').textContent = visitLabels[vtSel.value] || vtSel.value;

    const arrived = document.getElementById('arrived_at').value;
    document.getElementById('preview-arrived').textContent = arrived || '—';

    const sym = document.getElementById('symptoms').value.trim();
    const wrap = document.getElementById('preview-symptoms-wrap');
    if (sym) {
        wrap.style.display = 'block';
        document.getElementById('preview-symptoms').textContent = sym.length > 80 ? sym.slice(0,80) + '…' : sym;
    } else {
        wrap.style.display = 'none';
    }
}

function selectPriority(p) {
    document.getElementById('priority_input').value = p;
    document.querySelectorAll('.prio-btn').forEach(b => b.classList.toggle('active', b.dataset.prio === p));
    const badge = document.getElementById('preview-prio-badge');
    const labels = { normale:'Normale', haute:'Priorité haute', urgente:'URGENT' };
    badge.textContent = labels[p] || p;
    badge.style.background = prioColors[p] || prioColors.normale;

    const banner = document.getElementById('preview-banner');
    if (p === 'urgente') banner.style.background = 'linear-gradient(135deg,#dc2626,#ef4444)';
    else if (p === 'haute') banner.style.background = 'linear-gradient(135deg,#d97706,#f59e0b)';
    else banner.style.background = 'linear-gradient(135deg,var(--teal-700),var(--teal-500))';
}
</script>
@endpush
