<?php

namespace App\Http\Controllers;

use App\Models\Filepond;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadController extends Controller
{
    public function process(Request $request)
    {
        if (!$request->hasFile('filepond')) {
            return response('No file uploaded', 400);
        }

        $file = $request->file('filepond');
        $uniqueId = Str::random(12);
        $disk = config('filesystems.default');
        $path = $file->storeAs('tmp/' . $uniqueId, $file->getClientOriginalName());

        $filepond = new Filepond();
        $filepond->filename = $file->getClientOriginalName();
        $filepond->filepath = $path;
        $filepond->extension = $file->getClientOriginalExtension();
        $filepond->mimetypes = $file->getMimeType();
        $filepond->disk = $disk;
        $filepond->created_by = auth()->id(); // Assure-toi que l'authentification est gérée
        $filepond->expires_at = now()->addDay();
        $filepond->save();

        return response($filepond->id, 200)->header('Content-Type', 'text/plain');
    }


    public function revert(Request $request)
    {
        $id = $request->input('id');  // Récupérer l'ID du corps de la requête

        $filepond = Filepond::find($id);
        if ($filepond) {
            Storage::disk($filepond->disk)->delete($filepond->filepath);
            $filepond->delete();  // Effectue une suppression soft delete
            return response()->json(null, 204);
        }

        return response()->json(['error' => 'File not found'], 404);
    }

    public function restore(Request $request)
    {
        $id = $request->input('id');  // Récupérer l'ID du corps de la requête

        $filepond = Filepond::find($id);
        if ($filepond && Storage::disk($filepond->disk)->exists($filepond->filepath)) {
            $file = Storage::disk($filepond->disk)->get($filepond->filepath);
            $mimeType = Storage::disk($filepond->disk)->mimeType($filepond->filepath);
            return response($file, 200)->header('Content-Type', $mimeType);
        }

        return response()->json(['error' => 'File not found'], 404);
    }

    public function load($id)
    {
        $filepond = Filepond::find($id);
        if ($filepond && Storage::disk($filepond->disk)->exists($filepond->filepath)) {
            $file = Storage::disk($filepond->disk)->get($filepond->filepath);
            $mimeType = Storage::disk($filepond->disk)->mimeType($filepond->filepath);
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
}
