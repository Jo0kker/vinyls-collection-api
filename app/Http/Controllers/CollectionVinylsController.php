<?php

namespace App\Http\Controllers;

use App\Models\CollectionVinyl;

class CollectionVinylsController extends \Orion\Http\Controllers\Controller
{
    protected $model = CollectionVinyl::class;

    public function includes(): array
    {
        return ['vinyl', 'collection', 'collection.user'];
    }
}
