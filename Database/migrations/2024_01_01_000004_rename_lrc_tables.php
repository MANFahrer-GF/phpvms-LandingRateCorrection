<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Rename landing_rate_corrections -> lrc_corrections
        if (Schema::hasTable('landing_rate_corrections') && !Schema::hasTable('lrc_corrections')) {
            Schema::rename('landing_rate_corrections', 'lrc_corrections');
        }

        // Rename lrc_notification_recipients -> lrc_recipients
        if (Schema::hasTable('lrc_notification_recipients') && !Schema::hasTable('lrc_recipients')) {
            Schema::rename('lrc_notification_recipients', 'lrc_recipients');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('lrc_corrections') && !Schema::hasTable('landing_rate_corrections')) {
            Schema::rename('lrc_corrections', 'landing_rate_corrections');
        }

        if (Schema::hasTable('lrc_recipients') && !Schema::hasTable('lrc_notification_recipients')) {
            Schema::rename('lrc_recipients', 'lrc_notification_recipients');
        }
    }
};
