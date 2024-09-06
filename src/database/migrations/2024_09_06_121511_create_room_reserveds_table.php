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
        Schema::create('room_reserveds', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('room_id')->nullable();
            $table->foreign('room_id')->references('id')->on('rooms')->noActionOnDelete();
            $table->uuid('reservation_id')->nullable();
            $table->foreign('reservation_id')->references('id')->on('reservations')->noActionOnDelete();
            $table->dateTime('start_day');
            $table->dateTime('end_day');

            $table->timestampTz('created_at')->nullable();
            $table->string('created_by', 100)->nullable();
            $table->timestampTz('updated_at')->nullable();
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
        Schema::dropIfExists('room_reserveds');
    }
};
