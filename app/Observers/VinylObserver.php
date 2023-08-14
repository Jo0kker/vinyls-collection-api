<?php

namespace App\Observers;

use App\Models\Vinyl;

class VinylObserver
{
    /**
     * Handle the Vinyl "created" event.
     */
    public function created(Vinyl $vinyl): void
    {
        //
    }

    /**
     * Handle the Vinyl "updated" event.
     */
    public function updated(Vinyl $vinyl): void
    {
        //
    }

    /**
     * Handle the Vinyl "deleted" event.
     */
    public function deleted(Vinyl $vinyl): void
    {
        //
    }

    /**
     * Handle the Vinyl "restored" event.
     */
    public function restored(Vinyl $vinyl): void
    {
        //
    }

    /**
     * Handle the Vinyl "force deleted" event.
     */
    public function forceDeleted(Vinyl $vinyl): void
    {
        //
    }
}
