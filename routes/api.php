<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopListController; // Import ShopListController
use Illuminate\Http\Request;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    // Route::get('/todos', [TodoController::class, 'index']); // Hapus atau komentari
    // Route::post('/todos', [TodoController::class, 'store']); // Hapus atau komentari
    // Route::delete('/todos/{todo}', [TodoController::class, 'destroy']); // Hapus atau komentari
    // Route::get('/todos/search', [TodoController::class, 'search']); // Hapus atau komentari

    Route::get('/shoplist', [ShopListController::class, 'index']);
    Route::post('/shoplist', [ShopListController::class, 'store']);
    Route::delete('/shoplist/{shopItem}', [ShopListController::class, 'destroy']);
    Route::get('/shoplist/search', [ShopListController::class, 'search']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::post('/profile', [ProfileController::class, 'update']);

    Route::post('/tes', function(Request $request){
        dd($request->all());
    });
});
