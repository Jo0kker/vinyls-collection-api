<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Orion\Http\Controllers\RelationController;

class CollectionVinylController extends RelationController
{
    protected $model = Collection::class;

    protected $relation = 'collectionVinyls';

    public function includes(): array
    {
        return ['vinyl'];
    }
}
