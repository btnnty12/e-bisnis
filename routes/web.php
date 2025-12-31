<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\InteractionController;
use App\Models\Event;
use App\Models\Rating;

// =====================
// Public Routes
// =====================
Route::get('/', function () {
    return view('landing');
})->name('landing');

// =====================
// Auth Routes
// =====================
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'webLogin'])->name('login.post');
Route::post('/logout', [AuthController::class, 'webLogout'])->name('logout');

// =====================
// Home
// =====================
Route::get('/home', function () {

    $stats = [
        'total_menus'      => \App\Models\Menu::count(),
        'total_tenants'    => \App\Models\Tenant::count(),
        'total_users'      => \App\Models\User::count(),
        'total_categories' => \App\Models\Category::count(),
        'total_moods'      => \App\Models\Mood::count(),
        'avg_rating'       => round(\App\Models\Rating::avg('rating'), 1) ?? 0,
    ];

    $moods = \App\Models\Mood::withCount([
        'interactions as total_clicks' => function ($q) {
            $q->where('type', 'mood_click');
        }
    ])->get();

    $menus = \App\Models\Menu::with(['tenant','category'])->latest()->get();
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

// =====================
// Debug
// =====================
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

// =====================
// Mood Detail Page
// =====================
Route::get('/mood/{slug}', function ($slug) {
    $name = str_replace('-', ' ', $slug);
    $mood = \App\Models\Mood::whereRaw(
        'LOWER(mood_name) = ?',
        [strtolower($name)]
    )->firstOrFail();

    $menus = \App\Models\Menu::whereHas('category', function ($q) use ($mood) {
        $q->where('mood_id', $mood->id);
    })
    ->with(['tenant', 'category'])
    ->orderBy('created_at', 'desc')
    ->get();

    $categories = \App\Models\Category::where('mood_id', $mood->id)
        ->orderBy('category_name')
        ->get();

    $popular = $menus->take(6);

    return view('mood', compact('mood', 'menus', 'categories', 'popular'));
})->name('mood.show');

// =====================
// Explore Page
// =====================
Route::get('/explore', function() {
    $moods = \App\Models\Mood::with('categories.menus.tenant')->get();
    return view('explore', compact('moods'));
})->name('mood.explore');

// =====================
// AJAX Endpoint
// =====================
Route::get('/explore/ajax/{moodId}', [MoodController::class, 'ajaxMenus'])->name('mood.ajax');

// =====================
// Public interaction endpoint
// =====================
Route::post('/interactions/public', [InteractionController::class, 'storePublic'])
    ->name('interactions.public.store');

// =====================
// ⭐ RATING (SEMUA USER LOGIN, TERMASUK CUSTOMER)
// =====================
Route::post('/rating', [RatingController::class, 'store'])
    ->middleware('auth')
    ->name('rating.store');

// =====================
// Tenant-Event AJAX Route (Baru)
// =====================
Route::post('/tenant-event', [TenantController::class, 'storeTenantEvent'])
    ->middleware('auth')
    ->name('tenant.event.store');

// =====================
// Dashboard Routes (Admin & Tenant ONLY)
// =====================
Route::middleware(['auth', 'role:admin,tenant'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Tenants
    Route::get('/dashboard/tenants', [TenantController::class, 'index'])->name('dashboard.tenants');
    Route::post('/dashboard/tenants', [TenantController::class, 'store'])->name('dashboard.tenants.store');
    Route::put('/dashboard/tenants/{id}', [TenantController::class, 'update'])->name('dashboard.tenants.update');
    Route::delete('/dashboard/tenants/{id}', [TenantController::class, 'destroy'])->name('dashboard.tenants.delete');

    // Menus
    Route::get('/dashboard/menus', [DashboardController::class, 'menus'])->name('dashboard.menus');
    Route::post('/dashboard/menus', [DashboardController::class, 'storeMenu'])->name('dashboard.menus.store');
    Route::put('/dashboard/menus/{id}', [DashboardController::class, 'updateMenu'])->name('dashboard.menus.update');
    Route::delete('/dashboard/menus/{id}', [DashboardController::class, 'deleteMenu'])->name('dashboard.menus.delete');

    // Categories
    Route::get('/dashboard/categories', [DashboardController::class, 'categories'])->name('dashboard.categories');
    Route::post('/dashboard/categories', [DashboardController::class, 'storeCategory'])->name('dashboard.categories.store');
    Route::put('/dashboard/categories/{id}', [DashboardController::class, 'updateCategory'])->name('dashboard.categories.update');
    Route::delete('/dashboard/categories/{id}', [DashboardController::class, 'deleteCategory'])->name('dashboard.categories.delete');

    // Moods
    Route::get('/dashboard/moods', [DashboardController::class, 'moods'])->name('dashboard.moods');
    Route::post('/dashboard/moods', [DashboardController::class, 'storeMood'])->name('dashboard.moods.store');
    Route::put('/dashboard/moods/{id}', [DashboardController::class, 'updateMood'])->name('dashboard.moods.update');
    Route::delete('/dashboard/moods/{id}', [DashboardController::class, 'deleteMood'])->name('dashboard.moods.delete');

    // Statistics
    Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/before-after', [StatisticsController::class, 'beforeAfterWeb'])->name('statistics.before-after');
    Route::get('/statistics/per-event', [StatisticsController::class, 'perEventWeb'])->name('statistics.per-event');
});