<?php

use App\Models\Client;
use App\Services\ClientService;

beforeEach(function () {
    $this->service = new ClientService();
});

it('lists clients with filters and sorting', function () {
    Client::factory()->create(['name' => 'John Doe', 'email' => 'john@example.com']);
    Client::factory()->create(['name' => 'Jane Doe', 'email' => 'jane@example.com']);

    $service = $this->service;

    // basic list without filters
    $clients = $service->list([]);
    expect($clients)->toHaveCount(2);

    // filter by name
    $filteredClients = $service->list(['filter' => 'John']);
    expect($filteredClients)->toHaveCount(1)
        ->and($filteredClients->first()->name)->toBe('John Doe');

    // filter by email
    $filteredClients = $service->list(['filter' => 'john@example.com']);
    expect($filteredClients)->toHaveCount(1)
        ->and($filteredClients->first()->email)->toBe('john@example.com');

    // sort
    $sortedClients = $service->list(['sort_by' => 'name', 'sort_order' => 'desc']);
    expect($sortedClients->first()->name)->toBe('John Doe');
});

it('creates a client', function () {
    $service = $this->service;

    $data = Client::factory()->make([
        'name'  => 'Alice Smith',
        'email' => 'alice@example.com',
    ])->toArray();

    $client = $service->create($data);

    expect($client)->toBeInstanceOf(Client::class)
        ->and($client->name)->toBe('Alice Smith')
        ->and($client->email)->toBe('alice@example.com');

    $this->assertDatabaseHas('clients', $data);
});

it('updates a client', function () {
    $client = Client::factory()->create([
        'name'  => 'Original Name',
        'email' => 'original@example.com',
    ]);

    $service = $this->service;

    $updatedData = Client::factory()->make([
        'name'  => 'Updated Name',
        'email' => 'updated@example.com',
    ])->toArray();

    $updatedClient = $service->update($client, $updatedData);

    expect($updatedClient)->toBeInstanceOf(Client::class)
        ->and($updatedClient->name)->toBe('Updated Name')
        ->and($updatedClient->email)->toBe('updated@example.com');

    $this->assertDatabaseHas('clients', $updatedData);
});
