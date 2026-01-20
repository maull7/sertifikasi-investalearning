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
        Schema::create('trans_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('id_package')
                ->constrained('packages')
                ->cascadeOnDelete();
            $table->foreignId('id_exam')
                ->constrained('exams')
                ->cascadeOnDelete();
            $table->foreignId('id_type')
                ->constrained('master_types')
                ->cascadeOnDelete();
            $table->integer('questions_answered');
            $table->integer('total_questions');
            $table->integer('total_score');
            $table->enum('status', ['lulus', 'tidak lulus']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_questions');
    }
};
