<?php

namespace App\Http\Controllers;

use App\Models\Vinyl;
use Orion\Http\Controllers\Controller;

class VinylsController extends Controller
{
    protected $model = Vinyl::class;

    public function includes(): array
    {
        return ['user'];
    }

    public function sortableBy(): array
    {
        return ['created_at', 'updated_at'];
    }
}
