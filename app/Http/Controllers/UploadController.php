<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Trade;
use App\Models\CollectionVinyl;

class UploadController extends Controller
{
    protected function getFullUrl($path)
    {
        $disk = config('filesystems.default');
        $bucket = config("filesystems.disks.{$disk}.bucket");
        $endpoint = config("filesystems.disks.{$disk}.endpoint");

        if ($disk === 's3' || $disk === 'do') {
            return rtrim($endpoint, '/') . '/' . $bucket . '/' . ltrim($path, '/');
        }

        return Storage::disk($disk)->url($path);
    }

    public function process(Request $request)
    {
        if (!$request->hasFile('filepond')) {
            return response('No file uploaded', 400);
        }

        Gate::authorize('create', [Media::class, $request]);

        $file = $request->file('filepond');

        // check file extension
        if (!in_array($file->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'webp'])) {
            return response('Invalid file type', 400);
        }

        if ($file->getSize() > 2000000) { // 2MB
            return response('File is too large', 400);
        }

        $media = new Media();
        $media->model_type = $request->input('model_type');
        $media->model_id = $request->input('model_id');

        // parse to get the model type get the last part of the string and get the last
        $model_type = explode('\\', $media->model_type);
        $model_type = end($model_type);

        $storageSystem = config('filesystems.default');

        if ($storageSystem === 'do') {
            $imageFolder = config('filesystems.disks.do.folder') . '/' . $model_type;
        } else {
            $imageFolder = $model_type;
        }

        $uniqueId = Str::random(12);
        $disk = config('filesystems.default');
        $path = $file->storeAs($imageFolder . '/' . $uniqueId, $file->getClientOriginalName(), $disk);

        $fullUrl = $this->getFullUrl($path);

        $media->name = $file->getClientOriginalName();
        $media->file_name = $fullUrl;
        $media->size = $file->getSize();
        $media->manipulations = [];
        $media->custom_properties = [];
        $media->responsive_images = [];
        $media->order_column = 1;
        $media->collection_name = 'default';
        $media->generated_conversions = [];
        $media->disk = $disk;
        $media->conversions_disk = $disk;
        $media->uuid = $uniqueId;
        $media->mime_type = $file->getMimeType();
        $media->disk = $disk;
        $media->save();

        return response($media, 200)->header('Content-Type', 'text/plain');
    }


    public function revert(Request $request)
    {
        $id = $request->input('id');  // Récupérer l'ID du corps de la requête

        /** @var Media $media */
        $media = Media::find($id);
        if ($media) {
            Storage::disk($media->disk)->delete($media->file_name);
            $media->delete();  // Effectue une suppression soft delete
            return response()->json(null, 204);
        }

        return response()->json(['error' => 'File not found'], 404);
    }

    public function restore(Request $request)
    {
        $id = $request->input('id');  // Récupérer l'ID du corps de la requête

        /** @var Media $media */
        $media = Media::find($id);
        if ($media && Storage::disk($media->disk)->exists($media->file_name)) {
            $file = Storage::disk($media->disk)->get($media->file_name);
            $mimeType = Storage::disk($media->disk)->mimeType($media->file_name);
            return response($file, 200)->header('Content-Type', $mimeType);
        }

        return response()->json(['error' => 'File not found'], 404);
    }

    public function load($id)
    {
        /** @var Media $media */
        $media = Media::find($id);
        if ($media && Storage::disk($media->disk)->exists($media->file_name)) {
            $file = Storage::disk($media->disk)->get($media->file_name);
            $mimeType = Storage::disk($media->disk)->mimeType($media->file_name);
            return response($file, 200)->header('Content-Type', $mimeType);
        }

        return response()->json(['error' => 'File not found'], 404);
    }

    public function fetch(Request $request)
    {
        $url = $request->input('url');
        $contents = file_get_contents($url);
        $uniqueId = Str::random(12);
        Storage::put('public/tmp/' . $uniqueId, $contents);
        return response()->json(['id' => $uniqueId], 200);
    }

    public function delete(Request $request)
    {
        $file_name = $request->input('file_name');
        $media = Media::where('file_name', $file_name)->first();
        if ($media) {
            try {
                $disk = $media->disk;
                $bucket_name = config("filesystems.disks.{$disk}.bucket");
                $url_path = parse_url($media->file_name, PHP_URL_PATH);
                $path = str_replace("{$bucket_name}/", '', ltrim($url_path, '/'));

                if (Storage::disk($media->disk)->exists($path)) {
                    Storage::disk($media->disk)->delete($path);
                } else {
                    return response()->json(['error' => 'File not found on S3.'], 404);
                }

                // Supprimer l'entrée de la base de données
                $media->delete();
                return response()->json(null, 204);

            } catch (Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        } else {
            return response()->json(['error' => 'File not found in database.'], 404);
        }
    }
}
