<?php

namespace Database\Factories;


use App\Models\Collection;
use App\Models\FormatVinyls;
use App\Models\Vinyl;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CollectionVinyl>
 */
class CollectionVinylFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws \Exception
     */
    public function definition()
    {
        return [
            'collection_id' => Collection::inRandomOrder()->first()->getKey(),
            'format_vinyl_id' => FormatVinyls::inRandomOrder()->first()->getKey(),
            'vinyl_id' => Vinyl::factory()->create()->getKey(),
            'cover_state' => fake()->numberBetween(0, 5),
            'year_purchased' => fake()->year,
            'price' => fake()->numberBetween(1, 100000),
            'is_sellable' => fake()->boolean,
            'description' => fake()->text,
            'created_at' => Carbon::now()->subDays(random_int(1, 365)),
        ];
    }
}
