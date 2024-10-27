<?php

use App\Models\{Client, Order, Product, User};
use Illuminate\Testing\Fluent\AssertableJson;

use function Pest\Laravel\{actingAs};

beforeEach(function () {
    Storage::fake('public');
    actingAs(User::factory()->create());
});



it('updates an existing order successfully', function () {
    $client = Client::factory()->create();

    $products = Product::factory()->count(3)->create();

    $order = Order::factory()->create([
        'client_id' => $client->id,
        'total'     => $products->sum('price'),
    ]);

    $order->products()->attach($products->pluck('id'));

    $newProducts = Product::factory()->count(2)->create();

    $updateData = [
        'client_id' => $client->id,
        'products'  => $newProducts->pluck('id')->toArray(),
    ];

    $response = $this->putJson(route('orders.update', $order), $updateData);

    $response->assertStatus(200)
             ->assertJson(
                 fn (AssertableJson $json) => $json
                 ->where('data.id', $order->id)
                 ->where('data.client.id', $client->id)
                 ->has('data.products', 2)
                 ->etc()
             );

    $this->assertDatabaseHas('orders', [
        'id'        => $order->id,
        'client_id' => $client->id,
        'total'     => $newProducts->sum('price') * 100,
    ]);

    $this->assertDatabaseHas('order_product', [
        'order_id'   => $order->id,
        'product_id' => $newProducts->pluck('id')->toArray(),
    ]);
});

it('returns validation errors when required fields are missing', function () {
    $client = Client::factory()->create();

    $products = Product::factory()->count(3)->create();

    $order = Order::factory()->create([
        'client_id' => $client->id,
        'total'     => $products->sum('price'),
    ]);

    $order->products()->attach($products->pluck('id'));

    $response = $this->putJson(route('orders.update', $order), [
        'products' => $products->pluck('id')->toArray(),
    ]);

    $response->assertStatus(422)
             ->assertJsonValidationErrors(['client_id']);
});

it('returns a 404 error if the order does not exist', function () {
    $updatedData = [
        'client_id' => Client::factory()->create()->id,
        'products'  => [Product::factory()->create()->id],
    ];

    $response = $this->putJson(route('orders.update', 999), $updatedData);

    $response->assertStatus(404);
    $response->assertJson(['message' => __('Record not found.')]);
});
