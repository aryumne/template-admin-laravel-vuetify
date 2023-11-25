<?php

namespace Database\Seeders;

use App\Enums\BlogTypeEnum;
use App\Repositories\BlogTypeRepository;
use Illuminate\Database\Seeder;

class BlogTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(BlogTypeRepository $btRepo): void
    {
        $data = [
            [
                'bt_name'   => 'Destinasi',
                'key'       => BlogTypeEnum::DESTINATION,
                'menu_name' => 'detailDestinasi'
            ],
            [
                'bt_name'   => 'Festival',
                'key'       => BlogTypeEnum::FESTIVAL,
                'menu_name' => 'detailFestival'
            ],
            [
                'bt_name'   => 'Cerita Inspirasi',
                'key'       => BlogTypeEnum::INSPIRATION,
                'menu_name' => 'detailInspirasi'
            ],
            [
                'bt_name'   => 'Cerita Perjalanan',
                'key'       => BlogTypeEnum::TRIP,
                'menu_name' => 'detailPerjalanan'
            ],
            [
                'bt_name'   => 'Kuliner & Oleh-oleh',
                'key'       => BlogTypeEnum::CULINARY,
                'menu_name' => 'detailKuliner'
            ]
        ];

        foreach ($data as $dt) {
            $btRepo->store($dt);
        }
    }
}
