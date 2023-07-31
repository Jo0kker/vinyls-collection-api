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
            $baseFolder = env('DO_FOLDER');
            Storage::put($baseFolder, $request->file('image'), ['visibility' => 'public']);
            $entity->image = Storage::url($baseFolder . '/' . $request->file('image')->hashName());
        }
    }
}
