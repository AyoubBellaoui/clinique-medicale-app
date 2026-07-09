# ClinicPro — Development Log

A step-by-step story of how this app was built, in build order, so it's easy to re-read and remember the journey. Keep adding new numbered steps as work continues — don't regenerate this file from scratch.

**Last updated:** 2026-07-09 (last commit so far: `b369fd8`, 2026-07-08; step 19 / File d'attente CRUD below is uncommitted, working-tree changes ready to commit)

---

## The story so far

**1. Created the Laravel app**
Bootstrapped a fresh Laravel 13 project (PHP 8.3): `composer.json`, `artisan`, `bootstrap/app.php`, base `config/*` files, Vite + Tailwind CSS v4 tooling (`vite.config.js`, `package.json`), PHPUnit test scaffold. App named **ClinicPro**.

**2. Connected the app to the database**
Configured `.env` for MySQL: `DB_CONNECTION=mysql`, database **`medical_app_db`**, host `127.0.0.1:3306`. Sessions, cache, and queue all backed by the database driver (`SESSION_DRIVER=database`, `CACHE_STORE=database`, `QUEUE_CONNECTION=database`).

**3. Built the frontend shell**
Set up Tailwind CSS + Vite pipeline (`resources/css/app.css`, `resources/js/app.js`), plus a custom `public/css/clinicpro.css` / `public/js/clinicpro.js`. Built the main layout `resources/views/layouts/app.blade.php` (sidebar, topbar, navigation) that every module page extends.

**4. Designed the first database schema**
Wrote the initial migrations: `users`, `patients`, `staff_medicals`, `file_attentes`, `consultations`, plus Laravel's default `cache`, `jobs`, `sessions` tables.

**5. Added seeders**
`database/seeders/AdminUserSeeder.php` + `DatabaseSeeder.php` to bootstrap a default admin account so the app is usable right after `php artisan migrate --seed`.

**6. Built authentication**
`AuthController` (login/logout), `auth/Login.blade.php` view, login rate limiting (5 attempts/minute via `RateLimiter`), session regeneration on login. `auth/Forgetpassword.blade.php` view was added later as a mockup — no backend logic behind it yet.

**7. Built the Patients module (first pass)**
`PatientsController` (later renamed `PatientController`), `Patient` model, `patients/index` and `patients/create` views. Schema grew over several passes: identity fields, then medical fields (blood type, allergies, history), then insurance/emergency-contact fields, then a `color` tag column — each via its own migration (`add_fields_to_patients_table`, `add_color_to_patients_table`).

**8. Built the Staff médical module (Staff)**
`StaffMedicalController`, `StaffMedical` model, `staff/create` and `staff/index` views. Schema evolved similarly: roles (médecin, infirmier, secrétaire, admin, technicien), contract/schedule fields, then `color` and `license_number` columns added later.

**9. Added flash notifications (UI toasts)**
Integrated `php-flasher/flasher-laravel` (all its visual themes) so every create/update/delete action gives user feedback.

**10. Completed CRUD for Staff médical**
Added `edit`/`update`/`destroy` to `StaffMedicalController`, built `staff/edit.blade.php`. Staff médical became the first fully working end-to-end module.

**11. Built the remaining module skeletons**
Added models + migrations for `RendezVous` (Rendez-vous / Appointments), `Ordonnances` (Prescriptions), and completed `FileAttente` (File d'attente / Queue). Created controllers for all of them — but only `FileAttenteController` got a working `store()`; `RendezVousController`, `ConsultationController`, `OrdonnancesController`, and `FacturesController` (Facturation / Billing) were left as **stubs** (`index` + `create` only, views are static mockups with no persistence).

**12. Added the Dashboard, Account, and card views**
`DashboardController`, `AccountController` (index only, no profile editing yet), and a card-style list view for Staff médical alongside the table view.

**13. Added dark mode**
Theme toggle wired into the main layout and the login page.

**14. Completed CRUD for Patients**
Added `edit`/`update`/`destroy` to `PatientController`, built `patients/edit.blade.php`, added search/filter to `patients/index`. Patients became the second fully working end-to-end module.

**15. Built the in-app notification system**
New `notifications` table, `NotificationController`, and a `ClinicNotification::broadcast()` helper that fires whenever a patient/staff record is created, updated, or deleted, or a patient joins the queue. Wired a notification bell into the layout. Rewrote `README.md` with full install/run instructions.

**16. Produced UML documentation**
Class diagram and use-case diagram for the project, stored under `UML/`.

**17. Produced the academic report package**
Added `Rapport/`: professional dissertation (`Mémoire professionnel`, docx + pdf), ERD, MVC, and sequence diagrams — material for the internship/thesis defense.

**18. Completed CRUD for Rendez-vous (Appointments)**
Extended the `rendez_vous` table with `motif`, `type_consultation`, `duree` (minutes), `priorite`, and `notes` (migration `add_details_to_rendez_vous_table`) so the existing create-form fields actually have somewhere to persist to. Updated the `RendezVous` model's `$fillable`/`$casts` accordingly. Built out `RendezVousController` (was `index`+`create` only) with real `store`/`edit`/`update`/`destroy`, following the same validate → save → `ClinicNotification::broadcast` → flash → redirect pattern as Patients/Staff médical. Rewrote `appointments/create.blade.php` to use real `$patients`/`$doctors` dropdowns instead of hardcoded fake names, fixed several field-name mismatches that would have silently dropped data (`doctor_id`→`staff_id`, `time_start`→`heure`, missing `value` attributes on enum selects), and dropped the reminder (SMS/email) fields since the app has no notification-delivery mechanism to back them. Rewrote `appointments/index.blade.php` to pull real data (stats, calendar, upcoming list) instead of a hardcoded array, with manual French month-name mapping since Carbon's locale isn't set to `fr` app-wide. Added `appointments/edit.blade.php` (new file, same shape as `staff/edit.blade.php`). Full create → list → edit → update → delete cycle verified against the real `medical_app_db` database via authenticated HTTP requests. Rendez-vous became the third fully working end-to-end module.

**19. Completed CRUD for File d'attente (Queue)**
Extended the `file_attentes` table with `priorite`, `type_visite`, and `motif` (migration `add_details_to_file_attentes_table`), same treatment step 18 gave Rendez-vous, so the create-form's priority/visit-type/symptom fields have somewhere to persist. Updated `FileAttente`'s `$fillable` accordingly. Normalized the route names in `web.php` from mixed `FileAttente.*`/`fileAttente.*` casing to all-lowercase `fileAttente.*`, matching every other module. Built out `FileAttenteController` with real `index` (today's queue only, `whereDate('arrived_at', today())`, ordered by `position`), `create` (real `$patients`/`$doctors` dropdowns, plus today's un-checked-in `RendezVous` for optional linking), `store`, `edit` (new), `update` (new), and `destroy` — same validate → save → broadcast → flash → redirect pattern as the other modules. Rewrote both `fileAttente/index.blade.php` and `fileAttente/create.blade.php`, which previously rendered hardcoded mock arrays and had a `create.blade.php` form pointing at `action="#"` (submitted nowhere); fixed the same class of field-name mismatch fixed for Rendez-vous in step 18 (`doctor_id`→`staff_id`, `priority`→`priorite`, `symptoms`→`motif`). Added `fileAttente/edit.blade.php` (new file). `position` is now computed on `store()` as a per-day ticket number (`count of today's entries + 1`); the index view computes "patients ahead" / average wait live from the active (`en_attente`/`en_cours`) entries rather than storing them. The index table's row actions are inline one-field status transitions (En attente → **En cours** → **Terminer**) submitted as small POST/PUT forms carrying the entry's existing values as hidden fields, alongside a full edit page and a delete button — same UX pattern as the Rendez-vous "quick edit" row actions.

Also implemented step 2 of the "Automatic `rendez_vous.statut` transitions" design decision logged after step 18 (see below): `store()` now accepts an optional `rendez_vous_id` (a same-day `programme`/`confirme` appointment not yet checked in); when a queue entry is created against a `programme` appointment, that appointment's `statut` auto-flips to `confirme` and a second `ClinicNotification::broadcast` logs the auto-transition separately from the "added to queue" notification, so there's an audit trail distinguishing a manual edit from an automatic one. Step 3 of that plan (auto-`termine` driven by Facturation) is still deferred — Facturation doesn't exist yet.

Full create → list → status transitions (`en_attente`→`en_cours`→`termine`) → edit → delete → rendez-vous-link-and-auto-confirm cycle verified against the real `medical_app_db` database via authenticated HTTP requests (`php artisan serve` on a scratch port, curl with a real login session and CSRF tokens); also verified invalid submissions (missing `patient_id`) are rejected by validation without creating a row. All test data created during verification was deleted afterward. File d'attente became the fourth fully working end-to-end module.

**20. File d'attente / Rendez-vous polish pass**
Auto-fill doctor from patient: `fileAttente/create.blade.php` now auto-selects the patient's assigned `medecin_id` when a patient is picked, and further auto-links a matching same-day rendez-vous (adopting its time/doctor/`avec_rdv` visit-type) — explicitly resetting back to walk-in defaults (`sans_rdv`, current time) when switching to a patient with no same-day appointment, so stale linked state (or Laravel's `old()` sticky input after a failed submission) can never misrepresent the currently-selected patient. Same `medecin_id` auto-fill added to `appointments/create.blade.php` for consistency. While building this, found and fixed a real pre-existing bug: a page-load IIFE in `fileAttente/create.blade.php` called `selectPriority()`/`updatePreview()` *before* the `const` declarations (`visitLabels`, `prioColors`) those functions depend on were reached in the same `<script>` block — a genuine temporal-dead-zone `ReferenceError` in every browser that silently killed all of the page's interactivity (live preview, and later the auto-fill), masked because the initial preview values are also baked into the server-rendered HTML. Fixed by moving the IIFE to the end of the script, after everything it depends on.

Also fixed a broken "médecin" icon: an SVG `<path>` used in 5 places (twice in `fileAttente/create.blade.php`, twice in `patients/create.blade.php`, twice in `patients/edit.blade.php`) turned out to be a truncated fragment of a *different*, complete icon (the "beaker" icon used for Ordonnances) — replaced with the correct complete doctor icon (same academic-cap icon as the sidebar's Staff Médical link) in those, and with the complete beaker icon in a 6th spot that actually needed it (`consultations/index.blade.php`'s "Ordonnances émises" stat had the same truncated fragment but needed the prescription icon, not a doctor one). Added a "Médecin traitant" row to the patient view/detail modal (`patients/index.blade.php`, Médical tab), backed by eager-loading `Patient::with('medecin')` in `PatientController::index()` to avoid N+1 queries.

Three "make it more professional" fixes to the queue itself: (1) the index no longer sorts by pure arrival order — it now orders active entries by priority (`urgente` → `haute` → `normale`) before falling back to arrival position, and moves resolved (`termine`/`annule`) entries to the bottom, so an urgent walk-in actually jumps the queue instead of waiting behind normal-priority patients who arrived earlier; (2) added no-show detection — `FileAttenteController::index()` computes today's rendez-vous still `programme` whose scheduled time has already passed with no linked queue entry, surfaced as a "Patients non présentés" warning card with an "Enregistrer l'arrivée" link that deep-links to the create page (`?rdv=<id>`), pre-selecting and auto-applying that appointment on load; (3) added a unique index on `file_attentes.rendez_vous_id` (migration `add_unique_index_to_file_attentes_rendez_vous_id`) plus a matching `unique:file_attentes,rendez_vous_id` validation rule in `store()`, so the same rendez-vous can't end up linked to two queue entries from a double submit — a clean validation message instead of a raw SQL constraint violation.

Everything verified against the real database via authenticated HTTP requests, including a genuine bug caught mid-testing (the late-appointment "minutes late" badge showed a negative fractional number like `+-24.39 min` due to a Carbon `diffInMinutes` sign/precision quirk — fixed with `abs()` + rounding). The client-side JS auto-fill/reset logic was verified by extracting the actual rendered `<script>` from a live response and executing it in Node against a minimal fake DOM, rather than just eyeballing the code — caught the TDZ crash this way before it reached production. All test data created during verification was deleted afterward (except rendez-vous #2 / queue entry #3, which the user created themselves while testing the check-in flow live).

**21. Rendez-vous: double-booking prevention + past-time guard**
Audited `RendezVousController` for the same class of gap fixed on File d'attente in step 20 and found two: nothing stopped the same médecin from being booked twice at the same date+time, and nothing stopped booking a slot on today's date that had already passed. Fixed both in `store()` and `update()`: `heure` now carries a `Rule::unique('rendez_vous', 'heure')->where(...)` scoped to the same `staff_id` + `date`, excluding cancelled (`annule`) appointments so a freed-up slot can be rebooked, and `update()` adds `->ignore($appointment->id)` so resubmitting an appointment unchanged (or editing its other fields) doesn't collide with itself. `store()` additionally throws a `ValidationException` if the submitted date is today and the time has already passed — scoped to `store()` only, since `update()` legitimately needs to keep editing/closing out appointments whose time has already come and gone.

Verified all four directions against the real database via authenticated HTTP requests: same doctor/same slot rejected; same slot with a *different* doctor accepted; resubmitting an appointment unchanged accepted (ignore-self works); moving one appointment onto another's already-booked slot rejected; a past time today rejected while a later time today succeeded; and cancelling an appointment correctly freed its slot for a new booking. Hit and fixed an unrelated environment snag while starting the test server — `public/build/manifest.json` was missing (Vite assets never built in this environment), causing every page to 500; a one-time `npm run build` resolved it, unrelated to any code change. All test appointments created during verification were deleted afterward.

**22. Completed CRUD for Consultations**
The mockup's create form (vital signs, diagnostic, traitement, four "examens complémentaires" checkboxes, next-visit scheduling) needed far more columns than the original `consultations` table had (just `date_consultation`, `diagnostic`, `notes`, `patient_id`, `staff_id`). Extended it (migration `add_details_to_consultations_table`) with `motif`, `type_consultation` (same enum as Rendez-vous), seven vital-sign columns (`tension_systolique`/`tension_diastolique`/`frequence_cardiaque`/`temperature`/`spo2`/`poids`/`taille`), `traitement`, four boolean order flags (`ordonnance_demandee`/`scanner_demande`/`analyse_demandee`/`kine_demandee`), and `prochain_rdv_date`. Built out `ConsultationController` (was `index`+`create` returning bare views, no data at all) with real `store`/`edit`/`update`/`destroy`, the same validate → save → broadcast → flash → redirect pattern as every other module, plus a shared private `validateConsultation()` helper since `store`/`update` share the same rule set. A consultation's date is validated `before_or_equal:today` — unlike a rendez-vous, a consultation is a record of something that already happened, so a future date doesn't make sense. Rewrote `consultations/index.blade.php` (hardcoded 6-row mock array → real data, real stat counts) and `consultations/create.blade.php` (fixed the same class of field-name mismatch as every prior module: `doctor_id`→`staff_id`, `reason`→`motif`, `diagnosis`→`diagnostic`, `treatment`→`traitement`, `bp_sys`/`bp_dia`→`tension_systolique`/`tension_diastolique`, `heart_rate`→`frequence_cardiaque`, `temp`→`temperature`, `weight`→`poids`, `height`→`taille`, short checkbox names `ord`/`scan`/`anal`/`kine`→their full boolean column names; dropped the redundant `specialty` field since that's already available via the selected doctor's own `specialite`). Added `consultations/edit.blade.php` (new file). The "Dans" follow-up-delay dropdown is wired via a small `applyDelay()` JS function that computes the "Prochain rendez-vous" date from the consultation date + N days, verified with a Node date-math check.

Closed a cross-module loop the same way step 20 did for File d'attente/Rendez-vous: if `prochain_rdv_date` is filled in, `store()` auto-creates a follow-up `RendezVous` for the same patient/doctor (type `suivi`, default 09:00) — but first checks for a scheduling conflict at that slot (reusing the same logic as step 21's double-booking guard) and skips auto-scheduling with a flash notice rather than silently creating a bad double-booked appointment. Verified end-to-end against the real database: full field set (vitals, flags, motif, diagnostic, traitement) persists correctly including unchecked/checked checkboxes; future-dated consultations are rejected; the follow-up rendez-vous auto-creates correctly when a next-visit date is given; edit page pre-fills every field correctly; update correctly toggles a flag off; delete works. Consultations became the fifth fully working end-to-end module. All test data created during verification was deleted afterward.

**23. Completed CRUD for Ordonnances (Prescriptions)**
This one didn't even have a model — `app/Models/Ordonnance.php` didn't exist yet, only a bare `ordonnances` migration (`consultation_id` **required**, `contenu` text, nothing else) and a controller returning empty views. The create-form mockup needed far more: patient/doctor/date, an *optional* consultation link (mismatched against the required FK), validity duration, a dynamic multi-row medication list (name/posologie/durée per row, add/remove via JS), instructions, renewal count, generic-substitution toggle, and private notes. Extended the table (migration `add_details_to_ordonnances_table`) with `patient_id`/`staff_id` (denormalized directly on the ordonnance rather than only reachable through the consultation, since the mockup treats the consultation link as optional), `date_prescription`, `duree_validite`, `diagnostic_associe`, `instructions`, `renouvelable`, `substitution_autorisee`, `notes_privees`, and made `consultation_id`/`contenu` nullable. Modifying existing column nullability needed `->change()`, which needs `doctrine/dbal` — not installed in this project — so used raw `DB::statement('ALTER TABLE ... MODIFY ...')` instead of adding a new dependency.

The medication list is genuinely one-to-many (a prescription can list any number of drugs), so unlike every other module so far this one got a proper child table: new `ordonnance_medicaments` (`ordonnance_id`, `nom`, `posologie`, `duree`, cascade-deletes with the parent) plus a new `OrdonnanceMedicament` model and an `Ordonnance::medicaments()` hasMany. `store`/`update` fully replace an ordonnance's medication rows on every save (delete-then-recreate from the submitted array) rather than diffing — simpler and correct for a form that always resubmits the complete list. `contenu` (the original bare-bones text column) is now auto-generated from the structured medication list, kept for backward compatibility/print-friendly display rather than requiring the doctor to type a redundant summary by hand.

Built out `OrdonnancesController` with real `index`/`create`/`store`/`edit`/`update`/`destroy`, and — matching the auto-fill work from steps 20/22 — picking a patient auto-fills their assigned doctor, and picking a linked consultation auto-fills patient/doctor/diagnostic from that consultation (same `data-*` attribute + small JS pattern used everywhere else). Rewrote `prescriptions/index.blade.php` and `prescriptions/create.blade.php` (hardcoded 5-item mock array → real data; fixed `doctor_id`→`staff_id`, `diagnosis`→`diagnostic_associe`, `duration`(top-level)→`duree_validite`, `renewable`→`renouvelable`, `generic_allowed`→`substitution_autorisee`, `private_notes`→`notes_privees`; kept the existing `meds[i][name/dosage/duration]` JS repeater as-is since the controller was written to match it). Added `prescriptions/edit.blade.php`, which repopulates the dynamic medication rows from the database instead of just a single blank row.

Hit and fixed a genuine Blade compiler bug while testing: `@if($a) X @endif@if($b) Y @endif` — two conditionals glued with zero whitespace on one line — silently failed to compile the second `@if` (it passed through as literal text while its `@endif` *did* compile), producing a stray `endif` and a 500 on every page load. Adding a space or a `{{-- --}}` comment between them didn't reliably fix it either; replaced the whole expression with a small `@php` block that builds the string in plain PHP, which sidesteps the issue entirely. Verified end-to-end against the real database: an ordonnance linked to a consultation auto-fills correctly, multiple medications persist and display correctly, editing correctly drops a removed medication and adds fields for new ones (cascade-delete verified), submitting with a blank medication name is rejected, and deleting an ordonnance cascades to delete its medication rows. Ordonnances became the sixth fully working end-to-end module. All test data created during verification was deleted afterward.

**24. Completed CRUD for Facturation (Billing) — last remaining module**
Started from nothing: no model, no migration, no table at all, just a controller returning two empty views. The mockup was the most complex of all six modules built this cycle: patient/doctor/optional-consultation-link, billing + due dates, payment method, an *unbounded* multi-row "Prestations" list (designation/qty/unit price, a preset dropdown that autofills price or "Autre" for a custom one), discount %, tax %, insurance name + reimbursement rate, notes, and a client-computed running total (subtotal → after discount → after tax) that had to match server-side exactly. Built `factures` (invoice number, patient/nullable-staff/nullable-consultation, billing/due dates, payment method, `statut` enum `en_attente`/`paye` — deliberately *not* storing a third "overdue" state, since "overdue" is just "unpaid past its due date" and computing it live avoids needing a background job to flip statuses as due dates pass) and a second child table `facture_lignes` (designation/quantité/prix_unitaire, cascade-deletes with the parent) — the same one-to-many pattern as Ordonnances' medications in step 23, for the same reason: an invoice can list any number of billed services.

`FacturesController` computes `sous_total`/`total_ttc` server-side using the exact same formula as the page's live-preview JS (`sous_total × (1 − remise/100) × (1 + tva/100)`), and generates sequential invoice numbers (`FAC-{year}-{0001}`) counted per calendar year. Added one route beyond the standard CRUD set, `PUT /billing/{id}/mark-paid` → `markPaid()`, instead of overloading the generic `update()` route for the index page's one-click "Marquer payé" action — `update()` needs the full form payload (all fields + the complete service-line array) rebuilt from hidden inputs to work safely, which doesn't fit a lightweight one-click action, so `markPaid()` only ever touches `statut`/`paid_at` and leaves everything else alone. The two dashboard charts (monthly revenue bar chart, payment-method breakdown polar chart) that were hardcoded arrays in the mockup now aggregate real data from the `$factures` collection in the controller (correctly rendering all zeros right now, since there's no seed billing data yet — accurate, not faked).

