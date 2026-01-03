<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Category;
use App\Models\Mood;
use App\Models\Tenant;
use App\Models\Event;
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

        // Statistik per tenant/event aktif
        $activeTenants = Tenant::active()
            ->with('menus', 'menus.category', 'events')
            ->get();

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
     * MENU MANAGEMENT  ✅ (INI YANG KURANG)
     */
    public function menus()
    {
        $menus = Menu::with(['category', 'tenant'])
            ->orderBy('menu_name')
            ->get();

        $categories = Category::orderBy('category_name')->get();
        $tenants    = Tenant::orderBy('tenant_name')->get();

        return view('dashboard.menus', compact('menus', 'categories', 'tenants'));
    }

    /**
     * TENANT MANAGEMENT
     */
    public function tenants()
    {
        $tenants = Tenant::with('events')
            ->orderBy('tenant_name')
            ->get();

        $events = Event::orderBy('event_name')->get();

        return view('dashboard.tenants', compact('tenants', 'events'));
    }

    /**
 * CATEGORY MANAGEMENT ✅ (FIX FINAL)
 */
public function categories()
{
    $categories = Category::orderBy('category_name')->get();
    $moods      = Mood::orderBy('mood_name')->get(); // ✅ TAMBAHAN FIX

    return view('dashboard.categories', compact('categories', 'moods'));
}

/**
 * MOOD MANAGEMENT ✅ (FIX)
 */
public function moods()
{
    $moods = Mood::orderBy('mood_name')->get();

    return view('dashboard.moods', compact('moods'));
}

/**
 * DELETE MOOD ✅ (FIX)
 */
public function deleteMood($id)
{
    $mood = Mood::findOrFail($id);

    // Optional: kalau ada relasi (category/menu), bisa dicek dulu
    // if ($mood->categories()->exists()) {
    //     return back()->with('error', 'Mood masih digunakan.');
    // }

    $mood->delete();

    return redirect()
        ->route('dashboard.moods')
        ->with('success', 'Mood berhasil dihapus.');
}

/**
 * UPDATE MOOD ✅ (FIX)
 */
public function updateMood(Request $request, $id)
{
    $request->validate([
        'mood_name'   => 'required|string|max:100',
        'description' => 'nullable|string',
    ]);

    $mood = Mood::findOrFail($id);

    $mood->update([
        'mood_name'   => $request->mood_name,
        'description' => $request->description,
    ]);

    return redirect()
        ->route('dashboard.moods')
        ->with('success', 'Mood berhasil diperbarui.');
}

}