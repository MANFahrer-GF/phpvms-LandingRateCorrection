{{-- LandingRateCorrection :: pilot/create.blade.php --}}
@extends('app')

@php
$lang = str_starts_with(app()->getLocale(), 'en') ? 'en' : 'de';
$t = [
  'title'        => $lang==='en' ? 'Submit Correction Request'      : 'Korrekturantrag stellen',
  'back'         => $lang==='en' ? 'Back to overview'               : 'Zurück zur Übersicht',
  'pirep_info'   => $lang==='en' ? 'Flight Information'             : 'Flug-Informationen',
  'flight'       => $lang==='en' ? 'FLIGHT'                         : 'FLUG',
  'route'        => $lang==='en' ? 'ROUTE'                          : 'ROUTE',
  'date'         => $lang==='en' ? 'DATE'                           : 'DATUM',
  'current_rate' => $lang==='en' ? 'CURRENT LANDING RATE'           : 'AKTUELLE LANDERATE',
  'aircraft'     => $lang==='en' ? 'AIRCRAFT'                       : 'FLUGZEUG',
  'form_title'   => $lang==='en' ? 'Correction Request'             : 'Korrekturantrag',
  'rate_label'   => $lang==='en' ? 'CORRECT LANDING RATE'           : 'KORREKTE LANDERATE',
  'rate_hint'    => $lang==='en' ? 'Negative value · -50 to -250 = smooth · -250 to -600 = normal · below -600 = hard'
                                 : 'Negativer Wert · -50 bis -250 = sanft · -250 bis -600 = normal · unter -600 = hart',
  'reason_label' => $lang==='en' ? 'REASON'                         : 'BEGRÜNDUNG',
  'reason_ph'    => $lang==='en' ? 'Describe why the recorded landing rate is incorrect – e.g. ACARS error, connection loss, sim crash on touchdown …'
                                 : 'Beschreibe warum die aufgezeichnete Landerate falsch ist – z.B. ACARS-Fehler, Verbindungsabbruch, Sim-Absturz beim Touchdown …',
  'reason_hint'  => $lang==='en' ? 'At least 10 characters.'        : 'Mindestens 10 Zeichen.',
  'evidence_label'=> $lang==='en' ? 'EVIDENCE'                      : 'NACHWEIS',
  'evidence_opt' => $lang==='en' ? 'optional'                       : 'optional',
  'evidence_hint'=> $lang==='en' ? 'JPG, PNG, GIF or PDF · max. 5 MB · e.g. screenshot from sim or ACARS log'
                                 : 'JPG, PNG, GIF oder PDF · max. 5 MB · z.B. Screenshot aus dem Sim oder ACARS-Log',
  'notify_label' => $lang==='en' ? 'Notify me by email'             : 'Per E-Mail benachrichtigen',
  'notify_sub'   => $lang==='en' ? 'You will receive an email when an admin approves or rejects your request.'
                                 : 'Du erhältst eine E-Mail, wenn ein Admin deinen Antrag genehmigt oder ablehnt.',
  'info_note'    => $lang==='en' ? 'Your request will be reviewed by an admin. The original landing rate remains unchanged until approved and is visible in the history.'
                                 : 'Dein Antrag wird von einem Admin geprüft. Die ursprüngliche Landerate bleibt bis zur Freigabe unverändert und ist in der Historie sichtbar.',
  'submit'       => $lang==='en' ? 'Submit Request'                 : 'Antrag einreichen',
  'cancel'       => $lang==='en' ? 'Cancel'                         : 'Abbrechen',
];
@endphp

@section('title', $t['title'])

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">

{{-- Same theme detection as AirlineInfoPulse --}}
<script>
(function(){
  function d(){var h=document.documentElement,b=document.body;
    var dk=h.getAttribute('data-bs-theme')==='dark'||h.getAttribute('data-theme')==='dark'
      ||b.classList.contains('dark-mode')||b.classList.contains('dark')
      ||h.classList.contains('dark-mode')||h.classList.contains('dark')
      ||(!h.getAttribute('data-bs-theme')&&!b.getAttribute('data-bs-theme')
         &&!b.classList.contains('light-mode')&&window.matchMedia('(prefers-color-scheme:dark)').matches);
    h.classList.toggle('ap-dark',dk);h.classList.toggle('ap-light',!dk);}
  d();
  new MutationObserver(d).observe(document.documentElement,{attributes:true,attributeFilter:['data-bs-theme','data-theme','class']});
  new MutationObserver(d).observe(document.body,{attributes:true,attributeFilter:['data-bs-theme','data-theme','class']});
})();
</script>

