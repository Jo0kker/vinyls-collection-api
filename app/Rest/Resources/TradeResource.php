<?php

namespace App\Rest\Resources;

use App\Models\Trade;
use App\Rest\Resource as RestResource;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\BelongsTo;

class TradeResource extends RestResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = Trade::class;

    public function exposedFields(RestRequest $request): array
    {
        return [
            'id'
        ];
    }

    public function relations(RestRequest $request): array
    {
        return [
            BelongsTo::make('vinyl', VinylResource::class),
            BelongsTo::make('format', FormatVinylResource::class),
            BelongsTo::make('user', UserResource::class),
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
