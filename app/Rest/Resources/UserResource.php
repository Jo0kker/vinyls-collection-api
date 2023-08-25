<?php

namespace App\Rest\Resources;

use App\Models\User;
use App\Rest\Resource as RestResource;
use Illuminate\Database\Eloquent\Model;
use Lomkit\Rest\Http\Requests\RestRequest;

class UserResource extends RestResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<Model>
     */
    public static $model = User::class;

    public function exposedFields(RestRequest $request): array
    {
        return [
            'id',
            'name',
            'email',
            'is_subscribed_newsletter',
            'last_activity',
            'first_name',
            'last_name',
            'phone',
            'birth_date',
            'audio_equipment',
            'influence',
            'description',
            'avatar',
        ];
    }

    public function relations(RestRequest $request): array
    {
        return [
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
