<?php

namespace App\Rest\Resources;

use App\Models\User;
use App\Models\Vinyl;
use App\Rest\Resource;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\BelongsToMany;
use Lomkit\Rest\Relations\HasMany;
use Lomkit\Rest\Relations\HasManyThrough;

class VinylResource extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = Vinyl::class;

    public function fields(RestRequest $request): array
    {
        return [
            'id',
            'title',
            'artist',
            'genre',
            'image',
            'track_list',
            'released',
            'provenance',
            'discog_id',
            'discog_url',
            'discog_videos',
            'created_at',
            'updated_at'
        ];
    }

    public function relations(RestRequest $request): array
    {
        return [
            HasMany::make('trades', TradeResource::class),
            HasMany::make('searches', SearchResource::class),
            BelongsToMany::make('traders', UserResource::class)->withPivotFields(['description']),
            BelongsToMany::make('searchers', UserResource::class)->withPivotFields(['description']),
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
