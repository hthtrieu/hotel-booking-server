<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->uuid('hotel_id')->nullable();
            $table->foreign('hotel_id')->references('id')->on('hotels')->cascadeOnDelete();
            $table->float('rating');
            $table->text('comment');

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
        Schema::dropIfExists('reviews');
    }
};
