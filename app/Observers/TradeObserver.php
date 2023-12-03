<?php

namespace App\Observers;

use App\Models\Search;
use App\Models\Trade;
use Illuminate\Support\Facades\Auth;

class TradeObserver
{
    /**
     * Handle the Search "created" event.
     */
    public function creating(Trade $trade): void
    {
        if (! $trade->user_id) {
            $trade->user()->associate(Auth::user());
        }
    }

    /**
     * Handle the Search "updated" event.
     */
    public function updated(Trade $trade): void
    {
        //
    }

    /**
     * Handle the Search "deleted" event.
     */
    public function deleted(Trade $trade): void
    {
        //
    }

    /**
     * Handle the Search "restored" event.
     */
    public function restored(Trade $trade): void
    {
        //
    }

    /**
     * Handle the Search "force deleted" event.
     */
    public function forceDeleted(Trade $trade): void
    {
        //
    }
}
