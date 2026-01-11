<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Mood;
use App\Models\Category;
use App\Models\Menu;

class MoodPageRecommendationTest extends TestCase
{
    use RefreshDatabase;

    public function test_mood_page_uses_rule_based_matching_for_marah()
    {
        $mood = Mood::create(['mood_name' => 'Marah']);

        $catSpicy = Category::create(['category_name' => 'Menu Pedas Berbumbu', 'mood_id' => $mood->id]);
        $catOther = Category::create(['category_name' => 'Dessert', 'mood_id' => $mood->id]);

        $tenant = \App\Models\Tenant::create([
            'tenant_name' => 'Test Tenant',
            'location'    => 'City',
            'start_date'  => now()->subDays(10)->format('Y-m-d'),
            'end_date'    => now()->addDays(10)->format('Y-m-d'),
        ]);

        $menu1 = Menu::create(['menu_name' => 'Ayam Pedas', 'price' => 10000, 'tenant_id' => $tenant->id, 'category_id' => $catSpicy->id]);
        $menu2 = Menu::create(['menu_name' => 'Es Krim', 'price' => 8000, 'tenant_id' => $tenant->id, 'category_id' => $catOther->id]);

        $resp = $this->get('/mood/marah');

        $resp->assertStatus(200);
        $resp->assertSee('Ayam Pedas');
        $resp->assertDontSee('Es Krim');
        $resp->assertSee('😡');
    }

    public function test_mood_page_uses_rule_based_matching_for_biasa_aja()
    {
        $mood = Mood::create(['mood_name' => 'Biasa Aja']);

        $catLight = Category::create(['category_name' => 'Salad Ringan', 'mood_id' => $mood->id]);
        $catSnack = Category::create(['category_name' => 'Snack', 'mood_id' => $mood->id]);

        $tenant = \App\Models\Tenant::create([
            'tenant_name' => 'Test Tenant 2',
            'location'    => 'City',
            'start_date'  => now()->subDays(10)->format('Y-m-d'),
            'end_date'    => now()->addDays(10)->format('Y-m-d'),
        ]);

        $menu1 = Menu::create(['menu_name' => 'Salad Sayur', 'price' => 12000, 'tenant_id' => $tenant->id, 'category_id' => $catLight->id]);
        $menu2 = Menu::create(['menu_name' => 'Burger Berat', 'price' => 20000, 'tenant_id' => $tenant->id, 'category_id' => $catSnack->id]);

        $resp = $this->get('/mood/biasa-aja');

        $resp->assertStatus(200);
        $resp->assertSee('Salad Sayur');
    }
}
