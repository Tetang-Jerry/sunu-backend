<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('training_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained('coaches')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('members')->cascadeOnDelete();
            $table->dateTime('date_time');
            $table->integer('duration'); // minutes
            $table->string('status')->default('à venir'); // à venir, complétée, annulée
            $table->string('location')->nullable(); // en ligne, en salle, etc.
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('training_sessions');
    }
};
