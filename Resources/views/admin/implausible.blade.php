@extends('admin.app')
@section('title', 'LRC – Implausible PIREPs & Audit')

@section('actions')
<li>
    <a href="{{ route('lrc.admin.index') }}" class="btn btn-default"
       style="padding: 8px 16px; font-weight: 600; display: inline-block;">
        ← Back to Requests
    </a>
</li>

@endsection

@section('content')
<style>
/* LRC Admin – force readable dark text */
.lrc-admin-table td, .lrc-admin-table th { color: #222 !important; }
.lrc-admin-table small, .lrc-admin-table span.ts { color: #444 !important; font-size:12px; }
.lrc-admin-table code { font-weight:700; font-size:13px; }
.lrc-admin-table .code-red { color: #b71c1c !important; }
.lrc-admin-table .code-grn { color: #1b5e20 !important; }
.panel-heading strong { color: #111 !important; }
.panel-heading small  { color: #555 !important; }
.text-muted           { color: #555 !important; }
dl dt { color: #444 !important; }
dl dd { color: #111 !important; }
label { color: #222 !important; }
pre, code { color: #111 !important; }
</style>
<div class="row">
<div class="col-md-12">

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))  <div class="alert alert-danger">{{ session('error') }}</div>@endif

{{-- Tabs --}}
<ul class="nav nav-tabs" style="margin-bottom:20px">
    <li class="{{ $tab==='implausible' ? 'active' : '' }}">
        <a href="{{ route('lrc.admin.implausible', ['tab'=>'implausible']) }}">
            ⚠ Implausible PIREPs
            @if($implausibleCount > 0)
                <span class="badge" style="background:#d9534f;color:#fff">{{ $implausibleCount }}</span>
            @endif
        </a>
    </li>
    <li class="{{ $tab==='audit'    ? 'active' : '' }}">
        <a href="{{ route('lrc.admin.implausible', ['tab'=>'audit']) }}">✦ Audit Log</a>
    </li>

</ul>

{{-- ═══ TAB: IMPLAUSIBLE ═══ --}}
@if($tab === 'implausible')

<div class="panel panel-default">
    <div class="panel-heading">
        <strong>Implausible Landing Rates</strong>
        <small class="text-muted" style="margin-left:8px">No value, positive, or shallower than {{ $threshold }} ft/min</small>
    </div>
    @if($pireps->isEmpty())
    <div class="panel-body text-center text-muted" style="padding:40px">
        <i class="fas fa-check-circle fa-2x" style="display:block;margin-bottom:10px;color:#5cb85c"></i>
        No implausible PIREPs found.
    </div>
    @else
    <table class="table table-hover lrc-admin-table" style="margin:0">
        <thead>
            <tr>
                <th>Flight</th>
                <th>Pilot</th>
                <th>Route</th>
                <th>Landing Rate</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @foreach($pireps as $pirep)
        @php
            $pName = $pirep->user
                ? trim(($pirep->user->name_first ?? '') . ' ' . ($pirep->user->name_last ?? ''))
                  ?: ($pirep->user->name ?? '–')
                : '–';
        @endphp
        <tr>
            <td><strong>{{ ($pirep->airline?->icao ?? '') . $pirep->flight_number }}</strong></td>
            <td>{{ $pName }}</td>
            <td>{{ $pirep->dpt_airport_id }} &rarr; {{ $pirep->arr_airport_id }}</td>
            <td>
                @if($pirep->landing_rate === null)
                    <span class="label label-default">No value</span>
                @else
                    <code class="code-red">{{ $pirep->landing_rate }} ft/min</code>
                @endif
            </td>
            <td><small>{{ $pirep->submitted_at?->format('d.m.Y') ?? '–' }}</small></td>
            <td>
                <button class="btn btn-xs btn-warning"
                        onclick="lrcToggleFix('fix{{ $pirep->id }}', this)">
                    Direct Fix
                </button>
            </td>
        </tr>
        <tr id="fix{{ $pirep->id }}" style="display:none;background:#fff8e1">
            <td colspan="6">
                <form action="{{ route('lrc.admin.fix_implausible', $pirep->id) }}" method="POST"
                      style="display:flex;gap:10px;align-items:flex-end;padding:8px 0;flex-wrap:wrap">
                    @csrf
                    <div>
                        <label style="font-size:12px;display:block">Corrected Rate (ft/min)</label>
                        <input type="number" name="landing_rate" class="form-control input-sm"
                               style="width:160px" placeholder="-150" min="-9999" max="-1" required>
                    </div>
                    <div style="flex:1;min-width:200px">
                        <label style="font-size:12px;display:block">Admin Note *</label>
                        <input type="text" name="admin_note" class="form-control input-sm"
                               placeholder="Reason for direct fix" required>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-sm btn-success">Apply</button>
                        <button type="button" class="btn btn-sm btn-default"
                                onclick="lrcToggleFix('fix{{ $pirep->id }}', this)">Cancel</button>
                    </div>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @endif
</div>
<div style="text-align:right">{{ $pireps->links() }}</div>

{{-- ═══ TAB: AUDIT LOG ═══ --}}
@elseif($tab === 'audit')

<div class="panel panel-default">
    <div class="panel-heading">
        <strong>Audit Log</strong>
        <small class="text-muted" style="margin-left:8px">All correction requests and decisions</small>
    </div>
    @if($auditLog->isEmpty())
    <div class="panel-body text-center text-muted" style="padding:40px">
        No audit entries found.
    </div>
    @else
    <table class="table table-hover table-striped lrc-admin-table" style="margin:0">
        <thead>
            <tr>
                <th style="width:110px">Date</th>
                <th>Pilot</th>
                <th>Flight</th>
                <th>Route</th>
                <th style="text-align:right">Original</th>
                <th style="text-align:right">Corrected</th>
                <th style="width:100px">Status</th>
                <th>Admin Note</th>
                <th>Decided by</th>
            </tr>
        </thead>
        <tbody>
        @foreach($auditLog as $entry)
        @php
            $ePilot = $entry->pilot
                ? trim(($entry->pilot->name_first ?? '') . ' ' . ($entry->pilot->name_last ?? ''))
                  ?: ($entry->pilot->name ?? '–')
                : '–';
            $eAdmin = $entry->admin
                ? trim(($entry->admin->name_first ?? '') . ' ' . ($entry->admin->name_last ?? ''))
                  ?: ($entry->admin->name ?? '–')
                : '–';
            $eFlight = $entry->pirep
                ? (($entry->pirep->airline?->icao ?? '') . $entry->pirep->flight_number)
                : $entry->pirep_id;
            $eRoute = $entry->pirep
                ? ($entry->pirep->dpt_airport_id . ' → ' . $entry->pirep->arr_airport_id)
                : '–';
        @endphp
        <tr>
            <td>
                <small>{{ $entry->created_at->format('d.m.Y') }}</small><br>
                <small class="text-muted">{{ $entry->created_at->format('H:i') }}</small>
            </td>
            <td>{{ $ePilot }}</td>
            <td><strong>{{ $eFlight }}</strong></td>
            <td><small>{{ $eRoute }}</small></td>
            <td style="text-align:right">
                <code class="code-red">{{ $entry->original_landing_rate }} ft/min</code>
            </td>
            <td style="text-align:right">
                <code class="code-grn">{{ $entry->requested_landing_rate }} ft/min</code>
            </td>
            <td>
                @if($entry->isPending())
                    <span class="label label-warning">Pending</span>
                @elseif($entry->isApproved())
                    <span class="label label-success">Approved</span>
                @else
                    <span class="label label-danger">Rejected</span>
                @endif
            </td>
            <td>
                @if($entry->admin_note)
                    <small>{{ $entry->admin_note }}</small>
                @else
                    <span class="text-muted">–</span>
                @endif
            </td>
            <td>
                <small>{{ $eAdmin }}</small>
                @if($entry->processed_at)
                    <br><small class="text-muted">{{ $entry->processed_at->format('d.m.Y H:i') }}</small>
                @endif
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
    @endif
</div>
@if($auditLog instanceof \Illuminate\Pagination\LengthAwarePaginator)
<div style="text-align:right">{{ $auditLog->links() }}</div>
@endif

@endif

</div>
</div>

<script>
function lrcToggleFix(id, btn) {
    var row = document.getElementById(id);
    if (!row) return;
    var open = row.style.display !== 'table-row';
    row.style.display = open ? 'table-row' : 'none';
}
</script>
@endsection
