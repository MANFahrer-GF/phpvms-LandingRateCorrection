{{-- LandingRateCorrection :: pilot/index.blade.php --}}
@extends('app')
@section('title', app()->getLocale()==='de' ? 'Landeratenkorrekturen' : 'Landing Rate Corrections')

@php
$lang = str_starts_with(app()->getLocale(), 'en') ? 'en' : 'de';
$t = [
  'title'       => $lang==='en' ? 'Landing Rate Corrections'   : 'Landeratenkorrekturen',
  'subtitle'    => $lang==='en' ? 'Request a correction for an incorrectly recorded landing rate.'
                                : 'Beantrage die Korrektur einer falsch aufgezeichneten Landerate.',
  'tab_flights' => $lang==='en' ? 'My Flights'    : 'Meine Flüge',
  'tab_imp'     => $lang==='en' ? 'Implausible'   : 'Unplausibel',
  'tab_audit'   => $lang==='en' ? 'My Requests'   : 'Meine Anträge',
  'tab_guide'   => $lang==='en' ? 'Guide'          : 'Handbuch',
  'date'        => $lang==='en' ? 'Date'           : 'Datum',
  'flight'      => $lang==='en' ? 'Flight'         : 'Flugnr.',
  'route'       => $lang==='en' ? 'Route'          : 'Route',
  'aircraft'    => $lang==='en' ? 'Aircraft'       : 'Flugzeug',
  'rate'        => $lang==='en' ? 'Landing Rate'   : 'Landerate',
  'status'      => $lang==='en' ? 'Status'         : 'Status',
  'details'     => $lang==='en' ? 'Details'        : 'Details',
  'action'      => $lang==='en' ? 'Action'         : 'Aktion',
  'original'    => $lang==='en' ? 'Original'       : 'Original',
  'requested'   => $lang==='en' ? 'Requested'      : 'Beantragt',
  'decision'    => $lang==='en' ? 'Admin Decision' : 'Admin-Entscheid',
  'submitted'   => $lang==='en' ? 'Submitted'      : 'Eingereicht',
  'btn_fix'     => $lang==='en' ? 'Request Fix'    : 'Korrigieren',
  'btn_redo'    => $lang==='en' ? 'Re-apply'       : 'Neu beantragen',
  'pending'     => $lang==='en' ? 'Pending'        : 'Ausstehend',
  'approved'    => $lang==='en' ? 'Approved'       : 'Genehmigt',
  'rejected'    => $lang==='en' ? 'Rejected'       : 'Abgelehnt',
  'no_flights'  => $lang==='en' ? 'No completed flights found.'  : 'Keine abgeschlossenen Flüge.',
  'no_imp'      => $lang==='en' ? 'No implausible flights!'      : 'Keine unplausiblen Flüge!',
  'no_req'      => $lang==='en' ? 'No requests yet.'             : 'Noch keine Anträge.',
  'imp_title'   => $lang==='en' ? 'Implausible Landing Rates'    : 'Unplausible Landeraten',
  'imp_desc'    => $lang==='en' ? 'No value, positive, or shallower than -20 ft/min.'
                                : 'Kein Wert, positiv oder flacher als -20 ft/min.',
  'your_reason' => $lang==='en' ? 'Your Reason'   : 'Deine Begründung',
  'admin_reply' => $lang==='en' ? 'Admin Response': 'Admin-Antwort',
  'instead_of'  => $lang==='en' ? 'instead of'    : 'statt',
  'awaiting'    => $lang==='en' ? 'Awaiting review…':'Wartet auf Prüfung…',
  'no_val'      => $lang==='en' ? 'no value'       : 'kein Wert',
];
$impCount  = $implausiblePireps->count();
$pendCount = $auditLog->where('status','pending')->count();
@endphp

@section('content')

{{-- Fonts + Icons – same as AirlineInfoPulse --}}
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2/src/fill/style.css" rel="stylesheet">

{{-- Theme detection – copied exactly from AirlineInfoPulse --}}
<script>
(function(){
  function detectTheme(){
    var h=document.documentElement,b=document.body;
    var isDark=h.getAttribute('data-bs-theme')==='dark'
      ||h.getAttribute('data-theme')==='dark'
      ||b.getAttribute('data-bs-theme')==='dark'
      ||b.getAttribute('data-theme')==='dark'
      ||b.classList.contains('dark-mode')||b.classList.contains('dark')
      ||h.classList.contains('dark-mode')||h.classList.contains('dark')
      ||(!h.getAttribute('data-bs-theme')&&!b.getAttribute('data-bs-theme')
         &&!h.getAttribute('data-theme')&&!b.getAttribute('data-theme')
         &&!b.classList.contains('light-mode')&&!b.classList.contains('light')
         &&!h.classList.contains('light-mode')&&!h.classList.contains('light')
         &&window.matchMedia('(prefers-color-scheme:dark)').matches);
    h.classList.toggle('ap-dark',isDark);
    h.classList.toggle('ap-light',!isDark);
  }
  detectTheme();
  new MutationObserver(detectTheme).observe(document.documentElement,{attributes:true,attributeFilter:['data-bs-theme','data-theme','class']});
  new MutationObserver(detectTheme).observe(document.body,{attributes:true,attributeFilter:['data-bs-theme','data-theme','class']});
  window.matchMedia('(prefers-color-scheme:dark)').addEventListener('change',detectTheme);
})();
</script>

<style>
/* ── Same design tokens as AirlineInfoPulse ── */
:root {
  --ap-blue:   #3b82f6;
  --ap-green:  #22c55e;
  --ap-amber:  #f59e0b;
  --ap-red:    #ef4444;
  --ap-font-head: 'Outfit', sans-serif;
  --ap-font-mono: 'JetBrains Mono', monospace;
  --ap-font-body: 'Inter', sans-serif;
  /* dark defaults */
  --ap-surface:  rgba(255,255,255,0.04);
  --ap-border:   rgba(255,255,255,0.08);
  --ap-border2:  rgba(255,255,255,0.18);
  --ap-card-bg:  rgba(255,255,255,0.03);
  --ap-text:     #e2e8f0;
  --ap-text-head:#ffffff;
  --ap-muted:    #94a3b8;
  --ap-tag-bg:   rgba(255,255,255,0.07);
}
html.ap-light {
  --ap-surface:  rgba(255,255,255,0.9);
  --ap-border:   rgba(0,0,0,0.1);
  --ap-border2:  rgba(0,0,0,0.2);
  --ap-card-bg:  rgba(255,255,255,0.8);
  --ap-text:     #1e293b;
  --ap-text-head:#0f172a;
  --ap-muted:    #64748b;
  --ap-tag-bg:   rgba(0,0,0,0.06);
}

/* ── Wrapper ── */
.lrc-wrap {
  font-family: var(--ap-font-body);
  color: var(--ap-text);
  max-width: 1200px;
  margin: 0 auto;
  padding: 1.5rem 1rem;
}

/* ── Hero ── */
.lrc-hero { margin-bottom: 1.5rem; }
.lrc-hero h4 {
  font-family: var(--ap-font-head);
  font-size: 1.4rem; font-weight: 700;
  color: var(--ap-text-head); margin: 0 0 .3rem;
}
.lrc-hero p { font-size: .875rem; color: var(--ap-muted); margin: 0; }

