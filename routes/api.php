<?php

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

Orion::resource('vinyls', VinylsController::class);

Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});


// api check health
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
