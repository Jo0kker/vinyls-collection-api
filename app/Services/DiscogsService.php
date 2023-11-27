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
    public function getVinylDataById($id): object
    {
        $response = $this->client->request('GET', 'masters/'.$id, [
            'query' => [
                'token' => $this->discogToken,
            ],
        ]);

        return json_decode($response->getBody()->getContents(), false, 512, JSON_THROW_ON_ERROR);
    }

    public function search($title = '', $artist = '', $year = '', $page = 1, $perPage = 10): object
    {
        $discogToken = config('app.discogs.token');
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
                    'type' => 'master',
                    'token' => $discogToken,
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
            switch ($key) {
                case 'format':
                    unset($vinyl->$value);
                    $vinyl->$value = $vinyl->$key;
                    break;
                case 'uri':
                    $vinyl->$value = 'https://www.discogs.com'.$vinyl->$key;
                    unset($vinyl->$key);
                    break;
                default:
                    $vinyl->$value = $vinyl->$key;
                    break;
            }
            unset($vinyl->$key);
        }

        return $vinyl;
    }
}
