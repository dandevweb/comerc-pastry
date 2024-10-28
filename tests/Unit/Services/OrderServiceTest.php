<?php

use App\Models\{Order, Client, Product};
use App\Services\OrderService;

beforeEach(function () {
    $this->service = new OrderService();
});

it('lists orders with products and client', function () {
    $client   = Client::factory()->create();
    $products = Product::factory()->count(3)->create();
    $order    = Order::factory()->for($client)->create();
    $order->products()->attach($products);

    $service = $this->service;
    $orders  = $service->list();

    expect($orders)->toHaveCount(1);
    expect($orders->first()->products)->toHaveCount(6); // is created a product to each order fake
    expect($orders->first()->client->id)->toBe($client->id);
});

it('saves a new order', function () {
    $client = Client::factory()->create();
    Product::factory()->create(['price' => 5.00]);
    Product::factory()->create(['price' => 15.00]);

    $data = [
        'client_id' => $client->id,
        'products'  => Product::pluck('id')->toArray(),
    ];

    $service = $this->service;
    $order   = $service->save($data);

    expect($order)->toBeInstanceOf(Order::class)
        ->and($order->client_id)->toBe($client->id)
        ->and($order->total)->toBe(20.00);

    expect($order->products)->toHaveCount(2);
    $this->assertDatabaseHas('orders', ['client_id' => $client->id]);
});

it('updates an existing order with new data and products', function () {
    $client = Client::factory()->create();
    Product::factory()->create();
    $order       = Order::factory()->for($client)->create(['total' => 10.00]);
    $newProducts = Product::factory()->count(2)->create(['price' => 20.00]);

    $data = [
        'client_id' => $client->id,
        'products'  => $newProducts->pluck('id')->toArray(),
    ];

    $service      = $this->service;
    $updatedOrder = $service->save($data, $order);

    expect($updatedOrder)->toBeInstanceOf(Order::class)
        ->and($updatedOrder->total)->toBe(40.00);

    expect($updatedOrder->products)->toHaveCount(2);
    $this->assertDatabaseHas('orders', ['id' => $order->id, 'total' => 40.00 * 100]);
});
