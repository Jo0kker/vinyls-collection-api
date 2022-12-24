<?php

namespace App\Http\Controllers;

use App\Models\CollectionVinyl;
use Orion\Http\Controllers\Controller;

class CollectionVinylsController extends Controller
{
    protected $model = CollectionVinyl::class;

    public function sortableBy(): array
    {
        return ['created_at', 'updated_at'];
    }

    public function includes(): array
    {
        return ['vinyl', 'collection', 'collection.user'];
    }
}
