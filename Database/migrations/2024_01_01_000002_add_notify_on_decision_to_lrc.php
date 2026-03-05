<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Column added in migration 1 already; this is a no-op for fresh installs
        if (!Schema::hasColumn('landing_rate_corrections', 'notify_on_decision')) {
            Schema::table('landing_rate_corrections', function (Blueprint $table) {
                $table->boolean('notify_on_decision')->default(true)->after('reason');
            });
        }
    }

    public function down(): void {}
};
