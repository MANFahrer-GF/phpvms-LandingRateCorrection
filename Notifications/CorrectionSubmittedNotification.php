<?php

namespace Modules\LandingRateCorrection\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\LandingRateCorrection\Models\LandingRateCorrection;
use Modules\LandingRateCorrection\Models\MailSetting;

class CorrectionSubmittedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly LandingRateCorrection $correction) {}

    public function via(mixed $notifiable): array { return ['mail']; }

    public function toMail(mixed $notifiable): MailMessage
    {
        $c     = $this->correction;
        $pilot = $c->pilot;
        $pirep = $c->pirep;
        $route = $pirep ? "{$pirep->dpt_airport_id} → {$pirep->arr_airport_id}" : '–';
        $fn    = $pirep ? (($pirep->airline?->icao ?? '') . $pirep->flight_number) : $c->pirep_id;

        $vars = [
            'pilot_name'     => $pilot?->name ?? 'Unknown Pilot',
            'flight'         => $fn,
            'route'          => $route,
            'original_rate'  => $c->original_landing_rate,
            'requested_rate' => $c->requested_landing_rate,
            'reason'         => $c->reason,
        ];

        $subject = MailSetting::render(
            MailSetting::get('submitted_subject', '[LRC] New Correction Request from {pilot_name}'),
            $vars
        );
        $body = MailSetting::render(
            MailSetting::get('submitted_body', 'Pilot {pilot_name} submitted a request for flight {flight}.'),
            $vars
        );

        $msg = (new MailMessage)->subject($subject)->greeting('Hello,');
        foreach (explode("\n", $body) as $line) {
            if (trim($line) !== '') $msg->line(trim($line));
        }
        return $msg
            ->action('Review Request', route('lrc.admin.show', $c->id))
            ->salutation('');
    }
}
