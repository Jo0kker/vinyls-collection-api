<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class VinylCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // call api discogs and add the data to the vinyls
        $vinyls = $this->collection->map(function ($vinyl) {
            $discogs = new \App\Services\DiscogsService();
            $vinyl->discog = $vinyl->discog_id ? $discogs->getVinylDataById($vinyl->discog_id) : null;
            return $vinyl;
        });
        return $vinyls;
    }
}
