<?php

use App\Models\User;
use App\Enums\ProductTypeEnum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\{postJson, actingAs, assertDatabaseHas};

beforeEach(function () {
    Storage::fake('public');
    actingAs(User::factory()->create());
});

it('creates a product successfully', function () {
    $data = [
        'name'  => 'Pastel de Carne',
        'type'  => ProductTypeEnum::salty,
        'price' => 12.50,
        'photo' => UploadedFile::fake()->image('product.jpg', 500, 500),
    ];

    $response = postJson(route('products.store'), $data);

    $response->assertCreated();
    $response->assertJsonPath('data.name', $data['name']);
    $response->assertJsonPath('data.price', number_format($data['price'], 2, '.', ''));
    assertDatabaseHas('products', [
        'name' => $data['name'],
        'type' => $data['type'],
    ]);
});

it('fails to create a product without required fields', function () {
    $response = postJson(route('products.store'), []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['name', 'type', 'price', 'photo']);
});

it('fails to create a product with an invalid type', function () {
    $data = [
        'name'  => 'Pastel de Queijo',
        'type'  => 99, // invalid type
        'price' => 8.50,
        'photo' => UploadedFile::fake()->image('product.jpg'),
    ];

    $response = postJson(route('products.store'), $data);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['type']);
});

it('fails to create a product with a non-numeric price', function () {
    $data = [
        'name'  => 'Pastel de Frango',
        'type'  => ProductTypeEnum::salty,
        'price' => 'não-numérico',
        'photo' => UploadedFile::fake()->image('product.jpg'),
    ];

    $response = postJson(route('products.store'), $data);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['price']);
});

it('fails to create a product with a photo exceeding the max size', function () {
    $data = [
        'name'  => 'Pastel Especial',
        'type'  => ProductTypeEnum::sweet,
        'price' => 15.00,
        'photo' => UploadedFile::fake()->image('product.jpg')->size(2048), // 2MB
    ];

    $response = postJson(route('products.store'), $data);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['photo']);
});
