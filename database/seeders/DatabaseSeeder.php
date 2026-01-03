<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            RecommendationSeeder::class,
        ]);
    }
}