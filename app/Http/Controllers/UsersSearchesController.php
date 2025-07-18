<?php

namespace App\Http\Controllers;

use App\Models\User;
use Orion\Http\Controllers\RelationController;

class UsersSearchesController extends RelationController
{
    protected $model = User::class;

    protected $relation = 'searches';

    public function includes(): array
    {
        return ['vinyl'];
    }
}
