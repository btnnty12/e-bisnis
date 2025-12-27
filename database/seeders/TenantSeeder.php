<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run()
    {
        Tenant::insert([
            ['tenant_name' => 'Ayam Sayangan Semarang', 'location' => 'Food Destination'],
            ['tenant_name' => 'Mie Kangkung Balacan 89', 'location' => 'Food Destination'],
            ['tenant_name' => 'Kopi Huanan Siantar', 'location' => 'Food Destination'],
            ['tenant_name' => 'Peking Duck Lover', 'location' => 'Food Destination'],
            ['tenant_name' => 'Rujak Kolam Medan', 'location' => 'Food Destination'],
            ['tenant_name' => 'Chinese Food Aang 51', 'location' => 'Food Destination'],
            ['tenant_name' => 'Lapo Ni Tondongta', 'location' => 'Food Destination'],
            ['tenant_name' => 'Kuotie 22 Sanjaya', 'location' => 'Food Destination'],
            ['tenant_name' => 'Asiang 88 Nasi Hainam Campur', 'location' => 'Food Destination'],
            ['tenant_name' => 'Kwetiau Medan Alkap', 'location' => 'Food Destination'],
            ['tenant_name' => 'Mangkok Story', 'location' => 'Food Destination'],
            ['tenant_name' => 'Mie Temenan', 'location' => 'Food Destination'],
            ['tenant_name' => 'Es Podeng Daplun', 'location' => 'Food Destination'],
        ]);
    }
}
