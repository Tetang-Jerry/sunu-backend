<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->date('slot_date')->nullable()->after('user_id');
            $table->string('slot_period')->nullable()->after('slot_date'); // format HH:MM-HH:MM
        });
    }

    public function down(): void
    {
        Schema::table('training_sessions', function (Blueprint $table) {
            $table->dropColumn(['slot_date','slot_period']);
        });
    }
};
