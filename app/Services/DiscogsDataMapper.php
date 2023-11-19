<?php

namespace App\Services;

/**
 * TO map data from discogs to vinyl model
 */
class DiscogsDataMapper
{
    // vinyl_field title
    //discog_url
    //discog_videos


    // discogs_field => vinyl_field
    private $mappingField = [
        'id' => 'discogs_id',
        'title' => 'title',
        'artists' => 'artists',
        'genres' => 'genre',
        'images' => 'discog_img',
        'tracklist' => 'track_list',
        'released' => 'released',
        'country' => 'provenance',
        'videos' => 'discog_videos',
        'uri' => 'discog_url',
    ];

    public function mapData($discogsData): array
    {
        $vinyl = [];
        foreach ($this->mappingField as $discogsField => $vinylField) {
            switch ($vinylField) {
                case 'genre':
                    // loop on genres and concat them
                    $genres = [];
                    foreach ($discogsData->$discogsField as $genre) {
                        $genres[] = $genre;
                    }
                    $vinyl[$vinylField] = implode(', ', $genres);
                    break;
                case 'discog_img':
                    // transform to json
                    $images = [];
                    foreach ($discogsData->$discogsField as $image) {
                        $images[] = $image;
                    }
                    $vinyl['image'] = $images[0]->uri;
                    $vinyl[$vinylField] = json_encode($images);
                    break;
                case 'track_list':
                    // transform to json
                    $trackList = [];
                    foreach ($discogsData->$discogsField as $track) {
                        $trackList[] = $track;
                    }
                    $vinyl[$vinylField] = json_encode($trackList);
                    break;
                case 'discog_videos':
                    // transform to json
                    $videos = [];
                    foreach ($discogsData->$discogsField as $video) {
                        $videos[] = $video;
                    }
                    $vinyl[$vinylField] = json_encode($videos);
                    break;
                default:
                    $vinyl[$vinylField] = $discogsData->$discogsField;
                    break;
            }
        }
        return $vinyl;
    }
}
