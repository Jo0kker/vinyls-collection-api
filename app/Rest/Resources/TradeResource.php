<?php

namespace App\Rest\Resources;

use App\Models\Trade;
use App\Rest\Resource as RestResource;
use App\Rules\UniqueVinyl;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\BelongsTo;
use Lomkit\Rest\Relations\HasMany;

class TradeResource extends RestResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = Trade::class;

    public function fields(RestRequest $request): array
    {
        return [
            'id',
            'description',
            'vinyl_id',
            'format',
        ];
    }

    public function rules(RestRequest $request): array
    {
        $attributes = (array) $request->input('mutate')[0]['attributes'];

        return [
            'vinyl_id' => [
                'exists:vinyls,id',
                new UniqueVinyl($attributes, 'trades'),
            ],
        ];
    }

    public function relations(RestRequest $request): array
    {
        return [
            BelongsTo::make('vinyl', VinylResource::class),
            BelongsTo::make('user', UserResource::class),
            HasMany::make('medias', TradeMediaResource::class),
        ];
    }

    public function scopes(RestRequest $request): array
    {
        return [];
    }

    public function limits(RestRequest $request): array
    {
        return [
            1, 2, 3, 4, 5, 6, 7, 8, 9,
            10,
            12,
            25,
            50,
        ];
    }
}
