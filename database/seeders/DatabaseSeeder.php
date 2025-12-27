<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            MoodSeeder::class,
            CategorySeeder::class,
            TenantSeeder::class,
            MenuSeeder::class,
            EventSeeder::class,
            UserSeeder::class,
            RecommendationSeeder::class,
        ]);
    }
}