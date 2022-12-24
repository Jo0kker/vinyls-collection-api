<?php

namespace Database\Factories;

use App\Models\FormatVinyls;
use App\Models\User;
use App\Models\Vinyl;
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
            'description' => fake()->text,
            'vinyl_id' => Vinyl::factory()->create()->getKey(),
            'image_path' => 'https://picsum.photos/300?random='.random_int(5000, 10000),
            'user_id' => User::inRandomOrder()->first()->getKey(),
            'format_vinyl_id' => FormatVinyls::inRandomOrder()->first()->getKey(),
            'created_at' => Carbon::now()->subDays(random_int(1, 365)),
        ];
    }
}
