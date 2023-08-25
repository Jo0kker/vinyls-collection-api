<?php

namespace App\Rest\Resources;

use App\Models\Collection;
use App\Rest\Resource as RestResource;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\HasMany;
use Lomkit\Rest\Relations\HasOne;

class CollectionResource extends RestResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = Collection::class;

    public function exposedFields(RestRequest $request): array
    {
        return [
            'id'
        ];
    }

    public function relations(RestRequest $request): array
    {
        return [
            HasOne::make('user', UserResource::class),
            HasMany::make('collection_vinyls', CollectionVinylResource::class)
        ];
    }

    public function exposedScopes(RestRequest $request): array {
        return [];
    }

    public function exposedLimits(RestRequest $request): array {
        return [
            1,2,3,4,5,6,7,8,9,
            10,
            25,
            50
        ];
    }
}
