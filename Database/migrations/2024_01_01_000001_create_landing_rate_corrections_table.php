<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('landing_rate_corrections')) return;

        Schema::create('landing_rate_corrections', function (Blueprint $table) {
            $table->id();
            $table->string('pirep_id');
            $table->unsignedBigInteger('pilot_id');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->integer('original_landing_rate')->default(0);
            $table->integer('requested_landing_rate');
            $table->text('reason');
            $table->string('status')->default('pending'); // pending|approved|rejected
            $table->text('admin_note')->nullable();
            $table->boolean('notify_on_decision')->default(true);
            $table->string('evidence_path')->nullable();
            $table->string('evidence_original_name')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();

            $table->index('pirep_id');
            $table->index('pilot_id');
            $table->index('status');
            $table->index(['pilot_id', 'status']); // composite for pilot dashboard queries
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_rate_corrections');
    }
};
