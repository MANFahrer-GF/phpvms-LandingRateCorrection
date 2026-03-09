{{-- LandingRateCorrection :: pilot/index.blade.php --}}
@extends('app')
@section('title', __('lrc::lrc.title'))

@php
// UI translations — add new languages in Resources/lang/{locale}/lrc.php
$t = [];
foreach (['title','subtitle','tab_flights','tab_imp','tab_audit','tab_guide',
          'date','flight','route','aircraft','rate','status','details','action',
          'original','requested','decision','submitted','btn_fix','btn_redo',
          'pending','approved','rejected','no_flights','no_imp','no_req',
          'imp_title','imp_desc','your_reason','admin_reply','instead_of',
          'awaiting','no_val'] as $k) {
    $t[$k] = __('lrc::lrc.' . $k);
}
$impCount  = $implausiblePireps->count();
$pendCount = $auditLog->where('status','pending')->count();
$isGlass   = $appearance['glass_mode'];
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
  /* dark defaults (Glass mode) */
  --ap-surface:  rgba(255,255,255,0.04);
  --ap-border:   rgba(255,255,255,0.08);
  --ap-border2:  rgba(255,255,255,0.18);
  --ap-card-bg:  transparent;
  --ap-select-bg:rgba(255,255,255,0.05);
  --ap-kpi-bg:   rgba(255,255,255,0.03);
  --ap-blue:     {{ $appearance['accent'] }};
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
  --ap-select-bg:#ffffff;
  --ap-kpi-bg:   rgba(255,255,255,0.8);
  --ap-blue:     {{ $appearance['accent_l'] }};
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
  overflow-x: auto;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: none;
}
.lrc-tabs::-webkit-scrollbar { display: none; }
.lrc-tab {
  display: inline-flex; align-items: center; gap: .4rem;
  padding: .65rem 1.2rem; font-size: .875rem; font-weight: 600;
  font-family: var(--ap-font-head);
  color: var(--ap-muted); cursor: pointer;
  border: none; background: none;
  border-bottom: 2px solid transparent; margin-bottom: -1px;
  transition: color .15s, border-color .15s;
  white-space: nowrap; flex-shrink: 0;
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

/* ── Card Base ── */
.lrc-card {
  border-radius: 14px;
  overflow: hidden;
  margin-bottom: 1.25rem;
  transition: border-color .2s, box-shadow .2s;
}
.lrc-card:hover { border-color: var(--ap-border2); }

/* ══ GLASS MODE ══ */
.lrc-glass .lrc-card {
  background: var(--ap-card-bg);
  border: 1px solid var(--ap-border);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
}
html.ap-light .lrc-glass .lrc-card {
  box-shadow: 0 2px 16px rgba(0,0,0,.07), 0 1px 4px rgba(0,0,0,.05);
}
.lrc-glass .lrc-panel {
  background: transparent;
}
.lrc-glass .lrc-hero {
  background: transparent;
}
.lrc-glass .lrc-tabs {
  background: transparent;
}

/* ══ SOLID MODE ══ */
.lrc-solid .lrc-card {
  backdrop-filter: none;
  -webkit-backdrop-filter: none;
  background: #1a2235;
  border: 1px solid rgba(255,255,255,0.12);
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}
html.ap-light .lrc-solid .lrc-card {
  background: #ffffff;
  border: 1px solid rgba(0,0,0,0.1);
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
/* Solid Mode: Table headers */
.lrc-solid .lrc-t thead th {
  background: #1e293b;
}
html.ap-light .lrc-solid .lrc-t thead th {
  background: #f0f0f0;
}
/* Solid Mode: Implausible section */
.lrc-solid .lrc-imp-wrap {
  backdrop-filter: none;
  -webkit-backdrop-filter: none;
  background: #1a2235;
}
html.ap-light .lrc-solid .lrc-imp-wrap {
  background: #fff5f5;
}
/* Solid Mode: Tabs */
.lrc-solid .lrc-tabs {
  background: #1a2235;
  border-radius: 14px 14px 0 0;
  padding: 0 .5rem;
  border: 1px solid rgba(255,255,255,0.12);
  border-bottom: 1px solid rgba(255,255,255,0.08);
  margin-bottom: 0;
}
html.ap-light .lrc-solid .lrc-tabs {
  background: #f8fafc;
  border: 1px solid rgba(0,0,0,0.1);
  border-bottom: 1px solid rgba(0,0,0,0.06);
}
/* Solid Mode: Panels connected to tabs */
.lrc-solid .lrc-panel {
  background: #1a2235;
  border-radius: 0 0 14px 14px;
  border: 1px solid rgba(255,255,255,0.12);
  border-top: none;
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
  padding: 1rem;
}
html.ap-light .lrc-solid .lrc-panel {
  background: #ffffff;
  border: 1px solid rgba(0,0,0,0.1);
  border-top: none;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
/* Panel with guide-box: let box handle styling */
.lrc-solid #lrc-panel-guide {
  background: transparent;
  border: none;
  box-shadow: none;
  padding: 0;
}
/* Solid Mode: Cards inside panels shouldn't double up */
.lrc-solid .lrc-panel .lrc-card {
  box-shadow: none;
  border: none;
  border-radius: 0;
  margin-bottom: 0;
  background: transparent;
}
/* Solid Mode: Hero Section */
.lrc-solid .lrc-hero {
  background: #1a2235;
  border-radius: 14px;
  padding: 1.25rem 1.5rem;
  border: 1px solid rgba(255,255,255,0.12);
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
  margin-bottom: 1.25rem;
}
html.ap-light .lrc-solid .lrc-hero {
  background: #ffffff;
  border: 1px solid rgba(0,0,0,0.1);
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
/* Solid Mode: Guide container (gesamtes Handbuch) */
.lrc-solid .gd {
  background: #1a2235;
  border-radius: 14px;
  padding: 1.5rem !important;
  border: 1px solid rgba(255,255,255,0.12);
}
html.ap-light .lrc-solid .gd {
  background: #ffffff;
  border: 1px solid rgba(0,0,0,0.1);
}
/* Guide inside panel: no double background */
.lrc-solid .lrc-panel .gd {
  background: transparent !important;
  border: none !important;
  border-radius: 0;
  padding: 0 !important;
}
/* Solid Mode: Guide cards (navigation tiles) */
.lrc-solid .gd-c {
  background: #0f1623;
  border: 1px solid rgba(255,255,255,0.1);
}
html.ap-light .lrc-solid .gd-c {
  background: #f8fafc;
  border: 1px solid rgba(0,0,0,0.08);
}
/* Solid Mode: Guide sections content */
.lrc-solid .gd-sec-body {
  background: #0f1623;
}
html.ap-light .lrc-solid .gd-sec-body {
  background: #f8fafc;
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
  background: var(--ap-surface) !important;
  border-bottom: 1px solid var(--ap-border2);
  font-weight: 600; white-space: nowrap;
}
html.ap-light .lrc-t thead th { background: var(--ap-surface) !important; }
.lrc-t tbody td {
  padding: .45rem 1rem;
  border-bottom: 1px solid var(--ap-border);
  vertical-align: middle;
  font-size: .875rem;
  color: var(--ap-text);
}
.lrc-t tbody tr:last-child > td { border-bottom: none; }
.lrc-t tbody tr:hover > td { background: var(--ap-surface) !important; }

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
.lrc-imp-wrap { background: var(--ap-kpi-bg); border: 1px solid rgba(239,68,68,.4); border-radius: 14px; overflow: hidden; margin-bottom: 1.25rem; }
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

/* ── LRC Footer (always glass/transparent) ── */
.lrc-footer {
  text-align: center;
  color: var(--ap-muted);
  font-size: 12px;
  opacity: 0.4;
  transition: opacity .2s;
  margin-top: 2.5rem;
  padding: 1rem;
  max-width: 920px;
  margin-left: auto;
  margin-right: auto;
  /* Always transparent - never solid */
  background: transparent !important;
  border: none !important;
  box-shadow: none !important;
}
.lrc-footer:hover { opacity: 0.75; }
.lrc-footer a {
  color: var(--ap-muted);
  text-decoration: none;
}
.lrc-footer a:hover { text-decoration: underline; }
.lrc-footer-heart {
  color: #e25555;
  animation: lrc-pulse 1.8s ease-in-out infinite;
}
@keyframes lrc-pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }

/* ── Empty state ── */
.lrc-empty { text-align: center; padding: 3rem; color: var(--ap-muted); font-family: var(--ap-font-head); }
.lrc-empty .lrc-empty-ico { font-size: 2.5rem; display: block; margin-bottom: .75rem; }
</style>

@if(!$isGlass)
<style>
/* Solid Mode: User-defined colors from settings */
.lrc-solid .lrc-hero                      { background: {{ $appearance['card']     }} !important; border-color: {{ $appearance['border'] }} !important; }
html.ap-light .lrc-solid .lrc-hero        { background: {{ $appearance['card_l']   }} !important; border-color: {{ $appearance['border_l'] }} !important; }
.lrc-solid .lrc-tabs                      { background: {{ $appearance['card']     }} !important; border-color: {{ $appearance['border'] }} !important; }
html.ap-light .lrc-solid .lrc-tabs        { background: {{ $appearance['card_l']   }} !important; border-color: {{ $appearance['border_l'] }} !important; }
.lrc-solid .lrc-panel                     { background: {{ $appearance['card']     }} !important; border-color: {{ $appearance['border'] }} !important; }
html.ap-light .lrc-solid .lrc-panel       { background: {{ $appearance['card_l']   }} !important; border-color: {{ $appearance['border_l'] }} !important; }
.lrc-solid .gd                            { background: {{ $appearance['card']     }} !important; border-color: {{ $appearance['border'] }} !important; }
html.ap-light .lrc-solid .gd              { background: {{ $appearance['card_l']   }} !important; border-color: {{ $appearance['border_l'] }} !important; }
.lrc-solid .lrc-guide-box                 { background: {{ $appearance['card']     }} !important; border-color: {{ $appearance['border'] }} !important; }
html.ap-light .lrc-solid .lrc-guide-box   { background: {{ $appearance['card_l']   }} !important; border-color: {{ $appearance['border_l'] }} !important; }
.lrc-solid .lrc-card                      { background: {{ $appearance['card']     }} !important; }
html.ap-light .lrc-solid .lrc-card        { background: {{ $appearance['card_l']   }} !important; }
.lrc-solid .lrc-t thead th                { background: {{ $appearance['surface']  }} !important; }
html.ap-light .lrc-solid .lrc-t thead th  { background: {{ $appearance['surface_l']}} !important; }
.lrc-solid .lrc-t tbody tr:hover > td     { background: {{ $appearance['surface']  }} !important; }
html.ap-light .lrc-solid .lrc-t tbody tr:hover > td { background: {{ $appearance['surface_l']}} !important; }
.lrc-solid .gd-c, .lrc-solid .gd-sec-body, .lrc-solid .lrc-imp-wrap { background: {{ $appearance['kpi'] }} !important; }
html.ap-light .lrc-solid .gd-c,
html.ap-light .lrc-solid .gd-sec-body     { background: {{ $appearance['kpi_l']    }} !important; }
</style>
@endif

<div class="lrc-wrap {{ $isGlass ? 'lrc-glass' : 'lrc-solid' }}">

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

{{-- Guide Panel ist jetzt Teil des nächsten Blocks --}}

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
     Language: auto-detect via app()->getLocale()
     ═══════════════════════════════════════════════════════════════════ --}}

