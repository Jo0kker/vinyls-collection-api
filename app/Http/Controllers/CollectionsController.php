<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Vinyl;
use App\Services\DiscogsService;
use Illuminate\Database\Eloquent\Model;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;

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
