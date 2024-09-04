<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // add passport client in oauth_clients table directly (from env)
        $this->call(PassportClientSeeder::class);



        User::factory(100)
            ->afterCreating(
                function ($user) {
                    $user->roles()->attach(Role::inRandomOrder()->first());
                }
            )
            ->create();

        User::factory()
            ->afterCreating(
                function ($user) {
                    // role admin
                    $user->roles()->attach(Role::where('name', 'Administrator')->first());
                }
            )
            ->create(['name' => 'Test User', 'email' => 'test@example.com']);
        $this->call(BadgeSeeder::class);
        $this->call(FormatVinylsSeeder::class);
        $this->call(SearchSeeder::class);
        $this->call(TradeSeeder::class);
        $this->call(CollectionSeeder::class);
        $this->call(CollectionVinylSeeder::class);
    }
}
