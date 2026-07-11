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
php artisan migrate --seed
```

`--seed` crée le compte administrateur par défaut (voir [Accès par défaut](#accès-par-défaut)) — sans lui, l'application n'a aucun utilisateur et il est impossible de se connecter (il n'y a pas de page d'inscription).

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

Suite de tests (PHPUnit, SQLite en mémoire) couvrant l'authentification, le contrôle d'accès par rôle, les patients, la file d'attente, les consultations, les rendez-vous, la facturation (calcul des montants, factures en retard), les ordonnances, la gestion du personnel/des comptes, la recherche globale et la rétention des données (archivage vs. suppression définitive).

Un workflow GitHub Actions (`.github/workflows/tests.yml`) exécute cette suite à chaque push/PR sur `main`.

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
│   ├── AccountController.php
│   ├── UserController.php
│   └── SearchController.php
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

`php artisan migrate --seed` (ou `php artisan db:seed`) crée automatiquement un compte administrateur :

| Email | Mot de passe |
|-------|---------------|
| `admin@clinicpro.ma` | `admin123` |

Se connecter sur `/`, puis créer les autres comptes depuis **Utilisateurs** (`/users`, réservé aux admins). **Changez ce mot de passe avant tout déploiement autre que local.**

---

## Rôles & permissions

Chaque utilisateur (`users.role`) a l'un des rôles suivants : `admin`, `medecin`, `infirmier`, `secretariat`, `technicien`. Un `admin` a accès à tout ; les autres rôles sont restreints par module :

| Module | Route | Rôles autorisés |
|--------|-------|------------------|
| Tableau de bord | `/dashboard` | Tous les utilisateurs connectés |
| Patients | `/patients` | Tous les utilisateurs connectés |
| File d'attente | `/fileAttente` | Tous les utilisateurs connectés |
| Rendez-vous | `/appointments` | Tous les utilisateurs connectés |
| Mon compte | `/account` | Tous les utilisateurs connectés |
| Recherche globale | `/search` | Tous (résultats "Personnel" limités aux admins) |
| Personnel médical (RH) | `/staff` | `admin` uniquement |
| Comptes utilisateurs | `/users` | `admin` uniquement |
| Consultations | `/consultations` | `admin`, `medecin`, `infirmier` |
| Ordonnances | `/prescriptions` | `admin`, `medecin`, `infirmier` |
| Facturation | `/billing` | `admin`, `secretariat`, `technicien` |

Ces règles sont appliquées côté serveur par le middleware `EnsureRole` (`app/Http/Middleware/EnsureRole.php`), pas seulement côté interface.

---

## Rétention des données médicales

Les patients et le personnel médical sont en **suppression douce** (`SoftDeletes`) : "supprimer" un patient ou un membre du personnel l'archive (masqué des listes actives) sans détruire ses consultations, rendez-vous, ordonnances ou factures — ces enregistrements ont une valeur légale/médicale et doivent survivre à l'archivage de la fiche qui leur est liée. Voir `database/migrations/2026_07_11_100000_add_soft_deletes_to_patients_and_staff.php` et `tests/Feature/DataRetentionTest.php`.
