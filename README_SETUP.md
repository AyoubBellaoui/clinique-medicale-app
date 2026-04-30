# ClinicPro — Setup Instructions

## 1. Requirements
- PHP 8.2+
- MySQL
- Composer
- Node.js + npm

## 2. Environment
Edit `.env` and set your DB credentials:
```
DB_DATABASE=medical_app_db
DB_USERNAME=root
DB_PASSWORD=
```

## 3. Install & Setup
```bash
composer install
php artisan key:generate   # only if APP_KEY is missing
php artisan migrate
php artisan storage:link
npm install && npm run build
php artisan serve
```

## 4. Create First User
```bash
php artisan tinker
\App\Models\User::create([
    'email'    => 'admin@clinicpro.ma',
    'password' => bcrypt('password'),
    'role'     => 'admin',
]);
```

## 5. Access
- Login: http://localhost:8000/
- Dashboard: http://localhost:8000/dashboard

## Modules
| URL | Description |
|-----|-------------|
| /dashboard | KPI + résumé |
| /patients | CRUD patients + export CSV |
| /staff | CRUD staff médical |
| /queue | File d'attente + workflow statuts |
| /consultations | Dossiers + upload fichiers médicaux |
| /account | Profil + mot de passe |
| /search?q= | AJAX recherche globale |
