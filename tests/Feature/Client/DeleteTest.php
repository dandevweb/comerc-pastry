<?php

use App\Models\{Client, User};

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can delete a client successfully', function () {
    $client = Client::factory()->create();

    $response = $this->deleteJson(route('clients.destroy', $client));

    $response->assertStatus(204);

    $this->assertSoftDeleted('clients', [
        'id' => $client->id,
    ]);
});

it('returns a 404 error if the client does not exist', function () {
    $response = $this->deleteJson(route('clients.destroy', 999));

    $response->assertStatus(404);

    $response->assertJson(['message' => __('Record not found.')]);
});
