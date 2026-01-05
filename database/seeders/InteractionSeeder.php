<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Interaction;
use App\Models\Mood;
use App\Models\Menu;
use App\Models\Event;
use App\Models\User;
use Carbon\Carbon;

class InteractionSeeder extends Seeder
{
    public function run()
    {
        // Pastikan tabel kosong dulu biar bersih
        Interaction::truncate();

        $moods  = Mood::all();
        $menus  = Menu::all();
        $events = Event::all();
        $users  = User::all();

        if ($moods->isEmpty() || $menus->isEmpty()) {
            $this->command->info('Moods atau Menus kosong, skip InteractionSeeder.');
            return;
        }

        // 1. Buat Data "SEBELUM" (Misal: 7 hari yang lalu s/d kemarin)
        // Kita buat sekitar 50 interaksi
        for ($i = 0; $i < 50; $i++) {
            $date = Carbon::now()->subDays(rand(1, 7)); // 1-7 hari lalu

            Interaction::create([
                'user_id'    => $users->random()->id ?? null,
                'mood_id'    => $moods->random()->id,
                'menu_id'    => $menus->random()->id,
                'event_id'   => rand(0, 1) ? ($events->random()->id ?? null) : null,
                'type'       => 'mood_click',
                'created_at' => $date,
                // 'updated_at' tidak ada di Interaction
            ]);
        }

        // 2. Buat Data "SESUDAH" (Hari ini)
        // Kita buat sekitar 30 interaksi (biar ada beda angka)
        for ($i = 0; $i < 30; $i++) {
            $date = Carbon::now(); // Hari ini

            Interaction::create([
                'user_id'    => $users->random()->id ?? null,
                'mood_id'    => $moods->random()->id,
                'menu_id'    => $menus->random()->id,
                'event_id'   => rand(0, 1) ? ($events->random()->id ?? null) : null,
                'type'       => 'mood_click',
                'created_at' => $date,
            ]);
        }
        
        // 3. Tambahan Data Spesifik untuk Event (biar chart per event bagus)
        foreach($events as $event) {
             for ($j = 0; $j < rand(10, 20); $j++) {
                Interaction::create([
                    'user_id'    => $users->random()->id ?? null,
                    'mood_id'    => $moods->random()->id,
                    'menu_id'    => $menus->random()->id,
                    'event_id'   => $event->id,
                    'type'       => 'mood_click',
                    'created_at' => Carbon::now()->subDays(rand(0, 5)),
                ]);
             }
        }
    }
}
