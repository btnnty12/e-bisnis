<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\DashboardController;
use App\Models\Event;

// Public Routes
Route::get('/', function () {
    return view('landing');
})->name('landing');

// Auth Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'webLogin'])->name('login.post');
Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');

Route::get('/home', function () {

    $stats = [
        'total_menus' => \App\Models\Menu::count(),
        'total_tenants' => \App\Models\Tenant::count(),
        'total_users' => \App\Models\User::count(),
        'total_categories' => \App\Models\Category::count(),
        'total_moods' => \App\Models\Mood::count(),
    ];

    $menus = \App\Models\Menu::with(['tenant','category'])->latest()->get();
    $moods = \App\Models\Mood::orderBy('id')->get();
    $categories = \App\Models\Category::with('mood')->orderBy('id')->get();
    $events = Event::orderBy('event_name')->get();
    $tenants = \App\Models\Tenant::orderBy('tenant_name')->get();

    return view('home', compact(
        'stats',
        'menus',
        'moods',
        'categories',
        'events',
        'tenants'
    ));
})->name('home');

// Dev-only debug route to inspect auth/session/role (only when APP_DEBUG=true)
if (env('APP_DEBUG', false)) {
    Route::get('/debug-auth', function () {
        return [
            'auth' => auth()->check(),
            'user' => auth()->user(),
            'user_role' => auth()->user()?->role,
            'session_id' => session()->getId(),
        ];
    })->name('debug.auth');
}

// Individual mood shortcuts removed — the dynamic route `mood.show` handles all mood slugs.

// Full mood page (dynamic)
Route::get('/mood/{slug}', function ($slug) {
    // map slug back to mood_name (replace dash with space and compare case-insensitive)
    $name = str_replace('-', ' ', $slug);
    $mood = \App\Models\Mood::whereRaw('LOWER(mood_name) = ?', [strtolower($name)])->firstOrFail();

    $menus = \App\Models\Menu::whereHas('category', function ($q) use ($mood) {
        $q->where('mood_id', $mood->id);
    })->with(['tenant', 'category'])->orderBy('created_at', 'desc')->get();

    // Categories belonging to this mood (for filters)
    $categories = \App\Models\Category::where('mood_id', $mood->id)->orderBy('category_name')->get();

    // Small popular subset (first 6 menus)
    $popular = $menus->take(6);

    return view('mood', compact('mood', 'menus', 'categories', 'popular'));
})->name('mood.show');

// Public interaction endpoint for web (customers that don't login)
Route::post('/interactions/public', [\App\Http\Controllers\InteractionController::class, 'storePublic'])->name('interactions.public.store');

// Dashboard Routes - Only for admin and tenant
Route::middleware(['auth', 'role:admin,tenant'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/menus', [DashboardController::class, 'menus'])->name('dashboard.menus');
    Route::post('/dashboard/menus', [DashboardController::class, 'storeMenu'])->name('dashboard.menus.store');
    Route::put('/dashboard/menus/{id}', [DashboardController::class, 'updateMenu'])->name('dashboard.menus.update');
    Route::delete('/dashboard/menus/{id}', [DashboardController::class, 'deleteMenu'])->name('dashboard.menus.delete');

    Route::get('/dashboard/categories', [DashboardController::class, 'categories'])->name('dashboard.categories');
    Route::post('/dashboard/categories', [DashboardController::class, 'storeCategory'])->name('dashboard.categories.store');
    Route::put('/dashboard/categories/{id}', [DashboardController::class, 'updateCategory'])->name('dashboard.categories.update');
    Route::delete('/dashboard/categories/{id}', [DashboardController::class, 'deleteCategory'])->name('dashboard.categories.delete');

    Route::get('/dashboard/moods', [DashboardController::class, 'moods'])->name('dashboard.moods');
    Route::post('/dashboard/moods', [DashboardController::class, 'storeMood'])->name('dashboard.moods.store');
    Route::put('/dashboard/moods/{id}', [DashboardController::class, 'updateMood'])->name('dashboard.moods.update');
    Route::delete('/dashboard/moods/{id}', [DashboardController::class, 'deleteMood'])->name('dashboard.moods.delete');

    // Statistics Routes - Only for admin and tenant
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/before-after', [StatisticsController::class, 'beforeAfterWeb'])->name('statistics.before-after');
    Route::get('/statistics/per-event', [StatisticsController::class, 'perEventWeb'])->name('statistics.per-event');
});
