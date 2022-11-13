<?php

namespace App\Http\Controllers;

use App\Models\Vinyl;
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\Controller as Controller;
use Orion\Http\Requests\Request;

class VinylsController extends Controller
{

    protected $model = Vinyl::class;
}
