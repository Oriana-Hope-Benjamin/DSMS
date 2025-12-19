<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController; // Ensure this path is correct
use App\Http\Controllers\Branches;
use App\Http\Controllers\CourseAddons;
use App\Http\Controllers\Courses;
use App\Http\Controllers\CourseSyllabus;
use App\Http\Controllers\Durations;
use App\Http\Controllers\Payments;
use App\Http\Controllers\Staff;
use App\Http\Controllers\StudentController;


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
Route::prefix('api')->group(function () {

    // Public routes for 'guest' users
    Route::middleware('guest')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });

    // Protected routes for authenticated users
    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/user', [AuthController::class, 'user']);
        Route::apiResource('courses', Courses::class);
        Route::apiResource('students', StudentController::class);
        Route::apiResource('course-addons', CourseAddons::class);
        Route::apiResource('staff', Staff::class);
        Route::apiResource('course-syllabus', CourseSyllabus::class);
        Route::get('/durations', [Durations::class, 'index']);
        Route::get('/payments', [Payments::class, 'index']);
        Route::post('/payments', [Payments::class, 'store']);
    });
    Route::middleware('auth')->group(function () {
        Route::get('/branches', [Branches::class, 'index']);
        Route::post('/branches', [Branches::class, 'create']);
        Route::put('/branches/{id}', [Branches::class, 'update']);
        Route::delete('/branches/{id}', [Branches::class, 'destroy']);
    });
});
