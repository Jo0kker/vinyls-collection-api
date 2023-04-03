<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Orion\Http\Controllers\Controller;

class CollectionsController extends Controller
{
    protected $model = Collection::class;

    public function includes(): array
    {
        return ['user'];
    }

    public function sortableBy(): array
    {
        return ['created_at', 'updated_at'];
    }
}
