<?php

namespace Database\Factories;

use App\Models\FormatVinyls;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trade>
 */
class TradeFactory extends Factory
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
            'artist' => fake()->name,
            'description' => fake()->text,
            'discog_id' => fake()->numberBetween(1, 10000),
            'user_id' => User::inRandomOrder()->first()->getKey(),
            'format_vinyl_id' => FormatVinyls::inRandomOrder()->first()->getKey(),
            'created_at' => Carbon::now()->subDays(random_int(1, 365)),
        ];
    }
}
