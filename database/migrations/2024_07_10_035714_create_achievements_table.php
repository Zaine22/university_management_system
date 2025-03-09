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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->constrained('students');
            $table->unsignedBigInteger('batch_id')->constrained('batches');
            $table->boolean('generate_certificate')->defaultFalse();
            $table->unsignedBigInteger('certificate_template_id')->constrained('certificate_templates')->nullable();
            $table->string('certificateID')->nullable();
            $table->json('certificates')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};