<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        ResetPassword::createUrlUsing(static function (User $user, string $token) {
            return env('APP_WEB') . '/reset-password?token=' . $token;
        });
    }
}
