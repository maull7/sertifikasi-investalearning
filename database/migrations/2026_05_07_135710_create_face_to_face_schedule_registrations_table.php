<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('face_to_face_schedule_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('face_to_face_schedules')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('participant_email');
            $table->string('zoom_registrant_id', 120)->nullable();
            $table->string('zoom_join_url')->nullable();
            $table->timestamp('invitation_sent_at')->nullable();
            $table->timestamps();

            $table->unique(['schedule_id', 'user_id']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('face_to_face_schedule_registrations');
    }
};
