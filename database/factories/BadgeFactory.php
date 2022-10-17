<?php

namespace Database\Factories;

use App\Models\Collection;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Badge>
 */
class BadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => fake()->name,
            'description' => fake()->text,
            'color' => fake()->hexColor,
            'user_id' => User::inRandomOrder()->first()->getKey(),
            'created_at' => Carbon::now()->subDays(random_int(1, 365)),
        ];
    }
}