<div class="lrc-panel" id="lrc-panel-guide">

{{-- ══ GUIDE BOX - große Box um gesamtes Handbuch ══ --}}
<div class="lrc-guide-box">

<style>
/* ── Guide wrapper ── */
.gd{max-width:920px;padding:0;margin:0 auto}

/* ══ Guide Box (große Box um gesamtes Handbuch) ══ */
.lrc-guide-box {
  background: transparent;
  padding: 0;
}
.lrc-solid .lrc-guide-box {
  background: #1a2235;
  border-radius: 0 0 14px 14px;
  padding: 1.5rem;
  border: 1px solid rgba(255,255,255,0.12);
  border-top: none;
  box-shadow: 0 4px 20px rgba(0,0,0,0.3);
}
html.ap-light .lrc-solid .lrc-guide-box {
  background: #ffffff;
  border: 1px solid rgba(0,0,0,0.1);
  border-top: none;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
/* ── Hero ── */
.gd-hero{padding:0 0 1rem}
.gd-hero h2{font-family:var(--ap-font-head);font-weight:800;font-size:1.3rem;margin:0 0 .2rem;color:var(--ap-text-head)}
.gd-hero p{color:var(--ap-muted);font-size:.87rem;margin:0}
/* ── Card grid ── */
.gd-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(155px,1fr));gap:.55rem;margin-bottom:1.8rem}
.gd-c{background:var(--ap-kpi-bg);border:1px solid var(--ap-border);border-radius:9px;padding:.6rem .85rem;
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
.gd-sec-body{background:var(--ap-kpi-bg);border:1px solid var(--ap-border);border-radius:12px;padding:1.2rem 1.4rem}
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
.gd-codeblock{font-family:var(--ap-font-mono);font-size:.82rem;background:var(--ap-surface);
              border:1px solid var(--ap-border);border-radius:8px;padding:.8rem 1rem;
              margin:.7rem 0;overflow-x:auto;color:#a5f3fc;white-space:pre;line-height:1.6}
html.ap-light .gd-codeblock{background:#f1f5f9;color:#0e7490;border-color:#cbd5e1}
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


{{-- ═══════════════════════════════════════════════════════════════════
     GUIDE CONTENT - Uses language variables from Resources/lang/xx/lrc.php
     Fallback: English if translation missing
     ═══════════════════════════════════════════════════════════════════ --}}

@php $g = 'lrc::lrc.'; @endphp

<div class="gd" style="padding:1.5rem 0">
<div class="gd-hero">
  <h2>📋 {{ __($g.'guide_title') }}</h2>
  <p>{{ __($g.'guide_subtitle') }}</p>
</div>

{{-- CARD GRID --}}
<div class="gd-grid">
  <a href="#gen-what"   class="gd-c"><span class="gd-ci">❓</span><div class="gd-ct"><div class="t">{{ __($g.'guide_nav_what') }}</div><div class="s">{{ __($g.'guide_nav_what_sub') }}</div></div></a>
  <a href="#gen-rates"  class="gd-c"><span class="gd-ci">📊</span><div class="gd-ct"><div class="t">{{ __($g.'guide_nav_rates') }}</div><div class="s">{{ __($g.'guide_nav_rates_sub') }}</div></div></a>
  <a href="#gen-submit" class="gd-c"><span class="gd-ci">✏️</span><div class="gd-ct"><div class="t">{{ __($g.'guide_nav_submit') }}</div><div class="s">{{ __($g.'guide_nav_submit_sub') }}</div></div></a>
  <a href="#gen-status" class="gd-c"><span class="gd-ci">🔄</span><div class="gd-ct"><div class="t">{{ __($g.'guide_nav_status') }}</div><div class="s">{{ __($g.'guide_nav_status_sub') }}</div></div></a>
  <a href="#gen-faq"    class="gd-c"><span class="gd-ci">💬</span><div class="gd-ct"><div class="t">{{ __($g.'guide_nav_faq') }}</div><div class="s">{{ __($g.'guide_nav_faq_sub') }}</div></div></a>
  @if(auth()->user()->hasRole('admin'))
  <a href="#gen-admintabs"   class="gd-c adm"><span class="gd-ci">🧭</span><div class="gd-ct"><div class="t">{{ __($g.'guide_nav_admintabs') }} <span class="gd-abadge">ADMIN</span></div><div class="s">{{ __($g.'guide_nav_admintabs_sub') }}</div></div></a>
  <a href="#gen-review"      class="gd-c adm"><span class="gd-ci">🔍</span><div class="gd-ct"><div class="t">{{ __($g.'guide_nav_review') }} <span class="gd-abadge">ADMIN</span></div><div class="s">{{ __($g.'guide_nav_review_sub') }}</div></div></a>
  <a href="#gen-direct"      class="gd-c adm"><span class="gd-ci">⚡</span><div class="gd-ct"><div class="t">{{ __($g.'guide_nav_direct') }} <span class="gd-abadge">ADMIN</span></div><div class="s">{{ __($g.'guide_nav_direct_sub') }}</div></div></a>
  <a href="#gen-notify"      class="gd-c adm"><span class="gd-ci">✉️</span><div class="gd-ct"><div class="t">{{ __($g.'guide_nav_notify') }} <span class="gd-abadge">ADMIN</span></div><div class="s">{{ __($g.'guide_nav_notify_sub') }}</div></div></a>
  <a href="#gen-navlinks"    class="gd-c adm"><span class="gd-ci">🔗</span><div class="gd-ct"><div class="t">{{ __($g.'guide_nav_navlinks') }} <span class="gd-abadge">ADMIN</span></div><div class="s">{{ __($g.'guide_nav_navlinks_sub') }}</div></div></a>
  @endif
</div>

<div class="gd-body">

{{-- SECTION: WHAT --}}
<div class="gd-sec" id="gen-what">
  <h3>❓ {{ __($g.'guide_what_title') }}</h3>
<div class="gd-sec-body">
  <p>{!! __($g.'guide_what_p1') !!}</p>
  <p>{!! __($g.'guide_what_p2') !!}</p>
  <div class="gd-note"><strong>{{ __('Note') }}:</strong> {{ __($g.'guide_what_note') }}</div>
</div>
</div>

<hr class="gd-hr">

{{-- SECTION: RATES --}}
<div class="gd-sec" id="gen-rates">
  <h3>📊 {{ __($g.'guide_rates_title') }}</h3>
<div class="gd-sec-body">
  <p>{{ __($g.'guide_rates_intro') }}</p>
  <div class="gd-rategrid">
    <div class="gd-rb" style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3)">
      <div style="font-weight:700;color:#4ade80;font-family:var(--ap-font-head);font-size:.88rem">✈ {{ __($g.'guide_rates_smooth') }}</div>
      <div style="font-family:var(--ap-font-mono);font-size:.8rem;margin:.2rem 0;color:var(--ap-text)">{{ __($g.'guide_rates_smooth_range') }}</div>
      <div style="font-size:.75rem;color:var(--ap-muted)">{{ __($g.'guide_rates_smooth_desc') }}</div>
    </div>
    <div class="gd-rb" style="background:rgba(234,179,8,.1);border:1px solid rgba(234,179,8,.3)">
      <div style="font-weight:700;color:#fbbf24;font-family:var(--ap-font-head);font-size:.88rem">✈ {{ __($g.'guide_rates_normal') }}</div>
      <div style="font-family:var(--ap-font-mono);font-size:.8rem;margin:.2rem 0;color:var(--ap-text)">{{ __($g.'guide_rates_normal_range') }}</div>
      <div style="font-size:.75rem;color:var(--ap-muted)">{{ __($g.'guide_rates_normal_desc') }}</div>
    </div>
    <div class="gd-rb" style="background:rgba(239,68,68,.1);border:1px solid rgba(239,68,68,.3)">
      <div style="font-weight:700;color:#f87171;font-family:var(--ap-font-head);font-size:.88rem">✈ {{ __($g.'guide_rates_hard') }}</div>
      <div style="font-family:var(--ap-font-mono);font-size:.8rem;margin:.2rem 0;color:var(--ap-text)">{{ __($g.'guide_rates_hard_range') }}</div>
      <div style="font-size:.75rem;color:var(--ap-muted)">{{ __($g.'guide_rates_hard_desc') }}</div>
    </div>
    <div class="gd-rb" style="background:rgba(139,92,246,.1);border:1px solid rgba(139,92,246,.3)">
      <div style="font-weight:700;color:#a78bfa;font-family:var(--ap-font-head);font-size:.88rem">⚠ {{ __($g.'guide_rates_implausible') }}</div>
      <div style="font-family:var(--ap-font-mono);font-size:.8rem;margin:.2rem 0;color:var(--ap-text)">{{ __($g.'guide_rates_implausible_range') }}</div>
      <div style="font-size:.75rem;color:var(--ap-muted)">{{ __($g.'guide_rates_implausible_desc') }}</div>
    </div>
  </div>
  <div class="gd-warn"><strong>⚠</strong> {{ __($g.'guide_rates_warn') }}</div>
</div>
</div>

<hr class="gd-hr">

{{-- SECTION: SUBMIT --}}
<div class="gd-sec" id="gen-submit">
  <h3>✏️ {{ __($g.'guide_submit_title') }}</h3>
<div class="gd-sec-body">
  <ol class="gd-steps">
    <li><span class="gd-n">1</span>{{ __($g.'guide_submit_step1') }}</li>
    <li><span class="gd-n">2</span>{{ __($g.'guide_submit_step2') }}</li>
    <li><span class="gd-n">3</span>{{ __($g.'guide_submit_step3') }}</li>
    <li><span class="gd-n">4</span>{{ __($g.'guide_submit_step4') }}</li>
    <li><span class="gd-n">5</span>{{ __($g.'guide_submit_step5') }}</li>
    <li><span class="gd-n">6</span>{{ __($g.'guide_submit_step6') }}</li>
  </ol>
  <div class="gd-tip"><strong>💡 Tip:</strong> {{ __($g.'guide_submit_tip') }}</div>
</div>
</div>

<hr class="gd-hr">

{{-- SECTION: STATUS --}}
<div class="gd-sec" id="gen-status">
  <h3>🔄 {{ __($g.'guide_status_title') }}</h3>
<div class="gd-sec-body">
  <div class="gd-statuslist">
    <div class="gd-si">
      <span class="gd-si-ico">⏳</span>
      <div><div class="gd-si-t">{{ __($g.'guide_status_pending') }}</div><div class="gd-si-d">{{ __($g.'guide_status_pending_desc') }}</div></div>
    </div>
    <div class="gd-si">
      <span class="gd-si-ico">✅</span>
      <div><div class="gd-si-t">{{ __($g.'guide_status_approved') }}</div><div class="gd-si-d">{{ __($g.'guide_status_approved_desc') }}</div></div>
    </div>
    <div class="gd-si">
      <span class="gd-si-ico">❌</span>
      <div><div class="gd-si-t">{{ __($g.'guide_status_rejected') }}</div><div class="gd-si-d">{{ __($g.'guide_status_rejected_desc') }}</div></div>
    </div>
  </div>
</div>
</div>

<hr class="gd-hr">

{{-- SECTION: FAQ --}}
<div class="gd-sec" id="gen-faq">
  <h3>💬 {{ __($g.'guide_faq_title') }}</h3>
<div class="gd-sec-body">
  <div class="gd-faq-q">{{ __($g.'guide_faq_q1') }}</div>
  <div class="gd-faq-a">{{ __($g.'guide_faq_a1') }}</div>
  <div class="gd-faq-q">{{ __($g.'guide_faq_q2') }}</div>
  <div class="gd-faq-a">{{ __($g.'guide_faq_a2') }}</div>
  <div class="gd-faq-q">{{ __($g.'guide_faq_q3') }}</div>
  <div class="gd-faq-a">{{ __($g.'guide_faq_a3') }}</div>
  <div class="gd-faq-q">{{ __($g.'guide_faq_q4') }}</div>
  <div class="gd-faq-a">{{ __($g.'guide_faq_a4') }}</div>
</div>
</div>

{{-- ═══════════════════════════════════════════════════════════════════
     ADMIN SECTIONS - Only visible to admins
     ═══════════════════════════════════════════════════════════════════ --}}
@if(auth()->user()->hasRole('admin'))
<div class="gd-admin-section">
<div class="gd-admin-header">🔐 {{ __($g.'guide_admin_only') }}</div>

{{-- ADMIN TABS --}}
<div class="gd-sec" id="gen-admintabs">
  <h3>🧭 {{ __($g.'guide_admintabs_title') }}</h3>
<div class="gd-sec-body">
  <p>{{ __($g.'guide_admintabs_intro') }}</p>
  <table class="gd-tbl">
    <thead><tr><th>Tab</th><th>{{ __($g.'guide_navlinks_tbl_desc') }}</th></tr></thead>
    <tbody>
      <tr><td><strong>{{ __($g.'guide_admintabs_requests') }}</strong></td><td>{{ __($g.'guide_admintabs_requests_desc') }}</td></tr>
      <tr><td><strong>{{ __($g.'guide_admintabs_history') }}</strong></td><td>{{ __($g.'guide_admintabs_history_desc') }}</td></tr>
      <tr><td><strong>{{ __($g.'guide_admintabs_implausible') }}</strong></td><td>{{ __($g.'guide_admintabs_implausible_desc') }}</td></tr>
      <tr><td><strong>{{ __($g.'guide_admintabs_notifications') }}</strong></td><td>{{ __($g.'guide_admintabs_notifications_desc') }}</td></tr>
      <tr><td><strong>{{ __($g.'guide_admintabs_appearance') }}</strong></td><td>{{ __($g.'guide_admintabs_appearance_desc') }}</td></tr>
    </tbody>
  </table>
</div>
</div>

<hr class="gd-hr">

{{-- REVIEW --}}
<div class="gd-sec" id="gen-review">
  <h3>🔍 {{ __($g.'guide_review_title') }}</h3>
<div class="gd-sec-body">
  <p>{{ __($g.'guide_review_intro') }}</p>
  <ul style="margin:.5rem 0;padding-left:1.2rem">
    <li>{{ __($g.'guide_review_check1') }}</li>
    <li>{{ __($g.'guide_review_check2') }}</li>
    <li>{{ __($g.'guide_review_check3') }}</li>
    <li>{{ __($g.'guide_review_check4') }}</li>
  </ul>
  <div class="gd-tip"><strong>💡</strong> {{ __($g.'guide_review_tip') }}</div>
</div>
</div>

<hr class="gd-hr">

{{-- DIRECT FIX --}}
<div class="gd-sec" id="gen-direct">
  <h3>⚡ {{ __($g.'guide_direct_title') }}</h3>
<div class="gd-sec-body">
  <p>{{ __($g.'guide_direct_intro') }}</p>
  <ol class="gd-steps">
    <li><span class="gd-n">1</span>{{ __($g.'guide_direct_step1') }}</li>
    <li><span class="gd-n">2</span>{{ __($g.'guide_direct_step2') }}</li>
    <li><span class="gd-n">3</span>{{ __($g.'guide_direct_step3') }}</li>
    <li><span class="gd-n">4</span>{{ __($g.'guide_direct_step4') }}</li>
  </ol>
  <div class="gd-note"><strong>ℹ️</strong> {{ __($g.'guide_direct_note') }}</div>
</div>
</div>

<hr class="gd-hr">

{{-- NOTIFICATIONS --}}
<div class="gd-sec" id="gen-notify">
  <h3>✉️ {{ __($g.'guide_notify_title') }}</h3>
<div class="gd-sec-body">
  <p>{{ __($g.'guide_notify_intro') }}</p>
  <ul style="margin:.5rem 0;padding-left:1.2rem">
    <li>{{ __($g.'guide_notify_event1') }}</li>
    <li>{{ __($g.'guide_notify_event2') }}</li>
    <li>{{ __($g.'guide_notify_event3') }}</li>
  </ul>
  <p>{{ __($g.'guide_notify_setup') }}</p>
</div>
</div>

<hr class="gd-hr">

{{-- NAV LINKS --}}
<div class="gd-sec" id="gen-navlinks">
  <h3>🔗 {{ __($g.'guide_navlinks_title') }}</h3>
<div class="gd-sec-body">
  <p>{{ __($g.'guide_navlinks_intro') }}</p>
  <table class="gd-tbl">
    <thead><tr><th>{{ __($g.'guide_navlinks_tbl_who') }}</th><th>{{ __($g.'guide_navlinks_tbl_url') }}</th><th>{{ __($g.'guide_navlinks_tbl_desc') }}</th></tr></thead>
    <tbody>
      <tr><td>{{ __($g.'guide_navlinks_pilot') }}</td><td><span class="gd-code">{{ url('/lrc') }}</span></td><td>{{ __($g.'guide_navlinks_pilot_desc') }}</td></tr>
      <tr><td>{{ __($g.'guide_navlinks_admin') }}</td><td><span class="gd-code">{{ url('/admin/lrc') }}</span></td><td>{{ __($g.'guide_navlinks_admin_desc') }}</td></tr>
      <tr><td>{{ __($g.'guide_navlinks_admin') }}</td><td><span class="gd-code">{{ url('/admin/lrc/implausible') }}</span></td><td>{{ __($g.'guide_navlinks_admin_imp') }}</td></tr>
    </tbody>
  </table>
</div>
</div>

</div>{{-- gd-admin-section --}}
@endif {{-- isAdmin --}}

</div>{{-- gd-body --}}
</div>{{-- gd --}}


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

</div>{{-- /.lrc-guide-box --}}
</div>{{-- lrc-panel-guide --}}

</div>{{-- /.lrc-wrap --}}

{{-- ── LRC Footer (always glass mode - OUTSIDE lrc-wrap) ── --}}
<div class="lrc-footer">
  <a href="https://github.com/MANFahrer-GF" target="_blank">Landing Rate Corrections</a>
  &mdash; crafted with
  <span class="lrc-footer-heart">&#9829;</span>
  in Germany by Thomas Kant
</div>

@endsection
