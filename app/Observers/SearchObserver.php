<?php

namespace App\Observers;

use App\Models\Search;
use Illuminate\Support\Facades\Auth;

class SearchObserver
{
    /**
     * Handle the Search "created" event.
     */
    public function creating(Search $search): void
    {
        if (!$search->user_id) {
            $search->user()->associate(Auth::user());
        }
    }

    /**
     * Handle the Search "updated" event.
     */
    public function updated(Search $search): void
    {
        //
    }

    /**
     * Handle the Search "deleted" event.
     */
    public function deleted(Search $search): void
    {
        //
    }

    /**
     * Handle the Search "restored" event.
     */
    public function restored(Search $search): void
    {
        //
    }

    /**
     * Handle the Search "force deleted" event.
     */
    public function forceDeleted(Search $search): void
    {
        //
    }
}
