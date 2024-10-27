<?php

use App\Models\{Client, Product};
use App\Models\{User};
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\{actingAs};

beforeEach(function () {
    Storage::fake('public');
    actingAs(User::factory()->create());
});

it('creates an order successfully', function () {
    $client   = Client::factory()->create();
    $products = Product::factory()->count(2)->create();

    $total = $products->sum('price');

    $response = $this->postJson(route('orders.store'), [
        'client_id' => $client->id,
        'products'  => $products->pluck('id')->toArray(),
    ]);

    $response->assertStatus(201)
             ->assertJson(
                 fn (AssertableJson $json) => $json->where('data.client.id', $client->id)
                      ->has('data.products', 2)
                      ->etc()
             );

    $this->assertDatabaseHas('orders', [
        'client_id' => $client->id,
        'total'     => $total * 100,
    ]);

    $order = Order::latest()->first();
    $this->assertCount(2, $order->products);
});

it('fails to create an order without a client_id', function () {
    $products = Product::factory()->count(2)->create();

    $response = $this->postJson(route('orders.store'), [
        'products' => $products->pluck('id')->toArray(),
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['client_id']);
});

it('fails to create an order with invalid client_id', function () {
    $products = Product::factory()->count(2)->create();

    $response = $this->postJson(route('orders.store'), [
        'client_id' => 9999, // Non-existent client ID
        'products'  => $products->pluck('id')->toArray(),
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['client_id']);
});

it('fails to create an order without products', function () {
    $client = Client::factory()->create();

    $response = $this->postJson(route('orders.store'), [
        'client_id' => $client->id,
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['products']);
});

it('fails to create an order with an empty products array', function () {
    $client = Client::factory()->create();

    $response = $this->postJson(route('orders.store'), [
        'client_id' => $client->id,
        'products'  => [],
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['products']);
});

it('fails to create an order with invalid product ID', function () {
    $client  = Client::factory()->create();
    $product = Product::factory()->create();

    $response = $this->postJson(route('orders.store'), [
        'client_id' => $client->id,
        'products'  => [$product->id, 9999], // 9999 is a non-existent product ID
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['products.1']);
});
