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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->decimal('amount', 15, 2); // Số tiền
            // $table->string('currency')->default('USD'); // Đơn vị tiền tệ
            // $table->string('transaction_id')->nullable(); // Mã giao dịch
            $table->enum('payment_method', ['credit_card', 'cash', 'bank_transfer']);
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->timestamps();
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
