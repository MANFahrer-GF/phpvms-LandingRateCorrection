<?php

namespace Modules\LandingRateCorrection\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Modules\LandingRateCorrection\Models\LandingRateCorrection;
use Modules\LandingRateCorrection\Models\MailSetting;

class CorrectionProcessedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly LandingRateCorrection $correction) {}

    public function via(mixed $notifiable): array { return ['mail']; }

    public function toMail(mixed $notifiable): MailMessage
    {
        $c      = $this->correction;
        $isOk   = $c->isApproved();
        $pilot  = $c->pilot;
        $pirep  = $c->pirep;
        $route  = $pirep ? "{$pirep->dpt_airport_id} → {$pirep->arr_airport_id}" : '–';
        $fn     = $pirep ? (($pirep->airline?->icao ?? '') . $pirep->flight_number) : $c->pirep_id;

        $vars = [
            'pilot_name'     => $pilot?->name ?? 'Pilot',
            'flight'         => $fn,
            'route'          => $route,
            'original_rate'  => $c->original_landing_rate,
            'requested_rate' => $c->requested_landing_rate,
            'admin_note'     => $c->admin_note ?? '',
        ];

        $subjectKey = $isOk ? 'processed_approved_subject' : 'processed_rejected_subject';
        $bodyKey    = $isOk ? 'processed_approved_body'    : 'processed_rejected_body';

        $subject = MailSetting::render(MailSetting::get($subjectKey), $vars);
        $body    = MailSetting::render(MailSetting::get($bodyKey),    $vars);

        $msg = (new MailMessage)->subject($subject)->greeting('');
        foreach (explode("\n", $body) as $line) {
            if (trim($line) !== '') $msg->line(trim($line));
        }
        return $msg->salutation('');
    }
}
