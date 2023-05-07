<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
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

    protected function beforeStore(Request $request, Model $entity)
    {
        $entity->user_id = $request->user()->id;
        $entity->slug = Str::slug($request->name);

        return $entity;
    }
}
