<?php

namespace App\Rest\Resources;

use App\Models\FormatVinyl;
use App\Rest\Resource as RestResource;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;

class FormatVinylResource extends RestResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = FormatVinyl::class;

    public function fields(RestRequest $request): array
    {
        return [
            'id',
            'name',
        ];
    }

    public function relations(RestRequest $request): array
    {
        return [];
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
            25,
            50,
        ];
    }
}
