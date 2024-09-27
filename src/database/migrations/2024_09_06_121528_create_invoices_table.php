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
            $table->uuid('id')->primary();

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('SET NULl');

            $table->uuid('reservation_id')->nullable();
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('SET NULl');

            $table->uuid('payment_types_id')->nullable();
            $table->foreign('payment_types_id')->references('id')->on('payment_types')->onDelete('SET NULl');
            $table->string('order_id'); //vnp_TxnRef
            $table->double('invoice_amount')->default(NULL)->nullable();
            $table->double('refund_amount')->default(NULL)->nullable();
            $table->dateTime('time_canceled')->nullable()->default(NULL);
            $table->dateTime('time_created')->nullable()->default(NULL);
            $table->dateTime('time_paid')->nullable()->default(NULL);

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
        Schema::dropIfExists('invoices');
    }
};
