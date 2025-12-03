<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MoodController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// AUTH (tanpa login)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ROUTE YANG BUTUH LOGIN (SANCTUM)
Route::middleware('auth:sanctum')->group(function () {

    // Current user
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

    // MOODS CRUD (index, store, show, update, destroy)
    Route::apiResource('moods', MoodController::class);

    // NEXT:
    // Route::apiResource('categories', CategoryController::class);
    // Route::apiResource('tenants', TenantController::class);
    // Route::apiResource('menus', MenuController::class);
    // Route::apiResource('interactions', InteractionController::class);
    // Route::apiResource('recommendations', RecommendationController::class);

});