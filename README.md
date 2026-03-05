# Landing Rate Corrections — phpVMS 7 Module

A phpVMS 7 module that gives pilots a formal way to request corrections for incorrectly recorded landing rates, and gives admins the tools to review, approve, or reject those requests — with a full audit trail.

---

## The Problem

ACARS occasionally records a wrong landing rate due to simulator crashes, connection drops, or software glitches. The result is a value like `0 ft/min` or an extreme number that clearly doesn't reflect the actual landing. Without this module, there is no official process for a pilot to flag and correct such errors.

---

## Features

- **Pilot dashboard** — overview of all flights, implausible rate detection, and full request history
- **Implausible tab** — automatically highlights PIREPs with `0` or shallower than `-20 ft/min`
- **Request workflow** — pilots submit a reason + optional evidence file, admins decide
- **Admin panel** — pending queue, approve/reject with notes, audit log, direct fix for obvious errors
- **Email notifications** — admins notified on new requests, pilots notified on decisions
- **Configurable recipients** — choose which admins receive email alerts
- **Evidence upload** — JPG, PNG, GIF or PDF up to 5 MB
- **Built-in guide** — DE/EN documentation tab for pilots; admin-only section visible only to admins
- **Full dark/light theme support** — compatible with AirlinePulse / SPTheme

---

## Requirements

- phpVMS 7 (nWidart Laravel Modules)
- PHP 8.1+
- Laravel Mail configured for email notifications

---

## Installation

1. Copy the `LandingRateCorrection` folder into your `modules/` directory.

2. Run the migrations:
   ```bash
   php artisan module:migrate LandingRateCorrection
   ```

3. Enable the module in the phpVMS admin panel under **Modules**.

4. Configure email notification recipients:  
   Admin Panel → **Addons → LR Corrections → Recipients**

5. Optional — add a link to your theme navigation:
   ```blade
   <a href="{{ url('/lrc') }}">Landing Rate Corrections</a>
   ```

---

## Module URLs

| Who | URL | Description |
|-----|-----|-------------|
| Pilot | `/lrc` | Pilot dashboard — flights, implausible, requests, guide |
| Admin | `/admin/lrc` | Admin panel — manage all requests |
| Admin | `/admin/lrc/implausible` | All implausible PIREPs + Direct Fix |

---

## Important Policy Note

This module is intended strictly for correcting **technical recording errors** — not for improving landing statistics. Requests require real proof from an external tool (flight tracker, replay software, screenshot). All requests are logged in the audit trail regardless of outcome.

---

## Screenshots

> Add screenshots here after deployment.

---

## Changelog

### v1.0.0
- Initial release
- Pilot request workflow with evidence upload
- Admin review panel with audit log
- Email notifications (submitted + processed)
- Configurable notification recipients
- Direct Fix for admins on implausible PIREPs
- Built-in bilingual guide (DE/EN)
- Full dark/light theme support

---

## License

MIT License — see [LICENSE](LICENSE)

---

## Author

Developed for **German Sky Group** virtual airline.  
Built on top of [phpVMS 7](https://phpvms.net) by the nWidart Laravel Modules framework.
