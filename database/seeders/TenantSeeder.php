<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run()
    {
        $tenants = [
            'Ayam Sayangan Semarang',
            'Mie Kangkung Balacan 89',
            'Kopi Huanan Siantar',
            'Peking Duck Lover',
            'Rujak Kolam Medan',
            'Chinese Food Aang 51',
            'Lapo Ni Tondongta',
            'Kuotie 22 Sanjaya',
            'Asiang 88 Nasi Hainam Campur',
            'Kwetiau Medan Alkap',
            'Mangkok Story',
            'Mie Temenan',
            'Es Podeng Daplun',
        ];

        foreach ($tenants as $name) {
            Tenant::create([
                'tenant_name' => $name,
                'location'    => 'Food Destination',
            ]);
        }
    }
}