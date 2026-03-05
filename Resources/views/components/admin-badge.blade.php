{{--
    Admin Badge Component
    =====================
    Zeigt die Anzahl offener Korrekturanträge als Badge im Navigationsmenü.

    Einbinden in der Theme-Navigation (z. B. resources/views/layouts/nav.blade.php):
    
        @if(Auth::user()?->hasRole('admin'))
            @include('landingratecorecorrection::components.admin-badge')
        @endif

    Oder als Blade-Include-Direktive (registriert im ServiceProvider):
        @lrcAdminBadge
--}}
@php
    $lrcPendingCount = \Modules\LandingRateCorrection\Models\LandingRateCorrection::pending()->count();
@endphp

@if($lrcPendingCount > 0)
    <a href="{{ route('lrc.admin.index') }}" class="btn btn-sm btn-outline-warning position-relative ms-2"
       title="{{ $lrcPendingCount }} Landerate-Korrektur(en) offen">
        <i class="fas fa-plane-arrival"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
              style="font-size: 0.65rem;">
            {{ $lrcPendingCount }}
            <span class="visually-hidden">offene Korrekturanträge</span>
        </span>
    </a>
@endif
