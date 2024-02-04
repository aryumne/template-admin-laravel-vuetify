<?php

use App\Enums\OrderTypeEnum;
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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->char('barcode', 9);
            $table->char('transaction_number', 15);
            $table->foreign('barcode')->references('barcode')->on("products");
            $table->foreign('transaction_number')->references('transaction_number')->on("transactions");
            $table->enum('type', [OrderTypeEnum::PACK, OrderTypeEnum::PCS]);
            $table->integer('quantity');
            $table->integer('price');
            $table->integer('total_price');
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
