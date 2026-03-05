{{-- ═══════════════════════════════════════════════════════════════════
     LRC GUIDE  –  Frontend only
     - Pilots see: What is LRC, Landing rates, How to submit, Status, FAQ
     - Admins additionally see: Admin tabs, Review workflow, Direct Fix,
       Notifications, Frontend navigation links
     Language: auto-detect via $lang variable (set in pilot/index)
     ═══════════════════════════════════════════════════════════════════ --}}

<div class="lrc-panel" id="lrc-panel-guide">
<div class="lrc-card" style="overflow:hidden">

<style>
/* ── Guide wrapper ── */
.gd{max-width:860px;padding:0}
/* ── Hero ── */
.gd-hero{padding:1.6rem 2rem 1rem;border-bottom:1px solid var(--ap-border)}
.gd-hero h2{font-family:var(--ap-font-head);font-weight:800;font-size:1.3rem;margin:0 0 .2rem;color:var(--ap-text-head)}
.gd-hero p{color:var(--ap-muted);font-size:.87rem;margin:0}
/* ── Card grid ── */
.gd-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(155px,1fr));gap:.55rem;padding:1.2rem 2rem;border-bottom:1px solid var(--ap-border)}
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
.gd-body{padding:1.6rem 2rem 2rem}
/* ── Section ── */
.gd-sec{margin-bottom:2.2rem;scroll-margin-top:80px}
.gd-sec h3{font-family:var(--ap-font-head);font-weight:800;font-size:.98rem;margin:0 0 .8rem;
           display:flex;align-items:center;gap:.45rem;color:var(--ap-text-head);
           padding-bottom:.5rem;border-bottom:1px solid var(--ap-border)}
.gd-sec p,.gd-sec li{font-size:.875rem;line-height:1.78;color:var(--ap-text)}
/* ── Steps ── */
.gd-steps{list-style:none;padding:0;margin:.6rem 0}
.gd-steps li{display:flex;gap:.75rem;align-items:flex-start;margin-bottom:.55rem;font-size:.875rem;color:var(--ap-text)}
.gd-n{width:22px;height:22px;border-radius:50%;background:var(--ap-blue);color:#fff;font-size:.7rem;
      font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:.15rem;font-family:var(--ap-font-head)}
