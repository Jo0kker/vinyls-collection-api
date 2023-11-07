<?php

namespace App\Rest\Controllers;

use App\Rest\Controller as RestController;
use App\Rest\Resources\FormatVinylResource;
use Lomkit\Rest\Http\Resource;

class FormatVinylsController extends RestController
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<Resource>
     */
    public static $resource = FormatVinylResource::class;
}
