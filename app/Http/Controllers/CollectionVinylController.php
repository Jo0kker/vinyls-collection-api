<?php

namespace App\Http\Controllers;

use App\Models\CollectionVinyl;
use Illuminate\Http\Request;
use Orion\Http\Controllers\Controller as Controller;


class CollectionVinylController extends Controller
{
    protected $model = CollectionVinyl::class;

    public function includes(): array
    {
        return ['vinyl', 'collection', 'collection.user'];
    }
}
