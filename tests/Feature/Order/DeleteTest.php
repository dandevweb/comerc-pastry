<?php

use App\Models\{Client, Order, Product, User};

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('deletes an order successfully', function () {
    $client = Client::factory()->create();

    $products = Product::factory()->count(3)->create();

    $order = Order::factory()->create([
        'client_id' => $client->id,
        'total'     => $products->sum('price'),
    ]);

    $order->products()->attach($products->pluck('id'));

    $response = $this->deleteJson(route('orders.destroy', $order));

    $response->assertStatus(204);

    $this->assertSoftDeleted('orders', [
        'id' => $order->id,
    ]);

    $this->assertSoftDeleted('order_product', [
        'order_id' => $order->id,
    ]);
});

it('returns a 404 error if the order does not exist', function () {
    $response = $this->deleteJson(route('orders.destroy', 999));

    $response->assertStatus(404);

    $response->assertJson(['message' => __('Record not found.')]);
});
