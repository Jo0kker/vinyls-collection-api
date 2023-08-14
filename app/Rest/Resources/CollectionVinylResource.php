<?php

namespace App\Rest\Resources;

use App\Models\CollectionVinyl;
use App\Rest\Resource as RestResource;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;

class CollectionVinylResource extends RestResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = CollectionVinyl::class;

    public function exposedFields(RestRequest $request)
    {
        return [
            'id'
        ];
    }

    public function relations(RestRequest $request)
    {
        return [];
    }

    public function exposedScopes(RestRequest $request) {
        return [];
    }

    public function exposedPaginations(RestRequest $request) {
        return [
            10,
            25,
            50
        ];
    }
}
