<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\User;
use App\Models\Vinyl;

class StatsController extends Controller
{
    public function global()
    {
        $userCount = User::count();
        $collectionCount = Collection::count();
        $vinylCount = Vinyl::count();

        $stats = [
            'users' => $userCount,
            'collections' => $collectionCount,
            'vinyls' => $vinylCount,
        ];

        return response()->json([
            'data' => $stats,
        ]);
    }
}