/* ── Flash ── */
.lrc-alert {
  padding: .75rem 1rem; border-radius: 10px;
  margin-bottom: 1rem; font-size: .875rem;
  display: flex; align-items: center; gap: .5rem;
  border: 1px solid;
}
.lrc-alert-ok { background: rgba(34,197,94,.1);  border-color: rgba(34,197,94,.25);  color: #4ade80; }
.lrc-alert-wa { background: rgba(245,158,11,.1); border-color: rgba(245,158,11,.25); color: #fbbf24; }
.lrc-alert-er { background: rgba(239,68,68,.1);  border-color: rgba(239,68,68,.25);  color: #f87171; }
html.ap-light .lrc-alert-ok { color: #166534; }
html.ap-light .lrc-alert-wa { color: #92400e; }
html.ap-light .lrc-alert-er { color: #991b1b; }

/* ── Tabs ── */
.lrc-tabs {
  display: flex; gap: 0;
  border-bottom: 1px solid var(--ap-border2);
  margin-bottom: 1.25rem;
}
.lrc-tab {
  display: inline-flex; align-items: center; gap: .4rem;
  padding: .65rem 1.2rem; font-size: .875rem; font-weight: 600;
  font-family: var(--ap-font-head);
  color: var(--ap-muted); cursor: pointer;
  border: none; background: none;
  border-bottom: 2px solid transparent; margin-bottom: -1px;
  transition: color .15s, border-color .15s;
}
.lrc-tab:hover { color: var(--ap-text); }
.lrc-tab.on { color: var(--ap-blue); border-bottom-color: var(--ap-blue); }
.lrc-tc {
  display: inline-flex; align-items: center; justify-content: center;
  min-width: 18px; padding: .1em .4em;
  border-radius: 20px; font-size: .65rem; font-weight: 700;
}
.lrc-tc-r { background: var(--ap-red);   color: #fff; }
.lrc-tc-a { background: var(--ap-amber); color: #1a1a1a; }

/* ── Panels ── */
.lrc-panel { display: none; }
.lrc-panel.on { display: block; }

/* ── Glass card – same as AirlinePulse ap-glass ── */
.lrc-card {
  background: var(--ap-card-bg);
  border: 1px solid var(--ap-border);
  border-radius: 14px;
  overflow: hidden;
  margin-bottom: 1.25rem;
}
html.ap-light .lrc-card {
  background: rgba(255,255,255,0.85);
  box-shadow: 0 2px 16px rgba(0,0,0,.07);
}

/* ── Table ── */
.lrc-tw { overflow-x: auto; }
.lrc-t  { width: 100%; border-collapse: collapse; }
.lrc-t td:nth-child(3), .lrc-t th:nth-child(3) { white-space: nowrap; }
.lrc-t thead th {
  padding: .45rem 1rem;
  font-family: var(--ap-font-head);
  font-size: .68rem; text-transform: uppercase; letter-spacing: .08em;
  color: var(--ap-muted);
  background: var(--ap-surface);
  border-bottom: 1px solid var(--ap-border2);
  font-weight: 600; white-space: nowrap;
}
.lrc-t tbody td {
  padding: .45rem 1rem;
  border-bottom: 1px solid var(--ap-border);
  vertical-align: middle;
  font-size: .875rem;
  color: var(--ap-text);
}
.lrc-t tbody tr:last-child > td { border-bottom: none; }
.lrc-t tbody tr:hover > td { background: var(--ap-surface); }

/* Row status indicators */
.lrc-t tbody tr.rs-pen > td:first-child { border-left: 3px solid var(--ap-amber); }
.lrc-t tbody tr.rs-app > td:first-child { border-left: 3px solid var(--ap-green); }
.lrc-t tbody tr.rs-rej > td:first-child { border-left: 3px solid var(--ap-red); }
.lrc-t tbody tr.rs-imp > td { background: rgba(239,68,68,.04); }
.lrc-t tbody tr.rs-imp > td:first-child { border-left: 3px solid rgba(239,68,68,.5); }
.lrc-t tbody tr.rs-imp td { color: var(--ap-text) !important; }

/* ── Rate colours (JetBrains Mono like AirlinePulse) ── */
.lrc-rt { font-family: var(--ap-font-mono); font-weight: 600; font-size: .88rem; white-space: nowrap; }
.lrc-rg { color: #4ade80; }
.lrc-ry { color: #fbbf24; }
.lrc-ro { color: #fb923c; }
.lrc-rb { color: #f87171; }
.lrc-rn { color: var(--ap-muted); font-style: italic; }
html.ap-light .lrc-rg { color: #166534; }
html.ap-light .lrc-ry { color: #92400e; }
html.ap-light .lrc-ro { color: #c2410c; }
html.ap-light .lrc-rb { color: #991b1b; }

/* ── Tags / Badges ── */
.lrc-tag {
  display: inline-flex; align-items: center; gap: .2rem;
  padding: .2em .6em; border-radius: 20px;
  font-size: .72rem; font-weight: 600;
  font-family: var(--ap-font-head);
  white-space: nowrap;
}
.lrc-tag-ok { background: rgba(34,197,94,.12);  color: #4ade80;  border: 1px solid rgba(34,197,94,.3);  }
.lrc-tag-wa { background: rgba(245,158,11,.12); color: #fbbf24;  border: 1px solid rgba(245,158,11,.3); }
.lrc-tag-er { background: rgba(239,68,68,.12);  color: #f87171;  border: 1px solid rgba(239,68,68,.3);  }
.lrc-tag-im { background: rgba(239,68,68,.18);  color: #f87171;  border: 1px solid rgba(239,68,68,.4);  font-size: .68rem; }
html.ap-light .lrc-tag-ok { color: #166534; }
html.ap-light .lrc-tag-wa { color: #92400e; }
html.ap-light .lrc-tag-er { color: #991b1b; }
html.ap-light .lrc-tag-im { color: #991b1b; }

/* ── Buttons ── */
.lrc-btn {
  display: inline-flex; align-items: center; gap: .28rem;
  padding: .35rem .9rem; border-radius: 8px;
  font-size: .8rem; font-weight: 600;
  font-family: var(--ap-font-head);
  text-decoration: none; border: 1px solid transparent;
  cursor: pointer; white-space: nowrap; transition: filter .15s;
}
.lrc-btn:hover { text-decoration: none; filter: brightness(1.15); }
.lrc-btn-fix { background: rgba(245,158,11,.15); color: #fbbf24; border-color: rgba(245,158,11,.35); }
.lrc-btn-imp { background: rgba(239,68,68,.12);  color: #f87171; border-color: rgba(239,68,68,.35);  }
.lrc-btn-pen { background: rgba(245,158,11,.08); color: #fbbf24; border-color: rgba(245,158,11,.2);  cursor: default; }
.lrc-btn-app { background: rgba(34,197,94,.08);  color: #4ade80; border-color: rgba(34,197,94,.2);   cursor: default; white-space:nowrap; }
.lrc-btn-dt  {
  background: transparent; color: var(--ap-muted);
  border: 1px solid var(--ap-border2); padding: .28rem .65rem;
  font-size: .78rem; border-radius: 8px; cursor: pointer;
}
.lrc-btn-dt:hover { color: var(--ap-text); background: var(--ap-surface); }
.lrc-btn-dt.open  { color: var(--ap-blue); border-color: var(--ap-blue); background: rgba(59,130,246,.1); }
html.ap-light .lrc-btn-fix { color: #92400e; }
html.ap-light .lrc-btn-imp { color: #991b1b; }

/* ── Detail expand ── */
.lrc-det-row > td { padding: 0 !important; border-bottom: 1px solid var(--ap-border2) !important; }
.lrc-det-inner    { padding: .6rem 1.2rem; background: var(--ap-surface); border-top: 2px solid var(--ap-blue); }
.lrc-det-grid     { display: grid; grid-template-columns: repeat(auto-fill,minmax(260px,1fr)); gap: 1rem; }
.lrc-det-dl dt    {
  font-size: .68rem; text-transform: uppercase; letter-spacing: .07em;
  color: var(--ap-muted); margin-bottom: .3rem;
  font-family: var(--ap-font-head);
}
.lrc-det-dl dd    { font-size: .875rem; color: var(--ap-text); margin: 0; line-height: 1.6; }
.lrc-note         {
  padding: .55rem .9rem; border-radius: 0 8px 8px 0;
  font-size: .875rem; line-height: 1.6; color: var(--ap-text);
}
.lrc-note-ok { background: rgba(34,197,94,.07);  border-left: 3px solid var(--ap-green); }
.lrc-note-er { background: rgba(239,68,68,.07);  border-left: 3px solid var(--ap-red);   }
.lrc-note-wa { background: rgba(245,158,11,.07); border-left: 3px solid var(--ap-amber); }

/* ── Implausible banner ── */
.lrc-imp-wrap { background: rgba(239,68,68,.06); border: 1px solid rgba(239,68,68,.25); border-radius: 14px; overflow: hidden; margin-bottom: 1.25rem; }
.lrc-imp-head { padding: .75rem 1.2rem; background: rgba(239,68,68,.12); border-bottom: 1px solid rgba(239,68,68,.2); }
.lrc-imp-head h6 { margin: 0; font-size: .95rem; font-weight: 700; font-family: var(--ap-font-head); color: #f87171; }
.lrc-imp-desc { padding: .45rem 1.2rem .3rem; font-size: .8rem; color: var(--ap-muted); }
.lrc-imp-wrap .lrc-t thead th { background: rgba(239,68,68,.08); color: var(--ap-muted); border-bottom-color: rgba(239,68,68,.2); }
.lrc-imp-wrap .lrc-t tbody td { color: var(--ap-text) !important; }

/* ── Audit status pill ── */
.lrc-ap    { display: inline-flex; align-items: center; gap: .28rem; padding: .28em .7em; border-radius: 20px; font-size: .78rem; font-weight: 700; font-family: var(--ap-font-head); }
.lrc-ap-ok { background: rgba(34,197,94,.12);  color: #4ade80; border: 1px solid rgba(34,197,94,.3);  }
.lrc-ap-wa { background: rgba(245,158,11,.12); color: #fbbf24; border: 1px solid rgba(245,158,11,.3); }
.lrc-ap-er { background: rgba(239,68,68,.12);  color: #f87171; border: 1px solid rgba(239,68,68,.3);  }
html.ap-light .lrc-ap-ok { color: #166534; }
html.ap-light .lrc-ap-wa { color: #92400e; }
html.ap-light .lrc-ap-er { color: #991b1b; }

/* ── Footer / Pagination ── */
.lrc-foot { display: flex; justify-content: space-between; align-items: center; padding: .65rem 1.2rem; border-top: 1px solid var(--ap-border); flex-wrap: wrap; gap: .5rem; }
.lrc-legend { display: flex; flex-wrap: wrap; gap: .65rem; font-size: .77rem; color: var(--ap-muted); align-items: center; font-family: var(--ap-font-mono); }

/* ── Empty state ── */
.lrc-empty { text-align: center; padding: 3rem; color: var(--ap-muted); font-family: var(--ap-font-head); }
.lrc-empty .lrc-empty-ico { font-size: 2.5rem; display: block; margin-bottom: .75rem; }
</style>

<div class="lrc-wrap">

{{-- Hero --}}
<div class="lrc-hero">
  <h4>✈ {{ $t['title'] }}</h4>
  <p>{{ $t['subtitle'] }}</p>
</div>

{{-- Flash --}}
@if(session('success'))<div class="lrc-alert lrc-alert-ok">✓ {{ session('success') }}</div>@endif
@if(session('warning'))<div class="lrc-alert lrc-alert-wa">⚠ {{ session('warning') }}</div>@endif
@if(session('error'))  <div class="lrc-alert lrc-alert-er">✗ {{ session('error') }}</div>@endif

{{-- Tabs --}}
<div class="lrc-tabs">
  <button class="lrc-tab on" id="ltb-flights" onclick="lrcTab('flights',this)">
    ≡ {{ $t['tab_flights'] }}
  </button>
  <button class="lrc-tab" id="ltb-imp" onclick="lrcTab('imp',this)">
    ⚠ {{ $t['tab_imp'] }}
    @if($impCount > 0)<span class="lrc-tc lrc-tc-r">{{ $impCount }}</span>@endif
  </button>
  <button class="lrc-tab" id="ltb-audit" onclick="lrcTab('audit',this)">
    ✦ {{ $t['tab_audit'] }}
    @if($pendCount > 0)<span class="lrc-tc lrc-tc-a">{{ $pendCount }}</span>@endif
  </button>
  <button class="lrc-tab" id="ltb-guide" onclick="lrcTab('guide',this)">
    📖 {{ $t['tab_guide'] }}
  </button>
</div>

{{-- ═══ TAB 1: MY FLIGHTS ═══ --}}
<div class="lrc-panel on" id="lrc-panel-flights">
@if($pireps->isEmpty())
  <div class="lrc-empty"><span class="lrc-empty-ico">📭</span>{{ $t['no_flights'] }}</div>
@else
<div class="lrc-card">
  <div class="lrc-tw">
  <table class="lrc-t">
    <thead><tr>
      <th style="width:95px">{{ $t['date'] }}</th>
      <th style="width:105px">{{ $t['flight'] }}</th>
      <th style="width:130px">{{ $t['route'] }}</th>
      <th style="width:105px">{{ $t['aircraft'] }}</th>
      <th style="width:155px;text-align:right">{{ $t['rate'] }}</th>
      <th style="width:130px;text-align:center">{{ $t['status'] }}</th>
      <th style="width:90px;text-align:center">{{ $t['details'] }}</th>
      <th style="text-align:center">{{ $t['action'] }}</th>
    </tr></thead>
    <tbody>
    @foreach($pireps as $p)
    @php
      $corr  = $corrections[$p->id] ?? null;
      $rate  = $p->landing_rate;
      $isImp = ($rate === null || $rate >= $threshold);
      $rc    = $rate===null ? 'lrc-rn'
             : ($rate >= -150 ? 'lrc-rg'
             : ($rate >= -300 ? 'lrc-ry'
             : ($rate >= -600 ? 'lrc-ro' : 'lrc-rb')));
      $canReq = !$corr || $corr->isRejected();
      $hasDet = $corr && ($corr->reason || $corr->admin_note);
      $did    = 'ldet'.$p->id;
      $rs     = $corr ? 'rs-'.substr($corr->status,0,3) : ($isImp ? 'rs-imp' : '');
    @endphp
    <tr class="{{ $rs }}">
      <td style="color:var(--ap-muted);font-size:.82rem">{{ $p->submitted_at?->format('d.m.Y') ?? '–' }}</td>
      <td style="font-weight:700;font-family:var(--ap-font-head)">{{ $p->airline?->icao ?? '' }}{{ $p->flight_number }}</td>
      <td>
        <strong>{{ $p->dpt_airport_id }}</strong><span style="color:var(--ap-muted);margin:0 .25rem">→</span><strong>{{ $p->arr_airport_id }}</strong>
      </td>
      <td style="color:var(--ap-muted);font-size:.82rem">{{ $p->aircraft?->registration ?? $p->aircraft?->name ?? '–' }}</td>
      <td style="text-align:right">
        <span class="lrc-rt {{ $rc }}">{{ $rate !== null ? $rate.' ft/min' : $t['no_val'] }}</span>
        @if($isImp)<br><span class="lrc-tag lrc-tag-im">⚠ imp.</span>@endif
        @if($corr && $corr->isApproved())
          <br><span style="font-size:.74rem;color:#4ade80">✓ {{ $corr->requested_landing_rate }} ft/min</span>
        @endif
      </td>
      <td style="text-align:center">
        @if(!$corr)<span style="color:var(--ap-muted)">–</span>
        @elseif($corr->isPending()) <span class="lrc-tag lrc-tag-wa">⏳ {{ $t['pending'] }}</span>
        @elseif($corr->isApproved())<span class="lrc-tag lrc-tag-ok">✓ {{ $t['approved'] }}</span>
        @else                        <span class="lrc-tag lrc-tag-er">✗ {{ $t['rejected'] }}</span>
        @endif
      </td>
      <td style="text-align:center">
        @if($hasDet)
          <button class="lrc-btn-dt" id="lbtn{{ $did }}" onclick="lrcDet('{{ $did }}',this)">
            ▼ Info
          </button>
        @else<span style="color:var(--ap-muted)">–</span>@endif
      </td>
      <td style="text-align:center">
        @if($corr && $corr->isPending())
          <span class="lrc-btn lrc-btn-pen">⏳ {{ $t['pending'] }}</span>
        @elseif($corr && $corr->isApproved())
          <span class="lrc-btn lrc-btn-app">✓ {{ $t['approved'] }}</span>
        @elseif($canReq)
          <a href="{{ route('lrc.pilot.create', $p->id) }}"
             class="lrc-btn {{ $isImp ? 'lrc-btn-imp' : 'lrc-btn-fix' }}">
            {{ $corr ? $t['btn_redo'] : $t['btn_fix'] }}
          </a>
        @else<span style="color:var(--ap-muted)">🔒</span>@endif
      </td>
    </tr>
    @if($hasDet)
    <tr class="lrc-det-row" id="{{ $did }}" style="display:none">
      <td colspan="8">
        <div class="lrc-det-inner">
          <div class="lrc-det-grid">
            @if($corr->reason)
            <dl class="lrc-det-dl">
              <dt>{{ $t['your_reason'] }}</dt>
              <dd>{{ $corr->reason }}
                @if($corr->requested_landing_rate)
                  <div style="font-size:.78rem;color:var(--ap-muted);margin-top:.3rem;font-family:var(--ap-font-mono)">
                    {{ $t['requested'] }}: <strong style="color:#4ade80">{{ $corr->requested_landing_rate }} ft/min</strong>
                    {{ $t['instead_of'] }} <strong style="color:#f87171">{{ $corr->original_landing_rate }} ft/min</strong>
                  </div>
                @endif
              </dd>
            </dl>
            @endif
            @if($corr->admin_note)
            <dl class="lrc-det-dl">
              <dt>{{ $t['admin_reply'] }}</dt>
              <dd>
                <div class="lrc-note {{ $corr->isApproved() ? 'lrc-note-ok' : ($corr->isRejected() ? 'lrc-note-er' : 'lrc-note-wa') }}">
                  {{ $corr->admin_note }}
                </div>
                <div style="font-size:.72rem;color:var(--ap-muted);margin-top:.2rem">
                  {{ $corr->processed_at?->format('d.m.Y H:i') }}
                </div>
              </dd>
            </dl>
            @endif
          </div>
        </div>
      </td>
    </tr>
    @endif
    @endforeach
    </tbody>
  </table>
  </div>
  <div class="lrc-foot">
    <div class="lrc-legend">
      <span><span class="lrc-rt lrc-rg">●</span> &ge;-150</span>
      <span><span class="lrc-rt lrc-ry">●</span> -300</span>
      <span><span class="lrc-rt lrc-ro">●</span> -600</span>
      <span><span class="lrc-rt lrc-rb">●</span> &lt;-600</span>
    </div>
    <div>{{ $pireps->links() }}</div>
  </div>
</div>
@endif
</div>

{{-- ═══ TAB 2: IMPLAUSIBLE ═══ --}}
<div class="lrc-panel" id="lrc-panel-imp">
@if($implausiblePireps->isEmpty())
  <div class="lrc-empty" style="color:#4ade80"><span class="lrc-empty-ico">✓</span>{{ $t['no_imp'] }}</div>
@else
<div class="lrc-imp-wrap">
  <div class="lrc-imp-head">
    <h6>⚠ {{ $impCount }} × {{ $t['imp_title'] }}</h6>
  </div>
  <div class="lrc-imp-desc">{{ $t['imp_desc'] }}</div>
  <div class="lrc-tw">
  <table class="lrc-t">
    <thead><tr>
      <th>{{ $t['date'] }}</th>
      <th>{{ $t['flight'] }}</th>
      <th>{{ $t['route'] }}</th>
      <th>{{ $t['aircraft'] }}</th>
      <th style="text-align:right">{{ $t['rate'] }}</th>
      <th style="text-align:center">{{ $t['action'] }}</th>
    </tr></thead>
    <tbody>
    @foreach($implausiblePireps as $p)
    <tr>
      <td style="color:var(--ap-muted);font-size:.82rem">{{ $p->submitted_at?->format('d.m.Y') ?? '–' }}</td>
      <td style="font-weight:700;font-family:var(--ap-font-head)">{{ $p->airline?->icao ?? '' }}{{ $p->flight_number }}</td>
      <td>
        <strong>{{ $p->dpt_airport_id }}</strong><span style="color:var(--ap-muted);margin:0 .25rem">→</span><strong>{{ $p->arr_airport_id }}</strong>
      </td>
      <td style="color:var(--ap-muted);font-size:.82rem">{{ $p->aircraft?->registration ?? $p->aircraft?->name ?? '–' }}</td>
      <td style="text-align:right">
        <span class="lrc-rt lrc-rb">{{ $p->landing_rate !== null ? $p->landing_rate.' ft/min' : $t['no_val'] }}</span>
      </td>
      <td style="text-align:center">
        <a href="{{ route('lrc.pilot.create', $p->id) }}" class="lrc-btn lrc-btn-imp">
          {{ $t['btn_fix'] }}
        </a>
      </td>
    </tr>
    @endforeach
    </tbody>
  </table>
  </div>
</div>
@endif
</div>

{{-- ═══ TAB 3: MY REQUESTS ═══ --}}
<div class="lrc-panel" id="lrc-panel-audit">
@if($auditLog->isEmpty())
  <div class="lrc-empty"><span class="lrc-empty-ico">✦</span>{{ $t['no_req'] }}</div>
@else
<div class="lrc-card">
  <div class="lrc-tw">
  <table class="lrc-t" style="table-layout:auto">
    <thead><tr>
      <th style="width:110px">{{ $t['submitted'] }}</th>
      <th style="width:100px">{{ $t['flight'] }}</th>
      <th style="width:120px">{{ $t['route'] }}</th>
      <th style="width:130px;text-align:right">{{ $t['original'] }}</th>
      <th style="width:130px;text-align:right">{{ $t['requested'] }}</th>
      <th style="width:125px;text-align:center">{{ $t['status'] }}</th>
      <th>{{ $t['decision'] }}</th>
    </tr></thead>
    <tbody>
    @foreach($auditLog as $log)
    @php $lp = $log->pirep; @endphp
    <tr class="rs-{{ substr($log->status,0,3) }}">
      <td style="color:var(--ap-muted);font-size:.82rem;white-space:nowrap;line-height:1.3">
        {{ $log->created_at->format('d.m.Y') }}<br>
        <span style="font-size:.72rem;opacity:.75">{{ $log->created_at->format('H:i') }}</span>
      </td>
      <td style="font-weight:700;font-family:var(--ap-font-head)">
        {{ $lp ? (($lp->airline?->icao ?? '').$lp->flight_number) : '–' }}
      </td>
      <td style="font-size:.84rem">
        @if($lp){{ $lp->dpt_airport_id }} → {{ $lp->arr_airport_id }}@else–@endif
      </td>
      <td style="text-align:right"><span class="lrc-rt lrc-rb">{{ $log->original_landing_rate }} ft/min</span></td>
      <td style="text-align:right"><span class="lrc-rt lrc-rg">{{ $log->requested_landing_rate }} ft/min</span></td>
      <td style="text-align:center">
        @if($log->isPending()) <span class="lrc-ap lrc-ap-wa">⏳ {{ $t['pending'] }}</span>
        @elseif($log->isApproved())<span class="lrc-ap lrc-ap-ok">✓ {{ $t['approved'] }}</span>
        @else                      <span class="lrc-ap lrc-ap-er">✗ {{ $t['rejected'] }}</span>
        @endif
      </td>
      <td>
        @if($log->admin_note)
          <div class="lrc-note {{ $log->isApproved() ? 'lrc-note-ok' : ($log->isRejected() ? 'lrc-note-er' : 'lrc-note-wa') }}"
               style="font-size:.8rem;padding:.25rem .6rem">{{ $log->admin_note }}</div>
          <div style="font-size:.7rem;color:var(--ap-muted);margin-top:.15rem">
            {{ $log->processed_at?->format('d.m.Y H:i') }}
          </div>
        @elseif($log->isPending())
          <span style="color:var(--ap-muted);font-style:italic;font-size:.82rem">{{ $t['awaiting'] }}</span>
        @else<span style="color:var(--ap-muted)">–</span>@endif
      </td>
    </tr>
    @endforeach
    </tbody>
  </table>
  </div>
</div>
@endif
</div>

</div>{{-- /.lrc-wrap --}}

<script>
function lrcTab(name, btn) {
    document.querySelectorAll('.lrc-panel').forEach(function(p){ p.classList.remove('on'); });
    document.querySelectorAll('.lrc-tab').forEach(function(b){ b.classList.remove('on'); });
    var p = document.getElementById('lrc-panel-' + name);
    if (p) p.classList.add('on');
    btn.classList.add('on');
}
function lrcDet(id, btn) {
    var row = document.getElementById(id);
    if (!row) return;
    var open = row.style.display !== 'table-row';
    row.style.display = open ? 'table-row' : 'none';
    if (btn) {
        btn.classList.toggle('open', open);
        btn.innerHTML = open ? '▲ Info' : '▼ Info';
    }
}
(function() {
    var h = window.location.hash, el;
    if (h === '#imp'   && (el = document.getElementById('ltb-imp')))   el.click();
    if (h === '#audit' && (el = document.getElementById('ltb-audit'))) el.click();
    if (h === '#guide' && (el = document.getElementById('ltb-guide'))) el.click();
})();
</script>


{{-- ═══════════════════════════════════════════════════════════════════
     LRC GUIDE  –  Frontend only
     - Pilots see: What is LRC, Landing rates, How to submit, Status, FAQ
     - Admins additionally see: Admin tabs, Review workflow, Direct Fix,
       Notifications, Frontend navigation links
     Language: auto-detect via $lang variable (set in pilot/index)
     ═══════════════════════════════════════════════════════════════════ --}}

<div class="lrc-panel" id="lrc-panel-guide">

<style>
/* ── Guide wrapper ── */
.gd{max-width:920px;padding:0;margin:0 auto}
/* ── Hero ── */
.gd-hero{padding:0 0 1rem}
.gd-hero h2{font-family:var(--ap-font-head);font-weight:800;font-size:1.3rem;margin:0 0 .2rem;color:var(--ap-text-head)}
.gd-hero p{color:var(--ap-muted);font-size:.87rem;margin:0}
/* ── Card grid ── */
.gd-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(155px,1fr));gap:.55rem;margin-bottom:1.8rem}
.gd-c{background:var(--ap-input-bg);border:1px solid var(--ap-border);border-radius:9px;padding:.6rem .85rem;
       text-decoration:none;display:flex;align-items:center;gap:.55rem;transition:.15s;cursor:pointer}
.gd-c:hover{border-color:var(--ap-blue);background:rgba(59,130,246,.07);text-decoration:none}
.gd-c.adm{border-color:rgba(245,158,11,.35);background:rgba(245,158,11,.04)}
.gd-c.adm:hover{border-color:rgba(245,158,11,.8);background:rgba(245,158,11,.1)}
.gd-ci{font-size:1.05rem;flex-shrink:0}
.gd-ct .t{font-size:.8rem;font-weight:700;color:var(--ap-text-head);line-height:1.3;font-family:var(--ap-font-head)}
.gd-ct .s{font-size:.69rem;color:var(--ap-muted);line-height:1.3}
.gd-abadge{font-size:.58rem;background:#f59e0b;color:#000;border-radius:3px;padding:.1em .35em;font-weight:700;vertical-align:middle;margin-left:.2rem}
/* ── Body ── */
.gd-body{padding:0;max-width:920px;margin:0 auto}
/* ── Section ── */
.gd-sec{margin-bottom:1.8rem;scroll-margin-top:80px}
.gd-sec>h3{font-family:var(--ap-font-head);font-weight:800;font-size:1rem;margin:0 0 .6rem;
            display:flex;align-items:center;gap:.45rem;color:var(--ap-text-head);padding:0}
.gd-sec-body{background:var(--ap-input-bg);border:1px solid var(--ap-border);border-radius:12px;padding:1.2rem 1.4rem}
.gd-sec-body p,.gd-sec-body li{font-size:.875rem;line-height:1.78;color:var(--ap-text);margin-top:0}
.gd-sec-body p+p{margin-top:.6rem}
/* ── Steps ── */
.gd-steps{list-style:none;padding:0;margin:.4rem 0}
.gd-steps li{display:flex;gap:.75rem;align-items:flex-start;margin-bottom:.5rem;font-size:.875rem;color:var(--ap-text)}
.gd-n{width:24px;height:24px;border-radius:50%;background:var(--ap-blue);color:#fff;font-size:.72rem;
      font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.1rem;font-family:var(--ap-font-head)}
/* ── Callouts ── */
.gd-note,.gd-tip,.gd-warn{border-radius:0 6px 6px 0;padding:.55rem .85rem;font-size:.84rem;margin:.75rem 0}
.gd-note{background:rgba(59,130,246,.08);border-left:3px solid var(--ap-blue)}.gd-note strong{color:var(--ap-blue)}
.gd-tip {background:rgba(34,197,94,.08); border-left:3px solid #22c55e}.gd-tip  strong{color:#22c55e}
.gd-warn{background:rgba(245,158,11,.08);border-left:3px solid #f59e0b}.gd-warn strong{color:#f59e0b}
/* ── Rate boxes ── */
.gd-rategrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(148px,1fr));gap:.5rem;margin:.7rem 0}
.gd-rb{border-radius:9px;padding:.6rem .85rem}
/* ── Status items ── */
.gd-statuslist{display:flex;flex-direction:column;gap:.45rem;margin:.5rem 0}
.gd-si{display:flex;gap:.75rem;align-items:flex-start;background:rgba(255,255,255,.03);border:1px solid var(--ap-border);border-radius:9px;padding:.6rem .9rem}
.gd-si-ico{font-size:1rem;flex-shrink:0;margin-top:.05rem}
.gd-si-t{font-size:.85rem;font-weight:700;color:var(--ap-text-head);font-family:var(--ap-font-head)}
.gd-si-d{font-size:.8rem;color:var(--ap-muted);margin-top:.1rem}
/* ── Tables ── */
.gd-tbl{width:100%;border-collapse:collapse;font-size:.855rem;margin:.5rem 0}
.gd-tbl th{background:rgba(255,255,255,.05);padding:.45rem .7rem;text-align:left;border:1px solid var(--ap-border);font-weight:700;color:var(--ap-text-head);font-size:.78rem;text-transform:uppercase;letter-spacing:.04em}
.gd-tbl td{padding:.45rem .7rem;border:1px solid var(--ap-border);color:var(--ap-text);vertical-align:top}
/* ── Code inline ── */
.gd-code{font-family:var(--ap-font-mono);font-size:.82rem;background:rgba(0,0,0,.2);
         padding:.1rem .35rem;border-radius:4px;border:1px solid var(--ap-border);color:#f87171}
/* ── Code block ── */
.gd-codeblock{font-family:var(--ap-font-mono);font-size:.82rem;background:rgba(0,0,0,.3);
              border:1px solid var(--ap-border);border-radius:8px;padding:.8rem 1rem;
              margin:.7rem 0;overflow-x:auto;color:#a5f3fc;white-space:pre;line-height:1.6}
/* ── Admin block ── */
.gd-admin-section{background:rgba(245,158,11,.04);border:1px solid rgba(245,158,11,.3);
                  border-radius:12px;padding:1.2rem 1.4rem;margin:1.5rem 0}
.gd-admin-section .gd-admin-header{font-size:.75rem;font-weight:700;text-transform:uppercase;
    letter-spacing:.07em;color:#f59e0b;margin-bottom:.9rem;display:flex;align-items:center;gap:.4rem}
/* ── Divider ── */
.gd-hr{border:none;border-top:1px solid var(--ap-border);margin:1.5rem 0}
/* ── FAQ ── */
.gd-faq-q{font-weight:700;font-size:.875rem;color:var(--ap-text-head);margin-bottom:.15rem}
.gd-faq-a{font-size:.875rem;color:var(--ap-text);margin-bottom:1rem;line-height:1.75}
</style>

@if($lang==='en')
{{-- ═══════════════════════ ENGLISH ═══════════════════════ --}}

<div class="gd" style="padding:1.5rem 0">
<div class="gd-hero">
  <h2>📋 Landing Rate Corrections – Guide</h2>
  <p>Everything you need to know about this module · Pilot section visible to all · Admin section visible to admins only</p>
</div>

{{-- CARD GRID --}}
<div class="gd-grid">
  <a href="#gen-what"   class="gd-c"><span class="gd-ci">❓</span><div class="gd-ct"><div class="t">What is this?</div><div class="s">Overview</div></div></a>
  <a href="#gen-rates"  class="gd-c"><span class="gd-ci">📊</span><div class="gd-ct"><div class="t">Landing Rates</div><div class="s">Reference table</div></div></a>
  <a href="#gen-submit" class="gd-c"><span class="gd-ci">✏️</span><div class="gd-ct"><div class="t">Submit Request</div><div class="s">Step by step</div></div></a>
  <a href="#gen-status" class="gd-c"><span class="gd-ci">🔄</span><div class="gd-ct"><div class="t">Request Status</div><div class="s">What each state means</div></div></a>
  <a href="#gen-faq"    class="gd-c"><span class="gd-ci">💬</span><div class="gd-ct"><div class="t">FAQ</div><div class="s">Common questions</div></div></a>
  @if(auth()->user()->hasRole('admin'))
  <a href="#gen-admintabs"   class="gd-c adm"><span class="gd-ci">🧭</span><div class="gd-ct"><div class="t">Admin Tabs <span class="gd-abadge">ADMIN</span></div><div class="s">Navigation explained</div></div></a>
  <a href="#gen-review"      class="gd-c adm"><span class="gd-ci">🔍</span><div class="gd-ct"><div class="t">Review <span class="gd-abadge">ADMIN</span></div><div class="s">How to decide</div></div></a>
  <a href="#gen-direct"      class="gd-c adm"><span class="gd-ci">⚡</span><div class="gd-ct"><div class="t">Direct Fix <span class="gd-abadge">ADMIN</span></div><div class="s">Without pilot request</div></div></a>
  <a href="#gen-notify"      class="gd-c adm"><span class="gd-ci">✉️</span><div class="gd-ct"><div class="t">Notifications <span class="gd-abadge">ADMIN</span></div><div class="s">Email setup</div></div></a>
  <a href="#gen-navlinks"    class="gd-c adm"><span class="gd-ci">🔗</span><div class="gd-ct"><div class="t">Nav Links <span class="gd-abadge">ADMIN</span></div><div class="s">Frontend integration</div></div></a>
  @endif
</div>

<div class="gd-body">

{{-- SECTION: WHAT --}}
<div class="gd-sec" id="gen-what">
  <h3>❓ What is a Landing Rate Correction?</h3>
<div class="gd-sec-body">
  <p>ACARS records your landing rate automatically when you touch down. Sometimes this fails – for example when your simulator crashes at the exact moment of touchdown, your internet connection drops, or ACARS has a software glitch. The result is a clearly wrong value like <strong>0 ft/min</strong> or an extreme number that couldn't be real.</p>
  <p>This module lets you formally request a correction. You explain what happened, optionally attach a screenshot as proof, and an admin decides. If approved, your PIREP is updated with the correct value automatically.</p>
  <div class="gd-note"><strong>Note:</strong> Only accepted PIREPs can be corrected. Draft or rejected PIREPs are not eligible.</div>
</div>
</div>

<hr class="gd-hr">

{{-- SECTION: RATES --}}
<div class="gd-sec" id="gen-rates">
  <h3>📊 Landing Rate Reference</h3>
<div class="gd-sec-body">
  <p>Use this when filling in your correction request. Enter the value that best matches what you remember from your flight:</p>
  <div class="gd-rategrid">
    <div class="gd-rb" style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3)">
      <div style="font-weight:700;color:#4ade80;font-family:var(--ap-font-head);font-size:.88rem">✈ Smooth</div>
</div>
      <div style="font-family:var(--ap-font-mono);font-size:.8rem;margin:.2rem 0;color:var(--ap-text)">-50 to -250 ft/min</div>
      <div style="font-size:.75rem;color:var(--ap-muted)">Excellent landing</div>
    </div>
    <div class="gd-rb" style="background:rgba(234,179,8,.1);border:1px solid rgba(234,179,8,.3)">
      <div style="font-weight:700;color:#fbbf24;font-family:var(--ap-font-head);font-size:.88rem">✈ Normal</div>
      <div style="font-family:var(--ap-font-mono);font-size:.8rem;margin:.2rem 0;color:var(--ap-text)">-250 to -600 ft/min</div>
      <div style="font-size:.75rem;color:var(--ap-muted)">Acceptable</div>
    </div>
    <div class="gd-rb" style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3)">
      <div style="font-weight:700;color:#f87171;font-family:var(--ap-font-head);font-size:.88rem">✈ Hard</div>
      <div style="font-family:var(--ap-font-mono);font-size:.8rem;margin:.2rem 0;color:var(--ap-text)">below -600 ft/min</div>
      <div style="font-size:.75rem;color:var(--ap-muted)">Hard landing</div>
    </div>
    <div class="gd-rb" style="background:rgba(139,92,246,.1);border:1px solid rgba(139,92,246,.3)">
      <div style="font-weight:700;color:#a78bfa;font-family:var(--ap-font-head);font-size:.88rem">⚠ Implausible</div>
      <div style="font-family:var(--ap-font-mono);font-size:.8rem;margin:.2rem 0;color:var(--ap-text)">0 or above -20 ft/min</div>
      <div style="font-size:.75rem;color:var(--ap-muted)">Recording error likely</div>
    </div>
  </div>
  <div class="gd-warn">
  <strong>⚠ Only submit if you have real proof.</strong><br><br>
  This module is strictly for fixing technical recording errors – not for improving your landing stats. Before submitting, you need actual evidence from an external tool (flight tracker, replay tool, or a screenshot of your instruments at the moment of landing).<br><br>
  <strong>Do not guess.</strong> "I think my landing was better" is not a valid reason. If you cannot provide proof, do not submit a request. Admins verify every claim and can access external data.<br><br>
  Requests without sufficient evidence will be <strong>rejected and recorded in the audit log</strong>. Repeated misuse will be escalated to VA staff.
  </div>
</div>

<hr class="gd-hr">

{{-- SECTION: SUBMIT --}}
<div class="gd-sec" id="gen-submit">
  <h3>✏️ How to Submit a Correction Request</h3>
<div class="gd-sec-body">
  <ol class="gd-steps">
    <li><span class="gd-n">1</span><span>Click the <strong>Implausible</strong> tab – it shows all your PIREPs with suspicious landing rates (0 or above -20 ft/min).</span></li>
    <li><span class="gd-n">2</span><span>Click <strong>Fix →</strong> next to the flight you want to correct.</span></li>
    <li><span class="gd-n">3</span><span>Enter the <strong>correct landing rate</strong> – must be a negative number, e.g. <span class="gd-code">-180</span>. Use the table above as a reference.</span></li>
    <li><span class="gd-n">4</span><span>Write a clear <strong>reason</strong> (min. 10 characters). Example: <em>"ACARS lost connection 2 sec before touchdown. Actual landing was approx. -180 ft/min."</em></span></li>
    <li><span class="gd-n">5</span><span>Optional: attach a <strong>screenshot</strong> from your simulator or ACARS log as evidence (JPG, PNG or PDF, max 5 MB).</span></li>
    <li><span class="gd-n">6</span><span>Check <strong>Notify me by email</strong> if you want to know when the admin decides.</span></li>
    <li><span class="gd-n">7</span><span>Click <strong>Submit Request</strong>. The request is now pending admin review.</span></li>
  </ol>
  <div class="gd-note"><strong>Note:</strong> Your original PIREP stays unchanged until an admin approves the request.</div>
</div>
</div>

<hr class="gd-hr">

{{-- SECTION: STATUS --}}
<div class="gd-sec" id="gen-status">
  <h3>🔄 Request Status – What Does Each State Mean?</h3>
<div class="gd-sec-body">
  <p>Check the <strong>My Requests</strong> tab at any time to see the current state of your requests:</p>
  <div class="gd-statuslist">
    <div class="gd-si">
      <span class="gd-si-ico">⏳</span>
      <div><div class="gd-si-t">Pending</div>
</div><div class="gd-si-d">Your request has been submitted and is waiting for an admin to review it. No action needed from you.</div></div>
    </div>
    <div class="gd-si">
      <span class="gd-si-ico" style="color:#4ade80">✓</span>
      <div><div class="gd-si-t">Approved</div><div class="gd-si-d">The admin approved your request. Your PIREP's landing rate has been updated to the value you requested.</div></div>
    </div>
    <div class="gd-si">
      <span class="gd-si-ico" style="color:#f87171">✗</span>
      <div><div class="gd-si-t">Rejected</div><div class="gd-si-d">The admin rejected the request. The reason is shown in the Admin Decision column. You can submit a new request with better evidence.</div></div>
    </div>
  </div>
</div>

<hr class="gd-hr">

{{-- SECTION: FAQ --}}
<div class="gd-sec" id="gen-faq">
  <h3>💬 Frequently Asked Questions</h3>
<div class="gd-sec-body">
  <p class="gd-faq-q">My flight isn't in the Implausible tab – can I still correct it?</p>
  <p class="gd-faq-a">The Implausible tab only shows values of 0 or above -20 ft/min. If your flight doesn't appear there but the rate still seems wrong, go to <strong>My Flights</strong>, find the flight, and use the <strong>Fix →</strong> button there.</p>
  <p class="gd-faq-q">How long does a review take?</p>
  <p class="gd-faq-a">It depends on admin availability. Enable email notification when submitting so you're informed as soon as a decision is made.</p>

  <p class="gd-faq-q">My request was approved but my PIREP still shows the old value.</p>
  <p class="gd-faq-a" style="margin-bottom:0">Try clearing your browser cache and reloading. If the issue persists, contact an admin.</p>
</div>
</div>

{{-- ═══════════════ ADMIN ONLY SECTIONS ═══════════════ --}}
@if(auth()->user()->hasRole('admin'))

<hr class="gd-hr">
<div class="gd-admin-section">
  <div class="gd-admin-header">🔒 Admin Section – only visible to admins</div>

  {{-- Admin Tabs --}}
  <div class="gd-sec" id="gen-admintabs" style="margin-bottom:1.5rem">
    <h3>🧭 Admin Panel – All Tabs Explained</h3>
<div class="gd-sec-body">
    <p>Go to <a href="{{ url('/admin/lrc') }}" style="color:var(--ap-blue)">{{ url('/admin/lrc') }}</a> to access the admin panel.</p>
  <div class="gd-tip"><strong>Tip:</strong> The admin panel is also accessible via the left sidebar in the phpVMS admin area: <strong>Addons → LR Corrections</strong>.</div>
    <table class="gd-tbl">
      <thead><tr><th>Tab</th><th>What you see</th><th>When to use</th></tr></thead>
      <tbody>
        <tr><td>⏳ <strong>Pending</strong></td><td>Open requests awaiting a decision</td><td>Your main work queue – check this daily</td></tr>
        <tr><td>✓ <strong>Approved</strong></td><td>All approved corrections</td><td>Verify corrections were applied correctly</td></tr>
        <tr><td>✗ <strong>Rejected</strong></td><td>Rejected requests with admin notes</td><td>Look up past rejection reasons</td></tr>
        <tr><td>≡ <strong>All Requests</strong></td><td>Complete history all pilots</td><td>Full audit search</td></tr>
        <tr><td>✦ <strong>Audit Log</strong></td><td>Chronological log – who decided what and when</td><td>Accountability trail</td></tr>
        <tr><td>✉ <strong>Recipients</strong></td><td>Select which admins receive email alerts</td><td>Initial setup / when team changes</td></tr>
        <tr><td>⚠ <strong>Implausible PIREPs</strong></td><td>All site-wide PIREPs with suspicious rates</td><td>Proactive cleanup + Direct Fix</td></tr>
      </tbody>
    </table>
  </div>
</div>

  {{-- Review --}}
  <div class="gd-sec" id="gen-review" style="margin-bottom:1.5rem">
    <h3>🔍 How to Review a Pilot Request</h3>
<div class="gd-sec-body">
    <ol class="gd-steps">
      <li><span class="gd-n">1</span><span>Go to <strong>⏳ Pending</strong> and click <strong>Review →</strong> on a request.</span></li>
      <li><span class="gd-n">2</span><span>Check the flight details: original rate, requested rate, submission time.</span></li>
      <li><span class="gd-n">3</span><span>Read the pilot's reason and view attached evidence if any.</span></li>
      <li><span class="gd-n">4</span><span>Enter an <strong>Admin Note</strong> – optional for approval, <strong>required</strong> for rejection.</span></li>
      <li><span class="gd-n">5</span><span>Click <strong>Approve</strong> or <strong>Reject</strong>. The PIREP updates immediately on approval.</span></li>
    </ol>
    <div class="gd-warn"><strong>Note:</strong> Decisions cannot be undone via the module. Contact a developer if a reversal is needed.</div>
</div>
    <div class="gd-tip"><strong>Tip:</strong> Always write a clear rejection reason – pilots need to know what evidence was missing.</div>
  </div>

  {{-- Direct Fix --}}
  <div class="gd-sec" id="gen-direct" style="margin-bottom:1.5rem">
    <h3>⚡ Direct Fix – Correct Without a Pilot Request</h3>
<div class="gd-sec-body">
    <p>In <strong>⚠ Implausible PIREPs</strong> each row has a <strong>Direct Fix</strong> form. This lets you correct a landing rate immediately, bypassing the request workflow.</p>
    <div class="gd-warn"><strong>Use with caution:</strong> No audit entry is created on the pilot's side. Only use when you're certain the value is wrong (e.g. clear ACARS 0 ft/min).</div>
</div>
  </div>

  {{-- Notifications --}}
  <div class="gd-sec" id="gen-notify" style="margin-bottom:1.5rem">
    <h3>✉️ Email Notifications Setup</h3>
<div class="gd-sec-body">
    <p>Go to <strong>✉ Recipients</strong> in the admin panel and check the box next to each admin who should receive an email when a pilot submits a new request.</p>
    <div class="gd-warn"><strong>Important:</strong> If no recipients are configured, no emails are sent at all. Always have at least one admin selected.</div>
</div>
    <div class="gd-tip"><strong>Tip:</strong> Only users with the <em>admin</em> role appear in this list. If someone is missing, check their role under Config → Roles.</div>
  </div>

  {{-- Frontend Navigation Links --}}
  <div class="gd-sec" id="gen-navlinks" style="margin-bottom:0">
    <h3>🔗 Frontend Navigation – How to Link This Module</h3>
<div class="gd-sec-body">
    <p>To make this module accessible for pilots, add a link in your theme's navigation. Here's how:</p>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 1 – Simple link in your nav blade template:</p>
    <div class="gd-codeblock">&lt;a href="@{{ url('/lrc') }}"&gt;Landing Rate Corrections&lt;/a&gt;</div>
</div>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 2 – Using the named route:</p>
    <div class="gd-codeblock">&lt;a href="@{{ route('lrc.pilot.index') }}"&gt;Landing Rate Corrections&lt;/a&gt;</div>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 3 – Link directly to a specific tab:</p>
    <div class="gd-codeblock">&lt;!-- Goes to the Implausible tab --&gt;
&lt;a href="@{{ url('/lrc#imp') }}"&gt;Implausible Landings&lt;/a&gt;

&lt;!-- Goes to My Requests tab --&gt;
&lt;a href="@{{ url('/lrc#audit') }}"&gt;My Requests&lt;/a&gt;

&lt;!-- Goes to the Guide tab --&gt;
&lt;a href="@{{ url('/lrc#guide') }}"&gt;LRC Guide&lt;/a&gt;</div>

    <div class="gd-note"><strong>Where to add the link:</strong> In phpVMS 7 with a custom theme, find your theme's navigation file – typically <span class="gd-code">resources/views/layouts/nav.blade.php</span> or similar. Add the link inside the authenticated user block (<span class="gd-code">@auth ... @endauth</span>) so guests don't see it.</div>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem;margin-top:1rem">All module URLs at a glance:</p>
    <table class="gd-tbl">
      <thead><tr><th>Who</th><th>URL</th><th>Description</th></tr></thead>
      <tbody>
        <tr><td>Pilot</td><td><span class="gd-code">{{ url('/lrc') }}</span></td><td>Pilot dashboard</td></tr>
        <tr><td>Admin</td><td><span class="gd-code">{{ url('/admin/lrc') }}</span></td><td>Admin panel – all request management</td></tr>
        <tr><td>Admin</td><td><span class="gd-code">{{ url('/admin/lrc/implausible') }}</span></td><td>All implausible PIREPs + Direct Fix</td></tr>
      </tbody>
    </table>
  </div>

</div>{{-- gd-admin-section --}}
@endif {{-- isAdmin --}}

</div>{{-- gd-body --}}
</div>{{-- gd --}}

@else
{{-- ═══════════════════════ DEUTSCH ═══════════════════════ --}}

<div class="gd" style="padding:1.5rem 0">
<div class="gd-hero">
  <h2>📋 Landeratenkorrekturen – Handbuch</h2>
  <p>Alles was du wissen musst · Pilotenbereich für alle · Admin-Bereich nur für Admins sichtbar</p>
</div>

<div class="gd-grid">
  <a href="#gde-what"   class="gd-c"><span class="gd-ci">❓</span><div class="gd-ct"><div class="t">Was ist das?</div><div class="s">Überblick</div></div></a>
  <a href="#gde-rates"  class="gd-c"><span class="gd-ci">📊</span><div class="gd-ct"><div class="t">Landeraten</div><div class="s">Referenztabelle</div></div></a>
  <a href="#gde-submit" class="gd-c"><span class="gd-ci">✏️</span><div class="gd-ct"><div class="t">Antrag stellen</div><div class="s">Schritt für Schritt</div></div></a>
  <a href="#gde-status" class="gd-c"><span class="gd-ci">🔄</span><div class="gd-ct"><div class="t">Antragsstatus</div><div class="s">Was jeder Status bedeutet</div></div></a>
  <a href="#gde-faq"    class="gd-c"><span class="gd-ci">💬</span><div class="gd-ct"><div class="t">FAQ</div><div class="s">Häufige Fragen</div></div></a>
  @if(auth()->user()->hasRole('admin'))
  <a href="#gde-admintabs"  class="gd-c adm"><span class="gd-ci">🧭</span><div class="gd-ct"><div class="t">Admin-Tabs <span class="gd-abadge">ADMIN</span></div><div class="s">Navigation erklärt</div></div></a>
  <a href="#gde-review"     class="gd-c adm"><span class="gd-ci">🔍</span><div class="gd-ct"><div class="t">Prüfen <span class="gd-abadge">ADMIN</span></div><div class="s">Wie entscheiden</div></div></a>
  <a href="#gde-direct"     class="gd-c adm"><span class="gd-ci">⚡</span><div class="gd-ct"><div class="t">Direktkorrektur <span class="gd-abadge">ADMIN</span></div><div class="s">Ohne Pilotenantrag</div></div></a>
  <a href="#gde-notify"     class="gd-c adm"><span class="gd-ci">✉️</span><div class="gd-ct"><div class="t">Benachrichtigungen <span class="gd-abadge">ADMIN</span></div><div class="s">E-Mail-Setup</div></div></a>
  <a href="#gde-navlinks"   class="gd-c adm"><span class="gd-ci">🔗</span><div class="gd-ct"><div class="t">Nav-Links <span class="gd-abadge">ADMIN</span></div><div class="s">Frontend einbinden</div></div></a>
  @endif
</div>

<div class="gd-body">

<div class="gd-sec" id="gde-what">
  <h3>❓ Was ist eine Landeratenkorrektur?</h3>
<div class="gd-sec-body">
  <p>ACARS zeichnet deine Landerate automatisch beim Aufsetzen auf. Manchmal schlägt das fehl – wenn der Simulator genau beim Touchdown abstürzt, die Internetverbindung abbricht oder ACARS einen Software-Fehler hat. Das Ergebnis ist ein offensichtlich falscher Wert wie <strong>0 ft/min</strong>.</p>
  <p>Dieses Modul gibt dir die Möglichkeit, eine Korrektur formal zu beantragen. Du erklärst was passiert ist, kannst optional einen Screenshot anhängen, und ein Admin entscheidet. Bei Genehmigung wird dein PIREP automatisch aktualisiert.</p>
  <div class="gd-note"><strong>Hinweis:</strong> Nur akzeptierte PIREPs können korrigiert werden. Entwürfe oder abgelehnte PIREPs sind nicht berechtigt.</div>
</div>
</div>

<hr class="gd-hr">

<div class="gd-sec" id="gde-rates">
  <h3>📊 Landeraten-Referenz</h3>
<div class="gd-sec-body">
  <p>Nutze diese Übersicht wenn du deinen Korrekturwert eingibst:</p>
  <div class="gd-rategrid">
    <div class="gd-rb" style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3)">
      <div style="font-weight:700;color:#4ade80;font-family:var(--ap-font-head);font-size:.88rem">✈ Sanft</div>
</div>
      <div style="font-family:var(--ap-font-mono);font-size:.8rem;margin:.2rem 0;color:var(--ap-text)">-50 bis -250 ft/min</div>
      <div style="font-size:.75rem;color:var(--ap-muted)">Ausgezeichnete Landung</div>
    </div>
    <div class="gd-rb" style="background:rgba(234,179,8,.1);border:1px solid rgba(234,179,8,.3)">
      <div style="font-weight:700;color:#fbbf24;font-family:var(--ap-font-head);font-size:.88rem">✈ Normal</div>
      <div style="font-family:var(--ap-font-mono);font-size:.8rem;margin:.2rem 0;color:var(--ap-text)">-250 bis -600 ft/min</div>
      <div style="font-size:.75rem;color:var(--ap-muted)">Akzeptabel</div>
    </div>
    <div class="gd-rb" style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3)">
      <div style="font-weight:700;color:#f87171;font-family:var(--ap-font-head);font-size:.88rem">✈ Hart</div>
      <div style="font-family:var(--ap-font-mono);font-size:.8rem;margin:.2rem 0;color:var(--ap-text)">unter -600 ft/min</div>
      <div style="font-size:.75rem;color:var(--ap-muted)">Harte Landung</div>
    </div>
    <div class="gd-rb" style="background:rgba(139,92,246,.1);border:1px solid rgba(139,92,246,.3)">
      <div style="font-weight:700;color:#a78bfa;font-family:var(--ap-font-head);font-size:.88rem">⚠ Unplausibel</div>
      <div style="font-family:var(--ap-font-mono);font-size:.8rem;margin:.2rem 0;color:var(--ap-text)">0 oder über -20 ft/min</div>
      <div style="font-size:.75rem;color:var(--ap-muted)">Wahrscheinlich Fehler</div>
    </div>
  </div>
  <div class="gd-warn">
  <strong>⚠ Nur einreichen wenn du echten Nachweis hast.</strong><br><br>
  Dieses Modul dient ausschließlich zur Korrektur technischer Aufzeichnungsfehler – nicht um deine Landestatistik zu verbessern. Vor dem Einreichen brauchst du einen tatsächlichen Nachweis aus einem externen Tool (Flight-Tracker, Replay-Tool oder ein Screenshot deiner Instrumente im Moment der Landung).<br><br>
  <strong>Nicht raten.</strong> „Ich glaube meine Landung war besser" ist kein gültiger Grund. Wenn du keinen Nachweis vorweisen kannst, stell keinen Antrag. Admins prüfen jeden Antrag und können externe Daten einsehen.<br><br>
  Anträge ohne ausreichenden Nachweis werden <strong>abgelehnt und im Audit-Log erfasst</strong>. Wiederholter Missbrauch wird an das VA-Personal weitergeleitet.
  </div>
</div>

<hr class="gd-hr">

<div class="gd-sec" id="gde-submit">
  <h3>✏️ Wie stelle ich einen Korrekturantrag?</h3>
<div class="gd-sec-body">
  <ol class="gd-steps">
    <li><span class="gd-n">1</span><span>Klicke auf den Tab <strong>Unplausibel</strong> – dort siehst du alle deine PIREPs mit verdächtigen Landeraten.</span></li>
    <li><span class="gd-n">2</span><span>Klicke auf <strong>Korrigieren →</strong> beim betreffenden Flug.</span></li>
    <li><span class="gd-n">3</span><span>Gib die <strong>korrekte Landerate</strong> ein – muss negativ sein, z.B. <span class="gd-code">-180</span>. Nutze die Tabelle oben als Orientierung.</span></li>
    <li><span class="gd-n">4</span><span>Schreibe eine klare <strong>Begründung</strong> (min. 10 Zeichen). Beispiel: <em>„ACARS hat 2 Sekunden vor dem Touchdown die Verbindung verloren. Echte Landerate ca. -180 ft/min."</em></span></li>
    <li><span class="gd-n">5</span><span>Optional: Füge einen <strong>Screenshot</strong> als Nachweis bei (JPG, PNG oder PDF, max. 5 MB).</span></li>
    <li><span class="gd-n">6</span><span>Setze das Häkchen bei <strong>Per E-Mail benachrichtigen</strong> wenn du informiert werden möchtest.</span></li>
    <li><span class="gd-n">7</span><span>Klicke <strong>Antrag einreichen</strong>. Der Antrag wartet jetzt auf Admin-Prüfung.</span></li>
  </ol>
  <div class="gd-note"><strong>Hinweis:</strong> Dein originaler PIREP bleibt unverändert bis ein Admin den Antrag genehmigt.</div>
</div>
</div>

<hr class="gd-hr">

<div class="gd-sec" id="gde-status">
  <h3>🔄 Antragsstatus – Was bedeutet was?</h3>
<div class="gd-sec-body">
  <p>Im Tab <strong>Meine Anträge</strong> siehst du den aktuellen Stand aller deiner Anträge:</p>
  <div class="gd-statuslist">
    <div class="gd-si">
      <span class="gd-si-ico">⏳</span>
      <div><div class="gd-si-t">Ausstehend</div>
</div><div class="gd-si-d">Dein Antrag wurde eingereicht und wartet auf Admin-Prüfung. Du musst nichts weiter tun.</div></div>
    </div>
    <div class="gd-si">
      <span class="gd-si-ico" style="color:#4ade80">✓</span>
      <div><div class="gd-si-t">Genehmigt</div><div class="gd-si-d">Der Admin hat genehmigt. Die Landerate deines PIREPs wurde auf den beantragten Wert aktualisiert.</div></div>
    </div>
    <div class="gd-si">
      <span class="gd-si-ico" style="color:#f87171">✗</span>
      <div><div class="gd-si-t">Abgelehnt</div><div class="gd-si-d">Abgelehnt. Den Grund siehst du in der Spalte „Admin-Entscheid". Du kannst einen neuen Antrag mit besserem Nachweis stellen.</div></div>
    </div>
  </div>
</div>

<hr class="gd-hr">

<div class="gd-sec" id="gde-faq">
  <h3>💬 Häufig gestellte Fragen</h3>
<div class="gd-sec-body">
  <p class="gd-faq-q">Mein Flug erscheint nicht im Tab „Unplausibel" – kann ich ihn trotzdem korrigieren?</p>
  <p class="gd-faq-a">Der Tab zeigt nur Werte von 0 oder über -20 ft/min. Wenn dein Flug dort nicht auftaucht aber die Rate trotzdem falsch wirkt, gehe zu <strong>Meine Flüge</strong> und klicke dort auf <strong>Korrigieren →</strong>.</p>
  <p class="gd-faq-q">Wie lange dauert die Prüfung?</p>
  <p class="gd-faq-a">Das hängt von der Admin-Verfügbarkeit ab. Aktiviere die E-Mail-Benachrichtigung beim Einreichen damit du sofort informiert wirst.</p>

  <p class="gd-faq-q">Mein Antrag wurde genehmigt aber der PIREP zeigt noch den alten Wert.</p>
  <p class="gd-faq-a" style="margin-bottom:0">Browser-Cache leeren und Seite neu laden. Wenn das Problem bleibt, Admin kontaktieren.</p>
</div>
</div>

{{-- ═══════════════ NUR ADMINS ═══════════════ --}}
@if(auth()->user()->hasRole('admin'))

<hr class="gd-hr">
<div class="gd-admin-section">
  <div class="gd-admin-header">🔒 Admin-Bereich – nur für Admins sichtbar</div>

  <div class="gd-sec" id="gde-admintabs" style="margin-bottom:1.5rem">
    <h3>🧭 Admin-Panel – Alle Tabs erklärt</h3>
<div class="gd-sec-body">
    <p>Das Admin-Panel erreichst du unter <a href="{{ url('/admin/lrc') }}" style="color:var(--ap-blue)">{{ url('/admin/lrc') }}</a></p>
  <div class="gd-tip"><strong>Tipp:</strong> Das Admin-Panel ist auch über die linke Sidebar im phpVMS-Adminbereich erreichbar: <strong>Addons → LR Corrections</strong>.</div>
    <table class="gd-tbl">
      <thead><tr><th>Tab</th><th>Was du siehst</th><th>Wann benutzen</th></tr></thead>
      <tbody>
        <tr><td>⏳ <strong>Pending</strong></td><td>Offene Anträge die auf Entscheidung warten</td><td>Hauptarbeitswarteschlange – täglich prüfen</td></tr>
        <tr><td>✓ <strong>Approved</strong></td><td>Alle genehmigten Korrekturen</td><td>Prüfen ob Korrekturen korrekt angewendet wurden</td></tr>
        <tr><td>✗ <strong>Rejected</strong></td><td>Abgelehnte Anträge mit Admin-Notizen</td><td>Frühere Entscheidungen nachschlagen</td></tr>
        <tr><td>≡ <strong>All Requests</strong></td><td>Komplette Historie aller Piloten</td><td>Vollständiges Audit</td></tr>
        <tr><td>✦ <strong>Audit Log</strong></td><td>Chronologisches Protokoll – wer entschied was und wann</td><td>Nachvollziehbarkeit</td></tr>
        <tr><td>✉ <strong>Recipients</strong></td><td>Auswahl welche Admins E-Mail-Benachrichtigungen erhalten</td><td>Ersteinrichtung / wenn Team sich ändert</td></tr>
        <tr><td>⚠ <strong>Implausible PIREPs</strong></td><td>Alle site-weiten PIREPs mit verdächtigen Raten</td><td>Proaktive Bereinigung + Direktkorrektur</td></tr>
      </tbody>
    </table>
  </div>
</div>

  <div class="gd-sec" id="gde-review" style="margin-bottom:1.5rem">
    <h3>🔍 Wie prüfe ich einen Pilotenantrag?</h3>
<div class="gd-sec-body">
    <ol class="gd-steps">
      <li><span class="gd-n">1</span><span>Gehe zu <strong>⏳ Pending</strong> und klicke auf <strong>Review →</strong>.</span></li>
      <li><span class="gd-n">2</span><span>Flugdetails prüfen: originale Rate, beantragte Rate, Einreichungszeit.</span></li>
      <li><span class="gd-n">3</span><span>Begründung des Piloten lesen und Nachweis prüfen falls vorhanden.</span></li>
      <li><span class="gd-n">4</span><span><strong>Admin-Notiz</strong> eingeben – optional bei Genehmigung, <strong>Pflicht</strong> bei Ablehnung.</span></li>
      <li><span class="gd-n">5</span><span><strong>Approve</strong> oder <strong>Reject</strong> klicken. PIREP wird bei Genehmigung sofort aktualisiert.</span></li>
    </ol>
    <div class="gd-warn"><strong>Hinweis:</strong> Entscheidungen können nicht über das Modul rückgängig gemacht werden.</div>
</div>
    <div class="gd-tip"><strong>Tipp:</strong> Gib immer einen klaren Ablehnungsgrund an – Piloten können beim nächsten Mal besser Nachweise liefern.</div>
  </div>

  <div class="gd-sec" id="gde-direct" style="margin-bottom:1.5rem">
    <h3>⚡ Direktkorrektur – Ohne Pilotenantrag</h3>
<div class="gd-sec-body">
    <p>In <strong>⚠ Implausible PIREPs</strong> hat jede Zeile ein <strong>Direct Fix</strong>-Formular. Damit korrigierst du sofort, ohne auf einen Pilotenantrag zu warten.</p>
    <div class="gd-warn"><strong>Mit Vorsicht:</strong> Kein Audit-Eintrag auf Pilotenseite. Nur bei eindeutigen Fehlern verwenden (z.B. ACARS 0 ft/min).</div>
</div>
  </div>

  <div class="gd-sec" id="gde-notify" style="margin-bottom:1.5rem">
    <h3>✉️ E-Mail-Benachrichtigungen einrichten</h3>
<div class="gd-sec-body">
    <p>Unter <strong>✉ Recipients</strong> im Admin-Panel auswählen welche Admins eine E-Mail erhalten wenn ein Pilot einen neuen Antrag einreicht.</p>
    <div class="gd-warn"><strong>Wichtig:</strong> Wenn keine Empfänger konfiguriert sind werden gar keine E-Mails versendet. Mindestens einen Admin auswählen.</div>
</div>
    <div class="gd-tip"><strong>Tipp:</strong> Nur Benutzer mit der Rolle <em>admin</em> erscheinen in der Liste. Fehlende Admins → Config → Roles prüfen.</div>
  </div>

  <div class="gd-sec" id="gde-navlinks" style="margin-bottom:0">
    <h3>🔗 Frontend-Navigation – Modul einbinden</h3>
<div class="gd-sec-body">
    <p>Damit Piloten das Modul finden, muss ein Link in der Theme-Navigation eingefügt werden:</p>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 1 – Einfacher Link in der Nav-Vorlage:</p>
    <div class="gd-codeblock">&lt;a href="@{{ url('/lrc') }}"&gt;Landeratenkorrekturen&lt;/a&gt;</div>
</div>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 2 – Mit benannter Route:</p>
    <div class="gd-codeblock">&lt;a href="@{{ route('lrc.pilot.index') }}"&gt;Landeratenkorrekturen&lt;/a&gt;</div>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 3 – Direkt zu einem bestimmten Tab:</p>
    <div class="gd-codeblock">&lt;!-- Öffnet den Tab "Unplausibel" --&gt;
&lt;a href="@{{ url('/lrc#imp') }}"&gt;Unplausible Landungen&lt;/a&gt;

&lt;!-- Öffnet den Tab "Meine Anträge" --&gt;
&lt;a href="@{{ url('/lrc#audit') }}"&gt;Meine Anträge&lt;/a&gt;

&lt;!-- Öffnet das Handbuch --&gt;
&lt;a href="@{{ url('/lrc#guide') }}"&gt;LRC Handbuch&lt;/a&gt;</div>

    <div class="gd-note"><strong>Wo einfügen:</strong> In phpVMS 7 mit Custom-Theme die Navigation-Vorlage suchen – z.B. <span class="gd-code">resources/views/layouts/nav.blade.php</span>. Den Link innerhalb des <span class="gd-code">@auth ... @endauth</span>-Blocks einfügen damit Gäste ihn nicht sehen.</div>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem;margin-top:1rem">Alle Modul-URLs:</p>
    <table class="gd-tbl">
      <thead><tr><th>Wer</th><th>URL</th><th>Beschreibung</th></tr></thead>
      <tbody>
        <tr><td>Pilot</td><td><span class="gd-code">{{ url('/lrc') }}</span></td><td>Pilot-Dashboard</td></tr>
        <tr><td>Admin</td><td><span class="gd-code">{{ url('/admin/lrc') }}</span></td><td>Admin-Panel – alle Anträge verwalten</td></tr>
        <tr><td>Admin</td><td><span class="gd-code">{{ url('/admin/lrc/implausible') }}</span></td><td>Alle unplausiblen PIREPs + Direktkorrektur</td></tr>
      </tbody>
    </table>
  </div>

</div>{{-- gd-admin-section --}}
@endif {{-- isAdmin --}}

</div>{{-- gd-body --}}
</div>{{-- gd --}}

@endif {{-- lang --}}

<script>
// Smooth scroll for guide anchor links
document.querySelectorAll('.gd-c[href^="#"]').forEach(function(a){
  a.addEventListener('click', function(e){
    e.preventDefault();
    var target = document.querySelector(this.getAttribute('href'));
    if(target) target.scrollIntoView({behavior:'smooth', block:'start'});
  });
});
</script>

</div>{{-- lrc-panel-guide --}}


@endsection
