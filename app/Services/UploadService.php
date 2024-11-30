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
        $path = $image->storeAs($folder, $imageName, [
            'disk' => $this->storageSystem,
            'visibility' => 'public'
        ]);

        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk($this->storageSystem);

        // Assurez-vous que l'URL est correctement générée
        if ($this->storageSystem === 's3' || $this->storageSystem === 'do') {
            $fullUrl = $disk->url($path);
        } else {
            $fullUrl = asset('storage/' . $path);
        }

        return [
            'path' => $fullUrl,
            'name' => $imageName,
        ];
    }
}
