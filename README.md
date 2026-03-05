# Landing Rate Corrections — phpVMS 7 Module

A phpVMS 7 module that gives pilots a formal, structured way to request corrections for incorrectly recorded landing rates — and gives admins the tools to review, approve, or reject those requests with a complete audit trail.

---

## Table of Contents

- [The Problem](#the-problem)
- [Features](#features)
- [Requirements](#requirements)
- [Installation — Without SSH Access](#installation--without-ssh-access)
  - [Step 1 — Upload the Module Files](#step-1--upload-the-module-files)
  - [Step 2 — Enable the Module](#step-2--enable-the-module)
  - [Step 3 — Run the Database Migrations](#step-3--run-the-database-migrations)
  - [Step 4 — Configure Notification Recipients](#step-4--configure-notification-recipients)
  - [Step 5 — Verify the Installation](#step-5--verify-the-installation)
  - [Step 6 — Optional: Add a Navigation Link](#step-6--optional-add-a-navigation-link)
- [Installation — With SSH Access](#installation--with-ssh-access)
- [Configuration](#configuration)
- [Module URLs](#module-urls)
- [How It Works](#how-it-works)
- [Evidence Files](#evidence-files)
- [Email Notifications](#email-notifications)
- [Updating](#updating)
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

---

## Installation — Without SSH Access

This guide covers a complete installation using only **FTP** (or your hosting file manager) and your **browser** — no terminal, SSH, or phpMyAdmin required.

---

### Step 1 — Upload the Module Files

1. Download the latest release ZIP from GitHub and extract it on your local computer. You will get a folder named `LandingRateCorrection`.

2. Connect to your web server via **FTP** (FileZilla, WinSCP, or your hosting control panel file manager).

3. Navigate to your phpVMS root directory and open the `modules/` folder.

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

### Step 2 — Enable the Module

1. Log into your **phpVMS admin panel**.

2. Go to **Modules** in the left sidebar.

3. Find **LandingRateCorrection** in the list and click **Enable**.

> ⚠️ There is a known phpVMS 7 bug: after clicking Enable, you may see a server error page or a blank screen. This is normal. Simply close that tab, open the admin panel in a new tab, and continue. The module will be enabled correctly.

4. Confirm the module is active — **LR Corrections** should now appear under **Addons** in the admin sidebar.

---

### Step 3 — Run the Database Migrations

phpVMS handles database table creation automatically through its built-in updater — no phpMyAdmin or SQL required.

1. Open your browser and navigate to:
   ```
   https://your-site.com/update
   ```

2. phpVMS will detect the new module migrations and display them as pending.

3. Click **Run Updates** (or the equivalent button on your phpVMS version).

4. Wait for the process to complete — you will see a confirmation that the migrations ran successfully.

5. Click **Clear Cache** or go to Admin → **Maintenance → Clear Cache** when prompted.

> The `/update` page is protected and only accessible to logged-in administrators. If you are not logged in, phpVMS will redirect you to the login page first.

> **If the update page shows nothing to update:** The migrations may have already run when you enabled the module in Step 2. Proceed to Step 4 — the tables were created automatically.

---

### Step 4 — Configure Notification Recipients

Email notifications are sent to admins when a pilot submits a new correction request. You must configure at least one recipient for emails to be delivered.

1. In the admin panel, go to **Addons → LR Corrections**.

2. Click the **Recipients** tab (last tab on the right).

3. Check the box next to each admin who should receive new-request email alerts.

4. Click **Save**.

> If you skip this step the module still works fully — requests can be submitted and reviewed — but no email alerts will be sent to admins.

---

### Step 5 — Verify the Installation

Run through this checklist to confirm everything is working:

- [ ] `https://your-site.com/lrc` loads the pilot dashboard without errors
- [ ] `https://your-site.com/admin/lrc` loads the admin panel
- [ ] **LR Corrections** appears under **Addons** in the admin sidebar
- [ ] The **Unplausibel** tab shows PIREPs with `0 ft/min` (if any exist in your data)
- [ ] Submit a test correction request as a pilot — it appears under **Pending** in the admin panel
- [ ] Approve the test request — the PIREP's landing rate is updated to the requested value
- [ ] The approved request appears in the **Audit Log** tab

---

### Step 6 — Optional: Add a Navigation Link

To give pilots a direct link from your theme's navigation menu, add the following to your theme's navigation template:

```blade
<a href="{{ url('/lrc') }}">Landeratenkorrekturen</a>
```

To link directly to the Implausible tab:

```blade
<a href="{{ url('/lrc') }}#tab-imp">Unplausible Landeraten</a>
```

Admin-only link for the admin sidebar or nav:

```blade
@if(auth()->user()->hasRole('admin'))
    <a href="{{ url('/admin/lrc') }}">LR Corrections</a>
@endif
```

---

## Installation — With SSH Access

If you have SSH/terminal access the installation is even simpler:

```bash
# 1. Copy the module folder into your phpVMS modules directory
cp -r LandingRateCorrection /path/to/phpvms/modules/

# 2. Run the database migrations
cd /path/to/phpvms
php artisan module:migrate LandingRateCorrection

# 3. Clear application caches
php artisan optimize:clear
```

Then enable the module in Admin → **Modules** and continue from [Step 4](#step-4--configure-notification-recipients).

---

## Configuration

The configuration file is located at:

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

    // Maximum age in days of a PIREP that can still have a correction requested.
    // Set to 0 to allow corrections on PIREPs of any age (no time limit).
    // Default: 0
    'correction_window_days' => 0,

    // Maximum evidence file upload size in kilobytes.
    // Default: 5120 (= 5 MB)
    'max_upload_size' => 5120,

    // Allowed MIME types for evidence file uploads.
    'allowed_mimes' => ['jpg', 'jpeg', 'png', 'gif', 'pdf'],

];
```

> After editing this file, clear your phpVMS cache:  
> Admin Panel → **Maintenance → Clear Cache**

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
2. Pilot goes to `/lrc` → **My Flights** and clicks **Korrigieren** next to the affected flight.
3. Pilot fills in:
   - The corrected landing rate (must be a negative integer, e.g. `-180`)
   - A reason explaining what happened (minimum 10 characters)
   - Optional evidence file (screenshot, flight tracker export, PDF)
   - Optional: "Notify me on decision" checkbox for an email when the admin decides
4. After submitting, the request shows as **Ausstehend** in the **My Requests** tab.
5. When the admin decides, status changes to **Genehmigt** or **Abgelehnt**.
6. If approved, the PIREP landing rate is automatically updated in the database.

### Admin Workflow

1. Admin receives an email notification (if recipients are configured).
2. Admin goes to **Addons → LR Corrections** → **Pending** tab.
3. Admin reviews the pilot's reason and any attached evidence.
4. Admin either:
   - **Approves** — optionally adds a note; PIREP is updated automatically
   - **Rejects** — must enter a rejection reason (required, minimum 3 characters)
5. If the pilot opted in, they receive a decision email.
6. All decisions are permanently recorded in the **Audit Log**.

### Direct Fix (Admin Only)

For PIREPs with obvious technical errors (e.g. `0 ft/min`) where no pilot request exists:

1. Go to `/admin/lrc/implausible`
2. Enter the correct landing rate and an admin note
3. Click **Fix**

A fully audited approved correction record is created and the PIREP is updated immediately.

---

## Evidence Files

Uploaded files are stored at:

```
storage/app/public/lrc_evidence/
```

Files are served through the authenticated route `/lrc/evidence/{filename}`. Users must be logged in to access evidence — files are **not publicly accessible**.

The directory is created automatically on the first upload if it does not exist yet.

**Supported formats:** JPG, JPEG, PNG, GIF, PDF  
**Maximum size:** 5 MB (configurable)

---

## Email Notifications

| Trigger | Recipient | Content |
|---------|-----------|---------|
| Pilot submits a new request | All configured admin recipients | Pilot name, flight, route, original rate, requested rate, reason |
| Admin approves a request | Pilot (only if they opted in) | Approval confirmation, updated rate |
| Admin rejects a request | Pilot (only if they opted in) | Rejection notice, admin's reason |

Mail uses your existing phpVMS `.env` mail configuration. If emails already work in phpVMS (e.g. registration), they will work here too.

---

## Updating

When a new version is released:

1. Download the new release ZIP and extract it.
2. Upload the updated `LandingRateCorrection` folder via FTP, **overwriting** all existing files.
3. Visit `https://your-site.com/update` to run any new database migrations.
4. Go to Admin → **Maintenance → Clear Cache**.

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

If you want to permanently delete all correction data, run this in phpMyAdmin:

```sql
DROP TABLE IF EXISTS `lrc_corrections`;
DROP TABLE IF EXISTS `lrc_recipients`;

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
- Confirm the folder is named exactly `LandingRateCorrection` (capital L, R, C — no spaces, no underscores)
- Confirm the folder is directly inside `modules/` and not nested inside a subfolder
- Clear phpVMS cache: Admin → Maintenance → Clear Cache

### Error or blank page after clicking Enable
- This is a known phpVMS 7 bug — close the tab and reopen the admin panel in a new tab
- The module will be enabled correctly despite the error

### `/update` shows no pending migrations
- The migrations already ran automatically when you enabled the module — this is expected
- Check that the tables exist: in phpMyAdmin, look for `landing_rate_corrections` and `lrc_notification_recipients`

### Evidence files return 404
- Confirm `storage/app/public/lrc_evidence/` exists — create it manually via FTP if needed
- You must be logged in to view evidence files; test while authenticated

### Emails are not being sent
- Verify MAIL settings in your phpVMS `.env` file are correct
- Confirm at least one recipient is configured under the Recipients tab
- Check that the pilot enabled the "Notify me" option when submitting the request
- Check your mail server logs or spam folder

### Blade / template errors after update
- Delete cached views: remove files from `storage/framework/views/` via FTP
- Clear cache: Admin → Maintenance → Clear Cache

### The "LR Corrections" admin sidebar link is missing
- Disable and re-enable the module in Admin → Modules
- Clear application cache

---

## Policy Note

This module is intended strictly for correcting **technical recording errors** — not for improving landing statistics.

Requests require real, verifiable proof from an external tool: a flight tracker, replay software, or a screenshot of simulator instruments at the moment of landing. Guessing or estimating is explicitly not accepted.

All requests — approved or rejected — are permanently logged in the audit trail. Admins can cross-reference external data when reviewing. Repeated misuse should be escalated to VA staff.

---

## Screenshots

> Screenshots will be added after deployment. In the meantime, the built-in **Handbuch / Guide** tab at `/lrc` documents all features with detailed explanations for both pilots and admins.

---

## Changelog

### v1.0.0 — 2026-03-05

**Added**
- Pilot dashboard with My Flights, Implausible, My Requests, and Guide tabs
- Implausible PIREP detection (0 ft/min, positive, or shallower than -20 ft/min)
- Correction request workflow with reason field and evidence file upload
- Admin panel with Pending, Approved, Rejected, All, Audit Log, and Recipients tabs
- Admin Direct Fix for implausible PIREPs without requiring a pilot request
- Configurable email notification recipients
- Email to admins on new pilot request; email to pilot on admin decision
- Built-in bilingual guide (DE/EN) with admin-only sections
- Full dark/light theme support (AirlinePulse / SPTheme compatible)

**Security**
- CSRF protection on all forms
- Auth + role middleware on all routes
- Ownership check: pilots can only correct their own PIREPs
- Admin-only validation on recipient list (non-admin IDs rejected)
- Sanitized filenames in storage and HTTP response headers
- Race condition protection on approve/reject with DB-level re-check

**Performance**
- Composite DB index on `(pilot_id, status)` for pilot dashboard queries
- Eager loading on all Eloquent relations (no N+1 queries)
- Pagination on all admin and pilot tables

---

## License

MIT License — see [LICENSE](LICENSE)

---

## Author

Developed for **[German Sky Group](https://german-sky-group.eu)** virtual airline.  
Built on [phpVMS 7](https://phpvms.net) using the [nWidart Laravel Modules](https://nwidart.com/laravel-modules) framework.

Issues and pull requests are welcome.
