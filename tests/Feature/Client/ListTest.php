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

    $response = $this->getJson('/api/clients?filter=Alice');

    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
    $response->assertJsonFragment(['name' => 'Alice']);
});

it('can filter clients by email', function () {
    Client::factory()->create(['email' => 'alice@example.com']);
    Client::factory()->create(['email' => 'bob@example.com']);

    $response = $this->getJson('/api/clients?filter=alice@example.com');

    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
    $response->assertJsonFragment(['email' => 'alice@example.com']);
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
