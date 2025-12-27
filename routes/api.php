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
| Route ini bisa diakses tanpa login.
*/

Route::post('/register', [AuthController::class, 'register']); // Register user baru
Route::post('/login', [AuthController::class, 'login']);       // Login dan dapatkan token

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Sanctum Auth)
|--------------------------------------------------------------------------
| Route ini membutuhkan token Bearer dari login.
*/

Route::get('/recommendation/mood/{mood_id}',
    [RecommendationController::class, 'recommendByMood']
);

Route::post('/interactions',
    [InteractionController::class, 'storePublic']
);

Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::get('/me', [AuthController::class, 'me']);          // Ambil data user login
    Route::post('/logout', [AuthController::class, 'logout']); // Logout

    // Resources
    Route::apiResource('moods', MoodController::class);          // CRUD Mood
    Route::apiResource('categories', CategoryController::class);     // CRUD Category
    Route::apiResource('tenants', TenantController::class);      // CRUD Tenant
    Route::apiResource('menus', MenuController::class);          // CRUD Menu
    Route::apiResource('events', EventController::class);        // CRUD Event

    // Interactions (hanya index, store, show, destroy)
    Route::apiResource('interactions', InteractionController::class)
        ->only(['index', 'store', 'show', 'destroy']);

    // Recommendations
    Route::apiResource('recommendations', RecommendationController::class);

    // Statistics
    Route::get('/statistics/before-after', [StatisticsController::class, 'beforeAfter']);
    Route::get('/statistics/per-event', [StatisticsController::class, 'perEvent']);
});