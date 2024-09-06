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
        Schema::create('hotels', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Bỏ autoIncrement
            $table->string('name');
            $table->uuid('owner_id')->nullable(); // Cho phép nullable trước khi đặt foreign key
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
            $table->string('description', 255)->nullable();
            $table->enum('hotel_starts', [1, 2, 3, 4, 5, 6])->default(NULL);
            $table->string('phone_number', 20);
            $table->string('email', 50)->unique();
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->string('province');
            $table->string('district');
            $table->string('ward');
            $table->string('street')->nullable();
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'OCCUPIED'])->default(NULL);
            $table->timestampsTz(); // Sử dụng timestampsTz() để tự động tạo created_at và updated_at
            $table->string('created_by', 100)->nullable();
            $table->string('updated_by', 100)->nullable();
            $table->softDeletesTz();
            $table->string('deleted_by', 100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
