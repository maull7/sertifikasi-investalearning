<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trans_questions', function (Blueprint $table) {
            $table->dropForeign(['id_quiz']);
            $table->dropColumn('id_quiz');
        });
    }

    public function down(): void
    {
        Schema::table('trans_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('id_quiz')->nullable()->after('id_exam');
            $table->foreign('id_quiz')->references('id')->on('quizzes')->cascadeOnDelete();
        });
    }
};
