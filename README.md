# Clinique Médicale — Application de Gestion

Application web de gestion de clinique médicale développée avec Laravel 13 et Tailwind CSS.

---

## Stack Technique

| Couche | Technologie |
|--------|-------------|
| Backend | PHP 8.3 · Laravel 13 |
| Base de données | MySQL |
| Frontend | Blade · Tailwind CSS v4 |
| Build | Vite 8 · laravel-vite-plugin |
| Auth | Laravel Auth (sessions) |
| Flash messages | php-flasher/flasher-laravel |
| Tests | PHPUnit 12 |

---

## Fonctionnalités

- Authentification avec rate limiting (5 tentatives / minute)
- Gestion des **patients** (dossier médical complet : groupe sanguin, allergies, antécédents, assurance, contact d'urgence)
- Gestion du **personnel médical** (médecins, secrétaires, admins — spécialité, contrat, planning)
- **File d'attente** en temps réel
- **Rendez-vous** (appointments)
- **Consultations**
- **Ordonnances** (prescriptions)
- **Facturation** (billing)
- Tableau de bord (dashboard)
- Gestion du compte utilisateur

---

## Prérequis

- PHP >= 8.3 avec extensions : `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`
- Composer
- Node.js >= 18 + npm
- MySQL >= 8

---

## Installation

### 1. Cloner le projet

```bash
git clone <url-du-repo> clinique-medicale-app
cd clinique-medicale-app
```

### 2. Installer les dépendances

```bash
composer install
npm install
```

### 3. Configurer l'environnement

```bash
cp .env.example .env
php artisan key:generate
```

Éditer `.env` et renseigner la base de données :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clinique_medicale
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Créer la base de données et lancer les migrations

```bash
# Créer la base de données dans MySQL, puis :
php artisan migrate
```

### 5. Compiler les assets

```bash
npm run build
```

---

## Lancer en développement

La commande suivante démarre en parallèle le serveur Laravel, la queue, les logs (Pail) et Vite :

```bash
composer run dev
```

Ou séparément :

```bash
php artisan serve        # serveur HTTP → http://127.0.0.1:8000
npm run dev              # Vite HMR
php artisan queue:listen # file de jobs
php artisan pail         # logs en temps réel
```

---

## Raccourci tout-en-un (setup complet)

```bash
composer run setup
```

Cette commande enchaîne : `composer install` → copie `.env` → génération de clé → migrations → `npm install` → `npm run build`.

---

## Tests

```bash
composer run test
# ou directement :
php artisan test
```

---

## Structure principale

```
app/
├── Http/Controllers/
│   ├── AuthController.php
│   ├── DashboardController.php
│   ├── PatientController.php
│   ├── StaffMedicalController.php
│   ├── FileAttenteController.php
│   ├── RendezVousController.php
│   ├── ConsultationController.php
│   ├── OrdonnancesController.php
│   ├── FacturesController.php
│   └── AccountController.php
├── Models/
│   ├── Patient.php
│   ├── StaffMedical.php
│   ├── FileAttente.php
│   ├── RendezVous.php
│   ├── Consultation.php
│   └── Ordonnances.php
resources/views/
│   ├── auth/
│   ├── dashboard/
│   ├── patients/
│   ├── staff/
│   ├── fileAttente/
│   ├── appointments/
│   ├── consultations/
│   ├── prescriptions/
│   ├── billing/
│   └── layouts/
database/migrations/
routes/web.php
```

---

## Accès par défaut

Créer un utilisateur via `php artisan tinker` ou un seeder, puis se connecter sur `/`.

Les rôles disponibles : `medecin`, `secretariat`, `admin`.
