<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DiscogsController;
use App\Http\Controllers\DiscogsAuthController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\VinylsController as ControllersVinylsController;
use App\Http\Controllers\UsersController as ControllersUsersController;
use App\Rest\Controllers\CollectionsController;
use App\Rest\Controllers\CollectionVinylsController;
use App\Rest\Controllers\FormatVinylsController;
use App\Rest\Controllers\SearchesController;
use App\Rest\Controllers\TradesController;
use App\Rest\Controllers\UsersController;
use App\Rest\Controllers\VinylsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Lomkit\Rest\Facades\Rest;
use Illuminate\Support\Facades\URL;

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

Route::prefix('auth/discogs')->group(function () {
    Route::get('redirect', [DiscogsAuthController::class, 'redirect'])->name('api.auth.discogs.redirect');
    Route::get('callback', [DiscogsAuthController::class, 'callback'])->name('api.auth.discogs.callback');
});

Route::get('auth/discogs', function (Request $request) {
    $user = auth()->guard('api')->user();
    if (!$user) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    if ($user->discogs_id) {
        return response()->json(['error' => 'Votre compte est déjà lié à Discogs'], 400);
    }

    $url = URL::temporarySignedRoute(
        'api.auth.discogs.redirect',
        now()->addMinutes(5),
        [
            'nonce' => uniqid('', true),
            'user_id' => $user->id
        ]
    );
    return response()->json(['url' => $url]);
})->middleware('auth:api');

Route::middleware(['auth', 'throttle:1,30'])->post('/discogs/import', [DiscogsController::class, 'importCollections']);
Route::group(['middleware' => ['auth']], function () {
    Route::post('/vinyls', [ControllersVinylsController::class, 'store']);
    Route::put('/vinyls/discog/{id}', [ControllersVinylsController::class, 'updateDiscoq']);
    Route::post('/users/profile', [ControllersUsersController::class, 'updateProfile']);
    Route::post('/users/change-password', [AuthController::class, 'changePassword']);
});

// route group for media
Route::group(['prefix' => 'media'], function () {
    Route::post('/process', [UploadController::class, 'process']);
    Route::post('/revert', [UploadController::class, 'revert']);
    Route::post('/restore', [UploadController::class, 'restore']);
    Route::get('/load/{id}', [UploadController::class, 'load']);
    Route::get('/fetch', [UploadController::class, 'fetch']);
    Route::delete('/delete', [UploadController::class, 'delete']);
});

Route::middleware('auth')->get('/users/me', function (Request $request) {
    $user = $request->user();

    // On récupère les noms des permissions de l'utilisateur
    $permissions = $user->getAllPermissions()->pluck('name');

    // On convertit l'utilisateur en tableau sans les clés 'permissions' et 'roles'
    $userData = $user->toArray();
    unset($userData['permissions'], $userData['roles']);

    // On ajoute la clé 'ability' avec les noms des permissions
    $userData['ability'] = $permissions;

    // On retourne les données de l'utilisateur modifiées
    return response()->json($userData);
});

// add route to add discog vinyl
Route::middleware('auth')->post('/vinyls/discogs', [ControllersVinylsController::class, 'addDiscogs']);

Rest::resource('users', UsersController::class)->withSoftDeletes();
Rest::resource('vinyls', VinylsController::class);
Rest::resource('collections', CollectionsController::class);
Rest::resource('collectionVinyl', CollectionVinylsController::class);
Rest::resource('trades', TradesController::class);
Rest::resource('searches', SearchesController::class);
Rest::resource('formats', FormatVinylsController::class);

Route::get('stats/global', [StatsController::class, 'global']);

Route::post('discogs/search', [DiscogsController::class, 'search']);

Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::middleware('auth')->get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

// api check health
Route::get('/health', function () {
    return response()->json(['status' => 'ok']);
});
