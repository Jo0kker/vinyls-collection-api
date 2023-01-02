<?php

namespace App\Http\Controllers;

use App\Services\DiscogsService;
use Illuminate\Http\Request;

class DiscogsController extends Controller
{

    public function search(Request $request)
    {
        $discogs = new DiscogsService();
        $search = $discogs->search($request->input('title'), $request->input('artist'), $request->input('year'));
        return response()->json($search);
    }

}
