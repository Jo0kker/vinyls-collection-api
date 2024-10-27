<?php

namespace App\Services;

use Illuminate\Support\Str;

class UploadService
{

    private $storageSystem;

    public function __construct()
    {
        $this->storageSystem = config('filesystems.default');
    }

    /**
     * Upload an image to the storage and return a array with the image information
     *
     * @param $image string
     * @param $folder string
     * @return array
     */
    public function uploadImage($image, $folder = 'default'): array
    {
        $imageName = Str::random(12);
        $path = $image->storeAs($folder, $imageName, $this->storageSystem);
        return [
            'path' => $path,
            'name' => $imageName,
        ];
    }
}
