<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::insert([
            ['category_name' => 'Minuman', 'mood_id' => 1],
            ['category_name' => 'Makanan Berat', 'mood_id' => 2],
            ['category_name' => 'Snack', 'mood_id' => 3],
            ['category_name' => 'Kopi', 'mood_id' => 4],
            ['category_name' => 'Dessert', 'mood_id' => 6],
        ]);
    }
}