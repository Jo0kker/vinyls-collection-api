<?php

namespace App\Rest\Resources;

use App\Models\User;
use App\Rest\Resource as RestResource;
use App\Rest\Resources\CollectionVinylResource;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Relations\HasMany;

class UserResource extends RestResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = User::class;

    public function fields(RestRequest $request): array
    {
        return [
            'id',
            'name',
            'is_subscribed_newsletter',
            'last_activity',
            'first_name',
            'last_name',
            'birth_date',
            'audio_equipment',
            'influence',
            'description',
            'avatar',
            'collection_vinyls_count',
        ];
    }

    public function relations(RestRequest $request): array
    {
        return [
            HasMany::make('collectionVinyls', CollectionVinylResource::class),
        ];
    }

    public function scopes(RestRequest $request): array
    {
        return [];
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
            25,
            50,
        ];
    }
}
