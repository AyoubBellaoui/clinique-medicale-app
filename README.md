# ClinicPro — Medical Clinic Management

Web application for managing a medical clinic: patients, staff, waiting queue, appointments, consultations, prescriptions and billing.

**Stack:** PHP 8.3 · Laravel 13 · MySQL 8 · Blade · Tailwind CSS v4 · Vite 8 · PHPUnit 12

---

## Requirements

- PHP >= 8.3 (extensions `pdo_mysql`, `mbstring`, `openssl`, `tokenizer`, `xml`)
- Composer
- Node.js >= 18 + npm
- MySQL >= 8 (on Windows, Laragon or XAMPP is enough)

---

## Installation

**1. Clone the project**

```bash
git clone <repo-url> clinique-medicale-app
cd clinique-medicale-app
```

**2. Create the `.env` file**

```bash
cp .env.example .env
```

`.env.example` uses SQLite by default. To use MySQL, replace the `DB_` block with:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=medical_app_db
DB_USERNAME=root
DB_PASSWORD=
```

**3. Create the database** `medical_app_db` in MySQL (phpMyAdmin, HeidiSQL or `CREATE DATABASE medical_app_db;`).

**4. Run the setup**

```bash
composer run setup
```

This installs the PHP and JS dependencies, generates the application key, runs the migrations with seeding, and builds the assets.

The seed creates the admin account plus demo data (staff, patients, appointments, invoices…).

---

## Running the app

```bash
php artisan serve
```

Open **http://127.0.0.1:8000**.

To edit views or CSS with live reload, start Vite in a second terminal:

```bash
npm run dev
```

> **Windows:** don't use `composer run dev`. That script also starts `php artisan pail`, which requires the `pcntl` extension (not available on Windows); when it fails, it stops all the other processes. Run the two commands above separately instead.

---

## Default login

| Email | Password |
|-------|----------|
| `admin@clinicpro.ma` | `admin123` |

There is no sign-up page: other accounts are created from **Utilisateurs** (`/users`). **Change this password before any production deployment.**

---

## Roles & permissions

Available roles: `admin`, `medecin`, `infirmier`, `secretariat`, `technicien`. `admin` has access to everything.

| Module | Route | Allowed roles |
|--------|-------|---------------|
| Dashboard, Patients, Waiting queue, Appointments, My account | `/dashboard`, `/patients`, `/fileAttente`, `/appointments`, `/account` | All |
| Global search | `/search` | All ("Staff" results limited to admins) |
| Consultations, Prescriptions | `/consultations`, `/prescriptions` | `admin`, `medecin`, `infirmier` |
| Billing | `/billing` | `admin`, `secretariat`, `technicien` |
| Medical staff, User accounts | `/staff`, `/users` | `admin` |

These rules are enforced server-side by the [`EnsureRole`](app/Http/Middleware/EnsureRole.php) middleware.

---

## Data retention

Deleting a patient or staff member **archives** it (`SoftDeletes`) without erasing their consultations, appointments, prescriptions or invoices, which have legal value. Archived records can be viewed and restored from `/patients/archived` and `/staff/archived`.

---

## Tests

```bash
php artisan test
```

Tests run on in-memory SQLite (no database to configure). They also run on GitHub Actions on every push or PR to `main`.

---

## Deployment (Linux)

- Set `APP_ENV=production` and `APP_DEBUG=false` in `.env`.
- Give the web server write access, otherwise you'll get a 500 error:

```bash
chown -R $USER:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```
