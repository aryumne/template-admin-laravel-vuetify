<?php

namespace Database\Seeders;

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
                'bt_name' => 'Destinasi',
                'menu_name' => 'detailDestinasi'
            ],
            [
                'bt_name' => 'Festival',
                'menu_name' => 'detailFestival'
            ],
            [
                'bt_name' => 'Cerita Inspirasi',
                'menu_name' => 'detailInspirasi'
            ],
            [
                'bt_name' => 'Kuliner & Oleh-oleh',
                'menu_name' => 'detailKuliner'
            ]
        ];

        foreach ($data as $dt) {
            $btRepo->store($dt);
        }
    }
}
