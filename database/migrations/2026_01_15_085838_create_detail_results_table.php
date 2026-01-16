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
        Schema::create('detail_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_trans_question')
                ->constrained('trans_questions')
                ->cascadeOnDelete();
            $table->foreignId('id_question')
                ->constrained('bank_questions')
                ->cascadeOnDelete();
            $table->string('user_answer');
            $table->string('correct_answer');
            $table->integer('score_obtained');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_results');
    }
};
