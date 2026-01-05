<?php

namespace Database\Seeders;

use App\Models\Mood;
use Illuminate\Database\Seeder;

class MoodSeeder extends Seeder
{
    public function run()
    {
        $moods = [
            ['mood_name' => 'Senang', 'description' => 'Merasa bahagia'],
            ['mood_name' => 'Sedih', 'description' => 'Merasa murung'],
            ['mood_name' => 'Stress', 'description' => 'Merasa tertekan'],
            ['mood_name' => 'Lelah', 'description' => 'Merasa capek'],
            ['mood_name' => 'Biasa Aja', 'description' => 'Netral'],
            ['mood_name' => 'Excited', 'description' => 'Sangat bersemangat'],
        ];

        foreach ($moods as $mood) {
            Mood::firstOrCreate(
                ['mood_name' => $mood['mood_name']],
                ['description' => $mood['description']]
            );
        }
    }
}