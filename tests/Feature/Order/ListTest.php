<?php

use App\Models\User;
use App\Models\{Client, Order, Product};

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('returns the list of orders', function () {
    $client   = Client::factory()->create();
    $products = Product::factory()->count(3)->create();

    $order = Order::factory()->create([
        'client_id'  => $client->id,
        'total'      => 183,
        'created_at' => now(),
    ]);

    $order->products()->attach($products);

    $response = $this->get(route('orders.index'));

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => [
                         'id',
                         'total',
                         'created_at',
                         'client' => [
                             'id',
                             'name',
                             'email',
                             'phone',
                             'birth_date',
                             'address',
                             'number',
                             'complement',
                             'neighborhood',
                             'zip_code',
                             'city',
                             'state',
                             'created_at',
                             'updated_at',
                         ],
                         'products' => [
                             '*' => [
                                 'id',
                                 'name',
                                 'type' => [
                                     'value',
                                     'name',
                                     'description',
                                 ],
                                 'price',
                                 'photo',
                                 'photo_path',
                             ],
                         ],
                     ],
                 ],
             ]);
});

it('each order contains a valid client', function () {
    $client  = Client::factory()->create();
    $product = Product::factory(4)->create();
    $order   = Order::factory()->create(['client_id' => $client->id]);

    $response = $this->get(route('orders.index'));

    $response->assertJsonFragment([
        'id'    => $client->id,
        'name'  => $client->name,
        'email' => $client->email,
    ]);
});

it('each product contains valid information', function () {
    $client   = Client::factory()->create();
    $products = Product::factory()->count(2)->create();

    $order = Order::factory()->create(['client_id' => $client->id]);
    $order->products()->attach($products);

    $response = $this->get(route('orders.index'));

    foreach ($products as $product) {
        $response->assertJsonFragment([
            'id'    => $product->id,
            'name'  => $product->name,
            'price' => (string) $product->price,
        ]);
    }
});
