<?php

namespace App\Rest\Resources;

use App\Models\CollectionVinyl;
use App\Rest\Resource as RestResource;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\BelongsTo;
use Lomkit\Rest\Relations\HasOne;

class CollectionVinylResource extends RestResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = CollectionVinyl::class;

    public function fields(RestRequest $request): array
    {
        return [
            'id',
            'collection_id',
            'vinyl_id',
            'format_vinyl_id',
        ];
    }

    public function relations(RestRequest $request): array
    {
        return [
            HasOne::make('vinyl', VinylResource::class),
            HasOne::make('collection', CollectionResource::class),
            HasOne::make('format', FormatVinylResource::class)
        ];
    }

    public function limits(RestRequest $request): array
    {
        return [
            1,2,3,4,5,6,7,8,9,
            10,
            25,
            50
        ];
    }
}
