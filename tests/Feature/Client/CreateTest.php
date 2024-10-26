<?php

use App\Models\{Client, User};

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
});


it('can create a client', function () {
    $response = $this->postJson(route('clients.store'), [
        'name'         => 'Alice',
        'email'        => 'alice@example.com',
        'phone'        => '1234567890',
        'birth_date'   => '1990-01-01',
        'address'      => '123 Main St',
        'number'       => '456',
        'complement'   => 'Apt 1',
        'neighborhood' => 'Downtown',
        'zip_code'     => '12345678',
        'city'         => 'Metropolis',
        'state'        => 'NY',
    ]);

    $response->assertStatus(201);
    $this->assertDatabaseHas('clients', [
        'name'  => 'Alice',
        'email' => 'alice@example.com',
    ]);
});

it('validates required fields', function () {
    $response = $this->postJson(route('clients.store'), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'email', 'address', 'number', 'zip_code', 'city', 'state']);
});

it('does not allow duplicate emails', function () {
    Client::factory()->create(['email' => 'alice@example.com']);

    $response = $this->postJson(route('clients.store'), [
        'name'         => 'Alice',
        'email'        => 'alice@example.com',
        'phone'        => '1234567890',
        'birth_date'   => '1990-01-01',
        'address'      => '123 Main St',
        'number'       => '456',
        'complement'   => 'Apt 1',
        'neighborhood' => 'Downtown',
        'zip_code'     => '12345678',
        'city'         => 'Metropolis',
        'state'        => 'NY',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});
