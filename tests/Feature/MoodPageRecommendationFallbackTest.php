<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Mood;
use App\Models\Category;
use App\Models\Menu;

class MoodPageRecommendationFallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_mood_page_falls_back_to_mood_categories_when_rules_match_none()
    {
        $mood = Mood::create(['mood_name' => 'Marah']);

        // Categories don't contain rule keywords
        $catOther = Category::create(['category_name' => 'Dessert', 'mood_id' => $mood->id]);

        $tenant = \App\Models\Tenant::create([
            'tenant_name' => 'Fallback Tenant',
            'location'    => 'City',
            'start_date'  => now()->subDays(10)->format('Y-m-d'),
            'end_date'    => now()->addDays(10)->format('Y-m-d'),
        ]);

        $menu = Menu::create(['menu_name' => 'Es Krim', 'price' => 8000, 'tenant_id' => $tenant->id, 'category_id' => $catOther->id]);

        $resp = $this->get('/mood/marah');

        $resp->assertStatus(200);
        $resp->assertSee('Es Krim');
    }
}
