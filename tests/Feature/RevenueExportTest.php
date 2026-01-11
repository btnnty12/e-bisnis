<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Menu;
use App\Models\Interaction;

class RevenueExportTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_export_contains_correct_fee()
    {
        // Create tenant (include required fields)
        $tenant = Tenant::create(['tenant_name' => 'T1', 'location' => 'Jakarta']);

        // Create tenant user
        $user = User::create(["name" => "User1", "email" => "u1@example.com", "password" => bcrypt('password'), 'role' => 'tenant', 'tenant_id' => $tenant->id]);

        // Ensure mood and category exist for foreign keys
        $mood = \App\Models\Mood::first() ?? \App\Models\Mood::create(['mood_name' => 'Senang']);
        $category = \App\Models\Category::create(['category_name' => 'C1', 'mood_id' => $mood->id]);

        // Create menu
        $menu = Menu::create(['tenant_id' => $tenant->id, 'category_id' => $category->id, 'menu_name' => 'M1', 'price' => 50000]);

        // Create interactions (assign to tenant user)
        Interaction::create(['user_id' => $user->id, 'mood_id' => 1, 'menu_id' => $menu->id, 'type' => 'mood_click', 'created_at' => now()]);
        Interaction::create(['user_id' => $user->id, 'mood_id' => 1, 'menu_id' => $menu->id, 'type' => 'mood_click', 'created_at' => now()]);

        // Configure developer fee
        config(['moodfood.developer_fee' => 0.20]);

        $start = now()->subDay()->format('Y-m-d');
        $end = now()->format('Y-m-d');

        $response = $this->actingAs($user)->get(route('dashboard.revenue.export', ['start' => $start, 'end' => $end]));

        $response->assertStatus(200);

        $content = $response->streamedContent();

        // Revenue is 2 * 50000 = 100000
        // Developer fee is 20% => 20000
        $this->assertStringContainsString('T1', $content);
        $this->assertStringContainsString('100000', $content);
        $this->assertStringContainsString('20000', $content);
    }

    public function test_admin_export_includes_all_tenants()
    {
        $t1 = Tenant::create(['tenant_name' => 'A', 'location' => 'Loc A']);
        $t2 = Tenant::create(['tenant_name' => 'B', 'location' => 'Loc B']);

        $admin = User::create(["name" => "Admin", "email" => "admin@example.com", "password" => bcrypt('password'), 'role' => 'admin']);

        $mood = \App\Models\Mood::first() ?? \App\Models\Mood::create(['mood_name' => 'Senang']);
        $cat = \App\Models\Category::create(['category_name' => 'C2', 'mood_id' => $mood->id]);

        $m1 = Menu::create(['tenant_id' => $t1->id, 'category_id' => $cat->id, 'menu_name' => 'M1', 'price' => 30000]);
        $m2 = Menu::create(['tenant_id' => $t2->id, 'category_id' => $cat->id, 'menu_name' => 'M2', 'price' => 40000]);

        Interaction::create(['user_id' => $admin->id, 'mood_id' => 1, 'menu_id' => $m1->id, 'type' => 'mood_click', 'created_at' => now()]);
        Interaction::create(['user_id' => $admin->id, 'mood_id' => 1, 'menu_id' => $m2->id, 'type' => 'mood_click', 'created_at' => now()]);

        $response = $this->actingAs($admin)->get(route('dashboard.revenue.export'));

        $response->assertStatus(200);
        $content = $response->streamedContent();

        $this->assertStringContainsString('A', $content);
        $this->assertStringContainsString('B', $content);
    }
}
