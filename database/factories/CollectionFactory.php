<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Collections>
 */
class CollectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name,
            'slug' => fake()->slug,
            'description' => fake()->text,
            'user_id' => User::inRandomOrder()->first()->getKey(),
            'created_at' => Carbon::now()->subDays(random_int(1, 365)),
        ];
    }
}
