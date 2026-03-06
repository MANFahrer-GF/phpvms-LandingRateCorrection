<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('lrc_mail_settings')) return;

        Schema::create('lrc_mail_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('value', 2000);
            $table->timestamps();
        });

        // Default templates
        $defaults = [
            ['key' => 'submitted_subject',
             'value' => '[LRC] New Correction Request from {pilot_name}'],
            ['key' => 'submitted_body',
             'value' => "Pilot {pilot_name} has submitted a correction request for flight {flight} ({route}).\n\nOriginal landing rate: {original_rate} ft/min\nRequested landing rate: {requested_rate} ft/min\nReason: {reason}\n\nPlease review the request in the admin panel."],
            ['key' => 'processed_approved_subject',
             'value' => '[LRC] Your correction request has been approved ✅'],
            ['key' => 'processed_approved_body',
             'value' => "Hello {pilot_name},\n\nYour correction request for flight {flight} ({route}) has been approved.\nThe landing rate has been updated from {original_rate} ft/min to {requested_rate} ft/min.\n\n{admin_note}"],
            ['key' => 'processed_rejected_subject',
             'value' => '[LRC] Your correction request has been rejected ❌'],
            ['key' => 'processed_rejected_body',
             'value' => "Hello {pilot_name},\n\nYour correction request for flight {flight} ({route}) has been rejected.\nOriginal landing rate: {original_rate} ft/min\n\nReason: {admin_note}"],
        ];

        foreach ($defaults as $row) {
            DB::table('lrc_mail_settings')->insert(array_merge($row, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('lrc_mail_settings');
    }
};
