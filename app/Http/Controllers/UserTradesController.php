<?php

namespace App\Http\Controllers;

use App\Models\User;
use Orion\Http\Controllers\RelationController;

class UserTradesController extends RelationController
{
    protected $model = User::class;

    protected $relation = 'trades';

    public function includes(): array
    {
        return ['vinyl'];
    }
}
