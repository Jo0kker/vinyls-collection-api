<?php

use Illuminate\Support\Facades\Http;
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
//Route::get('/test', function () {
//    $response = Http::asForm()->post('http://localhost:8080/oauth/token', [
//        "grant_type" => "password",
//        "client_id" => "2",
//        "client_secret" => "F0XckSdexv1TDvYLZeC4BWWTou351rmaM0ViDO6G",
//        "username" => "test@example.com",
//        "password" => "password",
//        "scope" => "",
//    ]);
//
//    return $response->json();
//});
