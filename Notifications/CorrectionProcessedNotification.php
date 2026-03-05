<?php

namespace Modules\LandingRateCorrection\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\LandingRateCorrection\Models\LandingRateCorrection;

class CorrectionProcessedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly LandingRateCorrection $correction) {}

    public function via(mixed $notifiable): array { return ['mail']; }

    public function toMail(mixed $notifiable): MailMessage
    {
        $c     = $this->correction;
        $pilot = $c->pilot;
        $name  = $pilot ? ($pilot->name ?? 'Pilot') : 'Pilot';
        $pirep = $c->pirep;
        $route = $pirep ? "{$pirep->dpt_airport_id} → {$pirep->arr_airport_id}" : '–';
        $fn    = $pirep ? (($pirep->airline?->icao ?? '') . $pirep->flight_number . ' · ' . $route) : $c->pirep_id;
        $isOk  = $c->isApproved();

        $msg = (new MailMessage)
            ->subject('[GSG] Your correction request has been ' . ($isOk ? 'approved ✅' : 'rejected ❌'))
            ->greeting('Hello ' . $name . ',');

        if ($isOk) {
            $msg->line("Your correction request for flight **{$fn}** has been **approved**.")
                ->line("The landing rate has been updated from **{$c->original_landing_rate} ft/min** to **{$c->requested_landing_rate} ft/min**.");
        } else {
            $msg->line("Your correction request for flight **{$fn}** has been **rejected**.")
                ->line("Original landing rate: **{$c->original_landing_rate} ft/min**");
        }

        if ($c->admin_note) {
            $msg->line("**Admin note:** {$c->admin_note}");
        }

        return $msg
            ->line('If you have any questions, please contact an administrator.')
            ->salutation('German Sky Group');
    }
}
