<?php

namespace App\Http\Controllers;

use App\Models\Search;
use Orion\Http\Controllers\Controller as Controller;

class SearchesController extends Controller
{
    protected $model = Search::class;

    public function includes(): array
    {
        return ['user'];
    }
}
