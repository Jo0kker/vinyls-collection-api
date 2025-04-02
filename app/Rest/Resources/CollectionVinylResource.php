<?php

namespace App\Rest\Resources;

use App\Models\CollectionVinyl;
use App\Rest\Resource as RestResource;
use App\Rules\UniqueVinylInCollection;
use App\Models\Collection;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Http\Requests\MutateRequest;
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
            'user_id',
            'format',
            'discog_id',
            'comment',
            'description',
            'vinyl.title'
        ];
    }

    public function relations(RestRequest $request): array
    {
        return [
            HasOne::make('vinyl', VinylResource::class),
            HasOne::make('collection', CollectionResource::class),
            HasOne::make('user', UserResource::class),
            HasMany::make('media', MediaResource::class),
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

    public function mutating(MutateRequest $request, array $requestBody, Model $model): void
    {
        if ($requestBody['operation'] === 'create') {
            $collection = Collection::findOrFail($requestBody['attributes']['collection_id']);
            $model->user_id = $collection->user_id;
        }
    }

    public function limits(RestRequest $request): array
    {
        return [
            1,
            2,
            3,
            4,
            5,
            6,
            7,
            8,
            9,
            10,
            12,
            24,
            25,
            50,
            100,
        ];
    }

    public function scopes(RestRequest $request): array
    {
        return [
            'orderByVinylTitle'
        ];
    }

    public function sortableBy(RestRequest $request): array
    {
        return [
            'id',
            'collection_id',
            'vinyl_id',
            'user_id',
            'format',
            'discog_id',
            'comment',
            'description'
        ];
    }
}
