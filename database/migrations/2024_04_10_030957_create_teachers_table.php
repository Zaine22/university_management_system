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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('teacherID')->nullable()->unique();
            $table->string('salary');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('employee_id')->constrained('employees');
            $table->string('nrc')->unique()->nullable();
            $table->string('dob');
            $table->string('join_date');
            $table->string('nationality');
            $table->string('phone');
            $table->string('mail')->nullable()->unique();
            $table->string('marital_status')->default('single');
            $table->string('education');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};