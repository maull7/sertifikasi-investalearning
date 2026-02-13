<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trans_quiz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('questions_answered')->default(0);
            $table->unsignedInteger('total_questions')->default(0);
            $table->decimal('total_score', 8, 2)->default(0);
            $table->string('status', 20)->default('tidak lulus');
            $table->unsignedInteger('attempted_count')->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'quiz_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trans_quiz');
    }
};
