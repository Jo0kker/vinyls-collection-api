<?php

namespace Database\Factories;

use App\Models\FormatVinyl;
use App\Models\Search;
use App\Models\User;
use App\Models\Vinyl;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Search>
 */
class SearchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'description' => fake()->text,
            'vinyl_id' => Vinyl::factory()->create()->getKey(),
            'user_id' => User::inRandomOrder()->first()->getKey(),
            'format' => fake()->text(5),
            'created_at' => Carbon::now()->subDays(random_int(1, 365)),
        ];
    }
}
