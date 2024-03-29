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
        Schema::create('sales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->char('barcode', 9);
            $table->char('transaction_number', 15);
            $table->foreign('barcode')->references('barcode')->on("products");
            $table->foreign('transaction_number')->references('transaction_number')->on("transactions");
            $table->string('type');
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('total_price');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
