<?php

namespace App\Policies;

use App\Models\CollectionVinylMedia;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CollectionVinylMediaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CollectionVinylMedia $collectionVinylMedia): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, CollectionVinylMedia $collectionVinylMedia): bool
    {
        return $user->collections()->contains($collectionVinylMedia->collectionVinyl->collection);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CollectionVinylMedia $collectionVinylMedia): bool
    {
        return $collectionVinylMedia->collectionVinyl->collection->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CollectionVinylMedia $collectionVinylMedia): bool
    {
        return $collectionVinylMedia->collectionVinyl->collection->user_id === $user->id;
    }
}
