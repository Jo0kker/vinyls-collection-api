<?php

namespace App\Http\Requests;

use Orion\Http\Requests\Request;

class VinylRequest extends Request
{
    private const REQUIRED_STRING = 'required|string|max:255';

    public function commonRules(): array
    {
        return [];
    }

    public function storeRules(): array
    {
        return [
            'title' => self::REQUIRED_STRING,
            'artist' => self::REQUIRED_STRING,
            'genre' => self::REQUIRED_STRING,
            'image' => self::REQUIRED_STRING,
            'track_list' => 'json',
            'released' => 'required|date',
            'provenance' => self::REQUIRED_STRING,
            'discog_id' => 'integer',
            'discog_url' => 'string',
            'discog_videos' => 'string',
        ];
    }
}
