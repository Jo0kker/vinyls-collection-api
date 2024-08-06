<?php

namespace App\Policies;

use App\Models\CollectionVinyl;
use App\Models\Trade;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaPolicy
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
    public function view(User $user, Media $media): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Request $request): bool
    {
        $modelType = $request->input('model_type');
        $modelId = $request->input('model_id');

        $authorizedModelTypes = [CollectionVinyl::class, Trade::class];
        if (!in_array($modelType, $authorizedModelTypes, true)) {
            return false;
        }

        if ($modelType === CollectionVinyl::class) {
            $model = CollectionVinyl::find($modelId);
            $owner = $model->collection->user;
        } else {
            $model = Trade::find($modelId);
            $owner = $model->user;
        }

        // check if the collection or trade has already a max 3 media
        if ($model->media->count() >= 3) {
            return false;
        }

        return $user->id === $owner->id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Media $media): bool
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Media $media): bool
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Media $media): bool
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Media $media): bool
    {
        return true;
    }
}
