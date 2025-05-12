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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->string('name'); // Tên dịch vụ (đưa đón sân bay, ăn sáng, spa, v.v.)
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2); // Giá dịch vụ
            $table->foreign('hotel_id')->references('id')->on('hotels')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
