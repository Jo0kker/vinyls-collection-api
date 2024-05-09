<?php

namespace App\Rest\Resources;

use App\Models\CollectionVinyl;
use App\Rest\Resource as RestResource;
use App\Rules\UniqueVinylInCollection;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\HasMany;
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
            'format',
            'discog_id',
            'comment',
        ];
    }

    public function relations(RestRequest $request): array
    {
        return [
            HasOne::make('vinyl', VinylResource::class),
            HasOne::make('collection', CollectionResource::class),
            HasMany::make('medias', CollectionVinylMediaResource::class),
        ];
    }

    public function rules(RestRequest $request): array
    {
        $attributes = (array) $request->input('mutate')[0]['attributes'];

        return [
            'vinyl_id' => [
                'exists:vinyls,id',
                new UniqueVinylInCollection($attributes),
            ],
        ];
    }

    public function limits(RestRequest $request): array
    {
        return [
            1, 2, 3, 4, 5, 6, 7, 8, 9,
            10,
            12,
            24,
            25,
            50,
        ];
    }
}
