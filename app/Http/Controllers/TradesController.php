<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use App\Services\DiscogsService;
use Illuminate\Database\Eloquent\Model;
use Orion\Http\Controllers\Controller as Controller;
use Orion\Http\Requests\Request;

class TradesController extends Controller
{
    protected $model = Trade::class;

    public function includes(): array
    {
        return ['user', 'vinyl'];
    }

    public function sortableBy(): array
    {
        return ['created_at', 'updated_at'];
    }
}
