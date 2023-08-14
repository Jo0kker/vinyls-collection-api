<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

it('register User', function () {
    $userData = [
        'name' => 'stone',
        'email' => 'stone@codible.fr',
        'password' => 'password',
        'password_confirmation' => 'password',
        'terms' => true,
    ];

    $response = $this->postJson('/api/register', $userData);

    $response->assertStatus(200);
});

it('udpate user', function () {
    $userData = [
        'name' => 'stone',
        'email' => 'stone@codible.fr',
        'password' => 'password',
        'password_confirmation' => 'password',
        'terms' => true,
    ];

    $response = $this->postJson('/api/register', $userData);

    $response->assertStatus(200);

    $user = User::where('email', $userData['email'])->first();

    $response = $this->actingAs($user)->postJson('/api/users/mutate', [
        'mutate' => [
            [
                'operation' => 'update',
                'key' => $user->id,
                'attributes' => [
                    'first_name' => 'test_edit'
                ],
            ]
        ]
    ]);

    $response->assertStatus(200);

    $user = User::where('email', $userData['email'])->first();

    $this->assertEquals($user->first_name, 'test_edit');
});
