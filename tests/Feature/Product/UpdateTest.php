<?php

use App\Enums\ProductTypeEnum;
use App\Models\{User, Product};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\{actingAs, putJson};

beforeEach(function () {
    Storage::fake('public');
    actingAs(User::factory()->create());
});

it('can update a product successfully', function () {
    $product = Product::factory()->create([
        'name'  => 'Pastel',
        'price' => 10.00,
        'type'  => ProductTypeEnum::salty,
    ]);

    $updatedData = [
        'name'  => 'Pastel Updated',
        'price' => '12.50',
        'type'  => ProductTypeEnum::sweet,
        'photo' => UploadedFile::fake()->image('product.jpg', 500, 500),
    ];

    $response = putJson(route('products.update', $product), $updatedData);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id'   => $product->id,
            'name' => $updatedData['name'],
            'type' => [
                'value'       => $updatedData['type']->value,
                'name'        => $updatedData['type']->name,
                'description' => $updatedData['type'] ? ProductTypeEnum::getDescription($updatedData['type']->value) : null,
            ],
            'price' => $updatedData['price'],
            'photo' => $updatedData['photo'],
        ]);

    $this->assertDatabaseHas('products', [
        'id'   => $product->id,
        'name' => $updatedData['name'],
        'type' => $updatedData['type'],
    ]);
});

it('returns validation errors when required fields are missing', function () {
    $product = Product::factory()->create();

    $response = putJson(route('products.update', $product), [
        'name'  => '',
        'price' => '',
        'type'  => ProductTypeEnum::salty,
    ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'price',  'photo']);
});

it('returns a 404 error if the product does not exist', function () {
    $updatedData = [
        'name'  => 'Non-existent Product',
        'price' => '12.50',
        'type'  => ProductTypeEnum::sweet,
    ];

    $response = putJson(route('products.update', 999), $updatedData);

    $response->assertStatus(404);
    $response->assertJson(['message' => __('Record not found.')]);
});
