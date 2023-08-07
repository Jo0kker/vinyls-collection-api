<?php

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

    $user = \App\Models\User::where('email', $userData['email'])->first();

    $response = $this->actingAs($user)->putJson('/api/users/'.$user->id, [
        'first_name' => 'test_edit',
    ]);

    $response->assertStatus(200);

    $user = \App\Models\User::where('email', $userData['email'])->first();

    $this->assertEquals($user->first_name, 'test_edit');
});
