<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; // Ensure this path is correct

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return ['message' => 'Driving School API is accessible.'];
});

// --- STATEFUL AUTHENTICATION ROUTES ---
// We prefix with 'api' to keep the URLs consistent for the frontend
Route::prefix('auth')->group(function () {
    
    // Public routes for 'guest' users
    Route::middleware('guest')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Protected routes for authenticated users
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
    });

    // --- Add other protected API routes here ---
    // e.g., Route::post('/lessons', [LessonController::class, 'store'])->middleware('auth');
});
