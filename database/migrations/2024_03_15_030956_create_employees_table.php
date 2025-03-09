<?php

use App\Enums\Departments;
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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('employeeID')->nullable()->unique();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('avatar');
            $table->string('department')->default(Departments::Office ->value);
            $table->string('salary');
            $table->string('nrc')->unique()->nullable();
            $table->string('dob');
            $table->string('nationality');
            $table->string('phone');
            $table->string('mail')->nullable()->unique();
            $table->string('marital_status')->default('single');
            $table->string('join_date');
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
        Schema::dropIfExists('employees');
    }
};