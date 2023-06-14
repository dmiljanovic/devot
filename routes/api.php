<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ExpenseController;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('jwtAuth')->group(function () {
    Route::resource('categories', CategoryController::class)->except(['create', 'edit']);
    Route::resource('expenses', ExpenseController::class)->except(['create', 'edit']);
});

# AUTH ROUTES
Route::prefix('auth')->name('auth.')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');// user
    Route::post('/login',  [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/logout', [AuthController::class, 'logout'])->middleware("jwtAuth");
    Route::get('/refresh', [AuthController::class, 'refresh'])->middleware("jwtAuth");
    Route::get('/user-profile', [AuthController::class, 'userProfile'])->middleware("jwtAuth");
});

Route::fallback(static function () {
    return response()->json(['message' => 'Route not found.'], Response::HTTP_NOT_FOUND);
})->name('fallback');
