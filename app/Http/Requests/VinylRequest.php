<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VinylRequest extends FormRequest
{
    private const REQUIRED_STRING = 'required|string|max:255';

    public function rules(): array
    {
        return [
            'title' => self::REQUIRED_STRING,
            'artist' => self::REQUIRED_STRING,
            'genre' => self::REQUIRED_STRING,
            'image' => [
                function ($attribute, $value, $fail) {
                    if (! filter_var($value, FILTER_VALIDATE_URL) && ! is_uploaded_file($value)) {
                        $fail('The '.$attribute.' field must be either a valid URL or a valid uploaded file.');
                    }
                },
            ],
            'track_list' => 'json',
            'released' => 'string',
            'provenance' => 'string',
            'discog_id' => 'integer',
            'discog_url' => 'string',
            'discog_videos' => 'string',
        ];
    }
}
