<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfilRequest;
use App\Models\User;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
    if ($request->hasFile(key: 'avatar') && in_array($request->file(key: 'avatar')->getClientOriginalExtension(), ['jpg', 'jpeg', 'png', 'webp'])) {
        $uploadService = new UploadService();
        $image = $uploadService->uploadImage(
            image: $request->file(key: 'avatar'),
            folder: "user/{$user->id}"
        );

        // Remplacer l'avatar dans le tableau de données
        $data['avatar'] = $image['path'];
    }

        // Mettre à jour l'utilisateur avec le tableau temporaire
        $user->update(attributes: $data);
        return response()->json(data: $user);
    }
}
