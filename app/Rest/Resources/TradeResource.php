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

    public function exposedFields(RestRequest $request)
    {
        return [
            'id'
        ];
    }

    public function relations(RestRequest $request)
    {
        return [
            BelongsTo::make('vinyl', VinylResource::class),
            BelongsTo::make('format', FormatVinylResource::class)
        ];
    }

    public function exposedScopes(RestRequest $request) {
        return [];
    }

    public function exposedLimits(RestRequest $request) {
        return [
            10,
            25,
            50
        ];
    }
}
