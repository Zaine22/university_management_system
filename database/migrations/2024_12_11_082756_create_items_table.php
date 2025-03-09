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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assetable_id');
            $table->string('assetable_type');
            $table->string('item_id')->nullable();
            $table->unsignedBigInteger('employee_id')->constrained('employees')->nullable();
            $table->enum('department', array_map(fn ($case) => $case->value, Departments::cases()));
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
