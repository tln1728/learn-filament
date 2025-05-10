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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hotel_id');
            $table->string('name'); // Tên loại phòng (Standard, Deluxe,...)
            $table->text('description')->nullable();
            $table->integer('max_occupancy'); // Số người tối đa
            $table->decimal('base_price', 15, 2); // Giá cơ bản
            $table->foreign('hotel_id')->references('id')->on('hotels')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
