<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Mood;
use App\Models\Category;
use App\Models\Menu;

class RecommendationRulesTest extends TestCase
{
    use RefreshDatabase;

    public function test_recommend_by_mood_uses_rule_keywords_for_marah()
    {
        $mood = Mood::create(['mood_name' => 'Marah']);

        $catSpicy = Category::create(['category_name' => 'Menu Pedas Berbumbu', 'mood_id' => $mood->id]);
        $catOther = Category::create(['category_name' => 'Dessert', 'mood_id' => $mood->id]);

        $tenant = \App\Models\Tenant::create([
            'tenant_name' => 'Test Tenant',
            'location'    => 'Test City',
            'start_date'  => now()->subDays(10)->format('Y-m-d'),
            'end_date'    => now()->addDays(10)->format('Y-m-d'),
        ]);

        $menu1 = Menu::create(['menu_name' => 'Ayam Pedas', 'price' => 10000, 'tenant_id' => $tenant->id, 'category_id' => $catSpicy->id]);
        $menu2 = Menu::create(['menu_name' => 'Es Krim', 'price' => 8000, 'tenant_id' => $tenant->id, 'category_id' => $catOther->id]);

        $resp = $this->getJson('/api/recommendation/mood/' . $mood->id);

        $resp->assertStatus(200);
        $data = $resp->json();

        $this->assertCount(1, $data);
        $this->assertEquals('Ayam Pedas', $data[0]['menu_name']);
    }

    public function test_recommend_by_mood_uses_rule_keywords_for_biasa_aja()
    {
        $mood = Mood::create(['mood_name' => 'Biasa Aja']);

        $catLight = Category::create(['category_name' => 'Salad Ringan', 'mood_id' => $mood->id]);
        $catSnack = Category::create(['category_name' => 'Snack', 'mood_id' => $mood->id]);

        $tenant = \App\Models\Tenant::create([
            'tenant_name' => 'Test Tenant 2',
            'location'    => 'Test City',
            'start_date'  => now()->subDays(10)->format('Y-m-d'),
            'end_date'    => now()->addDays(10)->format('Y-m-d'),
        ]);

        $menu1 = Menu::create(['menu_name' => 'Salad Sayur', 'price' => 12000, 'tenant_id' => $tenant->id, 'category_id' => $catLight->id]);
        $menu2 = Menu::create(['menu_name' => 'Burger Berat', 'price' => 20000, 'tenant_id' => $tenant->id, 'category_id' => $catSnack->id]);

        $resp = $this->getJson('/api/recommendation/mood/' . $mood->id);

        $resp->assertStatus(200);
        $data = $resp->json();

        // Both categories have keywords in rules (ringan, snack)
        $this->assertGreaterThanOrEqual(1, count($data));
        $names = array_column($data, 'menu_name');
        $this->assertContains('Salad Sayur', $names);
    }
}
