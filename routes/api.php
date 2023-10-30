<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscogsController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\VerificationController;
use App\Rest\Controllers\CollectionsController;
use App\Rest\Controllers\CollectionVinylsController;
use App\Rest\Controllers\SearchesController;
use App\Rest\Controllers\TradesController;
use App\Rest\Controllers\UsersController;
use App\Rest\Controllers\VinylsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Lomkit\Rest\Facades\Rest;

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

Rest::resource('users', UsersController::class)->withSoftDeletes();
Rest::resource('vinyls', VinylsController::class);
Rest::resource('collections', CollectionsController::class);
Rest::resource('collectionVinyl', CollectionVinylsController::class);
Rest::resource('trades', TradesController::class);
Rest::resource('searches', SearchesController::class);

Route::get('stats/global', [StatsController::class, 'global']);

Route::post('discogs/search', [DiscogsController::class, 'search']);

Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::middleware('auth')->get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// api check health
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
