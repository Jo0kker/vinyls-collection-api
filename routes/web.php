<?php

use App\Http\Controllers\WebAuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [WebAuthController::class, 'showLogin'])->name('login');
Route::post('login', [WebAuthController::class, 'login']);
Route::get('logout', [WebAuthController::class, 'logout']);
