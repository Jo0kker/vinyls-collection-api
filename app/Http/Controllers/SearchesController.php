<?php

namespace App\Http\Controllers;

use App\Models\Search;
use App\Services\DiscogsService;
use Illuminate\Database\Eloquent\Model;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;

class SearchesController extends Controller
{
    protected $model = Search::class;

    public function includes(): array
    {
        return ['user', 'vinyl'];
    }

    public function sortableBy(): array
    {
        return ['created_at', 'updated_at'];
    }
}
