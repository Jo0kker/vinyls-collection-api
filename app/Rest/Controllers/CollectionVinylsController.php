<?php

namespace App\Rest\Controllers;

use App\Rest\Controller as RestController;
use App\Rest\Resources\CollectionVinylResource;
use Lomkit\Rest\Http\Resource;

class CollectionVinylsController extends RestController
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<resource>
     */
    public static $resource = CollectionVinylResource::class;
}
