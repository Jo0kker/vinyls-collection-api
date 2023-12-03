<?php

namespace App\Http\Controllers;

class CollectionUserController extends Controller
{
    public function includes(): array
    {
        return ['vinyl'];
    }
}
