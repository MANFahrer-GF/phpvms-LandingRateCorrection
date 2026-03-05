# Changelog

All notable changes to this project will be documented here.  
Format follows [Keep a Changelog](https://keepachangelog.com/en/1.0.0/).

---

## [1.0.0] — 2026-03-05

### Added
- Pilot dashboard with three tabs: My Flights, Implausible, My Requests
- Implausible PIREP detection (0 ft/min or shallower than -20 ft/min)
- Correction request workflow with reason field and evidence file upload (JPG/PNG/GIF/PDF, max 5 MB)
- Email notification to pilot on request submission confirmation
- Admin panel with tabs: Pending, Approved, Rejected, All Requests, Audit Log, Recipients
- Implausible PIREPs admin overview with Direct Fix (bypass pilot request)
- Configurable email notification recipients per admin
- Email notification to selected admins on new pilot request
- Email notification to pilot on admin decision (approved/rejected)
- Full audit log of all decisions with timestamps and admin notes
- Built-in guide tab (DE/EN) for pilots; admin-only section visible to admins only
- Dark/light theme support (AirlinePulse / SPTheme compatible)
- Admin sidebar link registered under Addons → LR Corrections
- Evidence file serving via dedicated route (bypasses phpVMS router)
- Full-width table layout with inline route display
