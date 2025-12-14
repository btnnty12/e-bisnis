<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        Menu::insert([
            [
                'menu_name' => 'Es Teh',
                'price' => 5000,
                'description' => 'Teh manis dingin',
                'image' => null,
                'tenant_id' => 1,
                'category_id' => 1,
            ],
            [
                'menu_name' => 'Nasi Goreng',
                'price' => 15000,
                'description' => 'Nasi goreng spesial',
                'image' => null,
                'tenant_id' => 1,
                'category_id' => 2,
            ],
            [
                'menu_name' => 'Kopi Susu',
                'price' => 12000,
                'description' => 'Kopi susu gula aren',
                'image' => null,
                'tenant_id' => 2,
                'category_id' => 4,
            ],
            [
                'menu_name' => 'Donat',
                'price' => 8000,
                'description' => 'Donat coklat',
                'image' => null,
                'tenant_id' => 3,
                'category_id' => 5,
            ],
        ]);
    }
}