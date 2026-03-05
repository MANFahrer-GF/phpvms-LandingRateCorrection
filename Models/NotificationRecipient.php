<?php

namespace Modules\LandingRateCorrection\Models;

use App\Contracts\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationRecipient extends Model
{
    protected $table = 'lrc_notification_recipients';
    protected $fillable = ['user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getRecipientEmails(): array
    {
        return static::with('user')
            ->get()
            ->filter(fn($r) => $r->user && $r->user->email)
            ->pluck('user.email')
            ->toArray();
    }
}
