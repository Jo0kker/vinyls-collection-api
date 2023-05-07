<?php

namespace App\Http\Controllers;

use App\Models\Vinyl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
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

    protected function beforeSave(Request $request, Model $entity)
    {
        if ($request->hasFile('image')) {
            $entity->image = Storage::putFile('public/images', $request->file('image'));
            $entity->image = str_replace('public', 'storage', $entity->image);
        }
    }
}
