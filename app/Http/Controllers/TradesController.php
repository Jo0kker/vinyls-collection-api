<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Orion\Http\Controllers\Controller as Controller;

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
