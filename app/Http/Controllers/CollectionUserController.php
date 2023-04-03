<?php

namespace App\Http\Controllers;

use App\Models\User;
use Orion\Http\Controllers\RelationController;

class CollectionUserController extends RelationController
{
    protected $model = User::class;

    protected $relation = 'collections';

    public function includes(): array
    {
        return ['vinyl'];
    }
}
