<?php

namespace App\Http\Controllers;

use App\Models\Vinyl;
use App\Services\DiscogsDataMapper;
use App\Services\DiscogsService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VinylsController extends Controller
{
    public function __construct(
        private readonly DiscogsService $discogsService,
        private readonly DiscogsDataMapper $discogsDataMapper
    )
    {
    }

    public function addDiscogs(Request $request)
    {
        $discog_id = $request->input('discog_id');
        $discog = $this->discogsService->getVinylDataById($discog_id);
        $vinyl = $this->discogsDataMapper->mapData($discog);

        // save image to storage
        $image = $vinyl['image'];
        $image = str_replace('http://', 'https://', $image);
        $image = file_get_contents($image);
        // get folder in config env

        $storageSystem = config('filesystems.default');

        if ($storageSystem === 'do') {
            $imageFolder = config('filesystems.disks.do.folder');
        } else {
            $imageFolder = "public";
        }
        $imageName = $imageFolder.'/'.$vinyl['discogs_id'].'.jpeg';

        Storage::put(
            $imageName,
            $image, [
            'visibility' => 'public',
        ]);
        $path = Storage::url($imageName);
        $vinyl['image'] = $path;

        $vinyl = Vinyl::create($vinyl);

        return response()->json($vinyl);
    }
}
