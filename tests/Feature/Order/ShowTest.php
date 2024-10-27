<?php

use App\Models\User;
use App\Models\{Client, Order, Product};
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('shows an order with products and client', function () {
    $client = Client::factory()->create();

    $products = Product::factory()->count(3)->create([
        'price' => 9.98,
    ]);

    $order = Order::factory()->create([
        'client_id' => $client->id,
        'total'     => $products->sum('price')
    ]);

    $order->products()->attach($products->pluck('id'));

    $response = $this->getJson(route('orders.show', $order));

    $response->assertStatus(200)
             ->assertJson(
                 fn (AssertableJson $json) => $json
                 ->where('data.id', $order->id)
                 ->where('data.total', $order->total) // Total in cents
                 ->where('data.client.id', $client->id)
                 ->where('data.client.name', $client->name)
                 ->etc()
             );
});

it('returns a 404 error if the order does not exist', function () {
    $response = $this->getJson(route('orders.show', 999));

    $response->assertStatus(404);

    $response->assertJson(['message' => __('Record not found.')]);
});
