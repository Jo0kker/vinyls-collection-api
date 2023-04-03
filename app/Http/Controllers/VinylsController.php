<?php

namespace App\Http\Controllers;

use App\Models\Vinyl;
use App\Services\DiscogsService;
use Illuminate\Database\Eloquent\Model;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;

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

    protected function afterShow(Request $request, Model $entity)
    {
        $discogs = new DiscogsService();
        try {
            $entity->discogs = $discogs->getVinylDataById($entity->discog_id);
        } catch (\Exception $e) {
            $entity->discogs = [];
        }

        return $entity;
    }
}