Revisited the "Facturation paid → auto-mark the rendez-vous `termine`" idea logged in step 19's design-decision notes, and deliberately did **not** build it this pass: that chain needs `Facture → Consultation → RendezVous`, but `Consultation` has no link back to the appointment it fulfilled (its own mockup never had one, so step 22 didn't add it) — building a reliable inference for that link now, without any new field or UI to anchor it to, risked fragile guessing (which rendez-vous did this consultation actually close out?) rather than a real fix. Left as a clearly-flagged follow-up rather than force-fitting it. Verified end-to-end against the real database: totals compute correctly with combined discount+tax, marking an invoice paid updates its status/timestamp and disappears from the "en attente" list, an unpaid invoice past its due date correctly displays as "En retard" (computed, not stored) and rolls into the retard stat, editing correctly replaces the service-line set and recalculates totals, a blank service name is rejected, and deleting an invoice cascades to its line items. All test data created during verification was deleted afterward.

With Facturation done, all seven core modules (Patients, Staff médical, File d'attente, Rendez-vous, Consultations, Ordonnances, Facturation) are now fully working end-to-end — see the status table below.

---

## Where things stand right now

| Module | Status |
|---|---|
| Auth | ✅ Login/logout + rate limiting work. "Forgot password" view exists, no backend. |
| **Patients** | ✅ Full CRUD, working end-to-end. |
| **Staff médical** | ✅ Full CRUD, working end-to-end. |
| **File d'attente** (Queue) | ✅ Full CRUD, working end-to-end. |
| **Rendez-vous** (Appointments) | ✅ Full CRUD, working end-to-end. |
| **Consultations** | ✅ Full CRUD, working end-to-end. |
| **Ordonnances** (Prescriptions) | ✅ Full CRUD, working end-to-end. |
| **Facturation** (Billing) | ✅ Full CRUD, working end-to-end. |
| Notifications | ✅ Working, broadcasts from Patients/Staff/Queue/Rendez-vous/Consultations/Ordonnances/Facturation. |
| Mon compte (Account) | ⚠️ Displays only, no editing. |
| Dashboard | ✅ Working. |

**Bottom line:** all seven core clinic modules are now fully working end-to-end, each built on the same pattern (validation → model → `ClinicNotification::broadcast` → flash message → redirect), verified against the real database, not just eyeballed. What's left is peripheral, not core workflow: "Mon compte" has no editing, "Mot de passe oublié" has no backend, and UML/report material in `UML/`/`Rapport/` is documentation rather than app code. Worth deciding next: polish/harden what exists (e.g. the deferred Facturation↔Consultation↔Rendez-vous auto-close loop, or a `Consultation → RendezVous` link to unlock it), or finish the remaining peripheral pieces (Account editing, password reset).

---

## Planned design decisions

**Automatic `rendez_vous.statut` transitions, driven by File d'attente / Facturation**
Decided 2026-07-08. The appointment's status updates itself as a side effect of what happens in other modules, instead of a secretary manually flipping it:

1. Patient is booked → `RendezVous.statut = 'programme'` (already works, default value).
2. ✅ **Done (step 19):** day of the appointment, patient checks in at the front desk → optionally links a `FileAttente` row to the appointment via `rendez_vous_id`. `FileAttenteController::store()` now auto-updates the linked `RendezVous.statut` to `'confirme'` when this happens, and logs it via a dedicated `ClinicNotification::broadcast()` separate from the "added to queue" notification.
3. **Still pending:** consultation happens and the bill gets paid (in `FacturesController`, not built yet) → auto-update `RendezVous.statut` to `'termine'`. Implement when Facturation is built, hooking into wherever billing gets marked paid rather than duplicating status-sync logic.

No new enum values needed — `programme/confirme/annule/termine` on `rendez_vous.statut` already cover this flow.

---

*To extend this log: append a new numbered step under "The story so far" describing what was built, and refresh the status table if a stub module became complete.*
