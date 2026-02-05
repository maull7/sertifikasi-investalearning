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
        Schema::table('trans_questions', function (Blueprint $table) {
            // hapus foreign key dulu
            $table->dropForeign(['id_type']);

            // baru hapus kolom
            $table->dropColumn('id_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_questions', function (Blueprint $table) {
            $table->unsignedBigInteger('id_type')->nullable();

            // balikin foreign key
            $table->foreign('id_type')
                ->references('id')
                ->on('master_types')
                ->onDelete('cascade');
        });
    }
};
