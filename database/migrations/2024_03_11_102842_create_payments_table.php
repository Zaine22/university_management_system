<?php

use App\Enums\PaymentStatus;
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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete()->nullable();
            $table->unsignedBigInteger('student_id')->constrained('students')->nullable();
            $table->unsignedBigInteger('invoice_id')->constrained('invoices')->nullable();
            $table->string('paymentID')->unique()->nullable();
            $table->string('status')->default(PaymentStatus::New->value);
            $table->string('payment_type')->nullable();
            $table->string('payment_price')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};