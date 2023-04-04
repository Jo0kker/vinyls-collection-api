<?php

namespace App\Http\Controllers;

use App\Models\CollectionVinyl;
use App\Models\Vinyl;
use App\Services\DiscogsService;
use Orion\Http\Controllers\Controller;
use Orion\Http\Requests\Request;

class CollectionVinylsController extends Controller
{
    protected $model = CollectionVinyl::class;

    public function sortableBy(): array
    {
        return ['created_at', 'updated_at'];
    }

    public function includes(): array
    {
        return ['vinyl', 'collection', 'collection.user'];
    }

    public function store(Request $request)
    {
        $collection = $request->input('collection_id');
        $discogId = $request->input('discog_id');

        $vinyl = Vinyl::where('discog_id', $discogId)->first();
        if ($vinyl) {
            $collectionVinyl = CollectionVinyl::where('collection_id', $collection)
                ->where('vinyl_id', $vinyl->id)->first();
            if ($collectionVinyl) {
                return response()->json(['message' => 'Vinyl already exists in collection'], 409);
            }
            $collectionVinyl = new CollectionVinyl();
            $collectionVinyl->collection_id = $collection;
            $collectionVinyl->vinyl_id = $vinyl->id;
            $collectionVinyl->save();

            return response()->json($collectionVinyl);
        }

        $discogs = new DiscogsService();
        $discogsVinyl = $discogs->getVinylDataById((int) $discogId);
        $vinyl = new Vinyl();
        $vinyl->discog_id = $discogId;
        $vinyl->label = $discogsVinyl->title;
        $vinyl->artist = $discogsVinyl->artists[0]->name;
        $vinyl->year_released = $discogsVinyl->year;
        $vinyl->image_path = $discogsVinyl->thumb;
        $vinyl->provenance = $discogsVinyl->country;
        $vinyl->track_list = json_encode($discogsVinyl->tracklist);
        // destructuring array of genres
        $vinyl->genre = implode(', ', $discogsVinyl->genres);
        $vinyl->save();
        $collectionVinyl = new CollectionVinyl();
        $collectionVinyl->collection_id = $collection;
        $collectionVinyl->vinyl_id = $vinyl->id;
        $collectionVinyl->save();

        return response()->json($collectionVinyl);
    }
}
