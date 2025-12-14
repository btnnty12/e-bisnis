<?php

namespace Database\Seeders;

use App\Models\Recommendation;
use Illuminate\Database\Seeder;

class RecommendationSeeder extends Seeder
{
    public function run()
    {
        Recommendation::insert([
            ['mood_id' => 1, 'category_id' => 1, 'score' => 10],
            ['mood_id' => 1, 'category_id' => 5, 'score' => 8],
            ['mood_id' => 2, 'category_id' => 2, 'score' => 9],
            ['mood_id' => 3, 'category_id' => 4, 'score' => 8],
            ['mood_id' => 4, 'category_id' => 1, 'score' => 7],
            ['mood_id' => 6, 'category_id' => 5, 'score' => 10],
        ]);
    }
}