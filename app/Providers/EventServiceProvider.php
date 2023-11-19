<?php

namespace App\Providers;

use App\Models\Collection;
use App\Models\CollectionVinyl;
use App\Models\Search;
use App\Models\Trade;
use App\Observers\CollectionObserver;
use App\Observers\CollectionVinylObserver;
use App\Observers\SearchObserver;
use App\Observers\TradeObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    protected $observers = [
        Collection::class => [
            CollectionObserver::class,
        ],
        Search::class => [
            SearchObserver::class,
        ],
        Trade::class => [
            TradeObserver::class,
        ],
        CollectionVinyl::class => [
            CollectionVinylObserver::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
