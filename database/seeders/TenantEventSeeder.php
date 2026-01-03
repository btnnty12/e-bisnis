<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant;
use App\Models\Event;
use Carbon\Carbon;

class TenantEventSeeder extends Seeder
{
    public function run(): void
    {
        $tenants = Tenant::all();
        $events  = Event::all();

        // safety
        if ($tenants->isEmpty() || $events->isEmpty()) {
            return;
        }

        foreach ($tenants as $tenant) {
            // setiap tenant ikut 1–2 event biar realistis
            foreach ($events->random(rand(1, 2)) as $event) {

                DB::table('tenant_event')->insert([
                    'tenant_id' => $tenant->id,
                    'event_id'  => $event->id,

                    // 🔥 PIVOT DATE SELALU MASUK
                    'start_date' => Carbon::parse($event->start_date)->format('Y-m-d'),
                    'end_date'   => Carbon::parse($event->end_date)->format('Y-m-d'),

                    'active'     => Carbon::now()->between(
                        Carbon::parse($event->start_date),
                        Carbon::parse($event->end_date)
                    ),

                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}