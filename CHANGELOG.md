# Changelog

All notable changes to this project will be documented here.  
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

---

## [1.1.1] — 2026-03-06

### Fixed
- `Undefined variable $lang` error on the Guide tab — replaced remaining `$lang` references with `app()->getLocale()` after the v1.2.0 lang file refactor
- `ServiceProvider` did not load translations when the published lang path did not exist — module now always falls back to `Resources/lang/` automatically, no `php artisan vendor:publish` required

### Added
- Language files for 6 additional locales (UI labels fully translated, guide falls back to English):
  - 🇪🇸 Spanish — `lang/es/lrc.php`
  - 🇮🇹 Italian — `lang/it/lrc.php`
  - 🇯🇵 Japanese — `lang/ja/lrc.php`
  - 🇵🇹 Portuguese (European) — `lang/pt-pt/lrc.php`
  - 🇧🇷 Portuguese (Brazilian) — `lang/pt-br/lrc.php`
  - 🇹🇷 Turkish — `lang/tr/lrc.php`
- **Mail Template Editor** — new admin tab ✏️ Mail Templates under Addons → LR Corrections
  - Edit subject and body of all 3 email notifications directly in the admin panel — no file editing required
  - Changes saved to `lrc_mail_settings` database table (migration 000005, runs via `/update`)
  - Available placeholders: `{pilot_name}` `{flight}` `{route}` `{original_rate}` `{requested_rate}` `{reason}` `{admin_note}`
  - Default templates are fully generic — no VA-specific branding

---

## [1.1.0] — 2026-03-06

### Added
- Admin URL in guide displayed as a clickable link
- Sidebar navigation hint in admin guide section: **Addons → LR Corrections**

### Changed
- Guide layout redesigned to match SkyOps style — section titles outside card, content in dark bordered card
- Abuse warning box rewritten to be clear and direct — no specific tool names, focus on the rule: no proof = no request
- Contradicting FAQ entry removed ("I don't remember the exact rate — what do I enter?") as it conflicted with the no-guessing policy
- Guide tab icon changed to 📖 with clean label "Guide" / "Handbuch"
- Admin guide URL changed from static code block to live clickable link
- Database tables renamed to follow community `lrc_` prefix convention:
  - `landing_rate_corrections` → `lrc_corrections`
  - `lrc_notification_recipients` → `lrc_recipients`
- Browser tab title now follows phpVMS language setting (DE: "Landeratenkorrekturen" / EN: "Landing Rate Corrections")

### Fixed
- Status badges (`✓ Genehmigt`, `✗ Abgelehnt`) no longer wrap across two lines (`white-space: nowrap`)
- Guide panel no longer wrapped in `lrc-card` border — removed oversized frame around guide tab
- Admin email notifications no longer cause ~30 second delay on form submit — replaced per-recipient `foreach` loop with single `Notification::send()` call (one SMTP connection for all recipients)

### Security
- `saveRecipients` now validates that submitted user IDs are actually admin users — arbitrary user IDs rejected
- `auditLog` query on pilot dashboard limited to 100 entries — prevents unbounded database reads
- Evidence filenames sanitized before storing (`getClientOriginalName` output stripped of special characters)
- `Content-Disposition` response header sanitized to prevent header injection via filenames
- Approve/reject race condition mitigated with `->fresh()->isPending()` DB-level re-check
- Composite database index added on `(pilot_id, status)` for pilot dashboard queries

---

## [1.0.0] — 2026-03-05

First public release.

### Added

**Pilot Features**
- Pilot dashboard at `/lrc` with four tabs: My Flights, Implausible, My Requests, Guide
- Automatic implausible PIREP detection — flags any PIREP with `0`, a positive value, or shallower than `-20 ft/min`
- Correction request form with reason field (min. 10 chars) and optional evidence file upload (JPG, PNG, GIF, PDF — max 5 MB)
- Request history in "My Requests" tab with full status tracking (Pending / Approved / Rejected)
- "Notify me on decision" opt-in checkbox — pilot receives an email when admin decides
- Re-apply button for rejected requests

**Admin Features**
- Admin panel at `/admin/lrc` with tabs: Pending, Approved, Rejected, All Requests, Audit Log, Recipients
- Pending queue as the default view — new requests appear here immediately
- Approve workflow: optional admin note, PIREP landing rate updated automatically on approval
- Reject workflow: admin note required (min. 3 chars) — pilot is informed of the reason
- Direct Fix at `/admin/lrc/implausible` — correct obvious errors without a pilot request
- Configurable email notification recipients — choose which admins receive new-request alerts
- Full audit log of every decision with timestamp, acting admin, and notes
- Admin sidebar link registered under **Addons → LR Corrections**

**Guide**
- Built-in bilingual guide tab (DE/EN) at `/lrc` for pilots
- Admin-only sections visible exclusively to users with the admin role
- Covers: what the module does, landing rate reference table, how to submit a request, status explanations, FAQ
- Admin section covers: tab navigation, review workflow, Direct Fix, email setup, URL reference

**Email Notifications**
- Admin notification on new pilot request (flight details, original rate, requested rate, reason, direct link to review)
- Pilot notification on admin decision (approved or rejected, with admin note if provided)
- Single SMTP connection for all admin recipients (no per-recipient reconnect)

**Technical**
- Database tables use `lrc_` prefix: `lrc_corrections`, `lrc_recipients`
- Evidence files served via authenticated route `/lrc/evidence/{filename}` — no `storage:link` required
- Full dark/light theme support (AirlinePulse / SPTheme compatible)
- Browser tab title follows phpVMS language setting (DE/EN)
- Installation via `/update` — no SSH or phpMyAdmin required

### Security
- CSRF protection on all forms
- `auth` middleware on all pilot routes, `auth + role:admin` on all admin routes
- Ownership check — pilots can only request corrections for their own PIREPs
- Recipient validation — only confirmed admin user IDs accepted in the recipients list
- Sanitized filenames in storage and HTTP `Content-Disposition` headers
- Race condition protection on approve/reject using DB-level `fresh()` re-check
- Path traversal prevention on evidence file serving

### Performance
- Composite database index on `(pilot_id, status)` for pilot dashboard queries
- Eager loading on all Eloquent relations — no N+1 queries
- Pagination on all admin and pilot tables
- Audit log limited to 100 most recent entries on pilot dashboard
