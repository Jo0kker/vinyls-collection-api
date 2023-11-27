<?php

namespace App\Http\Controllers;

use App\Http\Requests\VinylRequest;
use App\Models\Vinyl;
use App\Services\DiscogsDataMapper;
use App\Services\DiscogsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class VinylsController extends Controller
{
    public function __construct(
        private readonly DiscogsService $discogsService,
        private readonly DiscogsDataMapper $discogsDataMapper
    ) {
    }

    public function addDiscogs(Request $request)
    {
        $this->authorize('create', Vinyl::class);
        $rules = [
            'discog_id' => 'required|integer|unique:vinyls,discog_id',
        ];

        $messages = [
            'discog_id.unique' => 'Le vinyle est déjà présent dans la base de données.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $discog_id = $request->input('discog_id');
        $discog = $this->discogsService->getVinylDataById($discog_id);
        $vinyl = $this->discogsDataMapper->mapData($discog);
        // save image to storage
        $image = $vinyl['image'];
        $image = str_replace('http://', 'https://', $image);
        // context change user agent
        $opts = [
            'http' => [
                'method' => 'GET',
                'header' => 'User-Agent: Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B334b Safari/531.21.102011-10-16 20:23:10\r\n',
            ],
        ];
        $context = stream_context_create($opts);
        $image = file_get_contents($image, false, $context);
        // get folder in config env

        $storageSystem = config('filesystems.default');

        if ($storageSystem === 'do') {
            $imageFolder = config('filesystems.disks.do.folder');
        } else {
            $imageFolder = 'public';
        }
        $imageName = $imageFolder.'/'.$vinyl['discog_id'].'.jpeg';

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

    public function store(VinylRequest $request)
    {
        $this->authorize('create', Vinyl::class);
        $vinyl = Vinyl::create($request->validated());

        return response()->json($vinyl);
    }
}
