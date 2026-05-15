<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('question_images', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('path');
            $table->unsignedInteger('file_size')->nullable();
            $table->string('file_type')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('question_images');
    }
};
