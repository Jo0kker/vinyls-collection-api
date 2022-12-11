<?php

namespace App\Http\Controllers;

use App\Http\Resources\VinylCollection;
use App\Models\Search;
use Orion\Http\Controllers\Controller as Controller;

class SearchesController extends Controller
{
    protected $model = Search::class;

    protected $collectionResource = VinylCollection::class;

    public function includes(): array
    {
        return ['user'];
    }
}
