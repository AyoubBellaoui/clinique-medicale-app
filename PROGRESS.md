# ClinicPro — Development Log

A step-by-step story of how this app was built, in build order, so it's easy to re-read and remember the journey. Keep adding new numbered steps as work continues — don't regenerate this file from scratch.

**Last updated:** 2026-07-08 (last commit so far: `bee6275`, 2026-07-06)

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

---

## Where things stand right now

| Module | Status |
|---|---|
| Auth | ✅ Login/logout + rate limiting work. "Forgot password" view exists, no backend. |
| **Patients** | ✅ Full CRUD, working end-to-end. |
| **Staff médical** | ✅ Full CRUD, working end-to-end. |
| **File d'attente** (Queue) | ⚠️ Can list and add entries; edit/delete not implemented. |
| **Rendez-vous** (Appointments) | ❌ View-only mockup, no save logic. |
| **Consultations** | ❌ View-only mockup, no save logic. |
| **Ordonnances** (Prescriptions) | ❌ View-only mockup, no save logic. |
| **Facturation** (Billing) | ❌ View-only mockup, no save logic. |
| Notifications | ✅ Working, broadcasts from Patients/Staff/Queue. |
| Mon compte (Account) | ⚠️ Displays only, no editing. |
| Dashboard | ✅ Working. |

**Bottom line:** the app has a solid, reusable pattern established twice (Patients, Staff médical: validation → model → `ClinicNotification::broadcast` → flash message → redirect). The next logical step is applying that same pattern to Rendez-vous, Consultations, Ordonnances, and Facturation, then finishing File d'attente's edit/delete.

---

*To extend this log: append a new numbered step under "The story so far" describing what was built, and refresh the status table if a stub module became complete.*
