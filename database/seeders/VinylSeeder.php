<?php

namespace Database\Seeders;

use App\Models\Vinyl;
use Illuminate\Database\Seeder;

class VinylSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vinyl::factory(50)
            ->create();
    }
}
