<?php

namespace App\Rest\Controllers;

use App\Rest\Controller as RestController;
use App\Rest\Resources\VinylResource;
use Lomkit\Rest\Http\Resource;

class VinylsController extends RestController
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<Resource>
     */
    public static $resource = VinylResource::class;
}
