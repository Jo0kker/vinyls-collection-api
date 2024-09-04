<?php

namespace App\Observers;

use App\Models\Collection;
use App\Models\CollectionVinyl;
use Illuminate\Support\Facades\Auth;
use Str;

class CollectionObserver
{
    public function creating(Collection $collection)
    {
        if (! $collection->slug) {
            $collection->slug = Str::slug($collection->name);
        }

        if (! $collection->user_id) {
            $collection->user()->associate(Auth::user());
        }
    }

    /**
     * Handle the Collection "created" event.
     */
    public function created(Collection $collection): void
    {
        //
    }

    /**
     * Handle the Collection "updated" event.
     */
    public function updated(Collection $collection): void
    {
        //
    }

    /**
     * Handle the Collection "deleted" event.
     */
    public function deleted(Collection $collection): void
    {
        // soft delete related collection_vinyls
        $collection->collectionVinyls()->delete();
    }

    /**
     * Handle the Collection "restored" event.
     */
    public function restored(Collection $collection): void
    {
        //
    }

    /**
     * Handle the Collection "force deleted" event.
     */
    public function forceDeleted(Collection $collection): void
    {
        //
    }
}