/* ── Callouts ── */
.gd-note,.gd-tip,.gd-warn{border-radius:0 7px 7px 0;padding:.6rem .9rem;font-size:.84rem;margin:.7rem 0}
.gd-note{background:rgba(59,130,246,.07);border-left:3px solid var(--ap-blue)}.gd-note strong{color:var(--ap-blue)}
.gd-tip {background:rgba(34,197,94,.07); border-left:3px solid #22c55e}.gd-tip  strong{color:#22c55e}
.gd-warn{background:rgba(245,158,11,.07);border-left:3px solid #f59e0b}.gd-warn strong{color:#f59e0b}
/* ── Rate boxes ── */
.gd-rategrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(148px,1fr));gap:.5rem;margin:.7rem 0}
.gd-rb{border-radius:9px;padding:.6rem .85rem}
/* ── Status items ── */
.gd-statuslist{display:flex;flex-direction:column;gap:.45rem;margin:.7rem 0}
.gd-si{display:flex;gap:.75rem;align-items:flex-start;background:var(--ap-input-bg);border:1px solid var(--ap-border);border-radius:9px;padding:.6rem .9rem}
.gd-si-ico{font-size:1rem;flex-shrink:0;margin-top:.05rem}
.gd-si-t{font-size:.85rem;font-weight:700;color:var(--ap-text-head);font-family:var(--ap-font-head)}
.gd-si-d{font-size:.8rem;color:var(--ap-muted);margin-top:.1rem}
/* ── Tables ── */
.gd-tbl{width:100%;border-collapse:collapse;font-size:.855rem;margin:.7rem 0}
.gd-tbl th{background:var(--ap-input-bg);padding:.45rem .7rem;text-align:left;border:1px solid var(--ap-border);font-weight:700;color:var(--ap-text-head)}
.gd-tbl td{padding:.45rem .7rem;border:1px solid var(--ap-border);color:var(--ap-text);vertical-align:top}
.gd-tbl tr:nth-child(even) td{background:var(--ap-input-bg)}
/* ── Code inline ── */
.gd-code{font-family:var(--ap-font-mono);font-size:.82rem;background:var(--ap-input-bg);
         padding:.1rem .35rem;border-radius:4px;border:1px solid var(--ap-border);color:#f87171}
/* ── Code block ── */
.gd-codeblock{font-family:var(--ap-font-mono);font-size:.82rem;background:rgba(0,0,0,.25);
              border:1px solid var(--ap-border);border-radius:8px;padding:.8rem 1rem;
              margin:.7rem 0;overflow-x:auto;color:#a5f3fc;white-space:pre;line-height:1.6}
/* ── Admin block ── */
.gd-admin-section{background:rgba(245,158,11,.04);border:1px solid rgba(245,158,11,.3);
                  border-radius:12px;padding:1.2rem 1.4rem;margin:1.5rem 0}
.gd-admin-section .gd-admin-header{font-size:.75rem;font-weight:700;text-transform:uppercase;
    letter-spacing:.07em;color:#f59e0b;margin-bottom:.9rem;display:flex;align-items:center;gap:.4rem}
/* ── Divider ── */
.gd-hr{border:none;border-top:1px solid var(--ap-border);margin:1.8rem 0}
/* ── FAQ ── */
.gd-faq-q{font-weight:700;font-size:.875rem;color:var(--ap-text-head);margin-bottom:.15rem}
.gd-faq-a{font-size:.875rem;color:var(--ap-text);margin-bottom:1.1rem;line-height:1.75}
</style>

@if($lang==='en')
{{-- ═══════════════════════ ENGLISH ═══════════════════════ --}}

<div class="gd">
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
  <p>ACARS records your landing rate automatically when you touch down. Sometimes this fails – for example when your simulator crashes at the exact moment of touchdown, your internet connection drops, or ACARS has a software glitch. The result is a clearly wrong value like <strong>0 ft/min</strong> or an extreme number that couldn't be real.</p>
  <p>This module lets you formally request a correction. You explain what happened, optionally attach a screenshot as proof, and an admin decides. If approved, your PIREP is updated with the correct value automatically.</p>
  <div class="gd-note"><strong>Note:</strong> Only accepted PIREPs can be corrected. Draft or rejected PIREPs are not eligible.</div>
</div>

<hr class="gd-hr">

{{-- SECTION: RATES --}}
<div class="gd-sec" id="gen-rates">
  <h3>📊 Landing Rate Reference</h3>
  <p>Use this when filling in your correction request. Enter the value that best matches what you remember from your flight:</p>
  <div class="gd-rategrid">
    <div class="gd-rb" style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3)">
      <div style="font-weight:700;color:#4ade80;font-family:var(--ap-font-head);font-size:.88rem">✈ Smooth</div>
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
  <div class="gd-tip"><strong>Tip:</strong> Always enter the rate you genuinely remember. Don't use this module to improve your stats – admins verify requests carefully.</div>
</div>

<hr class="gd-hr">

{{-- SECTION: SUBMIT --}}
<div class="gd-sec" id="gen-submit">
  <h3>✏️ How to Submit a Correction Request</h3>
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

<hr class="gd-hr">

{{-- SECTION: STATUS --}}
<div class="gd-sec" id="gen-status">
  <h3>🔄 Request Status – What Does Each State Mean?</h3>
  <p>Check the <strong>My Requests</strong> tab at any time to see the current state of your requests:</p>
  <div class="gd-statuslist">
    <div class="gd-si">
      <span class="gd-si-ico">⏳</span>
      <div><div class="gd-si-t">Pending</div><div class="gd-si-d">Your request has been submitted and is waiting for an admin to review it. No action needed from you.</div></div>
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
  <p class="gd-faq-q">My flight isn't in the Implausible tab – can I still correct it?</p>
  <p class="gd-faq-a">The Implausible tab only shows values of 0 or above -20 ft/min. If your flight doesn't appear there but the rate still seems wrong, go to <strong>My Flights</strong>, find the flight, and use the <strong>Fix →</strong> button there.</p>
  <p class="gd-faq-q">How long does a review take?</p>
  <p class="gd-faq-a">It depends on admin availability. Enable email notification when submitting so you're informed as soon as a decision is made.</p>
  <p class="gd-faq-q">I don't remember the exact landing rate – what do I enter?</p>
  <p class="gd-faq-a">Enter your best estimate. Mention in your reason that it's an approximation. A screenshot of your HUD at landing helps significantly.</p>
  <p class="gd-faq-q">My request was approved but my PIREP still shows the old value.</p>
  <p class="gd-faq-a" style="margin-bottom:0">Try clearing your browser cache and reloading. If the issue persists, contact an admin.</p>
</div>

{{-- ═══════════════ ADMIN ONLY SECTIONS ═══════════════ --}}
@if(auth()->user()->hasRole('admin'))

<hr class="gd-hr">
<div class="gd-admin-section">
  <div class="gd-admin-header">🔒 Admin Section – only visible to admins</div>

  {{-- Admin Tabs --}}
  <div class="gd-sec" id="gen-admintabs" style="margin-bottom:1.5rem">
    <h3>🧭 Admin Panel – All Tabs Explained</h3>
    <p>Go to <span class="gd-code">{{ url('/admin/lrc') }}</span> to access the admin panel.</p>
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

  {{-- Review --}}
  <div class="gd-sec" id="gen-review" style="margin-bottom:1.5rem">
    <h3>🔍 How to Review a Pilot Request</h3>
    <ol class="gd-steps">
      <li><span class="gd-n">1</span><span>Go to <strong>⏳ Pending</strong> and click <strong>Review →</strong> on a request.</span></li>
      <li><span class="gd-n">2</span><span>Check the flight details: original rate, requested rate, submission time.</span></li>
      <li><span class="gd-n">3</span><span>Read the pilot's reason and view attached evidence if any.</span></li>
      <li><span class="gd-n">4</span><span>Enter an <strong>Admin Note</strong> – optional for approval, <strong>required</strong> for rejection.</span></li>
      <li><span class="gd-n">5</span><span>Click <strong>Approve</strong> or <strong>Reject</strong>. The PIREP updates immediately on approval.</span></li>
    </ol>
    <div class="gd-warn"><strong>Note:</strong> Decisions cannot be undone via the module. Contact a developer if a reversal is needed.</div>
    <div class="gd-tip"><strong>Tip:</strong> Always write a clear rejection reason – pilots need to know what evidence was missing.</div>
  </div>

  {{-- Direct Fix --}}
  <div class="gd-sec" id="gen-direct" style="margin-bottom:1.5rem">
    <h3>⚡ Direct Fix – Correct Without a Pilot Request</h3>
    <p>In <strong>⚠ Implausible PIREPs</strong> each row has a <strong>Direct Fix</strong> form. This lets you correct a landing rate immediately, bypassing the request workflow.</p>
    <div class="gd-warn"><strong>Use with caution:</strong> No audit entry is created on the pilot's side. Only use when you're certain the value is wrong (e.g. clear ACARS 0 ft/min).</div>
  </div>

  {{-- Notifications --}}
  <div class="gd-sec" id="gen-notify" style="margin-bottom:1.5rem">
    <h3>✉️ Email Notifications Setup</h3>
    <p>Go to <strong>✉ Recipients</strong> in the admin panel and check the box next to each admin who should receive an email when a pilot submits a new request.</p>
    <div class="gd-warn"><strong>Important:</strong> If no recipients are configured, no emails are sent at all. Always have at least one admin selected.</div>
    <div class="gd-tip"><strong>Tip:</strong> Only users with the <em>admin</em> role appear in this list. If someone is missing, check their role under Config → Roles.</div>
  </div>

  {{-- Frontend Navigation Links --}}
  <div class="gd-sec" id="gen-navlinks" style="margin-bottom:0">
    <h3>🔗 Frontend Navigation – How to Link This Module</h3>
    <p>To make this module accessible for pilots, add a link in your theme's navigation. Here's how:</p>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 1 – Simple link in your nav blade template:</p>
    <div class="gd-codeblock">&lt;a href="{{ url('/lrc') }}"&gt;Landing Rate Corrections&lt;/a&gt;</div>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 2 – Using the named route:</p>
    <div class="gd-codeblock">&lt;a href="{{ route('lrc.pilot.index') }}"&gt;Landing Rate Corrections&lt;/a&gt;</div>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 3 – Link directly to a specific tab:</p>
    <div class="gd-codeblock">&lt;!-- Goes to the Implausible tab --&gt;
&lt;a href="{{ url('/lrc#imp') }}"&gt;Implausible Landings&lt;/a&gt;

&lt;!-- Goes to My Requests tab --&gt;
&lt;a href="{{ url('/lrc#audit') }}"&gt;My Requests&lt;/a&gt;

&lt;!-- Goes to the Guide tab --&gt;
&lt;a href="{{ url('/lrc#guide') }}"&gt;LRC Guide&lt;/a&gt;</div>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 4 – Show a badge when the pilot has implausible PIREPs:</p>
    <div class="gd-codeblock">@php $lrcCount = \Modules\LandingRateCorrection\Models\LandingRateCorrection::implausibleCountForPilot(auth()->user()); @endphp
@if($lrcCount > 0)
  &lt;a href="{{ url('/lrc#imp') }}"&gt;
    Landing Rates &lt;span class="badge"&gt;{{ $lrcCount }}&lt;/span&gt;
  &lt;/a&gt;
@else
  &lt;a href="{{ url('/lrc') }}"&gt;Landing Rates&lt;/a&gt;
@endif</div>

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

<div class="gd">
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
  <p>ACARS zeichnet deine Landerate automatisch beim Aufsetzen auf. Manchmal schlägt das fehl – wenn der Simulator genau beim Touchdown abstürzt, die Internetverbindung abbricht oder ACARS einen Software-Fehler hat. Das Ergebnis ist ein offensichtlich falscher Wert wie <strong>0 ft/min</strong>.</p>
  <p>Dieses Modul gibt dir die Möglichkeit, eine Korrektur formal zu beantragen. Du erklärst was passiert ist, kannst optional einen Screenshot anhängen, und ein Admin entscheidet. Bei Genehmigung wird dein PIREP automatisch aktualisiert.</p>
  <div class="gd-note"><strong>Hinweis:</strong> Nur akzeptierte PIREPs können korrigiert werden. Entwürfe oder abgelehnte PIREPs sind nicht berechtigt.</div>
</div>

<hr class="gd-hr">

<div class="gd-sec" id="gde-rates">
  <h3>📊 Landeraten-Referenz</h3>
  <p>Nutze diese Übersicht wenn du deinen Korrekturwert eingibst:</p>
  <div class="gd-rategrid">
    <div class="gd-rb" style="background:rgba(34,197,94,.1);border:1px solid rgba(34,197,94,.3)">
      <div style="font-weight:700;color:#4ade80;font-family:var(--ap-font-head);font-size:.88rem">✈ Sanft</div>
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
  <div class="gd-tip"><strong>Tipp:</strong> Gib immer den Wert ein den du wirklich in Erinnerung hast. Nutze dieses Modul nicht um deine Statistiken zu verbessern.</div>
</div>

<hr class="gd-hr">

<div class="gd-sec" id="gde-submit">
  <h3>✏️ Wie stelle ich einen Korrekturantrag?</h3>
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

<hr class="gd-hr">

<div class="gd-sec" id="gde-status">
  <h3>🔄 Antragsstatus – Was bedeutet was?</h3>
  <p>Im Tab <strong>Meine Anträge</strong> siehst du den aktuellen Stand aller deiner Anträge:</p>
  <div class="gd-statuslist">
    <div class="gd-si">
      <span class="gd-si-ico">⏳</span>
      <div><div class="gd-si-t">Ausstehend</div><div class="gd-si-d">Dein Antrag wurde eingereicht und wartet auf Admin-Prüfung. Du musst nichts weiter tun.</div></div>
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
  <p class="gd-faq-q">Mein Flug erscheint nicht im Tab „Unplausibel" – kann ich ihn trotzdem korrigieren?</p>
  <p class="gd-faq-a">Der Tab zeigt nur Werte von 0 oder über -20 ft/min. Wenn dein Flug dort nicht auftaucht aber die Rate trotzdem falsch wirkt, gehe zu <strong>Meine Flüge</strong> und klicke dort auf <strong>Korrigieren →</strong>.</p>
  <p class="gd-faq-q">Wie lange dauert die Prüfung?</p>
  <p class="gd-faq-a">Das hängt von der Admin-Verfügbarkeit ab. Aktiviere die E-Mail-Benachrichtigung beim Einreichen damit du sofort informiert wirst.</p>
  <p class="gd-faq-q">Ich erinnere mich nicht genau an die Landerate – was soll ich eingeben?</p>
  <p class="gd-faq-a">Gib deinen besten Schätzwert ein und erwähne in der Begründung dass es eine Schätzung ist. Ein Screenshot deines Simulator-HUDs hilft enorm.</p>
  <p class="gd-faq-q">Mein Antrag wurde genehmigt aber der PIREP zeigt noch den alten Wert.</p>
  <p class="gd-faq-a" style="margin-bottom:0">Browser-Cache leeren und Seite neu laden. Wenn das Problem bleibt, Admin kontaktieren.</p>
</div>

{{-- ═══════════════ NUR ADMINS ═══════════════ --}}
@if(auth()->user()->hasRole('admin'))

<hr class="gd-hr">
<div class="gd-admin-section">
  <div class="gd-admin-header">🔒 Admin-Bereich – nur für Admins sichtbar</div>

  <div class="gd-sec" id="gde-admintabs" style="margin-bottom:1.5rem">
    <h3>🧭 Admin-Panel – Alle Tabs erklärt</h3>
    <p>Das Admin-Panel erreichst du unter <span class="gd-code">{{ url('/admin/lrc') }}</span></p>
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

  <div class="gd-sec" id="gde-review" style="margin-bottom:1.5rem">
    <h3>🔍 Wie prüfe ich einen Pilotenantrag?</h3>
    <ol class="gd-steps">
      <li><span class="gd-n">1</span><span>Gehe zu <strong>⏳ Pending</strong> und klicke auf <strong>Review →</strong>.</span></li>
      <li><span class="gd-n">2</span><span>Flugdetails prüfen: originale Rate, beantragte Rate, Einreichungszeit.</span></li>
      <li><span class="gd-n">3</span><span>Begründung des Piloten lesen und Nachweis prüfen falls vorhanden.</span></li>
      <li><span class="gd-n">4</span><span><strong>Admin-Notiz</strong> eingeben – optional bei Genehmigung, <strong>Pflicht</strong> bei Ablehnung.</span></li>
      <li><span class="gd-n">5</span><span><strong>Approve</strong> oder <strong>Reject</strong> klicken. PIREP wird bei Genehmigung sofort aktualisiert.</span></li>
    </ol>
    <div class="gd-warn"><strong>Hinweis:</strong> Entscheidungen können nicht über das Modul rückgängig gemacht werden.</div>
    <div class="gd-tip"><strong>Tipp:</strong> Gib immer einen klaren Ablehnungsgrund an – Piloten können beim nächsten Mal besser Nachweise liefern.</div>
  </div>

  <div class="gd-sec" id="gde-direct" style="margin-bottom:1.5rem">
    <h3>⚡ Direktkorrektur – Ohne Pilotenantrag</h3>
    <p>In <strong>⚠ Implausible PIREPs</strong> hat jede Zeile ein <strong>Direct Fix</strong>-Formular. Damit korrigierst du sofort, ohne auf einen Pilotenantrag zu warten.</p>
    <div class="gd-warn"><strong>Mit Vorsicht:</strong> Kein Audit-Eintrag auf Pilotenseite. Nur bei eindeutigen Fehlern verwenden (z.B. ACARS 0 ft/min).</div>
  </div>

  <div class="gd-sec" id="gde-notify" style="margin-bottom:1.5rem">
    <h3>✉️ E-Mail-Benachrichtigungen einrichten</h3>
    <p>Unter <strong>✉ Recipients</strong> im Admin-Panel auswählen welche Admins eine E-Mail erhalten wenn ein Pilot einen neuen Antrag einreicht.</p>
    <div class="gd-warn"><strong>Wichtig:</strong> Wenn keine Empfänger konfiguriert sind werden gar keine E-Mails versendet. Mindestens einen Admin auswählen.</div>
    <div class="gd-tip"><strong>Tipp:</strong> Nur Benutzer mit der Rolle <em>admin</em> erscheinen in der Liste. Fehlende Admins → Config → Roles prüfen.</div>
  </div>

  <div class="gd-sec" id="gde-navlinks" style="margin-bottom:0">
    <h3>🔗 Frontend-Navigation – Modul einbinden</h3>
    <p>Damit Piloten das Modul finden, muss ein Link in der Theme-Navigation eingefügt werden:</p>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 1 – Einfacher Link in der Nav-Vorlage:</p>
    <div class="gd-codeblock">&lt;a href="{{ url('/lrc') }}"&gt;Landeratenkorrekturen&lt;/a&gt;</div>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 2 – Mit benannter Route:</p>
    <div class="gd-codeblock">&lt;a href="{{ route('lrc.pilot.index') }}"&gt;Landeratenkorrekturen&lt;/a&gt;</div>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 3 – Direkt zu einem bestimmten Tab:</p>
    <div class="gd-codeblock">&lt;!-- Öffnet den Tab "Unplausibel" --&gt;
&lt;a href="{{ url('/lrc#imp') }}"&gt;Unplausible Landungen&lt;/a&gt;

&lt;!-- Öffnet den Tab "Meine Anträge" --&gt;
&lt;a href="{{ url('/lrc#audit') }}"&gt;Meine Anträge&lt;/a&gt;

&lt;!-- Öffnet das Handbuch --&gt;
&lt;a href="{{ url('/lrc#guide') }}"&gt;LRC Handbuch&lt;/a&gt;</div>

    <p style="font-weight:700;font-size:.88rem;margin-bottom:.3rem">Option 4 – Badge bei unplausiblen Landeraten:</p>
    <div class="gd-codeblock">@php $lrcCount = \Modules\LandingRateCorrection\Models\LandingRateCorrection::implausibleCountForPilot(auth()->user()); @endphp
@if($lrcCount > 0)
  &lt;a href="{{ url('/lrc#imp') }}"&gt;
    Landerate &lt;span class="badge"&gt;{{ $lrcCount }}&lt;/span&gt;
  &lt;/a&gt;
@else
  &lt;a href="{{ url('/lrc') }}"&gt;Landerate&lt;/a&gt;
@endif</div>

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

</div>{{-- lrc-card --}}
</div>{{-- lrc-panel-guide --}}