<style>
:root {
  --ap-blue:  #3b82f6; --ap-red: #ef4444; --ap-green: #22c55e;
  --ap-font-head: 'Outfit', sans-serif;
  --ap-font-mono: 'JetBrains Mono', monospace;
  --ap-font-body: 'Inter', sans-serif;
  /* dark defaults */
  --ap-bg:     #161b2e; --ap-surface: rgba(255,255,255,.04);
  --ap-border: rgba(255,255,255,.08); --ap-border2: rgba(255,255,255,.18);
  --ap-text:   #e2e8f0; --ap-text-head: #fff; --ap-muted: #94a3b8;
  --ap-input-bg: rgba(255,255,255,.05);
}
html.ap-light {
  --ap-bg:     #f1f5f9; --ap-surface: rgba(255,255,255,.9);
  --ap-border: rgba(0,0,0,.1); --ap-border2: rgba(0,0,0,.2);
  --ap-text:   #1e293b; --ap-text-head: #0f172a; --ap-muted: #64748b;
  --ap-input-bg: #fff;
}

.lrc-wrap  { max-width:680px; margin:0 auto; padding:1.5rem 1rem;
             font-family:var(--ap-font-body); color:var(--ap-text); }
.lrc-back  { display:inline-flex;align-items:center;gap:.35rem;
             color:var(--ap-muted);font-size:.85rem;text-decoration:none;
             margin-bottom:1.5rem;transition:.15s; }
.lrc-back:hover { color:var(--ap-text);text-decoration:none; }

.lrc-card { background:var(--ap-surface);border:1px solid var(--ap-border);
            border-radius:14px;overflow:hidden;margin-bottom:1.25rem; }
html.ap-light .lrc-card { box-shadow:0 2px 16px rgba(0,0,0,.07); }

.lrc-card-head { padding:.85rem 1.3rem;border-bottom:1px solid var(--ap-border);
                 display:flex;align-items:center;gap:.55rem; }
.lrc-card-head h5 { margin:0;font-size:.95rem;font-weight:700;
                    font-family:var(--ap-font-head);color:var(--ap-text-head); }

.lrc-card-body { padding:1.3rem; }

.info-grid { display:grid;grid-template-columns:repeat(auto-fill,minmax(155px,1fr));gap:.6rem; }
.info-item { background:var(--ap-input-bg);border:1px solid var(--ap-border);
             border-radius:10px;padding:.65rem .9rem; }
.info-item.bad { border-color:rgba(239,68,68,.45);background:rgba(239,68,68,.07); }
.info-item .lbl { font-size:.65rem;text-transform:uppercase;letter-spacing:.07em;
                  color:var(--ap-muted);margin-bottom:.2rem;font-family:var(--ap-font-head); }
