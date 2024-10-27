<?php

use App\Models\{Client, User};

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can update a client successfully', function () {
    $client = Client::factory()->create([
        'name'  => 'Alice',
        'email' => 'alice@example.com',
        'phone' => '1234567890',
    ]);

    $updatedData = [
        'name'         => 'Alice Updated',
        'email'        => 'aliceupdated@example.com',
        'phone'        => '0987654321',
        'birth_date'   => '1990-01-01',
        'address'      => 'New Address',
        'number'       => '101',
        'complement'   => 'Apt 1',
        'neighborhood' => 'Updated Neighborhood',
        'zip_code'     => '12345-678',
        'city'         => 'Updated City',
        'state'        => 'UP',
    ];

    $response = $this->putJson(route('clients.update', $client), $updatedData);

    $response->assertStatus(200)
        ->assertJson([
            'data' => [
                'id' => $client->id,
                ...$updatedData,
            ],
        ]);

    $this->assertDatabaseHas('clients', [
        'id'    => $client->id,
        'name'  => 'Alice Updated',
        'email' => 'aliceupdated@example.com',
    ]);
});

it('returns validation errors when required fields are missing', function () {
    $client = Client::factory()->create();

    $response = $this->putJson(route('clients.update', $client), [
        'name'  => '',
        'email' => '',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'email']);
});

it('returns a 404 error if the client does not exist', function () {
    $updatedData = [
        'name'  => 'Non-existent Client',
        'email' => 'nonexistent@example.com',
    ];

    $response = $this->putJson(route('clients.update', 999), $updatedData);

    $response->assertStatus(404);
    $response->assertJson(['message' => __('Record not found.')]);
});

it('returns validation error if email is duplicated', function () {
    Client::factory()->create([
        'email' => 'existing@example.com',
    ]);

    $clientToUpdate = Client::factory()->create();

    $response = $this->putJson(route('clients.update', $clientToUpdate), [
        'name'  => 'New Client',
        'email' => 'existing@example.com',
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['email']);
});
