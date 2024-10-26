<?php

use Illuminate\Support\Facades\Hash;
use App\Models\User;

it('registers a new user successfully and returns an access token', function () {
    $response = $this->postJson(route('register'), [
        'name'                  => 'John Doe',
        'email'                 => 'john@example.com',
        'password'              => 'Password123',
        'password_confirmation' => 'Password123',
    ]);

    $response->assertStatus(201)
             ->assertJson([
                 'data' => [
                     'name'  => 'John Doe',
                     'email' => 'john@example.com',
                 ],
                 'access_token' => true, // Check if access_token is present
                 'token_type'   => 'Bearer', // Check if token_type is 'Bearer'
             ]);

    $this->assertDatabaseHas('users', [
        'email' => 'john@example.com',
    ]);

    // Check if the password is hashed
    $user = User::where('email', 'john@example.com')->first();
    expect(Hash::check('Password123', $user->password))->toBeTrue();
});


it('fails to register with a duplicate email', function () {
    User::create([
        'name'     => 'Existing User',
        'email'    => 'existing@example.com',
        'password' => 'Password123',
    ]);

    $response = $this->postJson(route('register'), [
        'name'     => 'Another User',
        'email'    => 'existing@example.com', // Duplicate email
        'password' => 'Password123',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

it('fails to register with missing fields', function () {
    $response = $this->postJson(route('register'), []);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('fails to register with an invalid email format', function () {
    $response = $this->postJson(route('register'), [
        'name'     => 'Invalid Email User',
        'email'    => 'invalid-email',
        'password' => 'Password123',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});


it('fails to register with a short password', function () {
    $response = $this->postJson(route('register'), [
        'name'     => 'Short Password User',
        'email'    => 'shortpassword@example.com',
        'password' => 'short', // password with less than 8 characters
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});

it('fails to register with an empty name', function () {
    $response = $this->postJson(route('register'), [
        'name'     => '',
        'email'    => 'user@example.com',
        'password' => 'Password123',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['name']);
});

it('fails to register with an email that is too long', function () {
    $response = $this->postJson(route('register'), [
        'name'     => 'Long Email User',
        'email'    => str_repeat('a', 256) . '@example.com', // email with 256 characters
        'password' => 'Password123',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['email']);
});

it('fails to register with an empty password', function () {
    $response = $this->postJson(route('register'), [
        'name'     => 'No Password User',
        'email'    => 'nopassword@example.com',
        'password' => '',
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});

it('fails to register with a password that has no uppercase letter', function () {
    $response = $this->postJson(route('register'), [
        'name'     => 'No Uppercase User',
        'email'    => 'nouppercase@example.com',
        'password' => 'password123', // with no uppercase letter
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['password']);
});
