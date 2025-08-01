<?php

namespace Database\Factories;

use App\Models\Collection;
use App\Models\CollectionVinyl;
use App\Models\Vinyl;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CollectionVinyl>
 */
class CollectionVinylFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     *
     * @throws Exception
     */
    public function definition()
    {
        $collection = Collection::inRandomOrder()->first();

        return [
            'collection_id' => $collection->id,
            'user_id' => $collection->user_id,
            'format' => fake()->text(5),
            'vinyl_id' => Vinyl::factory()->create()->getKey(),
            'created_at' => Carbon::now()->subDays(random_int(1, 365)),
        ];
    }
}
