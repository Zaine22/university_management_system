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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('enrollmentID')->unique()->nullable();
            $table->unsignedBigInteger('student_id')->constrained('students');
            $table->unsignedBigInteger('batch_id')->constrained('batches');
            $table->unsignedBigInteger('invoice_id')->constrained('invoices')->nullable();
            $table->boolean('has_installment_plan')->default(false);
            $table->unsignedBigInteger('installment_id')->constrained('installments')->nullable();
            $table->integer('enrollment_payment_amount')->nullable();
            $table->integer('discount_percentage')->nullable();
            $table->integer('discounted_payment_amount')->nullable();
            $table->integer('additional_discount_amount')->nullable();
            $table->integer('total_payment_amount')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};