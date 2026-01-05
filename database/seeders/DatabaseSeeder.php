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
        // DB::table('tenant_event')->truncate(); // sudah tidak ada
        DB::table('menus')->truncate();
        DB::table('tenants')->truncate();
        DB::table('events')->truncate();
        DB::table('users')->truncate();
        DB::table('page_views')->truncate(); // tambah truncate PageView

        // Hidupkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Panggil Seeder yang ada
        $this->call([
            MoodSeeder::class,
            CategorySeeder::class,

            TenantSeeder::class,
            EventSeeder::class,

            // TenantEventSeeder::class, // sudah tidak ada

            MenuSeeder::class,
            UserSeeder::class,
            InteractionSeeder::class, // ✅ Ditambahkan
            RecommendationSeeder::class,
        ]);

        // ================================
        // Buat DUMMY PAGE VIEWS untuk tiap event
        // ================================
        $events = Event::all();

        foreach ($events as $event) {
            // buat random 5-20 interaksi per event
            $total = rand(5, 20);

            for ($i = 0; $i < $total; $i++) {
                PageView::create([
                    'event_id'   => $event->id,
                    'user_id'    => rand(1, 10),           // user dummy (1–10)
                    'page'       => 'public',              // wajib diisi sesuai kolom
                    'created_at' => now()->subDays(rand(0, 10)),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}