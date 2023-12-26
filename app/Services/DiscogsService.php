<?php

namespace App\Services;

use AllowDynamicProperties;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;

#[AllowDynamicProperties] class DiscogsService
{
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.discogs.com/',
            'headers' => [
                'User-Agent' => 'VinylApp/0.1 +https://vinylapp.com',
            ],
        ]);
        $this->discogToken = config('app.discogs.token');
    }

    public function getVinylData($artist, $title): object
    {
        $response = $this->client->request('GET', 'database/search', [
            'query' => [
                'q' => $artist.' '.$title,
                'type' => 'release',
                'per_page' => 1,
                'token' => $this->discogToken,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * @throws GuzzleException
     * @throws JsonException
     */
    public function getVinylDataById($id, $type = 'masters'): object
    {
        $response = $this->client->request('GET', $type.'/'.$id, [
            'query' => [
                'token' => $this->discogToken,
            ],
        ]);
        $response = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        $response->image = $response->images[0]->uri;
        $response->type = $type === 'masters' ? 'masters' : 'releases';

        return $response;
    }

    public function search($title = '', $artist = '', $year = '', $page = 1, $perPage = 10, $type = ''): object
    {
        $response = $this->client->request(
            method: 'GET',
            uri: 'database/search',
            options: [
                'query' => [
                    'title' => $title,
                    'artist' => $artist,
                    'year' => $year,
                    'page' => $page,
                    'per_page' => $perPage,
                    'type' => $type,
                    'token' => $this->discogToken,
                ],
            ]);

        $vinyls = json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
        $vinyls->results = array_map([$this, 'changeVinylsKeyName'], $vinyls->results);

        return $vinyls;
    }

    private function changeVinylsKeyName($vinyl): object
    {
        $nameToConvert = [
            'country' => 'provenance',
            'id' => 'discog_id',
            'cover_image' => 'image',
            'uri' => 'discog_url',
            'format' => 'formats',
        ];

        foreach ($nameToConvert as $key => $value) {
            if (! isset($vinyl->$key)) {
                continue;
            }
            switch ($key) {
                case 'format':
                    $vinyl->$value = $vinyl->$key;
                    break;
                case 'uri':
                    $vinyl->$value = 'https://www.discogs.com'.$vinyl->$key;
                    break;
                default:
                    $vinyl->$value = $vinyl->$key;
                    break;
            }
        }

        return $vinyl;
    }
}
