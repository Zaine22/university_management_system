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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('register_no')->nullable()->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('slug')->nullable();
            $table->string('dob');
            $table->string('avatar')->nullable();
            $table->string('nrc')->unique()->nullable();
            $table->string('nationality');
            $table->string('phone');
            $table->string('gender');
            $table->string('mail')->nullable()->unique();
            $table->string('marital_status')->default('single');
            $table->string('admission_date')->nullable();
            $table->string('address');
            $table->string('past_education')->nullable();
            $table->string('past_qualification')->nullable();
            $table->string('current_job_position')->nullable();
            $table->boolean('graduated')->default(false);
            $table->string('approval_documents')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};