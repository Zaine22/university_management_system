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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('thumbnail')->nullable();
            $table->integer('price');
            $table->string('category')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('installable')->default(false);
            $table->integer('installment_price')->nullable();
            $table->string('down_payment')->nullable();
            $table->integer('months')->nullable();
            $table->integer('monthly_payment_amount')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};