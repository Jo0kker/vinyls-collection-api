<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;

class UsersController extends Controller
{
    protected $model = User::class;

    public function aggregates(): array
    {
        return ['collections', 'collections.collectionVinyls', 'collectionVinyls'];
    }

    public function filterableBy(): array
    {
        return ['name', 'email'];
    }

    public function includes(): array
    {
        return ['collections', 'trades', 'searches'];
    }

    /**
     * add collectionVinyls count to all users on all route
     */
    public function afterIndex(Request $request, $entities)
    {
        $entities->each(function ($entity) {
            $entity->collectionVinyls_count = DB::table('collection_vinyls')
                ->join('collections', 'collection_vinyls.collection_id', '=', 'collections.id')
                ->where('collections.user_id', '=', $entity->id)
                ->count();
        });

        return $entities;
    }
}
