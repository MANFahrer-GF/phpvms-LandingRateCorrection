@extends('admin.app')
@section('title', 'LRC – Landing Rate Corrections')

@section('actions')
<li>
    <a href="{{ route('lrc.admin.implausible') }}" class="btn btn-sm btn-warning navbar-btn">
        ⚠ Implausible PIREPs
    </a>
</li>

{{-- ── LRC Footer ── --}}
<center style="color:gray;font-size:12px;opacity:0.5;transition:opacity .2s;margin-top:2.5rem;padding-bottom:1rem;display:block"
        onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='0.5'">
  <a href="https://github.com/MANFahrer-GF" target="_blank"
     style="color:gray;text-decoration:none">Landing Rate Corrections</a>
  &mdash; crafted with
  <span style="color:#e25555;animation:lrc-pulse 1.8s ease-in-out infinite">&#9829;</span>
  in Germany by Thomas Kant
</center>
<style>@keyframes lrc-pulse{0%,100%{opacity:1}50%{opacity:.4}}</style>

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

@endif

</div>
</div>
@endsection
