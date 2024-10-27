<?php

use App\Models\{User};
use App\Models\Product;

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can delete a product successfully', function () {
    $product = Product::factory()->create();

    $response = $this->deleteJson(route('products.destroy', $product));

    $response->assertStatus(204);

    $this->assertSoftDeleted('products', [
        'id' => $product->id,
    ]);
});

it('returns a 404 error if the product does not exist', function () {
    $response = $this->deleteJson(route('products.destroy', 999));

    $response->assertStatus(404);

    $response->assertJson(['message' => __('Record not found.')]);
});
