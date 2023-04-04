<?php

namespace App\Services;

class DiscogsService
{
    public function __construct()
    {
        $this->client = new \GuzzleHttp\Client([
            'base_uri' => 'https://api.discogs.com/',
            'headers' => [
                'User-Agent' => 'VinylApp/0.1 +https://vinylapp.com',
            ],
        ]);
    }

    public function getVinylData($artist, $title)
    {
        $response = $this->client->request('GET', 'database/search', [
            'query' => [
                'q' => $artist.' '.$title,
                'type' => 'release',
                'per_page' => 1,
                'token' => env('DISCOGS_TOKEN'),
            ],
        ]);

        return json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
    }

    public function getVinylDataById($id)
    {
        $response = $this->client->request('GET', 'releases/'.$id, [
            'query' => [
                'token' => env('DISCOGS_TOKEN'),
            ],
        ]);

        return json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
    }

    public function search($title = '', $artist = '', $year = '')
    {
        $response = $this->client->request('GET', 'database/search', [
            'query' => [
                'title' => $title,
                'artist' => $artist,
                'year' => $year,
                'type' => 'release',
                'per_page' => 10,
                'token' => env('DISCOGS_TOKEN'),
            ],
        ]);

        return json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
    }
}
