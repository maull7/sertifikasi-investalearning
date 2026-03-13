<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('certificate_templates', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('package_id')
                ->constrained('packages')
                ->cascadeOnDelete();
            $table->string('front_background_path')->nullable();
            $table->string('left_signature_image_path')->nullable();
            $table->string('left_signature_name')->nullable();
            $table->string('left_signature_title')->nullable();
            $table->string('right_signature_image_path')->nullable();
            $table->string('right_signature_name')->nullable();
            $table->string('right_signature_title')->nullable();
            $table->string('schema_title')->nullable();
            $table->text('schema_description')->nullable();
            $table->text('uk_list')->nullable();
            $table->text('facilitator_list')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('certificate_templates');
    }
};

