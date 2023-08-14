<?php

namespace App\Rest\Resources;

use App\Models\FormatVinyls;
use App\Rest\Resource as RestResource;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Concerns\Resource\DisableAutomaticGates;
use Lomkit\Rest\Http\Requests\RestRequest;

class FormatVinylResource extends RestResource
{
    use DisableAutomaticGates;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = FormatVinyls::class;

    public function exposedFields(RestRequest $request): array
    {
        return [
            'id',
            'name'
        ];
    }

    public function relations(RestRequest $request): array
    {
        return [];
    }

    public function exposedScopes(RestRequest $request): array
    {
        return [];
    }

    public function exposedLimits(RestRequest $request): array
    {
        return [
            10,
            25,
            50
        ];
    }
}
