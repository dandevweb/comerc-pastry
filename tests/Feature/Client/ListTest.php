<?php

namespace Tests\Unit;

use App\Models\{Client, User};

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can list all clients', function () {
    Client::factory()->count(3)->create();

    $response = $this->getJson(route('clients.index'));

    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
});

it('can filter clients by name', function () {
    Client::factory()->create(['name' => 'Alice']);
    Client::factory()->create(['name' => 'Bob']);

    $response = $this->getJson('/api/clients?name=Alice');

    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
    $response->assertJsonFragment(['name' => 'Alice']);
});

it('can paginate clients', function () {
    Client::factory()->count(20)->create();

    $response = $this->getJson('/api/clients?page=1&per_page=10');

    $response->assertStatus(200);
    $response->assertJsonCount(10, 'data');
    $this->assertArrayHasKey('links', $response->json());
});

it('can sort clients by name', function () {
    Client::factory()->create(['name' => 'Alice']);
    Client::factory()->create(['name' => 'Bob']);
    Client::factory()->create(['name' => 'Charlie']);

    $response = $this->getJson('/api/clients?sort=name');

    $response->assertStatus(200);
    $this->assertEquals('Alice', $response->json()['data'][0]['name']);
});
