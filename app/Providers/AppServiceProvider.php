<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\MediaPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;
use Illuminate\Support\Facades\Mail;

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

        Mail::extend('brevo', function () {
            return (new BrevoTransportFactory())->create(
                new Dsn(
                    'brevo+api',
                    'default',
                    config('services.brevo.api_key')
                )
            );
        });

        Gate::policy(Media::class, MediaPolicy::class);
    }
}
