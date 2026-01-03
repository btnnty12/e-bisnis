<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PageView;
use App\Models\Event;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Matikan sementara pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Hapus data di tabel-tabel yang ada
        DB::table('menus')->truncate();
        DB::table('tenants')->truncate();
        DB::table('events')->truncate();
        DB::table('users')->truncate();
        DB::table('interactions')->truncate();
        DB::table('page_views')->truncate();

        // Hidupkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Panggil Seeder yang ada
        $this->call([
            MoodSeeder::class,
            CategorySeeder::class,
            TenantSeeder::class,
            EventSeeder::class,
            MenuSeeder::class,
            UserSeeder::class,
            InteractionSeeder::class, // pastikan ini ada
            RecommendationSeeder::class,
        ]);

        // ================================
        // Buat DUMMY PAGE VIEWS per event
        // ================================
        $events = Event::all();
        foreach ($events as $event) {
            $totalViews = rand(5, 20); // 5–20 views per event
            for ($i = 0; $i < $totalViews; $i++) {
                PageView::create([
                    'event_id'   => $event->id,
                    'user_id'    => rand(1, 10),
                    'page'       => 'public', // wajib sesuai kolom
                    'created_at' => now()->subDays(rand(0, 10)),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}