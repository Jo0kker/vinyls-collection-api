<?php

namespace Database\Factories;

use App\Models\Collections;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vinyl>
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
        return [
            'label' => fake()->name,
            'track_list' => fake()->text,
            'artist' => fake()->name,
            'genre' => fake()->name,
            'year_released' => fake()->year,
            'image_path' => fake()->imageUrl(),
            'provenance' => fake()->name,
            'discog_id' => fake()->numberBetween(1, 10000),
            'created_at' => Carbon::now()->subDays(random_int(1, 365)),
        ];
    }
}
