@extends('admin.app')
@section('title', 'LRC – Review Request')

@section('actions')
<li>
    <a href="{{ route('lrc.admin.index') }}" class="btn btn-sm btn-default navbar-btn">
        ← Back to List
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
/* LRC Admin – force readable dark text */
.lrc-admin-table td, .lrc-admin-table th { color: #222 !important; }
.lrc-admin-table small, .lrc-admin-table span.ts { color: #444 !important; font-size:12px; }
.lrc-admin-table code { font-weight:700; font-size:13px; }
.panel-heading strong { color: #111 !important; }
.panel-heading small  { color: #555 !important; }
.text-muted           { color: #555 !important; }
dl dt { color: #444 !important; font-weight: 600; }
dl dd { color: #111 !important; }
label { color: #222 !important; }
pre, code { color: #111 !important; }
</style>
<div class="row">
<div class="col-md-8 col-md-offset-1">

@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
@if(session('error'))  <div class="alert alert-danger">{{ session('error') }}</div>@endif

{{-- Flight Details --}}
<div class="panel panel-default">
    <div class="panel-heading"><strong>Flight Details</strong></div>
    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>PIREP ID</dt>
            <dd><code>{{ $correction->pirep_id }}</code></dd>

            <dt>Pilot</dt>
            <dd><strong>{{ $pilotName }}</strong></dd>

            <dt>Flight</dt>
            <dd>
                @if($correction->pirep)
                    <strong>{{ ($correction->pirep->airline?->icao ?? '') . $correction->pirep->flight_number }}</strong>
                    &nbsp;
                    {{ $correction->pirep->dpt_airport_id }} → {{ $correction->pirep->arr_airport_id }}
                @else
                    –
                @endif
            </dd>

            <dt>Original Rate</dt>
            <dd><code style="color:#b71c1c;font-size:14px">{{ $correction->original_landing_rate }} ft/min</code></dd>

            <dt>Requested Rate</dt>
            <dd><code style="color:#1b5e20;font-size:14px">{{ $correction->requested_landing_rate }} ft/min</code></dd>

            <dt>Status</dt>
            <dd>
                @if($correction->isPending())
                    <span class="label label-warning" style="font-size:13px">⏳ Pending</span>
                @elseif($correction->isApproved())
                    <span class="label label-success" style="font-size:13px">✓ Approved</span>
                @else
                    <span class="label label-danger"  style="font-size:13px">✗ Rejected</span>
                @endif
            </dd>

            <dt>Submitted</dt>
            <dd>{{ $correction->created_at->format('d.m.Y H:i') }}</dd>

            @if(!$correction->isPending())
            <dt>Decided on</dt>
            <dd>{{ $correction->processed_at?->format('d.m.Y H:i') ?? '–' }}</dd>

            <dt>Decided by</dt>
            <dd>
                @if($correction->admin)
                    <strong>{{ $adminName }}</strong>
                @else
                    <span style="color:#999">–</span>
                @endif
            </dd>
            @endif
        </dl>
    </div>
</div>

{{-- Pilot's Reason --}}
<div class="panel panel-default">
    <div class="panel-heading"><strong>Pilot's Reason</strong></div>
    <div class="panel-body">
        <p style="white-space:pre-wrap;color:#222;margin:0">{{ $correction->reason }}</p>
    </div>
</div>

{{-- Evidence --}}
@if($correction->hasEvidence())
<div class="panel panel-default">
    <div class="panel-heading"><strong>Evidence</strong></div>
    <div class="panel-body">
        @php $evUrl = $correction->evidenceUrl(); @endphp
        @if($evUrl)
            @if($correction->isImage())
                <a href="{{ $evUrl }}" target="_blank">
                    <img src="{{ $evUrl }}"
                         style="max-width:100%;max-height:500px;border:1px solid #ddd;border-radius:4px;display:block"
                         onerror="this.style.display='none';document.getElementById('ev-err').style.display='block'">
                </a>
                <div id="ev-err" style="display:none">
                    <div class="alert alert-warning" style="margin-top:8px">
                        Image could not be loaded inline.
                        <a href="{{ $evUrl }}" target="_blank">Open directly →</a>
                    </div>
                </div>
            @else
                <a href="{{ $evUrl }}" target="_blank" class="btn btn-default">
                    📎 Open document
                </a>
            @endif
            <div style="margin-top:10px">
                <a href="{{ $evUrl }}" target="_blank" class="btn btn-xs btn-default">Open in new tab</a>
                <small style="color:#555;margin-left:10px">{{ $correction->evidence_original_name }}</small>
            </div>
        @else
            {{-- Storage::disk check failed – try direct public URL as fallback --}}
            @php
                $fallbackUrl = asset('storage/' . $correction->evidence_path);
            @endphp
            <div class="alert alert-warning" style="margin:0">
                <strong>File stored but not yet accessible via route.</strong><br>
                Try opening directly:
                <a href="{{ $fallbackUrl }}" target="_blank">{{ $correction->evidence_original_name ?: basename($correction->evidence_path) }}</a><br>
                <small style="color:#888">Path: <code>{{ $correction->evidence_path }}</code></small>
            </div>
        @endif
    </div>
</div>
@endif

{{-- Admin Decision --}}
@if($correction->isPending())

<div class="panel panel-default">
    <div class="panel-heading"><strong>Decision</strong></div>
    <div class="panel-body">

        <h5 style="color:#222;margin-top:0">✓ Approve</h5>
        <form action="{{ route('lrc.admin.approve', $correction->id) }}" method="POST" style="margin-bottom:24px">
            @csrf
            <div class="form-group">
                <label style="color:#333">Note for pilot <small class="text-muted">(optional)</small></label>
                <textarea name="admin_note" class="form-control" rows="3"
                          placeholder="e.g. Rate confirmed, corrected in system"></textarea>
            </div>
            <button type="submit" class="btn btn-success">
                ✓ Approve &amp; Apply Correction
            </button>
        </form>

        <hr>

        <h5 style="color:#222">✗ Reject</h5>
        <form action="{{ route('lrc.admin.reject', $correction->id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label style="color:#333">Reason for rejection <span class="text-danger">*</span></label>
                <textarea name="admin_note" class="form-control" rows="3"
                          placeholder="e.g. Landing rate matches the recorded data" required></textarea>
            </div>
            <button type="submit" class="btn btn-danger">✗ Reject Request</button>
        </form>

    </div>
</div>

@else

{{-- Already decided – show decision summary --}}
<div class="panel {{ $correction->isApproved() ? 'panel-success' : 'panel-danger' }}">
    <div class="panel-heading">
        <strong>{{ $correction->isApproved() ? '✓ Approved' : '✗ Rejected' }}</strong>
        <span style="float:right;font-size:12px">
            by <strong>{{ $adminName }}</strong>
            on {{ $correction->processed_at?->format('d.m.Y H:i') }}
        </span>
    </div>
    @if($correction->admin_note)
    <div class="panel-body">
        <p style="margin:0;color:#222"><strong>Admin note:</strong> {{ $correction->admin_note }}</p>
    </div>
    @endif
</div>

@endif

</div>
</div>
@endsection
