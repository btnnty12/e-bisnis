<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MoodController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\InteractionController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\EventController;

/*
|--------------------------------------------------------------------------
| Public Routes (No Auth Required)
|--------------------------------------------------------------------------
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Recommendation (public)
Route::get(
    '/recommendation/mood/{mood_id}',
    [RecommendationController::class, 'recommendByMood']
);

// Interaction public (page view, click, dll)
Route::post(
    '/interactions',
    [InteractionController::class, 'storePublic']
);

/*
|--------------------------------------------------------------------------
| 🔥 PUBLIC STATISTICS (FIX)
|--------------------------------------------------------------------------
| Statistik = data publik (page views / interactions)
*/

Route::get('/statistics/before-after', [StatisticsController::class, 'beforeAfter']);
Route::get('/statistics/per-event', [StatisticsController::class, 'perEvent']);
Route::get('/statistics/before-after-mood', [StatisticsController::class, 'beforeAfterMood']);

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Sanctum Auth)
|--------------------------------------------------------------------------
*/

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // Resources (ADMIN / TENANT)
    Route::apiResource('moods', MoodController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('tenants', TenantController::class);
    Route::apiResource('menus', MenuController::class);
    Route::apiResource('events', EventController::class);

    // Interactions (protected)
    Route::apiResource('interactions', InteractionController::class)
        ->only(['index', 'store', 'show', 'destroy']);

    // Recommendations
    Route::apiResource('recommendations', RecommendationController::class);
});