<?php

namespace Modules\LandingRateCorrection\Models;

use App\Contracts\Model;

class MailSetting extends Model
{
    protected $table    = 'lrc_mail_settings';
    protected $fillable = ['key', 'value'];

    /**
     * Get a mail setting value by key, with optional fallback.
     */
    public static function get(string $key, string $fallback = ''): string
    {
        $row = static::where('key', $key)->first();
        return $row ? $row->value : $fallback;
    }

    /**
     * Set or create a mail setting.
     */
    public static function set(string $key, string $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    /**
     * Replace placeholders in a template string.
     */
    public static function render(string $template, array $vars): string
    {
        foreach ($vars as $placeholder => $value) {
            $template = str_replace('{' . $placeholder . '}', $value ?? '', $template);
        }
        return $template;
    }
}
