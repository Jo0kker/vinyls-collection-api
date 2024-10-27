<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UploadService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UsersController extends Controller
{
    public static $resource = User::class;

    public function updateProfile(Request $request): JsonResponse|User
    {
        $user = auth()->guard('api')->user();
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }
        
        // update avatar if present
        if ($request->hasFile('avatar')) {
            $uploadService = new UploadService();
            $image = $uploadService->uploadImage($request->file('avatar'), 'user/' . $user->id);
            $user->avatar = $image['path'];
        }

        $user->update($request->all());
        return response()->json($user);
    }
}
