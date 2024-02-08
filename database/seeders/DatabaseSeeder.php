<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' =>  env("ACCOUNT_PASSWORD"),
            'email' => env("ACCOUNT_EMAIL"),
            'password' => env("ACCOUNT_PASSWORD"),
        ]);

        $this->call([
            BlogTypeSeeder::class,
            ProductTypeSeeder::class,
            ProductSeeder::class
        ]);
    }
}
