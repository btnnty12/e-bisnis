<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run()
    {
        Tenant::insert([
            ['tenant_name' => 'Kantin Bu Ani', 'location' => 'Lantai 1'],
            ['tenant_name' => 'Kopi Corner', 'location' => 'Lantai 2'],
            ['tenant_name' => 'Snack Bar', 'location' => 'Lantai 3'],
        ]);
    }
}