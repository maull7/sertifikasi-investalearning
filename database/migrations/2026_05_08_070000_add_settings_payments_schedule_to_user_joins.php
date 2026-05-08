<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_join_id')->constrained('user_joins')->cascadeOnDelete();
            $table->string('proof_image'); // path bukti transfer
            $table->enum('status', ['pending', 'confirmed', 'rejected'])->default('pending');
            $table->text('note')->nullable();
            $table->timestamps();
        });

        Schema::table('user_joins', function (Blueprint $table) {
            $table->foreignId('schedule_id')
                ->nullable()
                ->constrained('face_to_face_schedules')
                ->nullOnDelete()
                ->after('id_package');
        });
    }

    public function down(): void
    {
        Schema::table('user_joins', function (Blueprint $table) {
            $table->dropForeign(['schedule_id']);
            $table->dropColumn('schedule_id');
        });
        Schema::dropIfExists('payments');
        Schema::dropIfExists('settings');
    }
};
