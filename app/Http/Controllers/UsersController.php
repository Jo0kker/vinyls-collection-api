<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfilRequest;
use App\Models\User;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public static $resource = User::class;

    public function updateProfile(UpdateProfilRequest $request): JsonResponse|User
    {
        /**
         * @var User $user
         */
        $user = auth()->guard('api')->user();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Créer un tableau temporaire pour stocker les données de la requête
        $data = $request->all();
        unset($data['avatar']);

        // Mettre à jour l'avatar s'il est présent
        if ($request->hasFile('avatar') && in_array($request->file('avatar')->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'webp'])) {
            if ($request->file('avatar')->getSize() >= 2048 * 1024) {
                return response()->json(['error' => 'Image trop volumineuse'], 400);
            }

            $uploadService = new UploadService();
            $image = $uploadService->uploadImage(
                image: $request->file('avatar'),
                folder: "user/{$user->id}"
            );
            if ($user->avatar) {
                $disk = config('filesystems.default');
                $bucket_name = config("filesystems.disks.{$disk}.bucket");
                $url_path = parse_url($user->avatar, PHP_URL_PATH);
                $path = str_replace("{$bucket_name}/", '', ltrim($url_path, '/'));
                Storage::disk($disk)->delete($path);
            }

            // Remplacer l'avatar dans le tableau de données
            $data['avatar'] = $image['path'];
        }

        // Mettre à jour l'utilisateur avec le tableau temporaire
        $user->update($data);
        return response()->json($user);
    }
}
