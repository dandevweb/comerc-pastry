<?php

use App\Enums\ProductTypeEnum;
use App\Models\{User, Product};

use function Pest\Laravel\{actingAs};

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can retrieve a product by id', function () {
    $product = Product::factory()->create(['name' => 'Alice', 'price' => 10.00, 'type' => ProductTypeEnum::salty]);

    $response = $this->getJson(route('products.show', $product));

    $response->assertStatus(200);
    $response->assertJson([
        'data' => [
            'id'   => $product->id,
            'name' => 'Alice',
            'type' => [
                'value' => ProductTypeEnum::salty->value,
                'name'  => ProductTypeEnum::salty->name,
            ],
            'price' => '10.00',
            'photo' => $product->photo,
        ]
    ]);
});

it('returns a 404 error if the product does not exist', function () {
    $response = $this->getJson(route('products.show', 999));

    $response->assertStatus(404);

    $response->assertJson(['message' => __('Record not found.')]);
});
