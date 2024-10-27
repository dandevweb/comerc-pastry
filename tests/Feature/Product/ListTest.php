<?php

use App\Models\{User, Product};

use function Pest\Laravel\actingAs;

beforeEach(function () {
    actingAs(User::factory()->create());
});

it('can list all products', function () {
    Product::factory()->count(5)->create();

    $response = $this->getJson(route('products.index'));

    $response->assertStatus(200);
    $response->assertJsonCount(5, 'data');
});

it('can filter products by name', function () {
    Product::factory()->create(['name' => 'Coxinha']);
    Product::factory()->create(['name' => 'Esfiha']);

    $response = $this->getJson('/api/products?filter=Coxinha');

    $response->assertStatus(200);
    $response->assertJsonCount(1, 'data');
    $response->assertJsonFragment(['name' => 'Coxinha']);
});

it('can sort products by price in ascending order', function () {
    Product::factory()->create(['price' => 10.00]);
    Product::factory()->create(['price' => 20.00]);
    Product::factory()->create(['price' => 15.00]);

    $response = $this->getJson(route('products.index', ['sort_by' => 'price', 'sort_order' => 'asc']));

    $response->assertStatus(200);
    $data = $response->json('data');
    expect($data[0]['price'])->toBe('10.00');
    expect($data[1]['price'])->toBe('15.00');
    expect($data[2]['price'])->toBe('20.00');
});

it('can sort products by price in descending order', function () {
    Product::factory()->create(['price' => 10.00]);
    Product::factory()->create(['price' => 20.00]);
    Product::factory()->create(['price' => 15.00]);

    $response = $this->getJson(route('products.index', ['sort_by' => 'price', 'sort_order' => 'desc']));

    $response->assertStatus(200);
    $data = $response->json('data');
    expect($data[0]['price'])->toBe('20.00');
    expect($data[1]['price'])->toBe('15.00');
    expect($data[2]['price'])->toBe('10.00');
});

it('can paginate the product list', function () {
    Product::factory()->count(15)->create();

    $response = $this->getJson(route('products.index', ['page' => 1, 'limit' => 10]));

    $response->assertStatus(200);
    $response->assertJsonCount(10, 'data'); // Has 10 products per page
    $response->assertJsonPath('meta.last_page', 2); // Confirms that there are 2 pages
});
