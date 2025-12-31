<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Mood;
use App\Models\Tenant;
use App\Models\Event; // pastikan import model Event
use App\Models\Rating;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * DASHBOARD OVERVIEW
     */
    public function index()
    {
        $stats = [
            'total_menus'      => Menu::count(),
            'total_categories' => Category::count(),
            'total_moods'      => Mood::count(),
            'total_tenants'    => Tenant::count(),
            'avg_rating'       => round(Rating::avg('rating'), 1) ?? 0,
            'total_ratings'    => Rating::count(),
        ];

        // Statistik Mood
        $moods = Mood::orderBy('mood_name')->get();
        $moodCounts = [];
        foreach ($moods as $mood) {
            $moodCounts[$mood->id] = $mood->total_clicks ?? 0;
        }
        $stats['mood_counts'] = $moodCounts;

        // Statistik per event (tenant aktif hari ini)
        $activeTenants = Tenant::active()->with('menus', 'menus.category', 'events')->get();
        $eventStats = [];
        foreach ($activeTenants as $tenant) {
            $totalMenus = $tenant->menus->count();
            $avgRating  = $tenant->menus->flatMap->ratings->avg('rating') ?? 0;

            $eventStats[] = [
                'tenant_name' => $tenant->tenant_name,
                'total_menus' => $totalMenus,
                'avg_rating'  => round($avgRating, 1),
                'start_date'  => optional($tenant->start_date)->format('d-m-Y'),
                'end_date'    => optional($tenant->end_date)->format('d-m-Y'),
            ];
        }

        return view('dashboard.index', compact('stats', 'moods', 'eventStats'));
    }

    /**
     * TENANT MANAGEMENT
     */
    public function tenants()
    {
        // Ambil semua tenant beserta event yang terhubung
        $tenants = Tenant::with('events')->orderBy('tenant_name')->get();

        // Ambil semua event untuk modal dropdown
        $events = Event::orderBy('event_name')->get();

        return view('dashboard.tenants', compact('tenants', 'events'));
    }
}