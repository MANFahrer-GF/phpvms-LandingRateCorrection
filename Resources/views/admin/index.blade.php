@extends('admin.app')
@section('title', 'LRC – Landing Rate Corrections')

@section('actions')
<li>
    <a href="{{ route('lrc.admin.implausible') }}" class="btn btn-warning" 
       style="padding: 8px 16px; font-weight: 600; display: inline-block;">
        ⚠ Implausible PIREPs
    </a>
</li>

@endsection

@section('content')
<style>
/* LRC Admin – force readable dark text on light phpVMS admin theme */
body, .content-wrapper, .panel, .panel-body, .panel-heading,
.table td, .table th, .nav-tabs > li > a,
.lrc-admin-table td, .lrc-admin-table th {
    color: #222 !important;
}
.lrc-admin-table td span.ts,
.lrc-admin-table small { color: #444 !important; font-size: 12px; }
.lrc-admin-table code { font-weight: 700; font-size: 13px; }
.lrc-admin-table .code-red { color: #b71c1c !important; }
.lrc-admin-table .code-grn { color: #1b5e20 !important; }
.panel-heading strong { color: #111 !important; }
.panel-heading small  { color: #555 !important; }
.text-muted { color: #555 !important; }
dl dt { color: #444 !important; }
dl dd { color: #111 !important; }
pre, code { color: #111 !important; }
label { color: #222 !important; }
</style>
<div class="row">
<div class="col-md-12">

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))  <div class="alert alert-danger">{{ session('error') }}</div>@endif

{{-- Tabs --}}
<ul class="nav nav-tabs" style="margin-bottom:20px">
    <li class="{{ $tab==='pending'  ? 'active' : '' }}">
        <a href="{{ route('lrc.admin.index', ['tab'=>'pending']) }}">
            ⏳ Pending
            @if($pendingCount > 0)
                <span class="badge" style="background:#d9534f;color:#fff">{{ $pendingCount }}</span>
            @endif
        </a>
    </li>
    <li class="{{ $tab==='approved' ? 'active' : '' }}">
        <a href="{{ route('lrc.admin.index', ['tab'=>'approved']) }}">✓ Approved</a>
    </li>
    <li class="{{ $tab==='rejected' ? 'active' : '' }}">
        <a href="{{ route('lrc.admin.index', ['tab'=>'rejected']) }}">✗ Rejected</a>
    </li>
    <li class="{{ $tab==='all'      ? 'active' : '' }}">
        <a href="{{ route('lrc.admin.index', ['tab'=>'all']) }}">≡ All Requests</a>
    </li>
    <li class="{{ $tab==='audit'    ? 'active' : '' }}">
        <a href="{{ route('lrc.admin.index', ['tab'=>'audit']) }}">✦ Audit Log</a>
    </li>
    <li class="{{ $tab==='settings' ? 'active' : '' }}">
        <a href="{{ route('lrc.admin.index', ['tab'=>'settings']) }}">✉ Notification Recipients</a>
    </li>
    <li class="{{ $tab==='mail' ? 'active' : '' }}">
        <a href="{{ route('lrc.admin.index', ['tab'=>'mail']) }}">✏️ Mail Templates</a>
    </li>
    <li class="{{ $tab==='appearance' ? 'active' : '' }}">
        <a href="{{ route('lrc.admin.index', ['tab'=>'appearance']) }}">🎨 Appearance</a>
    </li>
</ul>

{{-- ═══ REQUESTS: pending / approved / rejected / all ═══ --}}
@if($tab === 'pending' || $tab === 'approved' || $tab === 'rejected' || $tab === 'all')

<div class="panel panel-default">
    @if($corrections->isEmpty())
    <div class="panel-body text-center text-muted" style="padding:40px">
        No requests found.
    </div>
    @else
    <table class="table table-hover lrc-admin-table" style="margin:0">
        <thead>
            <tr>
                <th style="width:140px">Flight</th>
                <th>Pilot</th>
                <th style="text-align:right">Original</th>
                <th style="text-align:right">Requested</th>
                <th style="width:100px">Status</th>
                <th style="width:140px">Submitted</th>
                <th style="width:80px">Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($corrections as $c)
        @php
            $pilotName = $c->pilot ? ($c->pilot->name ?? '–') : '–';
            $flight    = $c->pirep ? (($c->pirep->airline?->icao ?? '') . $c->pirep->flight_number) : $c->pirep_id;
            $route     = $c->pirep ? ($c->pirep->dpt_airport_id . ' → ' . $c->pirep->arr_airport_id) : '–';
        @endphp
        <tr>
            <td>
                <strong>{{ $flight }}</strong><br>
                <span class="ts">{{ $route }}</span>
            </td>
            <td>{{ $pilotName }}</td>
            <td style="text-align:right"><code class="code-red">{{ $c->original_landing_rate }} ft/min</code></td>
            <td style="text-align:right"><code class="code-grn">{{ $c->requested_landing_rate }} ft/min</code></td>
            <td>
                @if($c->isPending())     <span class="label label-warning">Pending</span>
                @elseif($c->isApproved())<span class="label label-success">Approved</span>
                @else                    <span class="label label-danger">Rejected</span>
                @endif
            </td>
            <td><span class="ts">{{ $c->created_at->format('d.m.Y H:i') }}</span></td>
            <td>
                <a href="{{ route('lrc.admin.show', $c->id) }}" class="btn btn-xs btn-primary">Review</a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @endif
</div>
<div style="text-align:right">{{ $corrections->links() }}</div>

{{-- ═══ AUDIT LOG ═══ --}}
@elseif($tab === 'audit')

<div class="panel panel-default">
    <div class="panel-heading">
        <strong>✦ Audit Log</strong>
        <small class="text-muted" style="margin-left:8px">All correction requests – who submitted, who decided, when and why</small>
    </div>
    @if($auditLog->isEmpty())
    <div class="panel-body text-center text-muted" style="padding:40px">No entries yet.</div>
    @else
    <table class="table table-hover table-striped lrc-admin-table" style="margin:0;font-size:13px">
        <thead>
            <tr style="background:#f5f5f5">
                <th style="width:95px">Submitted</th>
                <th style="width:95px">Decided</th>
                <th>Pilot</th>
                <th style="width:110px">Flight</th>
                <th style="width:130px">Route</th>
                <th style="text-align:right;width:120px">Original</th>
                <th style="text-align:right;width:120px">Corrected</th>
                <th style="width:95px">Status</th>
                <th>Admin Note</th>
                <th style="width:130px">Decided by</th>
            </tr>
        </thead>
        <tbody>
        @foreach($auditLog as $entry)
        @php
            $ePilot  = $entry->pilot ? ($entry->pilot->name ?? '–') : '–';
            $eAdmin  = $entry->admin ? ($entry->admin->name ?? '–') : '–';
            $eFlight = $entry->pirep ? (($entry->pirep->airline?->icao ?? '') . $entry->pirep->flight_number) : $entry->pirep_id;
            $eRoute  = $entry->pirep ? ($entry->pirep->dpt_airport_id . ' → ' . $entry->pirep->arr_airport_id) : '–';
        @endphp
        <tr>
            <td>
                {{ $entry->created_at->format('d.m.Y') }}<br>
                <span class="ts">{{ $entry->created_at->format('H:i') }}</span>
            </td>
            <td>
                @if($entry->processed_at)
                    {{ $entry->processed_at->format('d.m.Y') }}<br>
                    <span class="ts">{{ $entry->processed_at->format('H:i') }}</span>
                @else
                    <span style="color:#aaa">–</span>
                @endif
            </td>
            <td><strong>{{ $ePilot }}</strong></td>
            <td><strong>{{ $eFlight }}</strong></td>
            <td>{{ $eRoute }}</td>
            <td style="text-align:right"><code class="code-red">{{ $entry->original_landing_rate }} ft/min</code></td>
            <td style="text-align:right"><code class="code-grn">{{ $entry->requested_landing_rate }} ft/min</code></td>
            <td>
                @if($entry->isPending())     <span class="label label-warning">Pending</span>
                @elseif($entry->isApproved())<span class="label label-success">Approved</span>
                @else                        <span class="label label-danger">Rejected</span>
                @endif
            </td>
            <td>{{ $entry->admin_note ?? '–' }}</td>
            <td>
                <strong>{{ $eAdmin }}</strong>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @endif
</div>
<div style="text-align:right">{{ $auditLog->links() }}</div>

{{-- ═══ NOTIFICATION RECIPIENTS ═══ --}}
@elseif($tab === 'settings')

<div class="panel panel-default">
    <div class="panel-heading">
        <strong>✉ Email Notification Recipients</strong>
    </div>
    <div class="panel-body">
        <p class="text-muted" style="margin-bottom:20px">
            These admins will receive an email when a pilot submits a new correction request.
        </p>
        <form action="{{ route('lrc.admin.save_recipients') }}" method="POST">
            @csrf
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:10px;margin-bottom:20px">
                @foreach($admins as $admin)
                <label style="display:flex;align-items:center;gap:10px;padding:12px 14px;border:1px solid #ddd;border-radius:6px;cursor:pointer;font-weight:normal;margin:0;background:#fafafa">
                    <input type="checkbox" name="recipients[]"
                           value="{{ $admin->id }}"
                           {{ in_array($admin->id, $recipientIds) ? 'checked' : '' }}>
                    <span>
                        <strong style="color:#222">{{ $admin->name }}</strong><br>
                        <small style="color:#555">{{ $admin->email }}</small>
                    </span>
                </label>
                @endforeach
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>


@elseif($tab === 'mail')

<div class="panel panel-default">
    <div class="panel-heading">
        <strong>✏️ Mail Templates</strong>
    </div>
    <div class="panel-body">

        <p class="text-muted" style="margin-bottom:20px">
            Customize the subject and body of both email notifications.
            Available placeholders:
            <code>{pilot_name}</code>
            <code>{flight}</code>
            <code>{route}</code>
            <code>{original_rate}</code>
            <code>{requested_rate}</code>
            <code>{reason}</code>
            <code>{admin_note}</code>
        </p>

        <form action="{{ route('lrc.admin.save_mail_templates') }}" method="POST">
            @csrf

            {{-- ── Mail 1: Submitted (to admins) ── --}}
            <div class="panel panel-default" style="margin-bottom:24px">
                <div class="panel-heading" style="background:#f0f4ff">
                    <strong>📨 Mail 1 — New Request (sent to admins)</strong>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="submitted_subject" class="form-control"
                               value="{{ \Modules\LandingRateCorrection\Models\MailSetting::get('submitted_subject') }}"
                               maxlength="200" required>
                    </div>
                    <div class="form-group">
                        <label>Body</label>
                        <textarea name="submitted_body" class="form-control" rows="7"
                                  maxlength="2000" required
                                  style="font-family:monospace;font-size:13px">{{ \Modules\LandingRateCorrection\Models\MailSetting::get('submitted_body') }}</textarea>
                        <small class="text-muted">Use line breaks to separate paragraphs.</small>
                    </div>
                </div>
            </div>

            {{-- ── Mail 2: Approved (to pilot) ── --}}
            <div class="panel panel-default" style="margin-bottom:24px">
                <div class="panel-heading" style="background:#f0fff4">
                    <strong>✅ Mail 2 — Approved (sent to pilot)</strong>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="processed_approved_subject" class="form-control"
                               value="{{ \Modules\LandingRateCorrection\Models\MailSetting::get('processed_approved_subject') }}"
                               maxlength="200" required>
                    </div>
                    <div class="form-group">
                        <label>Body</label>
                        <textarea name="processed_approved_body" class="form-control" rows="7"
                                  maxlength="2000" required
                                  style="font-family:monospace;font-size:13px">{{ \Modules\LandingRateCorrection\Models\MailSetting::get('processed_approved_body') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- ── Mail 3: Rejected (to pilot) ── --}}
            <div class="panel panel-default" style="margin-bottom:24px">
                <div class="panel-heading" style="background:#fff4f0">
                    <strong>❌ Mail 3 — Rejected (sent to pilot)</strong>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>Subject</label>
                        <input type="text" name="processed_rejected_subject" class="form-control"
                               value="{{ \Modules\LandingRateCorrection\Models\MailSetting::get('processed_rejected_subject') }}"
                               maxlength="200" required>
                    </div>
                    <div class="form-group">
                        <label>Body</label>
                        <textarea name="processed_rejected_body" class="form-control" rows="7"
                                  maxlength="2000" required
                                  style="font-family:monospace;font-size:13px">{{ \Modules\LandingRateCorrection\Models\MailSetting::get('processed_rejected_body') }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">💾 Save Templates</button>

        </form>
    </div>
</div>

@elseif($tab === 'appearance')
{{-- ═══ APPEARANCE ═══ --}}
<div class="row">
<div class="col-md-9">

    <div class="panel panel-default">
        <div class="panel-heading"><strong>🎨 Card &amp; Background Mode</strong></div>
        <div class="panel-body">

            <form method="POST" action="{{ route('lrc.admin.save_appearance') }}" id="lrc-appearance-form">
                @csrf

                @php $glassMode = \Modules\LandingRateCorrection\Models\MailSetting::get('appearance_glass_mode', '1'); @endphp

                {{-- Mode Toggle --}}
                <div class="form-group">
                    <label style="font-weight:700;display:block;margin-bottom:10px">Visual Mode</label>
                    <div style="display:flex;gap:12px;flex-wrap:wrap">

                        <div id="lbl-glass" style="display:flex;align-items:center;gap:10px;padding:14px 22px;border:2px solid {{ $glassMode==='1' ? '#3b82f6' : '#ddd' }};border-radius:10px;min-width:260px;background:{{ $glassMode==='1' ? '#eff6ff' : '#fff' }};cursor:pointer" onclick="document.getElementById('mode-glass').click()">
                            <input type="radio" name="glass_mode" value="1" id="mode-glass" {{ $glassMode==='1' ? 'checked' : '' }}>
                            <span>🪟</span>
                            <span>
                                <strong>Glass Mode</strong><br>
                                <small style="color:#999;font-weight:400">Transparent cards — for themes without background images</small>
                            </span>
                        </div>

                        <div id="lbl-solid" style="display:flex;align-items:center;gap:10px;padding:14px 22px;border:2px solid {{ $glassMode==='0' ? '#3b82f6' : '#ddd' }};border-radius:10px;min-width:260px;background:{{ $glassMode==='0' ? '#eff6ff' : '#fff' }};cursor:pointer" onclick="document.getElementById('mode-solid').click()">
                            <input type="radio" name="glass_mode" value="0" id="mode-solid" {{ $glassMode==='0' ? 'checked' : '' }}>
                            <span>🧱</span>
                            <span>
                                <strong>Solid Mode</strong><br>
                                <small style="color:#999;font-weight:400">Opaque cards — for themes with background images</small>
                            </span>
                        </div>

                    </div>
                </div>

                {{-- Solid Color Settings --}}
                <div id="solid-colors" style="display:{{ $glassMode==='0' ? 'block' : 'none' }};margin-top:20px;padding:18px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px">

                    @php
                    $darkColors = [
                        ['appearance_solid_card',    '#1a1f2e', 'Card / Panel Background'],
                        ['appearance_solid_border',  '#2a3040', 'Card Border'],
                        ['appearance_solid_surface', '#171c28', 'Table Header / Surface'],
                        ['appearance_solid_select',  '#1e2535', 'Dropdown / Select Background'],
                        ['appearance_solid_kpi',     '#0f1420', 'KPI Tile Background'],
                        ['appearance_solid_accent',  '#3b82f6', 'Accent / Active Button Color'],
                    ];
                    $lightColors = [
                        ['appearance_solid_card_light',    '#ffffff', 'Card / Panel Background'],
                        ['appearance_solid_border_light',  '#e2e8f0', 'Card Border'],
                        ['appearance_solid_surface_light', '#f8fafc', 'Table Header / Surface'],
                        ['appearance_solid_select_light',  '#f1f5f9', 'Dropdown / Select Background'],
                        ['appearance_solid_kpi_light',     '#e2e8f0', 'KPI Tile Background'],
                        ['appearance_solid_accent_light',  '#2563eb', 'Accent / Active Button Color'],
                    ];
                    @endphp

                    <p style="font-weight:700;margin:0 0 14px">🌙 Dark Mode Colors</p>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:22px">
                        @foreach($darkColors as [$key, $default, $label])
                        @php 
                            $val = \Modules\LandingRateCorrection\Models\MailSetting::get($key, $default);
                            $fieldName = str_replace('appearance_solid_', 'solid_', $key);
                        @endphp
                        <div>
                            <label style="font-size:12px;display:block;margin-bottom:5px;color:#555">{{ $label }}</label>
                            <div style="display:flex;align-items:center;gap:8px">
                                <input type="color" class="lrc-color-picker"
                                       name="{{ $fieldName }}"
                                       value="{{ $val }}"
                                       style="width:38px;height:32px;padding:2px;border:1px solid #ccc;border-radius:5px;cursor:pointer">
                                <input type="text" class="lrc-color-text" value="{{ $val }}" readonly
                                       style="font-family:monospace;font-size:12px;width:85px;padding:4px 8px;border:1px solid #ccc;border-radius:5px;background:#fff">
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <p style="font-weight:700;margin:0 0 14px">☀️ Light Mode Colors</p>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px">
                        @foreach($lightColors as [$key, $default, $label])
                        @php 
                            $val = \Modules\LandingRateCorrection\Models\MailSetting::get($key, $default);
                            $fieldName = str_replace('appearance_solid_', 'solid_', $key);
                        @endphp
                        <div>
                            <label style="font-size:12px;display:block;margin-bottom:5px;color:#555">{{ $label }}</label>
                            <div style="display:flex;align-items:center;gap:8px">
                                <input type="color" class="lrc-color-picker"
                                       name="{{ $fieldName }}"
                                       value="{{ $val }}"
                                       style="width:38px;height:32px;padding:2px;border:1px solid #ccc;border-radius:5px;cursor:pointer">
                                <input type="text" class="lrc-color-text" value="{{ $val }}" readonly
                                       style="font-family:monospace;font-size:12px;width:85px;padding:4px 8px;border:1px solid #ccc;border-radius:5px;background:#fff">
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>{{-- /solid-colors --}}

                <div style="margin-top:22px;display:flex;align-items:center;gap:16px;flex-wrap:wrap">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Appearance
                    </button>
                    <small style="color:#999">Changes apply immediately after saving.</small>
                </div>

            </form>

            {{-- Reset to Defaults --}}
            <div style="margin-top:24px;padding-top:20px;border-top:1px solid #e2e8f0">
                <form method="POST" action="{{ route('lrc.admin.reset_appearance') }}" 
                      onsubmit="return confirm('Reset all appearance settings to defaults?\n\nThis will:\n• Set mode to Glass\n• Reset all colors to defaults\n\nThis cannot be undone.');">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="fas fa-undo"></i> Reset to Defaults
                    </button>
                    <small style="color:#999;margin-left:12px">Resets mode to Glass and all colors to factory defaults.</small>
                </form>
            </div>

        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><strong>ℹ️ How It Works</strong></div>
        <div class="panel-body" style="font-size:13px;line-height:1.8">
            <p><strong>Glass Mode</strong> — cards and table backgrounds are transparent. The theme's own background shows through.</p>
            <p><strong>Solid Mode</strong> — cards use fixed opaque colors. Essential when your theme has a background image or gradient.</p>
            <p style="margin:0"><strong>Color pickers</strong> update the hex value live. Dark defaults (<code>#1a1f2e</code>, <code>#2a3040</code> …) match a standard dark navy theme.</p>
        </div>
    </div>

</div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var rGlass   = document.getElementById('mode-glass');
    var rSolid   = document.getElementById('mode-solid');
    var solidBox = document.getElementById('solid-colors');
    var lblGlass = document.getElementById('lbl-glass');
    var lblSolid = document.getElementById('lbl-solid');

    function updateUI() {
        var isSolid = rSolid && rSolid.checked;
        if (solidBox)  solidBox.style.display      = isSolid ? 'block' : 'none';
        if (lblGlass) { lblGlass.style.borderColor = isSolid ? '#ddd'    : '#3b82f6'; lblGlass.style.background = isSolid ? '#fff'    : '#eff6ff'; }
        if (lblSolid) { lblSolid.style.borderColor = isSolid ? '#3b82f6' : '#ddd';   lblSolid.style.background = isSolid ? '#eff6ff' : '#fff'; }
    }

    if (rGlass) rGlass.addEventListener('change', updateUI);
    if (rSolid) rSolid.addEventListener('change', updateUI);

    document.querySelectorAll('.lrc-color-picker').forEach(function(picker) {
        picker.addEventListener('input', function() {
            var t = this.nextElementSibling;
            if (t) t.value = this.value;
        });
    });
});
</script>

@endif

</div>
</div>
@endsection
