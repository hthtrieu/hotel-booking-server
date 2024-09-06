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
        Schema::create('bed_types', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->string('description', 100);

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
        Schema::dropIfExists('bed_types');
    }
};
