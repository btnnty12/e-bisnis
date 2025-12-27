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
            ],
            [
                'event_name' => 'Kuliner Nusantara',
                'description' => 'FOOD DESTINATION - Tema Kuliner Nusantara dengan berbagai makanan tradisional Indonesia',
            ],
            [
                'event_name' => 'Peranakan Food Festival',
                'description' => 'FOOD DESTINATION - Tema Peranakan Food Festival dengan kuliner peranakan yang khas',
            ],
            [
                'event_name' => 'Street Food Festival',
                'description' => 'FOOD DESTINATION - Tema Street Food Festival dengan berbagai street food dari berbagai daerah',
            ],
        ]);
    }
}
