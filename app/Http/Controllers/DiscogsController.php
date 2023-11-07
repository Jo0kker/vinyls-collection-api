<?php

namespace App\Http\Controllers;

use App\Services\DiscogsService;
use Illuminate\Http\Request;

class DiscogsController extends Controller
{
    public function search(Request $request)
    {
        $discogs = new DiscogsService();
        $search = $discogs->search(
            $request->input('title'),
            $request->input('artist'),
            $request->input('year'),
            $request->input('page'),
            $request->input('per_page')
        );

        unset($search->pagination->urls);
        $search->data = $search->results;
        unset($search->results);
        $search->current_page = $search->pagination->page;
        $search->from = $search->pagination->page;
        $search->last_page = $search->pagination->pages;
        $search->per_page = $search->pagination->per_page;
        $search->to = $search->pagination->items;
        $search->total = $search->pagination->items;
        unset($search->pagination);

        return response()->json($search);
    }
}
