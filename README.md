# Landing Rate Corrections — phpVMS 7 Module

A phpVMS 7 module that gives pilots a formal, structured way to request corrections for incorrectly recorded landing rates — and gives admins the tools to review, approve, or reject those requests with a complete audit trail.

---

## Table of Contents

- [The Problem](#the-problem)
- [Features](#features)
- [Requirements](#requirements)
- [Installation — Without SSH Access](#installation--without-ssh-access)
  - [Step 1 — Upload the Module Files](#step-1--upload-the-module-files)
  - [Step 2 — Create the Database Tables](#step-2--create-the-database-tables)
  - [Step 3 — Enable the Module](#step-3--enable-the-module)
  - [Step 4 — Configure Notification Recipients](#step-4--configure-notification-recipients)
  - [Step 5 — Verify the Installation](#step-5--verify-the-installation)
  - [Step 6 — Optional: Add a Navigation Link](#step-6--optional-add-a-navigation-link)
- [Installation — With SSH Access](#installation--with-ssh-access)
- [Configuration](#configuration)
- [Module URLs](#module-urls)
- [How It Works](#how-it-works)
- [Evidence Files](#evidence-files)
- [Email Notifications](#email-notifications)
- [Uninstallation](#uninstallation)
- [Troubleshooting](#troubleshooting)
- [Policy Note](#policy-note)
- [Changelog](#changelog)
- [License](#license)

---

## The Problem

ACARS occasionally records a wrong landing rate due to simulator crashes, dropped internet connections, or software glitches at the exact moment of touchdown. The result is a value like `0 ft/min` or a physically impossible number that clearly does not reflect the actual landing.

Without this module there is no official process for a pilot to flag and correct such an error. This module provides exactly that — a transparent, auditable request workflow.

---

## Features

- **Pilot dashboard** — overview of all completed flights with their recorded landing rates
- **Implausible detection** — automatically highlights PIREPs with `0`, positive, or shallower than `-20 ft/min`
- **Request workflow** — pilots submit a reason and optional evidence file; admins decide
- **Admin panel** — pending queue, approve/reject with mandatory notes, direct fix for obvious errors
- **Full audit log** — every decision is recorded with timestamp and acting admin
- **Email notifications** — admins notified on new requests, pilots notified on decisions
- **Configurable recipients** — choose exactly which admins receive email alerts
- **Evidence upload** — JPG, PNG, GIF or PDF up to 5 MB
- **Built-in guide** — bilingual (DE/EN) documentation tab for pilots; extended admin section visible only to admins
- **Dark/light theme support** — compatible with AirlinePulse and SPTheme

---

## Requirements

- phpVMS 7 (nWidart Laravel Modules based)
- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Laravel Mail configured (for email notifications)
- FTP or file manager access to your web server
- phpMyAdmin or equivalent database tool (for no-SSH installation)

---

## Installation — Without SSH Access

This guide covers a complete installation using only **FTP** (or your hosting file manager) and **phpMyAdmin** — no terminal or SSH access required.

---

### Step 1 — Upload the Module Files

1. Download the latest release ZIP from GitHub and extract it on your local computer. You should see a folder named `LandingRateCorrection`.

2. Connect to your web server via **FTP** (FileZilla, WinSCP, or your hosting control panel file manager).

3. Navigate to your phpVMS root directory. Inside it, find the `modules/` folder:

```
your-phpvms-root/
└── modules/
```

4. Upload the entire `LandingRateCorrection` folder into `modules/`. The result should look like this:

```
modules/
└── LandingRateCorrection/
    ├── Config/
    ├── Database/
    ├── Http/
    ├── Models/
    ├── Notifications/
    ├── Providers/
    ├── Resources/
    ├── Routes/
    └── module.json
```

> **Important:** Upload the folder itself, not only its contents. The folder must be named exactly `LandingRateCorrection` — capitalization matters.

---

### Step 2 — Create the Database Tables

Because you have no SSH access, you cannot run `php artisan module:migrate`. Instead, create the required tables manually via **phpMyAdmin**.

1. Open **phpMyAdmin** and select your phpVMS database from the left sidebar.

2. Click the **SQL** tab at the top of the page.

3. Copy and paste the entire block below and click **Go**:

```sql
-- Table 1: Main corrections table
CREATE TABLE IF NOT EXISTS `landing_rate_corrections` (
  `id`                       BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `pirep_id`                 VARCHAR(255)    NOT NULL,
  `pilot_id`                 BIGINT UNSIGNED NOT NULL,
  `admin_id`                 BIGINT UNSIGNED DEFAULT NULL,
  `original_landing_rate`    INT             NOT NULL DEFAULT 0,
  `requested_landing_rate`   INT             NOT NULL,
  `reason`                   TEXT            NOT NULL,
  `status`                   VARCHAR(255)    NOT NULL DEFAULT 'pending',
  `admin_note`               TEXT            DEFAULT NULL,
  `notify_on_decision`       TINYINT(1)      NOT NULL DEFAULT 1,
  `evidence_path`            VARCHAR(255)    DEFAULT NULL,
  `evidence_original_name`   VARCHAR(255)    DEFAULT NULL,
  `processed_at`             TIMESTAMP       DEFAULT NULL,
  `created_at`               TIMESTAMP       DEFAULT NULL,
  `updated_at`               TIMESTAMP       DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `lrc_pirep_id_index`     (`pirep_id`),
  INDEX `lrc_pilot_id_index`     (`pilot_id`),
  INDEX `lrc_status_index`       (`status`),
  INDEX `lrc_pilot_status_index` (`pilot_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table 2: Notification recipients
CREATE TABLE IF NOT EXISTS `lrc_notification_recipients` (
  `id`         BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id`    BIGINT UNSIGNED NOT NULL,
  `created_at` TIMESTAMP       DEFAULT NULL,
  `updated_at` TIMESTAMP       DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `lrc_recipients_user_id_unique` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tell Laravel these migrations have already run (prevents duplicate execution)
INSERT IGNORE INTO `migrations` (`migration`, `batch`) VALUES
  ('2024_01_01_000001_create_landing_rate_corrections_table',      99),
  ('2024_01_01_000002_add_notify_on_decision_to_lrc',              99),
  ('2024_01_01_000003_create_lrc_notification_recipients_table',   99);
```

4. You should see a green success bar. If you see an error that a table already exists, that is fine — the `IF NOT EXISTS` clause prevents any data loss.

> **Why the INSERT INTO migrations?**  
> Laravel tracks which migrations have already run in the `migrations` table. Without these entries, phpVMS may attempt to run the migrations again later and fail with a "table already exists" error. Batch `99` marks them as already executed and skips them in future runs.

---

### Step 3 — Enable the Module

1. Log into your **phpVMS admin panel**.

2. Go to **Modules** in the left sidebar.

3. Find **LandingRateCorrection** in the list and click **Enable**.

4. The module now appears under **Addons → LR Corrections** in the admin sidebar.

> If the module does not appear in the Modules list, double-check the folder name (`LandingRateCorrection`, exact capitalization) and its location inside `modules/`.

---

### Step 4 — Configure Notification Recipients

Email notifications are sent to admins when a pilot submits a new correction request. You must configure at least one recipient for emails to be delivered.

1. In the admin panel, go to **Addons → LR Corrections**.

2. Click the **Recipients** tab (last tab on the right).

3. Check the box next to each admin who should receive new-request notifications.

4. Click **Save**.

> If you skip this step the module still works fully — requests can be submitted and reviewed — but no email alerts will be sent to admins.

---

### Step 5 — Verify the Installation

Run through this checklist to confirm everything is working correctly:

- [ ] `https://your-site.com/lrc` loads the pilot dashboard without errors
- [ ] `https://your-site.com/admin/lrc` loads the admin panel
- [ ] **LR Corrections** appears under **Addons** in the admin sidebar
- [ ] The **Unplausibel** tab on the pilot dashboard shows PIREPs with `0 ft/min` (if any exist)
- [ ] Submit a test correction request as a pilot → it appears under **Pending** in the admin panel
- [ ] Approve the test request → the PIREP's landing rate is updated to the requested value
- [ ] The approved request appears in the **Audit Log** tab

---

### Step 6 — Optional: Add a Navigation Link

To give pilots a direct link from your theme's navigation menu:

```blade
<a href="{{ url('/lrc') }}">Landeratenkorrekturen</a>
```

To link directly to the Implausible tab:

```blade
<a href="{{ url('/lrc') }}#tab-imp">Unplausible Landeraten</a>
```

To include a badge showing the count of open requests (admin only):

```blade
@if(auth()->user()->hasRole('admin'))
  <a href="{{ url('/admin/lrc') }}">LR Corrections</a>
@endif
```

---

## Installation — With SSH Access

If you do have SSH/terminal access the installation is simpler:

```bash
# 1. Copy the module folder into your phpVMS modules directory
cp -r LandingRateCorrection /path/to/phpvms/modules/

# 2. Run the database migrations
cd /path/to/phpvms
php artisan module:migrate LandingRateCorrection

# 3. Clear application caches
php artisan optimize:clear
```

Then continue from [Step 3 — Enable the Module](#step-3--enable-the-module).

---

## Configuration

The configuration file is at:

```
modules/LandingRateCorrection/Config/config.php
```

Edit it via FTP with any text editor:

```php
return [

    // Landing rates >= this value (ft/min) are flagged as implausible.
    // 0 and positive values are always implausible regardless of this setting.
    // Default: -20
    'min_plausible_rate' => -20,

    // Maximum age in days of a PIREP that can still be corrected.
    // Set to 0 to allow corrections on PIREPs of any age.
    // Default: 0 (no limit)
    'correction_window_days' => 0,

    // Maximum size of evidence file uploads, in kilobytes.
    // Default: 5120 (= 5 MB)
    'max_upload_size' => 5120,

    // Allowed MIME types for evidence uploads.
    'allowed_mimes' => ['jpg', 'jpeg', 'png', 'gif', 'pdf'],

];
```

> After editing this file, clear your phpVMS cache:  
> Admin Panel → **Maintenance → Clear Cache**,  
> or delete the contents of `bootstrap/cache/` via FTP.

---

## Module URLs

| Who | URL | Description |
|-----|-----|-------------|
| Pilot | `/lrc` | Pilot dashboard — My Flights, Implausible, My Requests, Guide |
| Admin | `/admin/lrc` | Admin panel — Pending, Approved, Rejected, All, Audit Log, Recipients |
| Admin | `/admin/lrc/implausible` | All implausible PIREPs across all pilots + Direct Fix |

---

## How It Works

### Pilot Workflow

1. Pilot notices an incorrect landing rate on a completed PIREP.
2. Pilot goes to `/lrc` → **My Flights** tab and clicks **Korrigieren** next to the affected flight.
3. Pilot fills in:
   - The corrected landing rate (must be a negative integer, e.g. `-180`)
   - A reason explaining what happened (minimum 10 characters)
   - Optional evidence file (screenshot from flight tracker, PDF, etc.)
   - Optional: "Notify me on decision" checkbox for an email when the admin decides
4. After submitting, the request appears as **Ausstehend** in the **My Requests** tab.
5. When the admin decides, status changes to **Genehmigt** (approved) or **Abgelehnt** (rejected).
6. If approved, the PIREP landing rate is automatically updated in the database.

### Admin Workflow

1. Admin receives an email notification (if recipients are configured).
2. Admin goes to **Addons → LR Corrections** → **Pending** tab.
3. Admin reviews the pilot's stated reason and any attached evidence file.
4. Admin either:
   - **Approves** — optionally adds a note; PIREP is updated automatically
   - **Rejects** — must enter a rejection reason (required, minimum 3 characters)
5. If the pilot opted in, they receive a decision email.
6. All decisions are permanently recorded in the **Audit Log** tab.

### Direct Fix (Admin Only)

For PIREPs with obvious technical errors (e.g. `0 ft/min`) where no pilot request exists:

1. Go to `/admin/lrc/implausible`
2. Enter the correct landing rate and an admin note
3. Click **Fix**

This creates a fully audited approved correction record and updates the PIREP immediately, without requiring the pilot to submit a request first.

---

## Evidence Files

Uploaded files are stored server-side at:

```
storage/app/public/lrc_evidence/
```

Files are served through the authenticated route `/lrc/evidence/{filename}`. Users must be logged in to access evidence files — they are **not publicly accessible**.

If the `storage/app/public/lrc_evidence/` directory does not exist yet, the module creates it automatically on the first upload. You can also create it manually via FTP if needed.

**Supported formats:** JPG, JPEG, PNG, GIF, PDF  
**Maximum size:** 5 MB (configurable in `config.php`)

---

## Email Notifications

| Trigger | Recipient | Content |
|---------|-----------|---------|
| Pilot submits a new request | All configured admin recipients | Pilot name, flight number, route, original rate, requested rate, reason |
| Admin approves a request | Pilot (only if they opted in) | Approval confirmation, updated landing rate |
| Admin rejects a request | Pilot (only if they opted in) | Rejection notice, admin's rejection reason |

### Mail Configuration (`.env`)

```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host.com
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@your-va.com
MAIL_FROM_NAME="Your VA Name"
```

Mail configuration is part of your phpVMS base setup. If emails already work in phpVMS (e.g. registration confirmations), they will work in this module too.

---

## Uninstallation

### 1. Disable the module

Admin Panel → **Modules** → **Disable** LandingRateCorrection

### 2. Remove the files via FTP

Delete the folder:
```
modules/LandingRateCorrection/
```

### 3. Remove the database tables (optional)

Run this in phpMyAdmin **only if** you want to permanently delete all data:

```sql
DROP TABLE IF EXISTS `landing_rate_corrections`;
DROP TABLE IF EXISTS `lrc_notification_recipients`;

DELETE FROM `migrations`
WHERE `migration` IN (
  '2024_01_01_000001_create_landing_rate_corrections_table',
  '2024_01_01_000002_add_notify_on_decision_to_lrc',
  '2024_01_01_000003_create_lrc_notification_recipients_table'
);
```

> ⚠️ **Warning:** This permanently deletes all correction requests and the complete audit log. This action cannot be undone.

---

## Troubleshooting

### Module does not appear in the Modules list

- Confirm the folder is named exactly `LandingRateCorrection` (capital L, R, C — no spaces)
- Confirm the folder is inside `modules/` and not nested inside a subfolder
- Clear phpVMS cache: Admin → Maintenance → Clear Cache

### "Table already exists" error

- The SQL tables were already created. This is expected and harmless.
- Confirm the three rows were inserted into the `migrations` table (Step 2 final block).

### Error: "Class not found" or "Module not found"

- Clear the application cache: Admin → Maintenance → Clear Cache
- If your host uses PHP opcache, ask them to restart PHP-FPM

### Evidence files return 404

- Confirm `storage/app/public/lrc_evidence/` exists on the server — create it via FTP if missing
- You must be logged in to access evidence files; test while authenticated

### Emails are not being sent

- Verify MAIL settings in your phpVMS `.env` file
- Confirm at least one recipient is set under the Recipients tab
- Check that the pilot enabled the "Notify me" option when submitting
- Check your mail server logs or spam folder

### Blade / template errors after update

- Delete all cached views: remove files from `storage/framework/views/` via FTP
- Clear cache: Admin → Maintenance → Clear Cache

### The "LR Corrections" admin sidebar link is missing

- Disable and re-enable the module in Admin → Modules
- Clear application cache

---

## Policy Note

This module is intended strictly for correcting **technical recording errors** — not for improving landing statistics.

Requests require real, verifiable proof from an external tool: a flight tracker, replay software, or a screenshot of simulator instruments captured at the moment of landing. Guessing or estimating is explicitly not accepted.

All requests — approved or rejected — are permanently logged in the audit trail. Admins can cross-reference external data when reviewing requests. Repeated misuse should be escalated to VA staff.

---

## Screenshots

> Screenshots will be added to this repository after deployment. In the meantime, the built-in **Handbuch / Guide** tab at `/lrc` documents all features with detailed explanations.

---

## Changelog

### v1.0.0 — 2026-03-05

**Added**
- Pilot dashboard with My Flights, Implausible, My Requests, and Guide tabs
- Implausible PIREP detection (0 ft/min, positive, or shallower than -20 ft/min)
- Correction request workflow with reason field and evidence file upload
- Admin panel with Pending, Approved, Rejected, All Requests, Audit Log, and Recipients tabs
- Admin Direct Fix for implausible PIREPs without a pilot request
- Configurable email notification recipients per admin
- Email to admins on new pilot request; email to pilot on admin decision
- Built-in bilingual guide (DE/EN) with admin-only sections
- Full dark/light theme support (AirlinePulse / SPTheme compatible)

**Security**
- CSRF protection on all forms
- Auth middleware on all pilot and admin routes
- Ownership check: pilots can only request corrections for their own PIREPs
- Admin role check on all admin routes and recipient validation
- Sanitized filenames in storage and HTTP headers
- Race condition protection on approve/reject with `fresh()` DB re-check

**Performance**
- Composite database index on `(pilot_id, status)`
- Eager loading on all Eloquent relations (no N+1 queries)
- Pagination on all tables

---

## License

MIT License — see [LICENSE](LICENSE)

---

## Author

Developed for **[German Sky Group](https://german-sky-group.eu)** virtual airline.  
Built on [phpVMS 7](https://phpvms.net) using the [nWidart Laravel Modules](https://nwidart.com/laravel-modules) framework.

Issues and pull requests are welcome.
