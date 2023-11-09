<?php

namespace App\Rest\Resources;

use App\Models\Collection;
use App\Rest\Resource as RestResource;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Concerns\Resource\DisableAuthorizations;
use Lomkit\Rest\Concerns\Resource\DisableAuthorizationsCache;
use Lomkit\Rest\Concerns\Resource\DisableGates;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\HasMany;
use Lomkit\Rest\Relations\HasManyThrough;
use Lomkit\Rest\Relations\HasOne;

class CollectionResource extends RestResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = Collection::class;

    public function fields(RestRequest $request): array
    {
        return [
            'id',
            'name',
            'slug',
            'vinyl',
            'vinyls',
            'description',
            'created_at',
            'updated_at'
        ];
    }

    public function relations(RestRequest $request): array
    {
        return [
            HasOne::make('user', UserResource::class),
            HasMany::make('collectionVinyls', CollectionVinylResource::class),
            HasManyThrough::make('vinyls', VinylResource::class, CollectionVinylResource::class)
        ];
    }
    public function limits(RestRequest $request): array {
        return [
            1,2,3,4,5,6,7,8,9,
            10,
            25,
            50
        ];
    }
}
