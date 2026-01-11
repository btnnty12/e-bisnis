<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Mood;
use App\Models\Category;
use App\Models\Menu;

class MoodMarahNameMatchTest extends TestCase
{
    use RefreshDatabase;

    public function test_marah_matches_menu_name_goreng()
    {
        $mood = Mood::create(['mood_name' => 'Marah']);

        $catOther = Category::create(['category_name' => 'Dessert', 'mood_id' => $mood->id]);

        $tenant = \App\Models\Tenant::create([
            'tenant_name' => 'Goreng Tenant',
            'location'    => 'City',
            'start_date'  => now()->subDays(10)->format('Y-m-d'),
            'end_date'    => now()->addDays(10)->format('Y-m-d'),
        ]);

        $menu = Menu::create(['menu_name' => 'Tempe Goreng Spesial', 'price' => 9000, 'tenant_id' => $tenant->id, 'category_id' => $catOther->id]);

        $resp = $this->get('/mood/marah');

        $resp->assertStatus(200);
        $resp->assertSee('Tempe Goreng Spesial');
    }
}
