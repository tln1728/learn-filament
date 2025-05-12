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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->unsignedBigInteger('hotel_id')->nullable(); // Liên kết với khách sạn (cho nhân viên/quản lý)
            $table->string('avatar')->nullable(); // Ảnh đại diện
            $table->date('birth')->nullable(); // Ngày sinh
            // $table->string('nationality')->nullable(); // Quốc tịch
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
