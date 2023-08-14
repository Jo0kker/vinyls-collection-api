<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public static $resource = User::class;
}
