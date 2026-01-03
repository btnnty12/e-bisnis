<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\InteractionController;

use App\Models\Event;

// =====================
// Public
// =====================
Route::get('/', fn () => view('landing'))->name('landing');

// =====================
// Auth
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
        'interactions as total_clicks' => fn ($q) =>
            $q->where('type', 'mood_click')
    ])->get();

    return view('home', [
        'stats'      => $stats,
        'menus'      => \App\Models\Menu::with(['tenant','category'])->latest()->get(),
        'moods'      => $moods,
        'categories' => \App\Models\Category::with('mood')->orderBy('id')->get(),
        'events'     => Event::orderBy('event_name')->get(),
        'tenants'    => \App\Models\Tenant::orderBy('tenant_name')->get(),
    ]);

})->name('home');

// =====================
// Mood Pages
// =====================
Route::get('/mood/{slug}', function ($slug) {

    $name = str_replace('-', ' ', $slug);

    $mood = \App\Models\Mood::whereRaw(
        'LOWER(mood_name) = ?',
        [strtolower($name)]
    )->firstOrFail();

    $menus = \App\Models\Menu::whereHas('category', fn ($q) =>
        $q->where('mood_id', $mood->id)
    )->with(['tenant','category'])->latest()->get();

    return view('mood', [
        'mood'       => $mood,
        'menus'      => $menus,
        'categories' => \App\Models\Category::where('mood_id', $mood->id)->orderBy('category_name')->get(),
        'popular'    => $menus->take(6),
    ]);

})->name('mood.show');

Route::get('/explore', function () {
    return view('explore', [
        'moods' => \App\Models\Mood::with('categories.menus.tenant')->get()
    ]);
})->name('mood.explore');

// =====================
// AJAX
// =====================
Route::get('/explore/ajax/{moodId}', [MoodController::class, 'ajaxMenus'])->name('mood.ajax');

// =====================
// Interaction & Rating
// =====================
Route::post('/interactions/public', [InteractionController::class, 'storePublic'])
    ->name('interactions.public.store');

Route::post('/rating', [RatingController::class, 'store'])
    ->middleware('auth')
    ->name('rating.store');

// =====================
// 🔥 STATISTICS (PUBLIC / AJAX)
// =====================
Route::get('/statistics/per-event', [StatisticsController::class, 'perEventWeb'])
    ->name('statistics.per-event');

Route::get('/statistics/before-after', [StatisticsController::class, 'beforeAfterWeb'])
    ->name('statistics.before-after');

// =====================
// Dashboard (Admin & Tenant)
// =====================
Route::middleware(['auth', 'role:admin,tenant'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Tenant
    Route::resource('/dashboard/tenants', TenantController::class)
        ->except(['create','edit']);

    // Menu
    Route::get('/dashboard/menus', [DashboardController::class, 'menus'])->name('dashboard.menus');
    Route::post('/dashboard/menus', [DashboardController::class, 'storeMenu'])->name('dashboard.menus.store');
    Route::put('/dashboard/menus/{id}', [DashboardController::class, 'updateMenu'])->name('dashboard.menus.update');
    Route::delete('/dashboard/menus/{id}', [DashboardController::class, 'deleteMenu'])->name('dashboard.menus.delete');

    // Category
    Route::get('/dashboard/categories', [DashboardController::class, 'categories'])->name('dashboard.categories');
    Route::post('/dashboard/categories', [DashboardController::class, 'storeCategory'])->name('dashboard.categories.store');
    Route::put('/dashboard/categories/{id}', [DashboardController::class, 'updateCategory'])->name('dashboard.categories.update');
    Route::delete('/dashboard/categories/{id}', [DashboardController::class, 'deleteCategory'])->name('dashboard.categories.delete');

    // Mood
    Route::get('/dashboard/moods', [DashboardController::class, 'moods'])->name('dashboard.moods');
    Route::post('/dashboard/moods', [DashboardController::class, 'storeMood'])->name('dashboard.moods.store');
    Route::put('/dashboard/moods/{id}', [DashboardController::class, 'updateMood'])->name('dashboard.moods.update');
    Route::delete('/dashboard/moods/{id}', [DashboardController::class, 'deleteMood'])->name('dashboard.moods.delete');

    // Statistics page (VIEW saja, aman pakai auth)
    Route::get('/statistics', [StatisticsController::class, 'index'])
        ->name('statistics.index');
});