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
        Schema::table('certificates', function (Blueprint $table) {
            $table->string('certificate_number')->nullable()->after('id_user');
            $table->date('training_date_start')->nullable()->after('certificate_number');
            $table->date('training_date_end')->nullable()->after('training_date_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certificates', function (Blueprint $table) {
            $table->dropColumn(['certificate_number', 'training_date_start', 'training_date_end']);
        });
    }
};
