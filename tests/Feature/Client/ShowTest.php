<?php

use App\Models\{Client, User};

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can retrieve a client by id', function () {
    $client = Client::factory()->create(['name' => 'Alice', 'email' => 'alice@example.com']);

    $response = $this->getJson(route('clients.show', $client));

    $response->assertStatus(200);
    $response->assertJson([
        'data' => [
            'id'    => $client->id,
            'name'  => 'Alice',
            'email' => 'alice@example.com',
        ]
    ]);
});

it('returns a 404 error if the client does not exist', function () {
    $response = $this->getJson('/api/clients/999'); // ID que não existe

    $response->assertStatus(404);

    $response->assertJson(['message' => __('Record not found.')]);
});
