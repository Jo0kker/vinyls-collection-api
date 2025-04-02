<?php

namespace App\Rest\Resources;

use App\Models\Collection;
use App\Rest\Resource as RestResource;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\BelongsToMany;
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

    public function fields(RestRequest $request): array
    {
        return [
            'id',
            'name',
            'slug',
            'description',
            'created_at',
            'updated_at',
            'vinyls_count',
        ];
    }

    public function relations(RestRequest $request): array
    {
        return [
            HasOne::make('user', UserResource::class),
            HasMany::make('collectionVinyls', CollectionVinylResource::class),
            BelongsToMany::make('vinyls', VinylResource::class)
                ->withPivotFields(['format_vinyl_id']),
        ];
    }

    public function limits(RestRequest $request): array
    {
        return [
            1, 2, 3, 4, 5, 6, 7, 8, 9,
            10,
            12,
            25,
            50,
            100,
        ];
    }

    public function scopes(RestRequest $request): array
    {
        return [
            'orderByVinylsCount'
        ];
    }

    public function sortableBy(RestRequest $request): array
    {
        return [
            'created_at',
            'updated_at',
        ];
    }

    public function rules(RestRequest $request): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:collections,name,NULL,id,user_id,' . auth()->id(),
            ],
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.unique' => 'You already have a collection with this name.',
        ];
    }
}
