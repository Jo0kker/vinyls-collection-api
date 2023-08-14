<?php

namespace App\Rest;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Lomkit\Rest\Http\Requests\RestRequest;
use Lomkit\Rest\Http\Resource as RestResource;

abstract class Resource extends RestResource
{
    /**
     * Build a query for fetching resource.
     *
     * @param  RestRequest  $request
     * @param  Builder  $query
     * @return Builder
     */
    public function fetchQuery(RestRequest $request, Builder $query) {
        return $query;
    }

    /**
     * Build a "destroy" query for the given resource.
     *
     * @param  RestRequest  $request
     * @param  Builder  $query
     * @return Builder
     */
    public function destroyQuery(RestRequest $request, Builder $query)
    {
        return $query;
    }

    /**
     * Build a "restore" query for the given resource.
     *
     * @param  RestRequest  $request
     * @param  Builder  $query
     * @return Builder
     */
    public function restoreQuery(RestRequest $request, Builder $query)
    {
        return $query;
    }

    /**
     * Build a "forceDelete" query for the given resource.
     *
     * @param  RestRequest  $request
     * @param  Builder  $query
     * @return Builder
     */
    public function forceDeleteQuery(RestRequest $request, Builder $query)
    {
        return $query;
    }
}
