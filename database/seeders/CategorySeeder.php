<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Kategori untuk setiap mood
        Category::insert([
            // Senang (mood_id: 1)
            ['category_name' => 'Minuman Segar', 'mood_id' => 1],
            ['category_name' => 'Makanan Spesial', 'mood_id' => 1],
            ['category_name' => 'Dessert Manis', 'mood_id' => 1],
            
            // Sedih (mood_id: 2)
            ['category_name' => 'Makanan Hangat', 'mood_id' => 2],
            ['category_name' => 'Minuman Hangat', 'mood_id' => 2],
            ['category_name' => 'Comfort Food', 'mood_id' => 2],
            
            // Stress (mood_id: 3)
            ['category_name' => 'Snack Ringan', 'mood_id' => 3],
            ['category_name' => 'Minuman Dingin', 'mood_id' => 3],
            ['category_name' => 'Makanan Cepat', 'mood_id' => 3],
            
            // Lelah (mood_id: 4)
            ['category_name' => 'Kopi & Energi', 'mood_id' => 4],
            ['category_name' => 'Makanan Berat', 'mood_id' => 4],
            ['category_name' => 'Minuman Energi', 'mood_id' => 4],
            
            // Biasa Aja (mood_id: 5)
            ['category_name' => 'Makanan Standar', 'mood_id' => 5],
            ['category_name' => 'Minuman Biasa', 'mood_id' => 5],
            
            // Excited (mood_id: 6)
            ['category_name' => 'Makanan Spesial', 'mood_id' => 6],
            ['category_name' => 'Dessert Premium', 'mood_id' => 6],
            ['category_name' => 'Minuman Spesial', 'mood_id' => 6],
        ]);
    }
}