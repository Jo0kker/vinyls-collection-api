<?php

namespace App\Services;

use AllowDynamicProperties;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use JsonException;
use App\Models\User;
use Exception;

#[AllowDynamicProperties] class DiscogsService
{
    public function __construct(
        private ?string $discogToken = null,
        private ?Client $client = null
    )
    {
        $this->client = new Client([
            'base_uri' => 'https://api.discogs.com/',
            'headers' => [
                'User-Agent' => 'VinylApp/0.1 +https://vinylapp.com',
            ],
        ]);
        $this->discogToken = config('app.discogs.token');
    }

    public function getRequestToken(string $uniqid): array
    {
        $nonce = time();
        $timestamp = time();

        $authHeader = sprintf(
            'OAuth oauth_consumer_key="%s",oauth_nonce="%s",oauth_signature="%s",oauth_signature_method="PLAINTEXT",oauth_timestamp="%s",oauth_callback="%s"',
            config('services.discogs.client_id'),
            $nonce,
            config('services.discogs.client_secret') . '&',
            $timestamp,
            config('services.discogs.redirect') . '?nonce=' . $uniqid
        );

        $response = $this->client->get('oauth/request_token', [
            'headers' => [
                'Authorization' => $authHeader
            ]
        ]);

        $result = [];
        parse_str($response->getBody()->getContents(), $result);
        return $result;
    }

    public function getAccessToken(string $oauthToken, string $oauthTokenSecret, string $oauthVerifier): array
    {
        $nonce = time();
        $timestamp = time();

        $authHeader = sprintf(
            'OAuth oauth_consumer_key="%s",oauth_nonce="%s",oauth_token="%s",oauth_signature="%s&%s",oauth_signature_method="PLAINTEXT",oauth_timestamp="%s",oauth_verifier="%s"',
            config('services.discogs.client_id'),
            $nonce,
            $oauthToken,
            config('services.discogs.client_secret'),
            $oauthTokenSecret,
            $timestamp,
            $oauthVerifier
        );

        $response = $this->client->post('oauth/access_token', [
            'headers' => [
                'Authorization' => $authHeader
            ]
        ]);

        $result = [];
        parse_str($response->getBody()->getContents(), $result);
        return $result;
    }

    public function getUserData(?string $accessToken = null, ?string $accessTokenSecret = null, ?string $username = null): array
    {
        if (!$accessToken && !$accessTokenSecret) {
            return $this->getIdentityWithToken($this->discogToken);
        }

        if (!$username) {
            $identity = $this->getIdentityWithOAuth($accessToken, $accessTokenSecret);
            $username = $identity['username'];
        }

        $authHeader = sprintf(
            'OAuth oauth_consumer_key="%s",oauth_nonce="%s",oauth_token="%s",oauth_signature="%s&%s",oauth_signature_method="PLAINTEXT",oauth_timestamp="%s"',
            config('services.discogs.client_id'),
            time(),
            $accessToken,
            config('services.discogs.client_secret'),
            $accessTokenSecret,
            time()
        );

        $response = $this->client->get("users/{$username}", [
            'headers' => [
                'Authorization' => $authHeader
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    private function getIdentityWithToken(string $token): array
    {
        $response = $this->client->get('oauth/identity', [
            'query' => [
                'token' => $token
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function getIdentityWithOAuth(string $accessToken, string $accessTokenSecret): array
    {
        $authHeader = sprintf(
            'OAuth oauth_consumer_key="%s",oauth_nonce="%s",oauth_token="%s",oauth_signature="%s&%s",oauth_signature_method="PLAINTEXT",oauth_timestamp="%s"',
            config('services.discogs.client_id'),
            time(),
            $accessToken,
            config('services.discogs.client_secret'),
            $accessTokenSecret,
            time()
        );

        $response = $this->client->get('oauth/identity', [
            'headers' => [
                'Authorization' => $authHeader
            ]
        ]);

        return json_decode($response->getBody()->getContents(), true);
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

    public function getUserFolders(string $token, string $tokenSecret, string $username)
    {
        $response = $this->makeAuthenticatedRequest(
            "users/{$username}/collection/folders",
            $token,
            $tokenSecret
        );

        return $response->folders;
    }

    public function getFolderItems(string $token, string $tokenSecret, string $username, int $folderId)
    {
        $response = $this->makeAuthenticatedRequest(
            "users/{$username}/collection/folders/{$folderId}/releases",
            $token,
            $tokenSecret
        );

        return $response->releases;
    }

    private function makeAuthenticatedRequest(string $endpoint, string $token, string $tokenSecret)
    {
        $authHeader = sprintf(
            'OAuth oauth_consumer_key="%s",oauth_nonce="%s",oauth_token="%s",oauth_signature="%s&%s",oauth_signature_method="PLAINTEXT",oauth_timestamp="%s"',
            config('services.discogs.client_id'),
            time(),
            $token,
            config('services.discogs.client_secret'),
            $tokenSecret,
            time()
        );

        $response = $this->client->get($endpoint, [
            'headers' => [
                'Authorization' => $authHeader
            ]
        ]);

        return json_decode($response->getBody()->getContents());
    }
}
