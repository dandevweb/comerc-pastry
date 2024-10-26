<?php

use Illuminate\Support\Facades\Hash;
use App\Models\User;

beforeEach(function () {
    User::create([
        'name'     => 'Test User',
        'email'    => 'test@example.com',
        'password' => Hash::make('Password123'),
    ]);
});

it('logs in an existing user successfully and returns an access token', function () {
    $response = $this->postJson(route('login'), [
        'email'    => 'test@example.com',
        'password' => 'Password123',
    ]);

    $response->assertStatus(200)
             ->assertJson([
                 'data' => [
                     'name'  => 'Test User',
                     'email' => 'test@example.com',
                 ],
                 'access_token' => true, // Check if access_token is present
                 'token_type'   => 'Bearer', // Check if token_type is 'Bearer'
             ]);
});

it('fails to log in with invalid credentials', function () {
    $response = $this->postJson(route('login'), [
        'email'    => 'test@example.com',
        'password' => 'WrongPassword',
    ]);

    $response->assertStatus(401)
             ->assertJson([
                 'message' => 'Invalid credentials',
             ]);
});

it('fails to log in with a missing email', function () {
    $response = $this->postJson(route('login'), [
        'password' => 'Password123',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

it('fails to log in with a missing password', function () {
    $response = $this->postJson(route('login'), [
        'email' => 'test@example.com',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});

it('fails to log in with an invalid email format', function () {
    $response = $this->postJson(route('login'), [
        'email'    => 'invalid-email',
        'password' => 'Password123',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});
