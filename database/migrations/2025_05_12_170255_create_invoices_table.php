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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('invoice_number')->unique(); // Mã hóa đơn
            $table->decimal('total_amount', 15, 2); // Tổng số tiền
            $table->string('currency')->default('USD');
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->foreign('booking_id')->references('id')->on('bookings')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
