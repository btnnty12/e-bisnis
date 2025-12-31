<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserMoodSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user_moods')->insert([
            ['user_id' => 1, 'mood_id' => 1, 'created_at' => '2025-12-28 10:00:00', 'updated_at' => now()],
            ['user_id' => 2, 'mood_id' => 2, 'created_at' => '2025-12-29 12:00:00', 'updated_at' => now()],
            ['user_id' => 1, 'mood_id' => 1, 'created_at' => '2025-12-30 09:00:00', 'updated_at' => now()],
            ['user_id' => 2, 'mood_id' => 1, 'created_at' => '2025-12-30 11:00:00', 'updated_at' => now()],
        ]);
    }
}