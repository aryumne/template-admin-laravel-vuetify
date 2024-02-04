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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('slug');
            $table->string('batch_number')->nullable()->unique();
            $table->char('barcode', 9)->unique();
            $table->integer('pack_stok')->default(0);
            $table->integer('pack_price')->default(0);
            $table->integer('items_per_pack')->default(0);
            $table->integer('item_price')->default(0);
            $table->integer('total_item')->default(0);
            $table->foreignUuid('product_type_id')->constrained('product_types');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
