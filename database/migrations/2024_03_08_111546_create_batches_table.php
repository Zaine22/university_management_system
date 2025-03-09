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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained('courses');
            $table->integer('batch');
            $table->string('code');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('total_section_count')->nullable();
            $table->string('description')->nullable();
            $table->date('start_date')->nullable();
            $table->string('duration')->nullable();
            $table->json('subject_ids')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
};