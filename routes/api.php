<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectionVinylsController;
use App\Http\Controllers\SearchesController;
use App\Http\Controllers\TradesController;
use App\Http\Controllers\VinylsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Orion\Facades\Orion;

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
// Route for register user
Route::post('/register', [AuthController::class, 'register']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');

Orion::resource('vinyls', VinylsController::class);
Orion::resource('collectionVinyl', CollectionVinylsController::class);
Orion::resource('trades', TradesController::class);
Orion::resource('searches', SearchesController::class);

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

// api check health
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
