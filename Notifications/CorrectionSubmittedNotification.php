<?php

namespace Modules\LandingRateCorrection\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\LandingRateCorrection\Models\LandingRateCorrection;

class CorrectionSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly LandingRateCorrection $correction) {}

    public function via(mixed $notifiable): array { return ['mail']; }

    public function toMail(mixed $notifiable): MailMessage
    {
        $c     = $this->correction;
        $pilot = $c->pilot;
        $name  = $pilot ? ($pilot->name ?? 'Unknown Pilot') : 'Unknown Pilot';
        $pirep = $c->pirep;
        $route = $pirep ? "{$pirep->dpt_airport_id} → {$pirep->arr_airport_id}" : '–';
        $fn    = $pirep ? (($pirep->airline?->icao ?? '') . $pirep->flight_number . ' · ' . $route) : $c->pirep_id;

        return (new MailMessage)
            ->subject('[GSG] New Landing Rate Correction Request from ' . $name)
            ->greeting('Hello,')
            ->line("Pilot **{$name}** has submitted a correction request for flight **{$fn}**.")
            ->line("**Original landing rate:** {$c->original_landing_rate} ft/min")
            ->line("**Requested landing rate:** {$c->requested_landing_rate} ft/min")
            ->line("**Pilot's reason:** {$c->reason}")
            ->action('Review Request', route('lrc.admin.show', $c->id))
            ->line('Please review the request in the admin panel.')
            ->salutation('German Sky Group');
    }
}
