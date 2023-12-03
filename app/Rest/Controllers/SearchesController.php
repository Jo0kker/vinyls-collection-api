<?php

namespace App\Rest\Controllers;

use App\Rest\Controller as RestController;
use App\Rest\Resources\SearchResource;
use Lomkit\Rest\Http\Resource;

class SearchesController extends RestController
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<resource>
     */
    public static $resource = SearchResource::class;
}
