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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');

            $table->unsignedBigInteger('grading_rule_id');
            $table->unsignedBigInteger('batch_id')->constrained();
            $table->unsignedBigInteger('subject_id')->constrained()->nullable();
            $table->json('chapter_ids')->nullable();
            $table->string('examId')->nullable()->unique();
            $table->boolean('submitted')->default(false);
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->dateTime('start_date_time');
            $table->dateTime('end_date_time');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};