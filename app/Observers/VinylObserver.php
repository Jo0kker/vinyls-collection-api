<?php

namespace App\Observers;

use App\Models\Vinyl;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class VinylObserver
{
    /**
     * Handle the Vinyl "created" event.
     */
    public function created(Vinyl $vinyl): void
    {
        // if image is file, store it with storage
        // if image is url, save url
        // if image is null, save null
        if (is_uploaded_file($vinyl->image) && $vinyl->image instanceof UploadedFile) {
            $storageSystem = config('filesystems.default');
            if ($storageSystem === 'do') {
                $imageFolder = config('filesystems.disks.do.folder');
            } else {
                $imageFolder = 'public';
            }
            $extension = $vinyl->image->extension();
            $imageName = $imageFolder.'/vc_'.$vinyl->id.'.'.$extension;
            $imageContent = file_get_contents($vinyl->image->getRealPath());
            Storage::put(
                $imageName,
                $imageContent,
                ['visibility' => 'public']
            );

            $vinyl->image = Storage::url($imageName);
            $vinyl->save();
        }
    }

    /**
     * Handle the Vinyl "updated" event.
     */
    public function updated(Vinyl $vinyl): void
    {
        //
    }

    /**
     * Handle the Vinyl "deleted" event.
     */
    public function deleted(Vinyl $vinyl): void
    {
        //
    }

    /**
     * Handle the Vinyl "restored" event.
     */
    public function restored(Vinyl $vinyl): void
    {
        //
    }

    /**
     * Handle the Vinyl "force deleted" event.
     */
    public function forceDeleted(Vinyl $vinyl): void
    {
        //
    }
}
