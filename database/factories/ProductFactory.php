<?php

namespace Database\Factories;

use App\Models\ProductType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->word;
        $batchNumber = fake()->unique()->randomNumber(6);
        $stokByPack = fake()->numberBetween(0, 100);
        $packPrice = fake()->numberBetween(10000, 60000);
        $stokByItem = fake()->numberBetween(0, 100);
        $itemPrice = fake()->numberBetween(3000, 12000);
        $totalItem = fake()->numberBetween(0, 500);

        // Ambil satu product_type secara acak dari database
        $productType = ProductType::inRandomOrder()->first();

        return [
            'name' => $name,
            'batch_number' => $batchNumber,
            'pack_stok' => $stokByPack,
            'pack_price' => $packPrice,
            'items_per_pack' => $stokByItem,
            'item_price' => $itemPrice,
            'total_item' => $totalItem,
            'product_type_id' => $productType->id,
        ];
    }
}