.info-item .val { font-size:.92rem;font-weight:700;font-family:var(--ap-font-head);color:var(--ap-text-head); }
.info-item.bad .val { color:#f87171; font-family:var(--ap-font-mono); }

.lrc-lbl { display:block;font-size:.7rem;font-weight:700;text-transform:uppercase;
           letter-spacing:.07em;color:var(--ap-muted);margin-bottom:.45rem;
           font-family:var(--ap-font-head); }
.lrc-lbl .opt { font-size:.65rem;font-weight:400;text-transform:none;letter-spacing:0;
                color:var(--ap-muted);opacity:.7;margin-left:.3rem; }
.lrc-req { color:#f87171; margin-left:.15rem; }

.lrc-inp { width:100%;background:var(--ap-input-bg);border:1px solid var(--ap-border2);
           border-radius:9px;padding:.65rem .9rem;color:var(--ap-text);
           font-size:.9rem;transition:.2s;box-sizing:border-box;font-family:var(--ap-font-body); }
.lrc-inp:focus { outline:none;border-color:var(--ap-blue);box-shadow:0 0 0 3px rgba(59,130,246,.15); }
.lrc-inp.has-err { border-color:#f87171; }
textarea.lrc-inp { resize:vertical;min-height:110px; }

.lrc-hint { font-size:.73rem;color:var(--ap-muted);margin-top:.3rem;line-height:1.5; }
.lrc-err  { font-size:.75rem;color:#f87171;margin-top:.25rem;display:flex;align-items:center;gap:.25rem; }

.rate-row { display:flex;align-items:center;gap:.75rem; }
.rate-row .lrc-inp { max-width:180px;font-family:var(--ap-font-mono);font-weight:600; }
.rate-unit { color:var(--ap-muted);font-family:var(--ap-font-mono);font-size:.9rem;white-space:nowrap; }

.lrc-divider { border:none;border-top:1px solid var(--ap-border);margin:1.2rem 0; }

.lrc-check-wrap { display:flex;align-items:flex-start;gap:.75rem;
                  background:var(--ap-input-bg);border:1px solid var(--ap-border2);
                  border-radius:10px;padding:.9rem 1rem;cursor:pointer; }
.lrc-check-wrap input { width:1.1em;height:1.1em;margin-top:.1em;cursor:pointer;flex-shrink:0;accent-color:var(--ap-blue); }
.lrc-check-lbl .ct { font-size:.875rem;font-weight:600;color:var(--ap-text-head);font-family:var(--ap-font-head); }
.lrc-check-lbl .cs { font-size:.76rem;color:var(--ap-muted);margin-top:.1rem; }

.lrc-note { background:rgba(59,130,246,.07);border:1px solid rgba(59,130,246,.2);
            border-radius:10px;padding:.8rem 1rem;font-size:.82rem;color:var(--ap-muted);
            display:flex;align-items:flex-start;gap:.6rem;line-height:1.6; }
.lrc-note-icon { font-size:1rem;flex-shrink:0;margin-top:.05rem; }

.lrc-actions { display:flex;align-items:center;gap:.75rem;flex-wrap:wrap; }
.btn-submit { background:var(--ap-blue);color:#fff;border:none;border-radius:9px;
              padding:.7rem 1.8rem;font-size:.9rem;font-weight:700;cursor:pointer;
              display:inline-flex;align-items:center;gap:.45rem;
              font-family:var(--ap-font-head);transition:.15s; }
.btn-submit:hover { filter:brightness(1.1); }
.btn-cancel { background:transparent;color:var(--ap-muted);border:1px solid var(--ap-border2);
              border-radius:9px;padding:.7rem 1.3rem;font-size:.9rem;font-weight:600;
              cursor:pointer;text-decoration:none;display:inline-flex;align-items:center;
              gap:.4rem;font-family:var(--ap-font-head);transition:.15s; }
.btn-cancel:hover { color:var(--ap-text);text-decoration:none;background:var(--ap-surface); }

.err-box { background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.3);
           border-radius:10px;padding:.9rem 1rem;margin-bottom:1.25rem;
           font-size:.85rem;color:#f87171; }
.err-box ul { margin:.4rem 0 0 1rem;padding:0; }

/* File input styling */
.lrc-file-wrap { position:relative; }
.lrc-inp[type="file"] { cursor:pointer;padding:.5rem .9rem; }
</style>

<div class="lrc-wrap">

{{-- Back link --}}
<a href="{{ route('lrc.pilot.index') }}" class="lrc-back">
    ← {{ $t['back'] }}
</a>

{{-- Validation errors --}}
@if($errors->any())
<div class="err-box">
    <strong>✗ {{ $lang==='en' ? 'Please fix the following errors:' : 'Bitte korrigiere folgende Fehler:' }}</strong>
    <ul>
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

{{-- Flash --}}
@if(session('error'))
<div class="err-box">✗ {{ session('error') }}</div>
@endif

{{-- PIREP Info --}}
<div class="lrc-card">
    <div class="lrc-card-head">
        <span style="color:var(--ap-blue);font-size:1.1rem">✈</span>
        <h5>{{ $t['pirep_info'] }}</h5>
    </div>
    <div class="lrc-card-body">
        <div class="info-grid">
            <div class="info-item">
                <div class="lbl">{{ $t['flight'] }}</div>
                <div class="val">{{ ($pirep->airline?->icao ?? '') . $pirep->flight_number }}</div>
            </div>
            <div class="info-item">
                <div class="lbl">{{ $t['route'] }}</div>
                <div class="val">{{ $pirep->dpt_airport_id }} → {{ $pirep->arr_airport_id }}</div>
            </div>
            <div class="info-item">
                <div class="lbl">{{ $t['date'] }}</div>
                <div class="val">{{ $pirep->submitted_at?->format('d.m.Y') ?? '–' }}</div>
            </div>
            <div class="info-item {{ ($pirep->landing_rate === null || $pirep->landing_rate >= -20) ? 'bad' : '' }}">
                <div class="lbl">{{ $t['current_rate'] }}</div>
                <div class="val">{{ $pirep->landing_rate !== null ? $pirep->landing_rate.' ft/min' : 'n/a' }}</div>
            </div>
            <div class="info-item">
                <div class="lbl">{{ $t['aircraft'] }}</div>
                <div class="val">{{ $pirep->aircraft?->registration ?? $pirep->aircraft?->name ?? '–' }}</div>
            </div>
        </div>
    </div>
</div>

{{-- Form --}}
<div class="lrc-card">
    <div class="lrc-card-head">
        <span style="color:var(--ap-blue);font-size:1.1rem">✎</span>
        <h5>{{ $t['form_title'] }}</h5>
    </div>
    <div class="lrc-card-body">
        <form method="POST"
              action="{{ route('lrc.pilot.store', $pirep->id) }}"
              enctype="multipart/form-data">
            @csrf

            {{-- Landing Rate --}}
            <div style="margin-bottom:1.2rem">
                <label class="lrc-lbl" for="lrc_rate">
                    {{ $t['rate_label'] }}<span class="lrc-req">*</span>
                </label>
                <div class="rate-row">
                    <input type="number"
                           id="lrc_rate"
                           name="requested_landing_rate"
                           class="lrc-inp {{ $errors->has('requested_landing_rate') ? 'has-err' : '' }}"
                           value="{{ old('requested_landing_rate', '') }}"
                           placeholder="-150"
                           min="-9999" max="-1"
                           required>
                    <span class="rate-unit">ft/min</span>
                </div>
                <div class="lrc-hint">{{ $t['rate_hint'] }}</div>
                @error('requested_landing_rate')
                    <div class="lrc-err">✗ {{ $message }}</div>
                @enderror
            </div>

            {{-- Reason --}}
            <div style="margin-bottom:1.2rem">
                <label class="lrc-lbl" for="lrc_reason">
                    {{ $t['reason_label'] }}<span class="lrc-req">*</span>
                </label>
                <textarea id="lrc_reason"
                          name="reason"
                          class="lrc-inp {{ $errors->has('reason') ? 'has-err' : '' }}"
                          placeholder="{{ $t['reason_ph'] }}"
                          required>{{ old('reason') }}</textarea>
                <div class="lrc-hint">{{ $t['reason_hint'] }}</div>
                @error('reason')
                    <div class="lrc-err">✗ {{ $message }}</div>
                @enderror
            </div>

            {{-- Evidence --}}
            <div style="margin-bottom:1.2rem">
                <label class="lrc-lbl" for="lrc_evidence">
                    {{ $t['evidence_label'] }}<span class="lrc-req opt" style="color:var(--ap-muted)"> ({{ $t['evidence_opt'] }})</span>
                </label>
                <div class="lrc-file-wrap">
                    <input type="file"
                           id="lrc_evidence"
                           name="evidence"
                           class="lrc-inp {{ $errors->has('evidence') ? 'has-err' : '' }}"
                           accept=".jpg,.jpeg,.png,.gif,.pdf">
                </div>
                <div class="lrc-hint">{{ $t['evidence_hint'] }}</div>
                @error('evidence')
                    <div class="lrc-err">✗ {{ $message }}</div>
                @enderror
            </div>

            <hr class="lrc-divider">

            {{-- Email notification --}}
            <label class="lrc-check-wrap" style="margin-bottom:1.2rem">
                <input type="checkbox" name="notify_on_decision" value="1"
                       {{ old('notify_on_decision', '1') ? 'checked' : '' }}>
                <div class="lrc-check-lbl">
                    <div class="ct">✉ {{ $t['notify_label'] }}</div>
                    <div class="cs">{{ $t['notify_sub'] }}</div>
                </div>
            </label>

            {{-- Info note --}}
            <div class="lrc-note" style="margin-bottom:1.5rem">
                <span class="lrc-note-icon">ℹ</span>
                <span>{{ $t['info_note'] }}</span>
            </div>

            {{-- Actions --}}
            <div class="lrc-actions">
                <button type="submit" class="btn-submit">
                    ✓ {{ $t['submit'] }}
                </button>
                <a href="{{ route('lrc.pilot.index') }}" class="btn-cancel">
                    ✗ {{ $t['cancel'] }}
                </a>
            </div>

        </form>
    </div>
</div>

</div>
@endsection
