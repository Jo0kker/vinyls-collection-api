<?php

namespace App\Rest\Controllers;

use App\Rest\Controller as RestController;
use App\Rest\Resources\TradeResource;
use Lomkit\Rest\Http\Resource;

class TradesController extends RestController
{
    /**
     * The resource the controller corresponds to.
     *
     * @var class-string<resource>
     */
    public static $resource = TradeResource::class;
}
