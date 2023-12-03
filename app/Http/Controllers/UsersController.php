<?php

namespace App\Http\Controllers;

use App\Models\User;

class UsersController extends Controller
{
    public static $resource = User::class;
}
