<?php

namespace App\Http\Controllers;

use App\Models\User;
use Orion\Http\Controllers\Controller;

class UsersController extends Controller
{
    protected $model = User::class;

    public function includes(): array
    {
        return ['collectionVinyls', 'trades', 'searches'];
    }
}
{

}
