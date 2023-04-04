<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\CollectionUserController;
use App\Http\Controllers\CollectionVinylController;
use App\Http\Controllers\CollectionVinylsController;
use App\Http\Controllers\DiscogsController;
use App\Http\Controllers\SearchesController;
use App\Http\Controllers\TradesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\UsersSearchesController;
use App\Http\Controllers\UserTradesController;
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

Route::middleware('auth')->get('/users/me', function (Request $request) {
    return $request->user();
});

Orion::resource('users', UsersController::class);
Orion::resource('vinyls', VinylsController::class);
Orion::resource('collections', CollectionsController::class);
Orion::resource('collectionVinyl', CollectionVinylsController::class);
Orion::resource('trades', TradesController::class);
Orion::resource('searches', SearchesController::class);
Orion::hasManyResource('users', 'collections', CollectionUserController::class);
Orion::hasManyResource('collections', 'collectionVinyl', CollectionVinylController::class);
Orion::hasManyResource('users', 'trades', UserTradesController::class);
Orion::hasManyResource('users', 'searches', UsersSearchesController::class);

Route::post('discogs/search', [DiscogsController::class, 'search']);

// api check health
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
