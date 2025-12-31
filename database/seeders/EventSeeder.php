<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run()
    {
        Event::insert([
            [
                'event_name' => 'Little China',
                'description' => 'FOOD DESTINATION - Tema Little China dengan tenant dan dekorasi khas Tiongkok',
                'start_date' => Carbon::now()->subDays(10),
                'end_date' => Carbon::now()->addDays(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_name' => 'Kuliner Nusantara',
                'description' => 'FOOD DESTINATION - Tema Kuliner Nusantara dengan berbagai makanan tradisional Indonesia',
                'start_date' => Carbon::now()->subDays(20),
                'end_date' => Carbon::now()->subDays(5),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_name' => 'Peranakan Food Festival',
                'description' => 'FOOD DESTINATION - Tema Peranakan Food Festival dengan kuliner peranakan yang khas',
                'start_date' => Carbon::now()->addDays(5),
                'end_date' => Carbon::now()->addDays(20),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'event_name' => 'Street Food Festival',
                'description' => 'FOOD DESTINATION - Tema Street Food Festival dengan berbagai street food dari berbagai daerah',
                'start_date' => Carbon::now()->subDays(3),
                'end_date' => Carbon::now()->addDays(3),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}