<?php

namespace App\Rest\Resources;

use App\Rest\Resource as RestResource;
use Lomkit\Rest\Concerns\Resource\DisableAuthorizations;
use Lomkit\Rest\Concerns\Resource\DisableGates;
use Lomkit\Rest\Http\Requests\RestRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaResource extends RestResource
{
    use DisableGates, DisableAuthorizations;

    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    public static $model = Media::class;

    /**
     * The exposed fields that could be provided
     * @param RestRequest $request
     * @return array
     */
    public function fields(RestRequest $request): array
    {
        return [
            'id',
            'model_type',
            'model_id',
            'uuid',
            'collection_name',
            'name',
            'file_name',
            'mime_type',
            'disk',
            'conversions_disk',
            'size',
            'original_url',
            'preview_url',
        ];
    }

    /**
     * The exposed relations that could be provided
     * @param RestRequest $request
     * @return array
     */
    public function relations(RestRequest $request): array
    {
        return [];
    }

    /**
     * The exposed scopes that could be provided
     * @param RestRequest $request
     * @return array
     */
    public function scopes(RestRequest $request): array
    {
        return [];
    }

    /**
     * The exposed limits that could be provided
     * @param RestRequest $request
     * @return array
     */
    public function limits(RestRequest $request): array
    {
        return [
            10,
            25,
            50
        ];
    }

    /**
     * The actions that should be linked
     * @param RestRequest $request
     * @return array
     */
    public function actions(RestRequest $request): array {
        return [];
    }

    /**
     * The instructions that should be linked
     * @param RestRequest $request
     * @return array
     */
    public function instructions(RestRequest $request): array {
        return [];
    }
}
