<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\StudentApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Student API Routes
Route::prefix('student')->group(function () {
    // Public routes (no authentication required)
    Route::post('/login', [StudentApiController::class, 'login']);

    // Protected routes (require authentication)
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/profile', [StudentApiController::class, 'profile']);
        Route::get('/schedule', [StudentApiController::class, 'schedule']);
        Route::get('/schedule/data', [StudentApiController::class, 'scheduleData']);
        Route::get('/schedule/pdf', [StudentApiController::class, 'schedulePdf']);
        Route::post('/logout', [StudentApiController::class, 'logout']);

        // Push notifications routes
        Route::post('/push-token', [StudentApiController::class, 'savePushToken']);
        Route::post('/notifications/settings', [StudentApiController::class, 'updateNotificationSettings']);
    });
});
