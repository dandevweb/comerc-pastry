<?php


use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->service = new ProductService();
});

it('lists products with filters, sorting, and pagination', function () {
    Product::factory()->create(['name' => 'Apple']);
    Product::factory()->create(['name' => 'Refri']);
    Product::factory()->create(['name' => 'Avocado']);

    $service  = $this->service;
    $filters  = ['filter' => 'A', 'sort_by' => 'name', 'sort_order' => 'asc', 'per_page' => 2];
    $products = $service->list($filters);

    expect($products->total())->toBe(2); // filtered by "A", returns "Apple" and "Avocado"
    expect($products->first()->name)->toBe('Apple'); // verify sort
});

it('creates a new product with photo', function () {
    Storage::fake('products');

    $file = UploadedFile::fake()->image('product.jpg');
    $data = Product::factory()->make([
        'name'  => 'New Product',
        'price' => 10.99,
        'photo' => $file,
    ])->toArray();

    $service = new ProductService();
    $product = $service->create($data);

    expect($product)->toBeInstanceOf(Product::class)
        ->and($product->name)->toBe('New Product')
        ->and(Storage::exists($product->photo))->toBeTrue();
});

it('updates a product and replaces photo', function () {
    Storage::fake('products');

    $oldFile = UploadedFile::fake()->image('old_product.jpg');
    $product = Product::factory()->create(['photo' => $oldFile->store('products')]);

    $newFile = UploadedFile::fake()->image('new_product.jpg');
    $data    = Product::factory()->make([
        'name'  => 'Updated Product',
        'price' => 1500,
        'photo' => $newFile,
    ])->toArray();

    $service        = $this->service;
    $updatedProduct = $service->update($product, $data);

    expect($updatedProduct)->toBeInstanceOf(Product::class)
        ->and($updatedProduct->name)->toBe('Updated Product')
        ->and(Storage::exists($updatedProduct->photo))->toBeTrue();
});
