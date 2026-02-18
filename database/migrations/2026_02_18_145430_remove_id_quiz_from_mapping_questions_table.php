<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('mapping_questions')->whereNotNull('id_quiz')->delete();

        Schema::table('mapping_questions', function (Blueprint $table) {
            $table->dropForeign(['id_quiz']);
            $table->dropColumn('id_quiz');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mapping_questions', function (Blueprint $table) {
            $table->foreignId('id_quiz')
                ->nullable()
                ->after('id_question_bank')
                ->constrained('quizzes')
                ->cascadeOnDelete();
        });
    }
};
