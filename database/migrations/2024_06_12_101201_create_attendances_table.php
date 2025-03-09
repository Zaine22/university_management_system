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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date');
            $table->foreignId('timetable_id')->constrained('timetables')->cascadeOnDelete();
            $table->unsignedBigInteger('batch_id')->constrained('batches');
            $table->unsignedBigInteger('subject_id')->constrained('subjects')->nullable();
            $table->boolean('submitted')->defualt(false);
            $table->unsignedBigInteger('teacher_id')->nullable();
            $table->json('present_students')->nullable();
            $table->json('absent_students')->nullable();
            $table->json('leave_students')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};