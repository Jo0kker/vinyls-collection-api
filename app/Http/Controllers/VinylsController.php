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
            'discog_id' => 'required|integer',
            'type' => 'required|string',
        ];

        $messages = [
            'discog_id.unique' => 'Id non conforme',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $discog_id = $request->input('discog_id');
        $type = \Str::plural($request->input('type'));

        // check if vinyl already exist
        $vinyl = Vinyl::query()
            ->where('discog_id', $discog_id)
            ->where('type', $type)
            ->first();

        if ($vinyl) {
            return response()->json($vinyl);
        }

        $discog = $this->discogsService->getVinylDataById($discog_id, $type);
        $vinyl = $this->discogsDataMapper->mapData($discog);

//        $vinyl['image'] = $this->importImage($vinyl['image'], $type, $discog_id);

        $vinyl = Vinyl::create($vinyl);

        return response()->json($vinyl);
    }

    public function store(VinylRequest $request)
    {
        $this->authorize('create', Vinyl::class);
        $vinyl = Vinyl::create($request->validated());

        return response()->json($vinyl);
    }

    public function updateDiscoq($id)
    {
        $vinyl = Vinyl::find($id);

        if (!$vinyl instanceof Vinyl) {
            return response()->json(['error' => 'Vinyl not found'], 404);
        }

        $discog = $this->discogsService->getVinylDataById($vinyl->discog_id, $vinyl->type);
        $data = $this->discogsDataMapper->mapData($discog);

        $data['image'] = $this->importImage($data['image'], $data['type'], $data['discog_id']);

        $vinyl->update($data);

        return response()->json($vinyl);
    }

    private function importImage($image, $type, $discog_id)
    {
        $image = str_replace('http://', 'https://', $image);

        // Initialiser cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $image);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3');
        $imageData = curl_exec($ch);
        curl_close($ch);

        if ($imageData === false) {
            throw new \Exception('Failed to download image.');
        }

        $storageSystem = config('filesystems.default');
        $imageFolder = $storageSystem === 'do' ? config('filesystems.disks.do.folder') . '/Vinyls' : 'Vinyls';
        $imageName = $imageFolder . '/' . $type[0] . $discog_id . '.jpg';

        Storage::put($imageName, $imageData, ['visibility' => 'public']);
        return Storage::url($imageName);
    }
}
