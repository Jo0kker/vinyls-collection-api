<?php

namespace Database\Factories;

use App\Models\Vinyl;
use App\Services\DiscogsService;
use Carbon\Carbon;
use Exception;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;

/**
 * @extends Factory<Vinyl>
 */
class VinylFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $discogsId = $this->faker->numberBetween(1000, 10000000);
        // get data from discogService
        $discogsService = new DiscogsService();

        try {
            $discogsData = $discogsService->getVinylDataById($discogsId);
        } catch (Exception|GuzzleException $e) {
            return [
                'title' => fake()->name,
                'artist' => fake()->name,
                'genre' => fake()->name,
                'released' => Carbon::now()->format('Y'),
                'image' => 'https://picsum.photos/300?random='.random_int(1, 1000),
                'provenance' => fake()->name,
            ];
        }

        if (isset($discogsData->id)) {
            if ($discogsData->images[0]->uri !== '') {
                $image = $discogsData->images[0]->uri;
                $imageData = file_get_contents($image);
                $imageName = $discogsData->id.'.jpeg';
                Storage::put($imageName, $imageData);
                $path = Storage::url($imageName);
            }

            $videos = [];
            if (isset($discogsData->videos)) {
                foreach ($discogsData->videos as $video) {
                    $videos[] = [
                        'title' => $video->title,
                        'description' => $video->description,
                        'duration' => $video->duration,
                        'uri' => $video->uri,
                    ];
                }
            }

            if (isset($discogsData->released) && $discogsData->released !== '') {
                $released = $discogsData->released;
            } else {
                $released = null;
            }

            return [
                'title' => $discogsData->title,
                'image' => $path ?? null,
                'track_list' => json_encode($discogsData->tracklist),
                'artist' => $discogsData->artists[0]->name,
                'genre' => $discogsData->genres[0] ?? null,
                'released' => $released,
                'provenance' => $discogsData->country ?? null,
                'discog_id' => $discogsData->id,
                'type' => 'masters',
                'discog_url' => $discogsData->uri,
                'discog_videos' => json_encode($videos),
            ];
        }

        return [
            'title' => fake()->name,
            'artist' => fake()->name,
            'genre' => fake()->name,
            'released' => Carbon::now()->format('Y'),
            'image' => 'https://picsum.photos/300?random='.random_int(1, 1000),
            'provenance' => fake()->name,
        ];
    }
}
