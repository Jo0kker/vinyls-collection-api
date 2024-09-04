<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\MediaPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
        Passport::enablePasswordGrant();
        ResetPassword::createUrlUsing(static function (User $user, string $token) {
            return config('app.url').'/reset-password?token='.$token;
        });

        Gate::policy(Media::class, MediaPolicy::class);
    }
}
