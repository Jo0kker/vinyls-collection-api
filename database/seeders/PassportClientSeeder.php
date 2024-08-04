<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PassportClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // add passport client in oauth_clients table directly (from env) it"s a passport client
        $client = [
            'id' => env('PASSPORT_CLIENT_ID'),
            'name' => env('PASSPORT_CLIENT_NAME'),
            'secret' => env('PASSPORT_CLIENT_SECRET'),
            'redirect' => 'https://localhost',
            'personal_access_client' => 0,
            'password_client' => 1,
            'revoked' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('oauth_clients')->insert($client);
    }
}
