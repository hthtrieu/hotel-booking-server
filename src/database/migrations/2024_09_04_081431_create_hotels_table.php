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
            $table->uuid('id')->primary();
            $table->string('name');
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('set null');
            $table->string('description', 255)->nullable();
            $table->string('phone_number', 20);
            $table->string('email', 50)->unique();
            $table->timestampTz('check_in');
            $table->timestampTz('check_out');
            $table->string('province');
            $table->string('district');
            $table->string('ward');
            $table->string('street')->nullable();


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
        Schema::dropIfExists('hotels');
    }
};
