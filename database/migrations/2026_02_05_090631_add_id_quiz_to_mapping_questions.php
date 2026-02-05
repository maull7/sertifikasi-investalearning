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
        Schema::table('mapping_questions', function (Blueprint $table) {
            // hapus foreign key dulu
            $table->dropForeign(['id_exam']);

            // ubah kolom jadi nullable
            $table->foreignId('id_exam')
                ->nullable()
                ->change();

            // pasang lagi foreign key
            $table->foreign('id_exam')
                ->references('id')
                ->on('exams')
                ->cascadeOnDelete();
            $table->foreignId('id_quiz')
                ->nullable()
                ->after('id_question_bank')
                ->constrained('quizzes')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mapping_questions', function (Blueprint $table) {
            $table->dropForeign(['id_quiz']);
            $table->dropColumn('id_quiz');
        });
    }
};
