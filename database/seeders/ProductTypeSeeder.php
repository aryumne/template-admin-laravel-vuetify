<?php

namespace Database\Seeders;

use App\Repositories\ProductTypeRepository;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(ProductTypeRepository $ptRepo): void
    {
        $data = [
            [
                'name' => 'Tablet'
            ],
            [
                'name' => 'Kapsul'
            ],
            [
                'name' => 'Sirup'
            ],
            [
                'name' => 'Salep'
            ],
            [
                'name' => 'Cairan (injeksi)'
            ],
        ];

        foreach ($data as $dt) {
            $ptRepo->store($dt);
        }
    }
}
