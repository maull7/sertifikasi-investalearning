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
        Schema::create('mapping_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_question_bank')
                ->constrained('bank_questions')
                ->cascadeOnDelete();
            $table->foreignId('id_exam')
                ->constrained('exams')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mapping_questions');
    }
};
