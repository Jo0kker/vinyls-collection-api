<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

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
    public function uploadImage($image, $imageName = null, $folder = 'default'): array
    {
        $imageName = $imageName ?? $image->getClientOriginalName();
        $path = $image->storeAs($folder, $imageName, $this->storageSystem);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($this->storageSystem);
        $fullUrl = $disk->url($path);

        return [
            'path' => $fullUrl,
            'name' => $imageName,
        ];
    }
}
