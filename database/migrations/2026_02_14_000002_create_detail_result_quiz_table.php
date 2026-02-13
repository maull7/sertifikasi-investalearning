<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_result_quiz', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_trans_quiz')
                ->constrained('trans_quiz')
                ->cascadeOnDelete();
            $table->foreignId('id_question')
                ->constrained('bank_questions')
                ->cascadeOnDelete();
            $table->string('user_answer');
            $table->string('correct_answer');
            $table->decimal('score_obtained', 8, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_result_quiz');
    }
};
