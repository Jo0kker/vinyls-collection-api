<?php

namespace Database\Seeders;

use App\Models\CollectionVinyl;
use Illuminate\Database\Seeder;

class CollectionVinylSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CollectionVinyl::factory(100)
            ->create();
    }
}
