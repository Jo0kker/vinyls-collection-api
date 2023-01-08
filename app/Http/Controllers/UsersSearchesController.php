<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\User;
use Orion\Http\Controllers\RelationController;

class UsersSearchesController extends RelationController
{
    protected $model = User::class;

    protected $relation = 'searches';
}
